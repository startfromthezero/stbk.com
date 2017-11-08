<?php
require_once(DIR_ROOT . '/system/wxpay_utils.php');
/**
 * 微信支付
 */
class ControllerAppWxpay extends modules_public
{
	/**
	 * @param Registry $registry 注册对象
	 */
	public function __construct($registry)
	{
		parent::__construct($registry);

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
	}

	/**
	 * 生成微信支付订单
	 */
	public function order()
	{
		$iccid = $this->login($this->getIccid());
		if (empty($iccid))
		{
			return '';
		}
		$this->registry->model('app/gprs');
		$this->registry->model('app/pack');
		$this->registry->model('app/pay');
		$this->registry->language('app/gprs');

		/**
		 * 获取流量套餐
		 */
		$pack_id   = $this->request->get_var('pack_id', 'i');
		$pack_info = $this->model_app_pack->getPack($pack_id, $this->_org_id);
		if (empty($pack_info))
		{
			return '';
		}

		$card_info = $this->model_app_gprs->getByIccid($iccid);

		/*
		 * 新增充值未支付记录，微信支付成功后回调修改为已支付
		 */
		$data   = array(
			'card_id'     => $card_info['card_id'],
			'org_id'      => $card_info['org_id'],
			'gprs_amount' => $pack_info['gprs_amount'],
			'gprs_price'  => $pack_info['gprs_price'],
			'pay_method'  => 1
		);
		$pay_id = $this->model_app_pay->insertPay($data);

		if (empty($pay_id))
		{
			return '';
		}

		/*
		 * 生成微信支付订单
		 * attach附加数据为json格式，套餐编号 + 合作伙伴编号
		 */
		$wxpay = new JsApiPay();
		$order = array(
			'pay_id'    => $pay_id,
			'openid'    => $this->session->data['wx_openid'],
			'body'      => sprintf($this->language->get('text_pay_body'), $pack_info['gprs_amount']),
			'attach'    => json_encode(array(
				'pack_id'    => $pack_id,
				'partner_id' => $this->session->data['partner_id']
			)),
			'total_fee' => $pack_info['gprs_price'] * 100,
		);

		$this->mem_set("GPRS-PACK-{$iccid}-{$pack_id}", $pack_info); //设置当前购买的套餐数据

		return $wxpay->getJsPay($order);
	}

	/**
	 * 微信支付回调
	 */
	public function notify()
	{
		$wxPayNotifyReply = new WxPayNotifyReply();
		$msg              = "OK";

		/**
		 * 获取微信通知的数据
		 */
		$data = WxPayApi::notify($msg);
		if ($data == false)
		{
			$wxPayNotifyReply->SetReturn_code("FAIL");
			$wxPayNotifyReply->SetReturn_msg($msg);
			echo $wxPayNotifyReply->ToXml();

			return;
		}

		/**
		 * 支付订单业务处理
		 */
		$result = $this->_notifyProcess($data, $msg);
		if ($result)
		{
			$wxPayNotifyReply->SetReturn_code("SUCCESS");
			$wxPayNotifyReply->SetReturn_msg("OK");
		}
		else
		{
			$wxPayNotifyReply->SetReturn_code("FAIL");
			$wxPayNotifyReply->SetReturn_msg($msg);
		}
		echo $wxPayNotifyReply->ToXml();

		return;
	}

	/**
	 * 回调支付订单数据处理
	 */
	private function _notifyProcess($data, &$msg)
	{
		if (!array_key_exists("transaction_id", $data))
		{
			$msg = '输入参数不正确';

			return false;
		}

		/*
		 * 查询订单，判断订单真实性
		 */
		if (!$this->_queryOrder($data["transaction_id"]))
		{
			$msg = '订单查询失败';

			return false;
		}

		$this->registry->model('app/gprs');
		$this->registry->model('app/pay');
		$pay_id   = intval(substr($data['out_trade_no'], strrpos($data['out_trade_no'], '-') + 1));
		$pay_info = $this->model_app_pay->getPayInfo($pay_id);

		if (empty($pay_info))
		{
			return false;
		}

		/*
		 * 判断该订单是否已经处理过，如果处理过直接返回成功
		 */
		if ($pay_info['is_paid'] == 1)
		{
			return true;
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

		$pay_info['transfer_id'] = $data['transaction_id']; //微信订单号
		$pay_info['time_paid']   = date('Y-m-d H:i:s', strtotime($data['time_end'])); //付款时间

		$data['attach'] = json_decode($data['attach'], true); //附加数据
		$this->checkOrg($data['attach']['partner_id']); //机构认证

		/**
		 * 获取购买的套餐数据
		 */
		$pack_info = $this->mem_get("GPRS-PACK-{$card_info['card_iccid']}-{$data['attach']['pack_id']}");
		if (empty($pack_info))
		{
			$pack_info = $this->model_app_pack->getPack($data['attach']['pack_id'], $this->_org_id);
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

			return true;
		}

		return false;
	}

	private function _queryOrder($transaction_id)
	{
		$input = new WxPayOrderQuery();//查询订单
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS")
		{
			return true;
		}

		return false;
	}
}
?>