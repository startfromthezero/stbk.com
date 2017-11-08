<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: wcore/session.php
 * 简述: 专门处理SESSION的库
 * 作者: woods·zhang  ->  hoojar@163.com -> http://www.hecart.com/
 * 版本: $Id: session.php 292 2016-06-20 14:31:31Z zhangsl $
 *
 * 版权 2006-2014, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006-2014, Hoojar Studio All Rights Reserved.
 *
 */
class wcore_session
{
	/**
	 * SESSION数组
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * 将SESSION存储在哪种物质类型中
	 *
	 * @var string 存储方式如下
	 * db	max:65535	会话内容存储在数据库表中
	 * mdb	max:255		会话内容存储在数据库内存表中
	 * mem	max:unlimit	会话内容存储在Memcache缓存中
	 * file	max:unlimit	会话内容存储在文件中
	 * dir	max:unlimit	会话内容存储在分目录的文件中
	 */
	private $_type = 'file';

	/**
	 * 当存储方式为file或dir时SESSION文件所存储的路径
	 *
	 * @var string 会话文件存储路径
	 */
	private $_path = '/tmp';

	/**
	 * 连接数据的模块对象
	 *
	 * @var wcore_mysql
	 */
	private $_db = null;

	/**
	 * 当SESSION存储在数据库中时要操作的数据表
	 *
	 * @var string 数据库表名称 (分普通表[session_wcore]与内存表[session_mem])
	 */
	private $_opt = 'session_wcore';

	/**
	 * 连接MEM的模块对象
	 *
	 * @var wcore_mem
	 */
	private $_mem = null;

	/**
	 * SESSION的寿命，默认为30分钟以秒为单位
	 *
	 * @var integer
	 */
	private $_life_time = 1800;

	/**
	 * SESSION 前缀
	 *
	 * @var string
	 */
	private $_prefix = 'ws';

	/**
	 * IP地址
	 *
	 * @var integer
	 */
	private $_ip = '';

	/**
	 * 初始化SESSION
	 *
	 * @param string  $type   会话的存储方式
	 * @param integer $ltime  会话寿命时间以分钟为单位
	 * @param string  $path   会话文件存储的路径
	 * @param string  $prefix 会话文件前缀
	 * @param boolean $start  是否马上启用SESSION处理
	 */
	public function __construct($type = 'file', $ltime = 30, $path = '', $prefix = 'ws', $start = true)
	{
		$this->_prefix = $prefix;
		$this->_type   = strtolower($type);
		if ($this->_type == 'file' || $this->_type == 'dir')
		{
			$this->_path = ($path && file_exists($path)) ? $path : get_cfg_var('session.save_path');
			wcore_fso::make_dir($this->_path); //处理SESSION存储的路径
		}

		$this->_ip        = wcore_utils::get_ip();
		$this->_life_time = ($ltime && is_numeric($ltime)) ? $ltime * 60 : get_cfg_var('session.gc_maxlifetime');
		session_set_save_handler(array(&$this, 'open'), array(&$this, 'close'),  array(&$this, 'read'),
								 array(&$this, 'write'),array(&$this, 'destroy'),array(&$this, 'gc'));
		register_shutdown_function('session_write_close');

		/**
		 * 是否马上启用SESSION处理
		 */
		if ($start)
		{
			session_start();
		}

		$this->data = &$_SESSION; //将SESSION数据赋值给公共data成员变量
	}

	/**
	 * 获取会话
	 *
	 * @param string $key     会话名
	 * @param string $default 默认值
	 * @return mixed null 会话值
	 */
	public function get($key, $default = null)
	{
		return isset($this->data[$key]) ? $this->data[$key] : $default;
	}

	/**
	 * 设置会话
	 *
	 * @param string $key   会话名
	 * @param mixed  $value 会话值
	 */
	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * 删除会话
	 *
	 * @param string $key 会话名
	 */
	public function del($key)
	{
		unset($this->data[$key]);
	}

	/**
	 * 打开 SESSION
	 *
	 * @param string $path 会话存储路径
	 * @param string $name 会话名称
	 * @return boolean
	 */
	public function open($path, $name)
	{
		if ($this->_type == 'db' || $this->_type == 'mdb') //以数据库方式来处理SESSION
		{
			$this->_db = wcore_object::mdb();
			if ($this->_type == 'mdb')
			{
				$this->_opt = 'session_mem';
			}
		}
		else if ($this->_type == 'mem') //以Memcache缓冲方式来处理SESSION
		{
			$this->_mem         = (MEM_USTYPE == 'redis') ? wcore_object::mds() : wcore_object::mem();
			$this->_mem->expire = $this->_life_time / 60;
		}

		$this->gc(0); //删除失效的SESSION
		return true;
	}

	/**
	 * 关闭SESSION
	 *
	 * @return boolean
	 */
	public function close() { return true; }

	/**
	 * 获取SESSION编号
	 *
	 * @return string
	 */
	public function get_id()
	{
		return session_id();
	}

	/**
	 * 读取SESSION内容
	 *
	 * @param string $sid 会话唯一标识
	 * @return string 会话值
	 */
	public function read($sid)
	{
		/**
		 * 以数据库方式来处理SESSION
		 */
		if ($this->_type == 'db' || $this->_type == 'mdb')
		{
			$res = $this->_db->fetch_row("SELECT sData FROM {$this->_opt} WHERE sId = '{$sid}';");

			return ($res) ? $res['sData'] : '';
		}

		/**
		 * 以Memcache缓冲方式来处理SESSION
		 */
		if ($this->_type == 'mem')
		{
			return $this->_mem->get('session', $sid);
		}

		/**
		 * 以文件系统的方式来处理SESSION
		 */
		if ($this->_type == 'dir')
		{
			$sfile = "{$this->_path}/{$sid[0]}/{$this->_prefix}-{$sid}";
		}
		else
		{
			$sfile = "{$this->_path}/{$this->_prefix}-{$sid}";
		}

		if (!file_exists($sfile))
		{
			return '';
		}

		return (string)file_get_contents($sfile);
	}

