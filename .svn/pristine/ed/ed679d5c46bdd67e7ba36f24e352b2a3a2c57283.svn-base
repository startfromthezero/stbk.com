<?php
class Controller extends modules_mem
{
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
	 * 模板名称
	 *
	 * @var string
	 */
	private $_tplname;

	/**
	 * @param Registry $registry 注册对象
	 */
	public function __construct(&$registry)
	{
		parent::__construct();
		$this->registry = $registry;
		$this->url      = $registry->get('url');
		$this->config   = $registry->get('config');
		$this->request  = $registry->get('request');
		$this->session  = $registry->get('session');
		$this->response = $registry->get('response');
		$this->language = $registry->get('language');
		$this->currency = $registry->get('currency');
		$this->document = $registry->get('document');
		$this->customer = $registry->get('customer');
		$this->_tplname = $registry->get('tplname');
	}

	/**
	 * 获取注册对象
	 *
	 * @param string $key 对象名称
	 * @return object     对象值
	 */
	public function __get($key)
	{
		return $this->registry->get($key);
	}

	/**
	 * 设置注册对象
	 *
	 * @param string $key   对象名称
	 * @param object $value 对象值
	 */
	public function __set($key, $value)
	{
		$this->registry->set($key, $value);
	}

	/**
	 * 根据模板提取生成后的内容
	 *
	 * @param string $template 模板名称
	 * @param array  $vrs      模板数据
	 * @return string          HTML
	 */
	protected function &view($template, &$vrs)
	{
		/**
		 * 判断模板文件是否存在，如不存在则使用默认模板
		 */
		$tpl_file = DIR_SITE . "/view/{$this->_tplname}/{$template}";
		if (!file_exists($tpl_file))
		{
			$tpl_file = DIR_SITE . "/view/default/{$template}";
		}

		/**
		 * 获取访问权限与修改权限,方便模板中做权限处理
		 */
		$vrs['apermission'] = $this->config->apermission; //访问权
		$vrs['mpermission'] = $this->config->mpermission; //修改权

		/**
		 * 组合数据生成HTML
		 */
		ob_start();
		extract($vrs);
		$mtpl = DIR_SITE . "/view/mhecart/{$template}";
		require((IS_MOBILE && file_exists($mtpl)) ? $mtpl : $tpl_file);
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * 缓存 - 控制器内容
	 *
	 * @param string $route 路由地址
	 * @param array  $args  参数
	 * @return string       执行结果
	 */
	protected function mem_ctrl($route, $args = array())
	{
		$mkey    = md5(DOMAIN_NAME . $route);
		$content = $this->mem_get($mkey);

		if (empty($content))
		{
			$content = $this->registry->exectrl($route, $args);
			$this->mem_set($mkey, $content);
		}

		return $content;
	}
}
?>