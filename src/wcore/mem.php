<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: wcore/mem.php
 * 简述: 专门用于提供各种memcached操作
 * 作者: woods·zhang  ->  hoojar@163.com -> http://www.hecart.com/
 * 版本: $Id: mem.php 364 2016-07-05 10:03:45Z zhangsl $
 *
 * 版权 2006-2016, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006-2016, Hoojar Studio All Rights Reserved.
 *
 */
class wcore_mem
{
	/**
	 * memcached 的连接对象
	 *
	 * @var Memcache
	 */
	public $object = null;

	/**
	 * MEM缓冲的有效期,以分钟为单位
	 *
	 * @var int
	 */
	public $expire = 30;

	/**
	 * 存储数据时KEY的前缀
	 *
	 * @var string
	 */
	public $prefix = '';

	/**
	 * 存储数据时的方式 0为一般MEMCACHE_COMPRESSED为压缩存储
	 *
	 * @var int
	 */
	private $_flag = 0;

	/**
	 * 是否开启使用MEM功能
	 *
	 * @var boolean
	 */
	private $_use = true;

	/**
	 * 是否持久连接
	 *
	 * @var boolean
	 */
	private $_pconnect = false;

	/**
	 * 构造函数，初始化memcached
	 *
	 * @param mixed   $servers  服务器主机或服务器数组 host:port
	 * @param boolean $use      是否使用缓存服务
	 * @param integer $expire   MEM的有效期,以分钟为单位
	 * @param string  $prefix   存储数据时KEY的前缀
	 * @param boolean $pconnect 连接服务器是否以长连接
	 */
	public function __construct($servers, $use = true, $expire = 30, $prefix = '', $pconnect = false)
	{
		/**
		 * 是否可以使用MEM功能
		 */
		if (!$this->_use = $use)
		{
			return;
		}

		$this->expire    = (int)$expire;//有效期时间
		$this->prefix    = $prefix;//KEY的前缀
		$this->_pconnect = $pconnect;//是否为长连接
		$this->object    = new Memcache();
		$timeout         = defined('MEM_TIMEOUT') ? MEM_TIMEOUT : 1;//连接MEM超时时间以秒为单位

		/**
		 * 判断是否为长连接
		 * 如果max_execution_time小于等于0代表SHELL下执行程序，永不超时，需采用长连接
		 */
		if (ini_get('max_execution_time') <= 0)
		{
			$timeout         = 0;
			$this->_pconnect = true;
		}

		/**
		 * 增加MEMCACHE服务器
		 */
		if (is_array($servers))
		{
			$links  = array();
			$weight = count($servers);
			foreach ($servers as $v)
			{
				$trs  = $this->_host2port($v);
				$link = $this->object->addServer($trs[0], $trs[1], $this->_pconnect, mt_rand(1, $weight), $timeout);
				if ($link)
				{
					$links[] = $link;
				}
				else
				{
					$this->halt("Can not connect memcache host: {$trs[0]}", false);
				}
			}
			if (empty($links) || !@$this->object->getversion())
			{
				$this->_use = false;
				$this->halt('Can not connect all memcache host');
			}
		}
		else
		{
			$trs = $this->_host2port($servers);
			if ($this->_pconnect)
			{
				$link = @$this->object->pconnect($trs[0], $trs[1], $timeout);
			}
			else
			{
				$link = @$this->object->connect($trs[0], $trs[1], $timeout);
			}
			if (!$link)
			{
				$this->_use = false;
				$this->halt("Can not connect memcache host: {$trs[0]}");
			}
		}

		/**
		 * 使用压缩存储数据
		 */
		if (function_exists('gzcompress'))
		{
			$this->_flag = MEMCACHE_COMPRESSED;
		}
	}

	/**
	 * 析构函数 关闭连接
	 */
	public function __destruct()
	{
		if (!$this->_pconnect)
		{
			$this->close();
		}
	}

	/**
	 * 执行redis所提供的函数
	 *
	 * @param string $name      函数名
	 * @param mixed  $arguments 　参数
	 * @return bool|mixed　执行结果
	 */
	public function __call($name, $arguments)
	{
		if ($this->object && method_exists($this->object, $name))
		{
			return call_user_func_array(array(
				&$this->object,
				$name
			), $arguments);
		}

		return false;
	}

	/**
	 * 组合Memcache的KEY
	 *
	 * @param string $t 数据类型字
	 * @param string $k 数据名称
	 * @return string
	 */
	private function _makey($t, $k)
	{
		return "{$this->prefix}{$t}-{$k}";
	}

	/**
	 * 根据地址获取主机与端口
	 *
	 * @param string $server 服务格式地址
	 * @return array array(host,port)
	 */
	private function _host2port($server)
	{
		$v = explode(':', $server);
		if (($v[0] == 'unix') && (!is_numeric($v[1])))
		{
			return array(
				$server,
				0
			);
		}

		return $v;
	}