	/**
	 * 写入SESSION内容
	 *
	 * @param string $sid   会话唯一标识
	 * @param string $sdata 会话内容
	 * @return boolean
	 */
	public function write($sid, $sdata)
	{
		/**
		 * SESSION数据为空则清除先前数据
		 */
		if (empty($sdata))
		{
			$this->destroy($sid);

			return false;
		}

		/**
		 * 以数据库方式来处理SESSION
		 */
		if ($this->_type == 'db' || $this->_type == 'mdb')
		{
			$expires = time() + $this->_life_time; //SESSION的有效期
			$sql     = "REPLACE INTO {$this->_opt} (sId, sData, sIp, sExpires) VALUES ('{$sid}', '{$sdata}', '{$this->_ip}', {$expires})";
			$this->_db->query($sql);

			return ($this->_db->affected_rows() > 0) ? true : false;
		}

		/**
		 * 以Memcache缓冲方式来处理SESSION
		 */
		if ($this->_type == 'mem')
		{
			$expires = $this->_life_time / 60; //SESSION的有效期
			return $this->_mem->set('session', $sid, $sdata, $expires);
		}

		/**
		 * 以文件系统的方式来处理SESSION
		 */
		if ($this->_type == 'dir')
		{
			$sfile = "{$this->_path}/{$sid[0]}";
			wcore_fso::make_dir($sfile); //处理SESSION存储的路径
			$sfile = "{$sfile}/{$this->_prefix}-{$sid}";
		}
		else
		{
			$sfile = "{$this->_path}/{$this->_prefix}-{$sid}";
		}

		return file_put_contents($sfile, $sdata);
	}

	/**
	 * 清除SESSION
	 *
	 * @param string $sid 会话唯一标识
	 * @return boolean 清除成功返回true否则为false
	 */
	public function destroy($sid = '')
	{
		if (empty($sid))
		{
			$sid = $this->get_id();
		}

		/**
		 * 以数据库方式来处理SESSION
		 */
		if ($this->_type == 'db' || $this->_type == 'mdb')
		{
			$this->_db->query("DELETE FROM {$this->_opt} WHERE sId = '{$sid}'");

			return ($this->_db->affected_rows() > 0) ? true : false;
		}

		/**
		 * 以Memcache缓冲方式来处理SESSION
		 */
		if ($this->_type == 'mem')
		{
			return $this->_mem->del('session', $sid);
		}

		/**
		 * 以文件系统的方式来处理SESSION
		 */
		if ($this->_type == 'dir')
		{
			$sfile = "{$this->_path}/{$sid[0]}/{$this->_prefix}-{$sid}";
		}
		else
		{
			$sfile = "{$this->_path}/{$this->_prefix}-{$sid}";
		}

		return !empty($sfile) ? @unlink($sfile) : true;
	}

	/**
	 * 定时去清除过期的SESSION
	 *
	 * @param integer $max_life_time
	 * @return boolean
	 */
	public function gc($max_life_time)
	{
		if ($this->_type == 'db' || $this->_type == 'mdb') //以数据库方式来处理SESSION
		{
			$this->_db->query("DELETE FROM {$this->_opt} WHERE sExpires < " . time());
		}
		else if ($this->_type == 'file') //以文件系统的方式来处理SESSION
		{
			$this->_del_sfile($this->_path);
		}
		else if ($this->_type == 'dir') //以目录分层文件的方式来处理SESSION
		{
			$dir = 'abcdefghijklmnopqrstuvwxyz';
			$len = strlen($dir);
			for ($i = 0; $i < $len; ++$i)
			{
				$this->_del_sfile("{$this->_path}/{$dir[$i]}");
			}
		}

		return true;
	}

	/**
	 * 删除session文件
	 *
	 * @param string  $dir      会话文件所在目录
	 * @param boolean $no_check 是否进行过期判断
	 * @return boolean
	 */
	private function _del_sfile($dir, $no_check = false)
	{
		if ($no_check) //直接删除SESSION文件不进行过期判断
		{
			foreach (glob("{$dir}/{$this->_prefix}-*") as $filename)
			{
				@unlink($filename);
			}

			return true;
		}

		foreach (glob("{$dir}/{$this->_prefix}-*") as $filename)
		{
			if (filemtime($filename) + $this->_life_time < time())
			{
				@unlink($filename);
			}
		}

		return true;
	}

	/**
	 * 清空所有SESSION
	 *
	 * @return boolean
	 */
	public function cleanup()
	{
		switch ($this->_type)
		{
			case 'mem':
				return $this->_mem->flush();
			case 'db':
			case 'mdb':
				return $this->_db->truncate($this->_opt);
			case 'file':
				return $this->_del_sfile($this->_path, true);
			case 'dir':
				$dir = 'abcdefghijklmnopqrstuvwxyz';
				$len = strlen($dir);
				for ($i = 0; $i < $len; ++$i)
				{
					wcore_fso::rm_dir($dir[$i]);
				}
			default:
				return true;
		}
	}
}
?>