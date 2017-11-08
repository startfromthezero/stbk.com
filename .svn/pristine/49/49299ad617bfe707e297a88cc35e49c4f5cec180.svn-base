<?php
/**
 * 微信js
 */
class modules_wechat extends modules_mem
{
	/**
	 * @var int 公共平台APPID
	 */
	private $_appid = WX_APPID;

	/**
	 * @var int 公共平台appsecret
	 */
	private $_appsecret = WX_APPSECRET;

	/**
	 * 获取access token
	 */
	public function getAccessToken()
	{
		$access_token = $this->mem_get("ACCESS-TOKEN-{$this->_appid}");
		if (!empty($access_token))
		{
			return $access_token;
		}

		$url          = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->_appid}&secret={$this->_appsecret}";
		$res          = json_decode(wcore_utils::curl($url), true);
		$access_token = isset($res['access_token']) ? $res['access_token'] : '';
		$this->mem_set("ACCESS-TOKEN-{$this->_appid}", $access_token);

		return $access_token;
	}

	/**
	 * 获取jsapi
	 */
	public function getJsapiTicket()
	{
		$jsapi_ticket = $this->mem_get("JSAPI-TICKET-{$this->_appid}");
		if (!empty($jsapi_ticket))
		{
			return $jsapi_ticket;
		}

		$access_token = $this->getAccessToken();
		$url          = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
		$res          = json_decode(wcore_utils::curl($url), true);
		$jsapi_ticket = isset($res['ticket']) ? $res['ticket'] : '';
		$this->mem_set("JSAPI-TICKET-{$this->_appid}", $jsapi_ticket);

		return $jsapi_ticket;
	}

	/**
	 * 获取JSAPI加密后的所有参数
	 *
	 * @return string
	 */
	public function getJsapiParameters()
	{
		$string     = '';
		$parameters = array(
			'noncestr'     => $this->createNonce(),
			'jsapi_ticket' => $this->getJsapiTicket(),
			'timestamp'    => time(),
			'url'          => substr(HTTP_STORE, 0, -1) . $_SERVER['REQUEST_URI'],
		);
		ksort($parameters);
		foreach ($parameters as $k => $v)
		{
			$string .= "{$k}={$v}&";
		}
		$parameters['appid']     = $this->_appid;
		$parameters['signature'] = sha1(substr($string, 0, -1));

		return $parameters;
	}

	/**
	 * 创建临时字符串
	 *
	 * @param int $length 创建长度
	 * @return string
	 */
	public function createNonce($length = 32)
	{
		$str   = '';
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
		for ($i = 0; $i < $length; $i++)
		{
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}

		return $str;
	}

	/**
	 * 获取微信用户openid
	 */
	public function getOpenid()
	{
		if (!isset($_GET['code']))
		{
			$url = urlencode(HTTP_STORE . $_GET['route']);
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . WX_APPID . "&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=sptcj#wechat_redirect";
			Header("Location: $url");
			exit();
		}

		/**
		 * 根据得到的微信授权code登录，获取微信open id
		 */
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . WX_APPID . '&secret=' . WX_APPSECRET . "&code={$_GET['code']}&grant_type=authorization_code";
		$res = json_decode(wcore_utils::curl($url), true);

		return isset($res['openid']) ? $res['openid'] : 0;
	}
}
?>