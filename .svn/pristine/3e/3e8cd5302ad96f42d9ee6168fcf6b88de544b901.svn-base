<?php
class Config extends modules_mem
{
	/**
	 * 配置数据组
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * 是否拥有访问权限
	 *
	 * @var bool
	 */
	public $apermission = false;

	/**
	 * 是否拥有更改权限
	 *
	 * @var bool
	 */
	public $mpermission = false;

	/**
	 * 从数据库中获取网站列表数据并格式化以域名为数组KEY
	 */
	public function __construct()
	{
		parent::__construct();

		/**
		 * 分析当前域名与哪个数据匹配，先快速定位以域名来判断是否在网站列表数组中
		 */
		$store_info = array();
		$domain     = strtolower(DOMAIN_NAME);
		$store_res  = $this->hash_sql('SELECT * FROM ' . DB_PREFIX . 'store', 'domain');
		if (isset($store_res[$domain]))
		{
			$store_info = $store_res[$domain];
		}
		else
		{
			foreach ($store_res as $v)
			{
				if (preg_match("/{$v['domain']}/", $domain))
				{
					$store_info = $v;
					break;
				}
			}
		}

		/**
		 * 设置系统参数到配置数据组中
		 */
		$this->set('config_store_id', $store_id = empty($store_info) ? 0 : (int)$store_info['store_id']);
		$res = $this->mem_sql('SELECT * FROM ' . DB_PREFIX . "setting WHERE store_id = {$store_id}", DB_GET_ALL);
		foreach ($res as $setting)
		{
			$this->set($setting['key'], $setting['serialized'] ? unserialize($setting['value']) : $setting['value']);
		}
	}

	/**
	 * 获取配置
	 *
	 * @param $key 配置名
	 * @return null 配置值
	 */
	public function get($key)
	{
		return (isset($this->data[$key]) ? $this->data[$key] : null);
	}

	/**
	 * 设置配置
	 *
	 * @param $key   配置名
	 * @param $value 配置值
	 */
	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * 根据配置名检测是有配置
	 *
	 * @param $key 配置名
	 * @return bool
	 */
	public function has($key)
	{
		return isset($this->data[$key]);
	}

	/**
	 * 加载配置文件
	 *
	 * @param $filename 配置文件
	 */
	public function load($filename)
	{
		$file = DIR_ROOT . "/system/config/{$filename}.php";
		if (file_exists($file))
		{
			$_ = array();
			require($file);
			$this->data = array_merge($this->data, $_);
		}
		else
		{
			trigger_error('Error: Could not load config ' . $filename . '!');
			exit();
		}
	}
}
?>