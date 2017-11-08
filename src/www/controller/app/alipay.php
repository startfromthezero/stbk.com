<?php
require_once(DIR_ROOT . '/system/alipay_utils.php');
/**
 * 支付宝支付
 */
class ControllerAppAlipay extends modules_public
{
	private $_alipay_utils;

	/**
	 * @param Registry $registry 注册对象
	 */
	public function __construct($registry)
	{
		parent::__construct($registry);

		/**
		 * 支付宝配置初始化
		 */
		$alipay_config       = array(
			'partner'   => $this->config->get('alipay_partner'),
			'seller_id' => $this->config->get('alipay_seller_id'),
			'key'       => $this->config->get('alipay_key'),
		);
		$this->_alipay_utils = new alipay_utils($alipay_config);
	}

	/**
	 * 进入支付宝支付页面
	 */
	public function order()
	{
		$iccid = $this->login($this->getIccid());
		if (empty($iccid))
		{
			return $this->_msg($this->language->get('text_no_iccid'), $this->url->flink('app/main/topup'));
		}
		$this->registry->model('app/gprs');
		$this->registry->model('app/pay');
		$this->registry->model('app/pack');
		$this->registry->language('app/gprs');

		/**
		 * 获取流量套餐
		 */
		$pack_id   = $this->request->get_var('pack_id', 'i');
		$pack_info = $this->model_app_pack->getPack($pack_id, $this->_org_id);
		if (empty($pack_info))
		{
			return $this->_msg($this->language->get('text_no_pack'), $this->url->flink('app/main/topup'));
		}

		$card_info = $this->model_app_gprs->getByIccid($iccid);

		/*
		 * 新增充值未支付记录，支付宝支付成功后回调修改为已支付
		 */
		$data   = array(
			'card_id'     => $card_info['card_id'],
			'org_id'      => $card_info['org_id'],
			'gprs_amount' => $pack_info['gprs_amount'],
			'gprs_price'  => $pack_info['gprs_price'],
			'pay_method'  => 2
		);
		$pay_id = $this->model_app_pay->insertPay($data);

		if (empty($pay_id))
		{
			return $this->_msg($this->language->get('text_no_pay'), $this->url->flink('app/main/topup'));
		}

		$this->mem_set("GPRS-PACK-{$iccid}-{$pack_id}", $pack_info); //设置当前购买的套餐数据

		/*
		 * 支付订单参数
		 * body附加数据为套餐编号
		 */
		$order = array(
			'pay_id'    => $pay_id,
			'subject'   => sprintf($this->language->get('text_pay_body'), $pack_info['gprs_amount']),
			'total_fee' => $pack_info['gprs_price'],
			'show_url'  => $this->url->flink('app/main/topup'),
			'body'      => $pack_id
		);
		echo($this->_alipay_utils->buildRequestForm($order, 'get', '确认'));
	}

	/**
	 * 支付宝异步通知接口
	 */
	public function notify()
	{
		/**
		 * 验证通知是否合法
		 */
		$verify_result = $this->_alipay_utils->verifyNotify();
		if (!$verify_result)
		{
			exit('fail');
		}

		/**
		 * 判断交易状态是否已支付成功
		 */
		if ($this->request->post['trade_status'] != 'TRADE_SUCCESS' && $this->request->post['trade_status'] != 'TRADE_FINISHED')
		{
			exit('fail');
		}

		$this->registry->model('app/gprs');
		$this->registry->model('app/pay');
		$pay_id   = intval(substr($this->request->post['out_trade_no'], strrpos($this->request->post['out_trade_no'], '-') + 1));
		$pay_info = $this->model_app_pay->getPayInfo($pay_id);

		if (empty($pay_info))
		{
			exit('fail');
		}

		/*
		 * 判断该订单是否已经处理过，如果处理过直接返回成功
		 */
		if ($pay_info['is_paid'] == 1)
		{
			exit('success');
		}
		$card_info = $this->model_app_gprs->get($pay_info['card_id']);

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
			$card_info = $this->model_app_gprs->get($pay_info['card_id']);
		}
		$pay_info['transfer_id'] = $this->request->post['trade_no']; //支付宝订单号
		$pay_info['time_paid']   = $this->request->post['gmt_payment']; //付款时间
		$this->login($card_info['card_iccid']);

		/**
		 * 获取购买的套餐数据
		 */
		$pack_info = $this->mem_get("GPRS-PACK-{$card_info['card_iccid']}-{$this->request->post['body']}");
		if (empty($pack_info))
		{
			$pack_info = $this->model_app_pack->getPack($this->request->post['body'], $this->_org_id);
		}

		/**
		 * 支付成功，流量套餐分配计算
		 */
		if ($result = $this->model_app_pay->online_pay($card_info, $pay_info, $pack_info))
		{
			/**
			 * 需通知接入机构已充值成功
			 */
			$notify = array(
				'time'  => $pay_info['time_paid'],
				'money' => $pay_info['gprs_price'],
				'value' => $pay_info['gprs_amount'],
			);
			$this->notifyCmd($card_info['card_iccid'], 'topup', $notify);
			exit('success');
		}
		exit('fail');
	}

	/**
	 * 支付宝同步跳转,显示充值结果页面
	 */
	public function return_url()
	{
		$this->registry->language('app/gprs');
		$verify_result = $this->_alipay_utils->verifyReturn();
		if ($verify_result && ($this->request->post['trade_status'] != 'TRADE_SUCCESS' || $this->request->post['trade_status'] != 'TRADE_FINISHED'))
		{
			$msg = $this->language->get('text_pay_success');
			$url = $this->url->flink('app/main/info');
		}
		else
		{
			$msg = $this->language->get('error_pay');
			$url = $this->url->flink('app/main/topup');
		}

		return $this->_msg($msg, $url);
	}
}
?>