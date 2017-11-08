<?php
/**
 * 流量平台API接口
 * $Id: api.php 413 2016-07-14 07:41:35Z huanghuan $
 */
class ControllerAppApi extends modules_public
{
	/**
	 * @param Registry $registry 注册对象
	 */
	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->checkToken();//token校验
	}

	/**
	 * 流量上报接口
	 */
	public function report()
	{
		/*
		 * 设置流量修改锁，流量计算修改完成后释放
		 * 防止流量充值时，接口流量计算产生数据混乱
		 */
		$this->registry->model('app/gprs');
		$iccid     = $this->getIccid();
		$card_info = $this->model_app_gprs->getByIccid($iccid);//获取流量卡信息
		if (empty($card_info))
		{
			$result = array(
				'status' => -1,
				'msg'    => 'can not get card info'
			);
			exit(json_encode($result));
		}

		/*
		 * 记录上行、下行流量日志
		 */
		$log_data = array(
			'card_id'   => $card_info['card_id'],
			'up_gprs'   => $this->request->get_var('up_gprs', 'f'),
			'down_gprs' => $this->request->get_var('down_gprs', 'f'),
		);
		$this->model_app_gprs->insertLog($log_data);

		$alert_info              = $this->model_app_gprs->getAlertInfo($this->_org_id);//获取预警信息
		$gprs                    = array(
			'month'     => $this->request->get_var('used_month', 'f', '', 0.01),
			'total'     => $this->request->get_var('used_total', 'f', '', 0.01),
			'is_unicom' => false
		);
		$card_info['used_month'] = $this->model_app_gprs->getSumMonthGprs($card_info['card_id']);

		/**
		 * 车机上报月流量大于当前的月流量，需计算流量
		 */
		if ($gprs['month'] > $card_info['used_month'])
		{
			$mem    = $this->mem();
			$mdb    = $this->mdb();
			$result = modules_funs::gprsCalculate($mem, $mdb, $card_info, $gprs);
			if (!$result)
			{
				$result = array(
					'status' => 0,
					'msg'    => 'update card gprs failed'
				);
				exit(json_encode($result));
			}
			$card_info['max_unused'] = $result['max_unused'];
			$card_info['used_total'] = $result['used_total'];
			$card_info['used_month'] = $result['used_month'];
		}

		/*
		 * 最大可使用流量低于预警值或流量有效期还差三天到期，发送消息通知机构
		 */
		$time_expire = $this->model_app_gprs->getTimeExpire($card_info['card_id']);
		if (!$this->mem_get("GPRS-CARD-NOTICE-{$iccid}") && ($card_info['max_unused'] <= $alert_info['alert_value'] || strtotime("+ 3day") >= strtotime($time_expire)))
		{
			/*
			 * 判断可使用流量或有效期时间
			 */
			$this->registry->language('app/api');
			if (strtotime("+ 3day") >= strtotime($time_expire))
			{
				$cmd    = 'expire';
				$notify = array(
					'time' => $time_expire,
					'msg'  => sprintf($this->language->get('text_gprs_expire'), $card_info['max_unused'])
				);
			}
			else
			{
				$cmd    = $card_info['max_unused'] > 0 ? 'warning' : 'over';
				$notify = array(
					'balance' => $card_info['max_unused'],
					'msg'     => $card_info['max_unused'] > 0 ? $alert_info['alert_tpl1'] : $alert_info['alert_tpl2']
				);
			}
			$this->notifyCmd($iccid, $cmd, $notify);
			$this->mem_set("GPRS-CARD-NOTICE-{$iccid}", 1);//设置微信通知间隔时间。默认30分钟
		}

		/**
		 * 返回统计好的数据给车机
		 */
		$result = array(
			'status'      => 1,
			'msg'         => 'update card gprs succeed',
			'alert_value' => $alert_info['alert_value'],
			'used_total'  => $card_info['used_total'],
			'used_month'  => $card_info['used_month'],
			'max_unused'  => $card_info['max_unused'],
		);
		exit(json_encode($result));
	}

	/**
	 * 获取流量卡信息
	 */
	public function info()
	{
		$iccids = $this->request->get_var('iccid', 'a');
		if (!empty($iccids))
		{
			$data = array();
			$this->registry->model('app/gprs');
			$num = 0;
			foreach ($iccids as $iccid)
			{
				if ($num >= 10)
				{
					break;
				}

				$card_info = $this->model_app_gprs->getByIccid($this->getIccid($iccid));//获取流量卡信息
				if ($card_info)
				{
					unset($card_info['card_id'], $card_info['org_id'], $card_info['batch_id'], $card_info['card_sn'], $card_info['card_name']);
					$data[] = $card_info;
				}
				$num++;
			}

			if ($data)
			{
				$result = array(
					'status' => 1,
					'msg'    => 'get iccids info succeed',
					'data'   => $data
				);
				exit(json_encode($result));
			}
		}

		$result = array(
			'status' => 0,
			'msg'    => 'get iccids info failed'
		);
		exit(json_encode($result));
	}

	/**
	 * 获取流量卡充值记录
	 */
	public function paylog()
	{
		$iccid    = $this->getIccid();
		$is_paid  = $this->request->get_var('is_paid', 'i');
		$page     = $this->request->get_var('page', 'i', '', 1);
		$pagesize = $this->request->get_var('pagesize', 'i', '', (int)$this->config->get('config_catalog_limit'));

		/**
		 * 获取流量卡信息
		 */
		$this->registry->model('app/gprs');
		$this->registry->model('app/pay');
		$card_info = $this->model_app_gprs->getByIccid($iccid);
		if (!$card_info)
		{
			$result = array(
				'status' => 0,
				'msg'    => 'iccid error',
			);
			exit(json_encode($result));
		}

		$data = array(
			'start'          => ((int)$page - 1) * $pagesize,
			'limit'          => $pagesize,
			'filter_card_id' => $card_info['card_id'],
		);

		if ($is_paid)
		{
			$data['filter_is_paid'] = $is_paid;
		}

		$items = $this->model_app_pay->getPayAll($data);
		if ($items)
		{
			$data = array();
			foreach ($items as $vo)
			{
				$data[] = array(
					'gprs_amount' => $vo['gprs_amount'],
					'gprs_price'  => $vo['gprs_price'],
					'pay_memo'    => $vo['pay_memo'],
					'pay_method'  => $vo['pay_method'],
					'transfer_id' => $vo['transfer_id'],
					'is_paid'     => $vo['is_paid'],
					'time_paid'   => $vo['time_paid'],
					'time_added'  => $vo['time_added']
				);
			}
			$result = array(
				'status' => 1,
				'msg'    => 'iccid info succeed',
				'data'   => $data
			);
		}
		else
		{
			$result = array(
				'status' => 0,
				'msg'    => 'log not_found',
			);
		}
		exit(json_encode($result));
	}

	/**
	 * 获取充值套餐
	 */
	public function paypack()
	{
		$this->registry->model('app/pack');
		$data   = $this->model_app_pack->getPackList($this->_org_id);
		$result = array(
			'status' => 1,
			'msg'    => 'get pack ok',
			'data'   => $data,
		);
		exit(json_encode($result));
	}

	/**
	 * 在线充值
	 */
	public function payup()
	{
		$iccid = $this->login($this->getIccid());
		if (empty($iccid))
		{
			$result = array(
				'status' => 0,
				'msg'    => 'iccid error',
			);
			exit(json_encode($result));
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
			$result = array(
				'status' => 0,
				'msg'    => 'pack error',
			);
			exit(json_encode($result));
		}

		$pay_method = $this->request->get_var('pay_method', 'i');
		if ($pay_method != 1)
		{
			$result = array(
				'status' => 0,
				'msg'    => 'only support wxpay',
			);
			exit(json_encode($result));
		}

		$card_info = $this->model_app_gprs->getByIccid($iccid);
		switch ($pay_method)
		{
			case 1:
				$this->_wxpay($pack_info, $card_info);
				break;
			default:
				$result = array(
					'status' => 0,
					'msg'    => 'only support wxpay',
				);
				exit(json_encode($result));
		}
	}

	/**
	 * 微信支付生成订单
	 *
	 * @param $pack_info
	 * @param $card_info
	 */
	private function _wxpay($pack_info, $card_info)
	{
		require_once(DIR_ROOT . '/system/wxpay_utils.php');

		/**
		 * 微信支付参数配置
		 */
		$wxpay_config = array(
			'appid'        => $this->config->get('wxpay_appid'),
			'mchid'        => $this->config->get('wxpay_mchid'),
			'mchkey'       => $this->config->get('wxpay_mchkey'),
			'appsecret'    => $this->config->get('wxpay_appsecret'),
			'sslcert_path' => $this->config->get('wxpay_sslcert_path'),
			'sslkey_path'  => $this->config->get('wxpay_sslkey_path'),
		);
		WxPayConfig::setParams($wxpay_config);

		/*
		 * 新增充值未支付记录，微信支付成功后回调修改为已支付
		 */
		$data   = array(
			'card_id'     => $card_info['card_id'],
			'org_id'      => $card_info['org_id'],
			'gprs_amount' => $pack_info['gprs_amount'],
			'gprs_price'  => $pack_info['gprs_price'],
			'pay_memo'    => $this->request->get_var('pay_memo'),
			'pay_method'  => 1
		);
		$pay_id = $this->model_app_pay->insertPay($data);
		if (empty($pay_id))
		{
			$result = array(
				'status' => 0,
				'msg'    => 'get order failed',
			);
			exit(json_encode($result));
		}

		/*
		 * 生成微信支付订单
		 * attach附加数据为json格式，套餐有效期 + 合作伙伴编号
		 */
		$wxpay     = new JsApiPay();
		$order     = array(
			'pay_id'    => $pay_id,
			'body'      => sprintf($this->language->get('text_pay_body'), $pack_info['gprs_amount']),
			'attach'    => json_encode(array(
				'live_month' => $pack_info['live_month'],
				'partner_id' => $this->session->data['partner_id']
			)),
			'total_fee' => $pack_info['gprs_price'] * 100,
		);
		$prepay_id = $wxpay->getAppPay($order);
		if (empty($prepay_id))
		{
			$result = array(
				'status' => 0,
				'msg'    => 'get order failed',
			);
			exit(json_encode($result));
		}

		$result = array(
			'status'    => 1,
			'msg'       => 'get order succeed',
			'prepay_id' => $prepay_id,
		);
		exit(json_encode($result));
	}
}
?>