	/**
	 * 存储数据
	 *
	 * @param string $type   数据类型说明
	 * @param string $key    数据名称
	 * @param mixed  $value  数据
	 * @param int    $expire 有效期以分钟为单位,为0时则永不过期只有当MEM服务器关闭才过期
	 * @return boolean 存储成功为true反知为false
	 */
	public function set($type, $key, &$value, $expire = -1)
	{
		if (!$this->_use)
		{
			return false;
		}

		//mt_rand(1, 120)为增加一个两分钟内的随机值，以避免对应缓存的同时更新
		if ($expire > 0)
		{
			$expire = $expire * 60 + mt_rand(1, 120);
		}
		elseif ($expire < 0)
		{
			$expire = $this->expire * 60 + mt_rand(1, 120);
		}

		return $this->object->set($this->_makey($type, $key), $value, $this->_flag, $expire);
	}

	/**
	 * 获取数据
	 *
	 * @param string $type    数据类型说明
	 * @param string $key     数据名称
	 * @param mixed  $default 默认值
	 * @return mixed 获取的缓存值
	 */
	public function &get($type, $key, $default = null)
	{
		if (!$this->_use || (isset($_GET['nocache']) && $type != 'session'))
		{
			return $default;
		}

		$prefix = $this->_makey($type, $key);
		$res    = $this->object->get($prefix);

		return $res;
	}

	/**
	 * 为某个数字类型的数据名称增值
	 *
	 * @param string $type  数据类型说明
	 * @param string $key   数据名称
	 * @param int    $value 要增加的数值
	 * @return mixed 成功为增加后的值失败则返回false
	 */
	public function increment($type, $key, $value = 1)
	{
		if (!$this->_use || !is_numeric($value))
		{
			return false;
		}

		$prefix = $this->_makey($type, $key);
		$this->object->add($prefix, 0); //创建此项方可增

		return $this->object->increment($prefix, $value);
	}

	/**
	 * 为某个数字类型的数据名称减值
	 *
	 * @param string $type  数据类型说明
	 * @param string $key   数据名称
	 * @param int    $value 要减的数值
	 * @return mixed 成功为减后的值失败则返回false
	 */
	public function decrement($type, $key, $value = 1)
	{
		if (!$this->_use || !is_numeric($value))
		{
			return false;
		}

		$prefix = $this->_makey($type, $key);
		$this->object->add($prefix, 0); //创建此项方可减

		return $this->object->decrement($prefix, $value);
	}

	/**
	 * 将数据存储到消息队列
	 *
	 * @param string $type  数据类型说明
	 * @param string $key   数据名称
	 * @param mixed  $value 数据
	 * @return bool 存储成功返回存储长度失败为false
	 */
	public function push($type, $key, $value)
	{
		if (!$this->_use)
		{
			return false;
		}

		$incr   = $this->increment("w{$type}", $key);
		$prefix = $this->_makey("{$type}{$incr}", $key);

		return $this->object->set($prefix, $value, $this->_flag);
	}

	/**
	 * 将数据弹出消息队列并删除key的值
	 *
	 * @param string $type    数据类型说明
	 * @param string $key     数据名称
	 * @param mixed  $default 默认值
	 * @return mixed 获取的队列值
	 */
	public function &pop($type, $key, $default = false)
	{
		if (!$this->_use)
		{
			return $default;
		}

		$incr   = $this->increment("r{$type}", $key);
		$prefix = $this->_makey("{$type}{$incr}", $key);
		$res    = $this->object->get($prefix);
		if (false === $res)
		{
			$this->decrement("r{$type}", $key);
		}
		else
		{
			$this->object->delete($prefix);
		}

		return $res;
	}

	/**
	 * 删除某个数据名称当中的数据
	 *
	 * @param string $type 数据类型说明
	 * @param string $key  数据名称
	 * @return boolean 删除成功为true反知为false
	 */
	public function del($type, $key)
	{
		if (!$this->_use)
		{
			return false;
		}

		return $this->object->delete($this->_makey($type, $key));
	}

	/**
	 * 删除某个数据名称当中的数据del的别名函数
	 *
	 * @param string $type 数据类型说明
	 * @param string $key  数据名称
	 * @return boolean 删除成功为true反知为false
	 */
	public function delete($type, $key)
	{
		return $this->del($type, $key);
	}

	/**
	 * 清空MEM当中的所有数据
	 *
	 * @return boolean 清空成功为true反知为false
	 */
	public function flush()
	{
		if (!$this->_use)
		{
			return false;
		}

		return $this->object->flush();
	}

	/**
	 * 关闭MEM对象
	 *
	 * @return boolean 关闭成功为true反知为false
	 */
	public function close()
	{
		if ($this->_use && is_object($this->object))
		{
			return $this->object->close();
		}

		return true;
	}

	/**
	 * 获取是否开启MEM功能
	 *
	 * @return boolean 可用为true反知为false
	 */
	public function used()
	{
		return $this->_use;
	}

	/**
	 * 类与到严重错误时停执行
	 *
	 * @param string  $msg  提示的信息
	 * @param boolean $exit 是否退出
	 */
	public function halt($msg, $exit = true)
	{
		if (defined('DEBUG_LOG') && DEBUG_LOG)
		{
			file_put_contents(DIR_ROOT . '/system/logs/mem.log', date('Y-m-d H:i:s') . " - Error: {$msg}\n", FILE_APPEND);
		}

		if ($exit)
		{
			exit($msg);
		}
	}
}
?>