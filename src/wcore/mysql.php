<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: wcore/mysql.php
 * 简述: 专门用于操作MYSQL数据的函数集
 * 作者: woods·zhang  ->  hoojar@163.com -> http://www.hecart.com/
 * 版本: $Id: mysql.php 371 2016-07-06 10:06:35Z zhangsl $
 *
 * 版权 2006-2014, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006-2014, Hoojar Studio All Rights Reserved.
 *
 */
class wcore_mysql extends wcore_db
{
	/**
	 * 连接mysql数据库服务器主机
	 *
	 * @var string 主机名
	 */
	private $_db_host = 'localhost';

	/**
	 * 连接mysql数据库的端口号
	 *
	 * @var integer
	 */
	private $_db_port = 3306;

	/**
	 * 连接mysql数据库服务器的用户名
	 *
	 * @var string 用户名
	 */
	private $_db_user = 'root';

	/**
	 * 连接mysql数据库服务器的用户密码
	 *
	 * @var string 密码
	 */
	private $_db_key = '';

	/**
	 * 连接mysql数据库服务器的用户密码
	 *
	 * @var string 数据库名
	 */
	private $_db_name = 'mysql';

	/**
	 * 是否持久连接
	 *
	 * @var boolean
	 */
	private $_pconnect = false;

	/**
	 * 数据库连接句柄
	 *
	 * @var object 连接句柄
	 */
	public $db_link = null;

	/**
	 * 执行SQL语句的资源句柄
	 *
	 * @var object 资源句柄
	 */
	public $result = null;

	/**
	 * 获取数据可以接受以下值：MYSQL_ASSOC，MYSQL_NUM 和 MYSQL_BOTH
	 *
	 * @var integer 数组类型
	 */
	public $result_type = MYSQL_ASSOC;

	/**
	 * 增加记录时自动编号的最后一个ID号
	 *
	 * @var integer
	 */
	public $insert_id = 0;

	/**
	 * 获取单条数据(一维数组)
	 *
	 * @var array 执行SQL语句的数据组结果
	 */
	public $fetch_row = array();

	/**
	 * 获取所有记录内容(多维数组)
	 *
	 * @var array 执行SQL语句的数据组结果
	 */
	public $fetch_all = array();

	/**
	 * 执行SELECT语句时获取了多少条记录
	 *
	 * @var integer 记录数
	 */
	public $num_rows = 0; //执行SELECT语句时获取了多少条记录

	/**
	 * 执行除SELECT语句所影响的记录行数
	 *
	 * @var integer 影响行数
	 */
	public $affected_rows = 0; //执行除SELECT语句所影响的记录行数

	/**
	 * 连接数据库主机
	 *
	 * @param string  $db_host  连接MYSQL数据库服务器地址
	 * @param string  $db_user  连接MYSQL数据库服务器的用户名
	 * @param string  $db_key   连接MYSQL数据库服务器的用户密码
	 * @param string  $db_name  连接MYSQL数据库服务数据库名
	 * @param string  $charset  连接MYSQL数据库所采用的字符集
	 * @param int     $db_port  连接MYSQL数据库服务器端口号
	 * @param boolean $pconnect 连接MYSQL数据库服务数据库名
	 */
	public function __construct($db_host = 'localhost', $db_user = 'root', $db_key = '', $db_name = 'mysql', $charset = 'utf8', $db_port = 3306, $pconnect = false)
	{
		$this->charset   = $charset;
		$this->_db_host  = $db_host; //连接MYSQL数据库服务器主机
		$this->_db_port  = $db_port; //连接MYSQL数据的端口号
		$this->_db_user  = $db_user; //连接MYSQL数据库服务器的用户名
		$this->_db_key   = $db_key; //连接MYSQL数据库服务器的用户密码
		$this->_db_name  = $db_name; //连接MYSQL数据库服务器的用户密码
		$this->_pconnect = $pconnect;
		$this->open_db($db_name); //打开数据库
	}

	/**
	 * 析构函数
	 *
	 */
	public function __destruct()
	{
		$this->free();
		if (!$this->_pconnect)
		{
			$this->close(); //非持久连接将关闭连接
		}
	}

	/**
	 * 释放查询值,释放资源
	 *
	 * @return boolean 成功为true失败为flase
	 */
	public function free()
	{
		if (is_resource($this->result))
		{
			return mysql_free_result($this->result);
		}

		return true;
	}

	/**
	 * 关闭数据库连接
	 *
	 * @return boolean 成功为true失败为flase
	 */
	public function close()
	{
		if (is_resource($this->db_link))
		{
			return mysql_close($this->db_link);
		}

		return true;
	}

	/**
	 * 获取服务器信息
	 *
	 * @return string 服务器信息
	 */
	public function server_info()
	{
		return mysql_get_server_info($this->db_link);
	}

	/**
	 * 打开数据连接，并选择数据库
	 *
	 * @param string $db_name 数据库名
	 * @return object 连接成功的句柄
	 */
	private function &open_db($db_name = 'mysql')
	{
		/**
		 * 判断是否为长连接
		 * 如果max_execution_time小于等于0代表SHELL下执行程序，永不超时，需采用长连接
		 */
		if (ini_get('max_execution_time') <= 0)
		{
			$this->_pconnect = true;
		}

		$db_host = ($this->_db_port == 3306) ? $this->_db_host : "{$this->_db_host}:{$this->_db_port}";
		if ($this->_pconnect) //以持久连接数据方式打开
		{
			$this->db_link = @mysql_pconnect("{$db_host}", $this->_db_user, $this->_db_key) or $this->halt('Connect MySql Server Error.', __LINE__, mysql_error());
		}
		else
		{
			$this->db_link = @mysql_connect("{$db_host}", $this->_db_user, $this->_db_key) or $this->halt('Connect MySql Server Error.', __LINE__, mysql_error());
		}
		$this->dbexec("SET NAMES '{$this->charset}'", false);
		$this->dbexec("SET time_zone = '" . date('P') . "'", false);
		$this->select_db($db_name);

		return $this->db_link;
	}

