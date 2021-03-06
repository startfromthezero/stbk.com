<?php
/**
 * 流量卡相关界面
 *
 * 此模块主要用于流量卡数据处理
 */
class ControllerAppMain extends modules_public
{
	/**
	 * @var int 流量卡ICCID
	 */
	private $_iccid;

	/**
	 * @param Registry $registry 注册对象
	 */
	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->_isweixin = $this->checkWeixin();

		/**
		 * 充值套餐，查询页面不做强制iccid登录校验
		 */
		if ($this->request->get_var('route') != 'app/main/topup' && $this->request->get_var('route') != 'app/main/scan')
		{
			$this->_iccid = $this->checkLogin();
		}
	}

	/**
	 * 流量卡详细信息
	 */
	public function info()
	{
		/**
		 * 初始化语言
		 */
		$this->registry->language('app/gprs');
		$this->registry->model('app/gprs');
		$this->registry->model('app/pack');
		$this->document->setTitle($this->language->get('heading_title'));
		$vrs = $this->language->data;

		$vrs['card_info'] = $this->model_app_gprs->getByIccid($this->_iccid);
		$vrs['pack_info'] = $this->model_app_pack->getPackInfo($vrs['card_info']['card_id']);

		/**
		 * 模板处理
		 */
		$vrs['page_footer'] = $this->mem_ctrl('app/main/bottom');
		$vrs['page_header'] = $this->registry->exectrl('app/main/top');

		return $this->view("template/app/info.tpl", $vrs);
	}

	/**
	 * 流量卡充值记录
	 */
	public function paylog()
	{
		/**
		 * 初始化语言
		 */
		$this->registry->language('app/gprs');
		$this->registry->model('app/pay');
		$this->document->setTitle($this->language->get('text_card_paylog'));
		$vrs = $this->language->data;

		/**
		 * 获取流量卡信息
		 */
		$this->registry->model('app/gprs');
		$vrs['card_info'] = $this->model_app_gprs->getByIccid($this->_iccid);

		$page         = $this->request->get_var('page', 'i', '', 1);
		$limit        = $this->request->get_var('limit', 'i', '', (int)$this->config->get('config_catalog_limit'));
		$data         = array(
			'start'          => ((int)$page - 1) * $limit,
			'limit'          => $limit,
			'filter_card_id' => $vrs['card_info']['card_id'],
		);
		$vrs['items'] = $this->model_app_pay->getPayList($data);
		if (empty($vrs['items']) && $page > 1)
		{
			return '';//多页查询无结果，返回空让前端停止翻页
		}

		/**
		 * 模板处理
		 */
		$vrs['page_footer'] = $this->mem_ctrl('app/main/bottom');
		$vrs['page_header'] = $this->registry->exectrl('app/main/top');
		$ajax               = $this->request->get_var('ajax', 'i') ? '1' : '';

		return $this->view("template/app/paylog{$ajax}.tpl", $vrs);
	}

	/**
	 * 流量卡在线充值，显示所有流量套餐
	 */
	public function topup()
	{
		/**
		 * 检测iccid登录状态，如果传入了iccid需校验机构token
		 */
		if (isset($this->request->get['iccid']))
		{
			$this->_iccid = $this->checkLogin();
		}
		elseif (isset($this->session->data['iccid']))
		{
			$this->_iccid = $this->login($this->session->data['iccid']);
		}

		/**
		 * 初始化语言
		 */
		$this->registry->language('app/gprs');
		$this->document->setTitle($this->language->get('text_gprs_pay'));
		$vrs             = $this->language->data;
		$vrs['isweixin'] = $this->_isweixin;
		$vrs['iccid']    = $this->_iccid;

		/**
		 * 微信中访问，使用微信支付，获取用户openid
		 */
		if ($this->_isweixin)
		{
			$wechat       = new modules_wechat();
			$vrs['jsapi'] = $wechat->getJsapiParameters();
			if (!isset($this->session->data['wx_openid']))
			{
				$this->session->data['wx_openid'] = $wechat->getOpenid();
			}
		}

		/**
		 * 获取流量卡套餐
		 */
		$this->registry->model('app/pack');
		$vrs['items'] = $this->model_app_pack->getPackList($this->_org_id);

		/**
		 * 模板处理
		 */
		$vrs['page_footer'] = $this->mem_ctrl('app/main/bottom');
		$vrs['page_header'] = $this->registry->exectrl('app/main/top');

		return $this->view("template/app/topup.tpl", $vrs);
	}

	/**
	 * 微信二维码扫描充值流量
	 */
	public function czk()
	{
		/**
		 * 获取充值卡信息并判断充值卡有效性
		 */
		$this->registry->language('app/gprs');
		$this->registry->model('app/gprs');
		$this->registry->model('app/pay');
		$code     = $this->request->get_var('code');
		$czk_info = $this->model_app_pay->getCzkInfo($code);//获取充值卡信息
		if (empty($czk_info))
		{
			return $this->language->get('error_no_czk');
		}

		if ($czk_info['time_used'])
		{
			return $this->language->get('error_czk_used');
		}

		if (strtotime($czk_info['time_expire']) < time())
		{
			return $this->language->get('error_czk_expire');
		}

		$card_info = $this->model_app_gprs->getByIccid($this->_iccid);
		/*
		 * 循环判断当前流量接口是否在计算修改中，如果有缓存程序则暂停1秒执行
		 */
		$is_update = 0;
		$mkey      = "GPRS-CARD-LOCK-{$card_info['card_iccid']}";
		while ($this->mem_get($mkey) == 1)
		{
			sleep(1);
			$is_update = 1;
		}

		/*
		 * 如果流量有过修改，需重新获取流量卡信息
		 */
		if ($is_update == 1)
		{
			$card_info = $this->model_app_gprs->getByIccid($this->_iccid);
		}

		$result = $this->model_app_pay->qrcode_pay($czk_info, $card_info);
		if ($result)
		{
			/**
			 * 通知接入机构已充值成功
			 */
			$data = array(
				'time'  => date('Y-m-d H:i:s'),
				'money' => $czk_info['zck_value'],
				'value' => $czk_info['zck_gprs'],
			);
			$this->notifyCmd($card_info['card_iccid'], 'topup', $data);

			return 'ok';
		}

		return $this->language->get('error_pay');
	}

	public function scan()
	{
		/**
		 * 初始化语言
		 */
		$this->registry->language('app/gprs');
		$this->document->setTitle($this->language->get('heading_title'));
		$vrs             = $this->language->data;
		$vrs['isweixin'] = $this->_isweixin;

		/**
		 * 微信中访问，需使用扫一扫
		 */
		if ($this->_isweixin)
		{
			$wechat       = new modules_wechat();
			$vrs['jsapi'] = $wechat->getJsapiParameters();
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST')
		{
			return $this->login($this->request->get_var('iccid')) ? 'ok' : '';
		}

		/**
		 * 模板处理
		 */
		$vrs['page_footer'] = $this->mem_ctrl('app/main/bottom');
		$vrs['page_header'] = $this->registry->exectrl('app/main/top');

		return $this->view("template/app/scan.tpl", $vrs);
	}

	/**
	 * 历史ICCID卡
	 */
	public function historyCard()
	{
		/**
		 * 初始化语言
		 */
		$this->registry->language('app/gprs');
		$this->document->setTitle($this->language->get('heading_title'));
		$vrs = $this->language->data;

		/**
		 * 模板处理
		 */
		$vrs['page_footer'] = $this->mem_ctrl('app/main/bottom');
		$vrs['page_header'] = $this->registry->exectrl('app/main/top');

		return $this->view("template/app/history_card.tpl", $vrs);
	}

	public function details()
	{
		/**
		 * 初始化语言
		 */
		$this->registry->language('app/gprs');
		$this->document->setTitle($this->language->get('heading_title'));
		$vrs = $this->language->data;

		/**
		 * 模板处理
		 */
		$vrs['page_footer'] = $this->mem_ctrl('app/main/bottom');
		$vrs['page_header'] = $this->registry->exectrl('app/main/top');

		return $this->view("template/app/details.tpl", $vrs);
	}
}
?>