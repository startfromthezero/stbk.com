<?php
class Model extends modules_mem
{
	/**
	 * 系统编号
	 *
	 * @var int
	 */
	public $store_id = 0;

	/**
	 * 系统语言编号
	 *
	 * @var int
	 */
	public $language_id = 1;

	/**
	 * @var Registry
	 */
	protected $registry;

	/**
	 * @var Url
	 */
	protected $url;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * @var Language
	 */
	protected $language;

	/**
	 * @var Currency
	 */
	protected $currency;

	/**
	 * @var wcore_session
	 */
	protected $session;

	/**
	 * @var Document
	 */
	protected $document;

	/**
	 * @var Customer
	 */
	protected $customer;

	/**
	 * 构造函数
	 *
	 * @param $registry Registry
	 */
	public function __construct($registry)
	{
		parent::__construct();
		$this->registry    = $registry;
		$this->url         = $registry->get('url');
		$this->config      = $registry->get('config');
		$this->request     = $registry->get('request');
		$this->session     = $registry->get('session');
		$this->response    = $registry->get('response');
		$this->language    = $registry->get('language');
		$this->currency    = $registry->get('currency');
		$this->document    = $registry->get('document');
		$this->customer    = $registry->get('customer');
		$this->store_id    = intval($this->config->get('config_store_id'));
		$this->language_id = intval($this->config->get('config_language_id'));
	}

	public function __get($key)
	{
		return $this->registry->get($key);
	}

	public function __set($key, $value)
	{
		$this->registry->set($key, $value);
	}

	/**
	 * 获取散列值与散列后密码
	 *
	 * @param $password 用户密码
	 * @return array salt|pswd
	 */
	public function salt2pwd($password)
	{
		$salt = substr($password, 0, 9);
		$pswd = md5(md5($salt) . $password);

		return array(
			'salt' => $salt,
			'pwd'  => $pswd
		);
	}

	/**
	 * 用户姓名组合
	 *
	 * @param string $as 表别名
	 * @return string 组合后的姓名
	 */
	public function fullname($as = '')
	{
		/**
		 * 判断姓是否为汉字,中国人的姓是在前面,其他国家的是姓在后
		 */
		return "IF({$as}lastname != '',
					IF(LENGTH({$as}lastname)=CHAR_LENGTH({$as}lastname),
						CONCAT({$as}firstname, ' ', {$as}lastname),
						CONCAT({$as}lastname,{$as}firstname)),
					{$as}firstname) ";
	}

	/**
	 * 收货人姓名组合
	 *
	 * @param string $as 表别名
	 * @return string 组合后的姓名
	 */
	public function consignee($as = '')
	{
		/**
		 * 判断姓是否为汉字,中国人的姓是在前面,其他国家的是姓在后
		 */
		return "IF({$as}shipping_lastname != '',
					IF(LENGTH({$as}shipping_lastname)=CHAR_LENGTH({$as}shipping_lastname),
						CONCAT({$as}shipping_firstname, ' ', {$as}shipping_lastname),
						CONCAT({$as}shipping_lastname,{$as}shipping_firstname)),
					{$as}shipping_firstname) ";
	}
}
?>