	/**
	 * 选择数据库
	 *
	 * @param string $db_name 数据库名
	 * @return boolean 选择成功为true失败为flase
	 */
	public function select_db($db_name)
	{
		if (empty($db_name))
		{
			return false;
		}
		if (!mysql_select_db($db_name, $this->db_link)) //如果没有连接成功则弹出出错信息
		{
			$this->halt("{$db_name} database did not find.", __LINE__, mysql_error($this->db_link));

			return false;
		}

		return true;
	}

	/**
	 * 返回MYSQL连接句柄
	 *
	 * @return object 数据库连接句柄
	 */
	public function &db_link() { return $this->db_link; }

	/**
	 * 执行SQL语句，并返回结果
	 *
	 * @param string  $sql      要执行的sql语句
	 * @param boolean $show_err 若有错误是否提示
	 * @return mixed 有结果才返回数据
	 */
	public function &query($sql, $show_err = true)
	{
		if (empty($sql))
		{
			$false = false;

			return $false;
		}

		if ($show_err)
		{
			$this->result = @mysql_query($sql, $this->db_link) or $this->halt($sql, __LINE__, mysql_error($this->db_link));
		}
		else
		{
			$this->result = @mysql_query($sql, $this->db_link);
		}

		return $this->result;
	}

	/**
	 * 执行事务SQL语句，不返回结果
	 *
	 * @param string  $sql      要执行的sql语句
	 * @param boolean $show_err 若有错误是否提示
	 * @return boolean 有SQL执行返回真反知为假
	 */
	public function dbexec($sql, $show_err = true)
	{
		if (empty($sql))
		{
			return false;
		}

		if ($show_err)
		{
			@mysql_query($sql, $this->db_link) or $this->halt($sql, __LINE__, mysql_error($this->db_link));
		}
		else
		{
			@mysql_query($sql, $this->db_link);
		}

		return true;
	}

	/**
	 * 获取一条记录数据内容存入数组中
	 *
	 * @param string $sql SQL语句
	 * @return mixed 执行成功有数据则返回数组,失败返回flase
	 */
	public function &fetch_row($sql = '')
	{
		if ($sql)
		{
			$this->query($sql);
		}

		$data = array();
		if (!is_resource($this->result))
		{
			return $data;
		}

		$this->fetch_row = mysql_fetch_array($this->result, $this->result_type);
		if (!is_array($this->fetch_row))
		{
			$this->fetch_row = $data;
		}
		$this->free();

		return $this->fetch_row;
	}

	/**
	 * 获取所有数据内容存入数组中
	 *
	 * @param string $sql SQL语句
	 * @return mixed 执行成功有数据则返回数组,失败返回flase
	 */
	public function &fetch_all($sql = '')
	{
		if ($sql)
		{
			$this->query($sql);
		}

		$this->fetch_all = array();
		if (is_resource($this->result))
		{
			while ($row = mysql_fetch_array($this->result, $this->result_type))
			{
				$this->fetch_all[] = $row;
			}
		}
		$this->free();

		return $this->fetch_all;
	}

	/**
	 * 移动内部数据行指针
	 *
	 * @param integer $nu 移到哪一条记录
	 * @return boolean 如果成功则返回 TRUE，失败则返回 FALSE。
	 */
	public function seek($nu)
	{
		if (!is_numeric($nu))
		{
			return false;
		}

		if (!is_resource($this->result))
		{
			$this->halt('Not found data.', __LINE__, mysql_error($this->db_link));
		}
		$seek_result = @mysql_data_seek($this->result, $nu) or $this->halt('Seek over records .', __LINE__, mysql_error($this->db_link));

		return $seek_result;
	}

	/**
	 * 获取最后增加记录的自动编号
	 *
	 * @return integer 自动编号
	 */
	public function insert_id()
	{
		$this->insert_id = mysql_insert_id($this->db_link);

		return $this->insert_id;
	}

	/**
	 * 执行非SELECT语句所影响的记录行数
	 *
	 * @return integer 影响记录行数
	 */
	public function affected_rows()
	{
		$this->affected_rows = mysql_affected_rows($this->db_link);

		return $this->affected_rows;
	}

	/**
	 * 执行SELECT语句所得的记录行数
	 *
	 * @return integer 记录数据数
	 */
	public function num_rows()
	{
		if (!is_resource($this->result))
		{
			return false;
		}
		$this->num_rows = mysql_num_rows($this->result);

		return $this->num_rows;
	}

	/**
	 * 设置MYSQL所使用的字符集
	 *
	 * @param string $charset 字符集 utf8 gb2312
	 */
	public function charset($charset) { $this->dbexec("SET NAMES '{$charset}'"); }

	/**
	 * 转义特殊字符在一个字符串中使用的SQL语句,考虑到当前连接的字符集
	 *
	 * @param string $value 字符串
	 * @return string 转义后的字符串
	 */
	public function escape($value) { return mysql_real_escape_string($value, $this->db_link); }
}
?>