<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: wcore/utils.php
 * 简述: 专门用于提供各种函数
 * 作者: woods·zhang  ->  hoojar@163.com -> http://www.hecart.com/
 * 版本: $Id: utils.php 339 2016-07-01 02:45:07Z zhangsl $
 *
 * 版权 2006-2014, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006-2014, Hoojar Studio All Rights Reserved.
 *
 */
class wcore_utils
{
	/**
	 * 安全获取变量
	 *
	 * @param string  $ob      为要取的数据名字
	 * @param string  $type    为要取的是什么数据类型 (i=整形, d=整形, f=浮点, s=字符, c=字符, b=布尔, a=数组, o=对象)
	 * @param string  $gpcs    为是取外部变量还是 get post request cookie session，或要取session变量则设置成s，取cookie则设置为c
	 * @param mixed   $default 当取不到数据时则将数据设置成默认值
	 * @param integer $length  如果是字符串则截取指定的长度
	 * @return mixed
	 */
	public static function get_var($ob, $type = 'string', $gpcs = 'request', $default = null, $length = 0)
	{
		if (empty($ob))
		{
			return $default;
		}

		/**
		 * 从GET、POST、COOKIE、SESSION、REQUEST对象中获取数据
		 */
		switch (strtolower($gpcs))
		{
			case 'g':
			case 'get':
				$value = isset($_GET[$ob]) ? $_GET[$ob] : $default;
				break;
			case 'p':
			case 'post':
				$value = isset($_POST[$ob]) ? $_POST[$ob] : $default;
				break;
			case 'c':
			case 'cookie':
				$value = isset($_COOKIE[$ob]) ? $_COOKIE[$ob] : $default;
				break;
			case 's':
			case 'session':
				$value = isset($_SESSION[$ob]) ? $_SESSION[$ob] : $default;
				break;
			default:
				$value = isset($_REQUEST[$ob]) ? $_REQUEST[$ob] : $default;
				break;
		}
		$value = empty($value) ? $default : $value; //0非常的特殊，使用empty去判断0的结果为true

		/**
		 * 转换成指定的数据类型
		 */
		switch (strtolower($type))
		{
			case '': //字符类型
			case 'c':
			case 's':
			case 'char':
			case 'string':
				return (settype($value, 'string')) ? (($length === 0) ? $value : mb_strcut($value, 0, intval($length))) : '';
			case 'f': //浮点类型
			case 'd':
			case 'float':
			case 'double':
				return (settype($value, 'float')) ? $value : 0.0;
			case 'i': //整数类型
			case 'int':
			case 'integer':
				return (settype($value, 'integer')) ? $value : 0;
			case 'b': //布尔类型
			case 'bool':
			case 'boolean':
				return (settype($value, 'boolean')) ? $value : false;
			case 'a': //数组类型
			case 'array':
				return (settype($value, 'array')) ? $value : array();
			case 'o': //对象类型
			case 'object':
				return (settype($value, 'object')) ? $value : null;
			default:
				return $default;
		}
	}

	/**
	 * 将Rewrite数据中指定的值转换成GET数据
	 *
	 * @param string $k         Rewrite数据中指定的值
	 * @param string $separator Rewrite数据的分隔符
	 * @return boolean 转换成功为true返知false
	 */
	public static function reparm($k = 'reparm', $separator = '/')
	{
		if (!isset($_GET[$k]) || empty($_GET[$k]))
		{
			return false;
		}

		$val = explode($separator, $_GET[$k]);
		unset($_GET[$k]);

		if (!empty($val))
		{
			$len = count($val);
			for ($i = 0; $i < $len; $i++)
			{
				$temp_val           = isset($val[$i + 1]) ? $val[$i + 1] : '';
				$_REQUEST[$val[$i]] = $_GET[$val[$i]] = $temp_val;
				$i++;
			}

			return true;
		}

		return false;
	}

	/**
	 * 加密
	 *
	 * @param string $str 要加密的字符串
	 * @return string 加密好的字符串
	 */
	public static function woods_encode($str)
	{
		if (empty($str))
		{
			return '';
		}

		$str = strrev(base64_encode($str));
		for ($i = 0; $i < strlen($str); ++$i)
		{
			$str[$i] = chr(ord($str[$i]) + 1);
		}

		return urlencode($str);
	}

	/**
	 * 解密
	 *
	 * @param string $str 要解密的字符串
	 * @return string 解密好的明文
	 */
	public static function woods_decode($str)
	{
		if (empty($str))
		{
			return '';
		}

		$str = urldecode($str);
		for ($i = 0; $i < strlen($str); ++$i)
		{
			$str[$i] = chr(ord($str[$i]) - 1);
		}
		$str = base64_decode(strrev($str));

		return $str;
	}

	/**
	 * 设置COOKIE数据
	 *
	 * @param string $name   COOKIE名称
	 * @param mixed  $value  COOKIE数据
	 * @param int    $expire COOKIE有效终止期(自定义设置以天为单位0代表将会在会话结束后(一般是浏览器关闭)失效)
	 * @param string $path   COOKIE存储的路径
	 * @param string $domain COOKIE对应的域名
	 * @return bool                存储成功返回true反知则为false
	 */
	public static function set_cookie($name, $value, $expire = 0, $path = '/', $domain = DOMAIN_NAME)
	{
		if (empty($name))
		{
			return false;
		}

		$expire = intval($expire);
		if (is_null($value) || $expire < 0)
		{
			$expire = -3600; //清除COOKIE数据
		}
		else if ($expire > 0)
		{
			$expire = time() + $expire * 86400; //设置COOKIE有效期
		}

		return setcookie($name, $value, $expire, $path, $domain); //保存COOKIE
	}

	/**
	 * 获取cookie数据
	 *
	 * @param string $name 要获取的cookie的名称
	 * @param string $type 为要取的是什么数据类型 (i, f, d, s, c, b, a)
	 * @return mixed
	 */
	public static function get_cookie($name, $type)
	{
		return wcore_utils::get_var($name, $type, 'c');
	}

	/**
	 * 我的md5加密
	 *
	 * @param string $str 要加密的内容
	 * @param string $wdk 加密密匙
	 * @return string 加密码后的内容
	 */
	public static function md5crypt($str, $wdk)
	{
		$string = md5("{$str}-{$wdk}");

		return substr($string, -15) . substr($string, 1, 17);
	}

	/**
	 * 判断数据来源是否来至我们的主机
	 */
	function data_is_from_ours_host()
	{
		$http_ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		if (empty($http_ref))
		{
			return false;
		}

		preg_match("/:\/\/(.+?)\//si", $http_ref, $rhost);
		$http_ref = $rhost[1]; //只取IP或域名
		$long_ip  = ip2long($http_ref);
		if ($long_ip == -1 || false === $long_ip)
		{
			$host      = explode('.', $http_ref);
			$http_ref  = (count($host) > 2) ? "{$host[1]}.{$host[2]}" : $http_ref;
			$host_name = $_SERVER['HTTP_HOST'];
			$host      = explode('.', $host_name);
			$host_name = (count($host) > 2) ? "{$host[1]}.{$host[2]}" : $host_name;
			if ($host_name != $http_ref)
			{
				return false;
			}
		}
		else
		{
			if ($_SERVER['SERVER_ADDR'] != $http_ref)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * 产生唯一代号
	 *
	 * @param string $str 要生成多少位的字符串
	 * @return string 返回生成的字符串
	 */
	public static function uuid($str = '')
	{
		return $str . date('Ymd-siH-') . strtoupper(sprintf('%04x-%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff)));
	}

	/**
	 * 生成随机字符串
	 *
	 * @param int $length 生成几位数
	 * @return string 随机字符串
	 */
	public static function rand_string($length = 4)
	{
		$words    = '';
		$length   = intval($length);
		$charsets = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz23456789';
		$cslen    = strlen($charsets) - 1;
		for ($i = 1; $i <= $length; ++$i)
		{
			$words .= $charsets[rand(0, $cslen)];
		}

		return $words;
	}

	/**
	 *    产生豪秒数据
	 *
	 * @return float 当前浮点豪秒数
	 */
	public static function microtime_float()
	{
		list($usec, $sec) = explode(' ', microtime());

		return ((float)$usec + (float)$sec);
	}

	/**
	 * 将字符串统一编码转换成UTF-8
	 *
	 * @param string $str 字符串
	 * @return string 转好的字符串
	 */
	public static function utf8($str)
	{
		//此处为什么返回$str而非空是为了当$str为0时返回0
		if (empty($str) || !trim($str))
		{
			return $str;
		}

		if (mb_detect_encoding($str) == "UTF-8" && mb_check_encoding($str, 'UTF-8'))
		{
			return $str;
		}
		if ($str === mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
		{
			return $str;
		}

		return iconv('GB2312', 'UTF-8//IGNORE', $str);
	}

	/**
	 * 获取真实的IP地址
	 *
	 * @return string IP地址
	 */
	public static function get_ip()
	{
		if (getenv('HTTP_CLIENT_IP'))
		{
			$ip = getenv('HTTP_CLIENT_IP');
		}
		else if (getenv('HTTP_X_FORWARDED_FOR'))
		{
			list($ip) = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
		}
		else if (getenv('REMOTE_ADDR'))
		{
			$ip = getenv('REMOTE_ADDR');
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	/**
	 * 根据IP获取对应的数据(高效)
	 *
	 * @param string $ip       IP地址
	 * @param string $filename IP库路径
	 * @return array 未找到返回false，找到返回array(sIp=>开始IP数字 eIp=>结束IP数字 pId=>省份编号 cId=>城市编号)
	 */
	public static function get_ip_info($ip, $filename = '')
	{
		/**
		 * IP转数字并判断是否为合法IP地址
		 */
		$ip = ip2long($ip); //将IP转换成数字

		//ip转数字无效返回false
		if ($ip === false)
		{
			return false;
		}
		$ip = sprintf('%u', $ip); //将转换成数字的IP再次转换成无符号的长整形

		/**
		 * 打开IP数据库文件并静态文件指针与下标数
		 */
		static $fp = null, $count = null;
		if ($fp === null)
		{
			if (empty($filename))
			{
				$filename = dirname(__FILE__) . '/ip-db.dat';
			}
			$fp = @fopen($filename, 'rb');
			if ($fp === false)
			{
				exit("{$filename} file not exists .");
			}
			$res   = unpack('Nhig', fread($fp, 4)); //高位
			$count = $res['hig'];
			unset($res);
		}

		/**
		 * 初始化二分查找参照数据
		 */
		$seek   = 12; //一条记录占几个字节
		$top    = $middle = 0; //低位//下标
		$bottom = $count; //底(记录总数)

		/**
		 * 二分查找对应的数据
		 */
		while ($top <= $bottom)
		{
			$middle = floor(($top + $bottom) / 2);
			fseek($fp, 4 + $middle * $seek);
			$data        = unpack('NsIp/NeIp/SpId/ScId', fread($fp, $seek));
			$data['sIp'] = sprintf('%u', $data['sIp']);
			$data['eIp'] = sprintf('%u', $data['eIp']);
			if ($ip < $data['sIp'])
			{
				$bottom = $middle - 1;
			}
			elseif ($ip > $data['eIp'])
			{
				$top = $middle + 1;
			}
			else
			{
				$rdata = $data; //找到了
				$top   = $middle + 1; //当有多条记录符号时则尽量取最后一个值
			}
		}

		return isset($rdata) ? $rdata : false; //未找到
	}

	/**
	 * 将IP地址转换成长整形数据
	 *
	 * @param string $ip ip地址
	 * @return float ip长整形
	 */
	public static function get_ip_long($ip = '')
	{
		$ip = $ip ? $ip : wcore_utils::get_ip();
		$ip = ip2long($ip); //将IP转换成数字

		//ip转数字无效返回false
		if ($ip === false)
		{
			return false;
		}

		return sprintf('%u', $ip); //将转换成数字的IP再次转换成无符号的长整形
	}

	/**
	 * 获取访问来源域名
	 *
	 * @return string
	 */
	public static function get_http_referer()
	{
		return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	}

	/**
	 * 提升性能的Etag
	 *
	 * @param string  $etag      eTag标签
	 * @param integer $timestamp 时间差
	 * @return bool
	 */
	public static function etag($etag, $timestamp = 0)
	{
		if (empty($etag))
		{
			$etag = date('Ym-dHi');
		}

		header("ETag: {$etag}");
		$modified_since = true; //校验最后修改时间是否有变动的,默认为没有变动

		/**
		 * 判断是否设置了有效期,若有效期不为0或空则设置Etag有效期
		 */
		if ($timestamp)
		{
			if (!is_numeric($timestamp))
			{
				return false;
			}
			$last_modified = substr(date('r', $timestamp), 0, -5) . 'GMT';
			header("Last-Modified: {$last_modified}");
			$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
			$modified_since    = ($if_modified_since && $if_modified_since == $last_modified) ? true : false;
		}

		/**
		 * 判断Etag有效期,若设置了GET[nocache]则无效
		 */
		$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
		if ($modified_since && $if_none_match == $etag && !isset($_GET['nocache']))
		{
			header('HTTP/1.1 304 Not Modified');
			exit();
		}

		return true;
	}

	/**
	 * 通过socket传递数据到另一个WEB服务器
	 * $data = array('s' => 'php', 't' => 2);//要POST的数据
	 * send_data('http://www.hoojar.com/search.php', $data);
	 * echo($get);//输入返回的数据
	 *
	 * @param string $url     网址
	 * @param array  $params  要发送的数据
	 * @param string $method  传递方式 GET POST
	 * @param int    $timeout 超时多少秒
	 * @return string 获取返回的内容
	 */
	public static function &send_data($url, &$params, $method = 'POST', $timeout = 30)
	{
		if (empty($url) || empty($params))
		{
			return '';
		}

		if (strpos($url, '://') !== false)
		{
			list($protocol, $url) = explode('://', $url); //get protocol
		}

		list($host, $temp) = explode('/', $url); //get o host
		list($temp, $path) = explode($host, $url); //get execute path
		$path = empty($path) ? '/' : $path;
		$port = 80;
		if (strpos($host, ':') !== false)
		{
			list($host, $port) = explode(':', $host); //get host and port
		}

		//用socket链接主机
		$errno = $errstr = '';
		$fp    = fsockopen($host, intval($port), $errno, $errstr, intval($timeout));
		if (!$fp)
		{
			return "/* ERROR NO: {$errno} ERROR CONTENT: {$errstr} */";
		}

		//组合HTTP头与数据
		$str = '';
		if (is_array($params))
		{
			foreach ($params as $key => $val)
			{
				$str .= "{$key}={$val}&";
			}
			$str = substr($str, 0, -1);
		}

		$method = strtoupper($method) != 'POST' ? 'GET' : 'POST';
		$out    = "{$method} {$path} HTTP/1.1\r\nHost: {$host}\r\n";
		$out .= "Pragma: no-cache\r\nContent-Range: bytes\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . strlen($str) . "\r\n";
		$out .= "Connection: Close\r\n\r\n{$str}";
		fputs($fp, $out);

		$content = '';
		while (!feof($fp))
		{
			$content .= fgets($fp, 1024);
		}
		fclose($fp);

		return $content;
	}

	/**
	 * CURL POST GET 请求
	 *
	 * @param  string $url     请求URL地址
	 * @param array   $params  请求数据
	 * @param bool    $ispost  是否为POST方式
	 * @param string  $referer 引用地址
	 * @param bool    $randip  是否伪装IP
	 * @return mixed
	 */
	public static function curl($url, $params = array(), $ispost = false, $referer = '', $randip = false)
	{
		$header = array(
			'Connection: keep-alive',
			'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0'
		);

		if ($randip)
		{
			$make_ip  = mt_rand(100, 254) . '.' . mt_rand(0, 254) . '.' . mt_rand(0, 254) . '.' . mt_rand(0, 254);
			$header[] = "CLIENT-IP: {$make_ip}";
			$header[] = "X-FORWARDED-FOR: {$make_ip}";
		}
		if ($referer)
		{
			$header[] = "Referer: {$referer}";
		}

		$curl = curl_init();
		if ($ispost)//POST方式发送数据
		{
			curl_setopt($curl, CURLOPT_POST, 1);//POST数据
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		}
		curl_setopt($curl, CURLOPT_URL, $url);//POST地址
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);//30秒超时
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);//不校验HTTPS协议
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);//不校验SSL的主机
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//返回执行后的结果
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);//发送各种头协议
		$str = curl_exec($curl);
		curl_close($curl);

		return $str;
	}

	/**
	 * 获取域名若未指定URL地址则获取HTTP REFERER的域名
	 *
	 * @param string $url 域名或URL地址
	 * @return string 返回域名
	 */
	public static function get_http_domain($url = '')
	{
		$url = $url ? $url : wcore_utils::get_http_referer();
		if (empty($url))
		{
			return '';
		}

		$res = wcore_utils::parse_href($url);

		return $res['host'];
	}

	/**
	 * 分析url地址
	 *
	 * @param string $url 域名或URL地址
	 * @return array protocol:协议 host:主机 port:端口 path:路径
	 */
	public static function parse_href($url)
	{
		$data = array(
			'protocol' => '',
			'host'     => '',
			'port'     => '80',
			'path'     => ''
		);
		if (empty($url))
		{
			return $data;
		}

		if (strpos($url, '://') !== false)
		{
			list($protocol, $url) = explode('://', $url); //get protocol
			$data['protocol'] = $protocol;
		}

		$temp = '';
		list($data['host'], $temp) = explode('/', $url); //get o host
		list($temp, $data['path']) = explode($data['host'], $url); //get execute filescript
		if (strpos($data['host'], ':') !== false)
		{
			list($temp, $data['port']) = explode(':', $data['host']); //get host and port
		}

		return $data;
	}

	/**
	 * 分析URL地址
	 *
	 * @param string $url 域名或URL地址
	 * @return array 返回域名分解数组
	 */
	public static function parse_url2($url)
	{
		$r = "^(?:(?P<scheme>[\w\-]+)://)?";
		$r .= "(?:(?P<login>[\w\-]+):(?P<pass>[\w\-]+)@)?";
		$r .= "(?P<host>(?:(?P<subdomain>[\w\-\.]+)\.)?(?P<domain>[\w\-]+\.(?P<extension>[\w\-]+)))";
		$r .= "(?::(?P<port>\d+))?";
		$r .= "(?P<path>[\w/]*/(?P<file>\w+(?:\.\w+)?)?)?";
		$r .= "(?:\?(?P<arg>[\w=&]+))?";
		$r .= "(?:#(?P<anchor>\w+))?";
		$r = "!$r!";
		preg_match($r, $url, $out);

		return $out;
	}

	/**
	 * 专用于调试，输出所有数据
	 */
	public static function debug()
	{
		$args = func_get_args();
		header('Content-type: text/html; charset=utf-8');
		echo("<html><head><title>debug infomation</title></head><body><pre>\n");
		foreach ($args as $v)
		{
			var_dump($v);
			echo("\n\n");
		}
		exit('</pre></body><html>');
	}

	/**
	 * 格式化显示价格
	 *
	 * @param int $price     价格
	 * @param int $precision 保留几位小数
	 * @return int 格式化好的价格
	 */
	public static function format_price($price, $precision = 2)
	{
		if ($precision == 0 || $price == round($price))
		{
			return round($price);
		}

		return round($price, $precision);
	}

	/**
	 * 价格4舍5入处理
	 *
	 * @param float $price     价格
	 * @param int   $precision 保留几位小数
	 * @return float 处理后的价格
	 */
	public static function price_round($price, $precision = 0)
	{
		$price = floatval($price);

		//不进行4舍5入处理
		if (PRICE_ROUND <= -1)
		{
			return $price;
		}

		//根据调用函数处自定义的来取小数位
		if ($precision > 0)
		{
			return round($price, $precision);
		}

		return round($price, PRICE_ROUND); //根据系统定义的来取小数位,进行4舍5入处理
	}

	/**
	 * 获取倒计时
	 *
	 * @param int  $endtime 结束时间秒
	 * @param bool $rehtml  是否返回HTML值
	 * @return array 失败返回空,倒计时成功,返回数组(day hour minute second)或组合好的HTML
	 */
	public static function countdown($endtime, $rehtml = true)
	{
		$sec = $endtime - time();
		if ($sec <= 0)
		{
			return '';
		}

		$rdata = array(
			'day'    => floor($sec / 86400),
			'hour'   => floor(($sec / 3600) % 24),
			'minute' => floor(($sec / 60) % 60),
			'second' => floor($sec % 60)
		);

		if (!$rehtml)
		{
			return $rdata;
		}

		$day    = ($rdata['day'] > 0) ? "<em>{$rdata['day']}</em>天" : '';
		$hour   = ($rdata['hour'] > 0) ? "<em>{$rdata['hour']}</em>小时" : '';
		$minute = ($rdata['minute'] > 0) ? "<em>{$rdata['minute']}</em>分" : '';
		$second = ($rdata['second'] > 0) ? "<em>{$rdata['second']}</em>秒" : '';

		return $day . $hour . $minute . $second;
	}

	/**
	 * 将XML数据转换成数组数据
	 * $array =  xml2array(file_get_contents('feed.xml'));
	 * $array =  xml2array(file_get_contents('feed.xml', true, 'attribute'));
	 *
	 * @param string  $contents       XML数据内容
	 * @param boolean $get_attributes 是否获取XML属性值
	 * @param string  $priority       优先顺序标签
	 * @return array 数组数据
	 */
	public static function xml2array($contents, $get_attributes = true, $priority = 'tag')
	{
		if (empty($contents))
		{
			return array();
		}

		if (!function_exists('xml_parser_create'))
		{
			return array();
		}

		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);
		if (!$xml_values)
		{
			return array();
		}

		//init data
		$xml_array = array();
		$tag       = '';
		$type      = '';
		$level     = 0;
		$current   = &$xml_array;

		//Go through the tags.
		$repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
		foreach ($xml_values as $data)
		{
			unset($attributes, $value); //Remove existing values, or there will be trouble
			extract($data); //We could use the array by itself, but this cooler.
			$result          = array();
			$attributes_data = array();
			if (isset($value))
			{
				if ($priority == 'tag')
				{
					$result = $value;
				}
				else
				{
					$result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
				}
			}

			//Set the attributes too.
			if (isset($attributes) && $get_attributes)
			{
				foreach ($attributes as $attr => $val)
				{
					if ($priority == 'tag')
					{
						$attributes_data[$attr] = $val;
					}
					else
					{
						$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
					}
				}
			}

			//See tag status and do the needed.
			if ($type == 'open') //The starting of the tag '<tag>'
			{
				$parent[$level - 1] = &$current;
				if (!is_array($current) || (!in_array($tag, array_keys($current)))) //Insert New tag
				{
					$current[$tag] = $result;
					if ($attributes_data)
					{
						$current[$tag . '_attr'] = $attributes_data;
					}
					$repeated_tag_index[$tag . '_' . $level] = 1;
					$current                                 = &$current[$tag];
				}
				else //There was another element with the same tag name
				{
					if (isset($current[$tag][0])) //If there is a 0th element it is already an array
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else //This section will make the value an array if multiple tags with the same name appear together
					{
						//This will combine the existing item and the new item together to make an array
						$current[$tag]                           = array(
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 2;
						if (isset($current[$tag . '_attr'])) //The attribute of the last(0th) tag must be moved as well
						{
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset($current[$tag . '_attr']);
						}
					}
					$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
					$current         = &$current[$tag][$last_item_index];
				}
			}
			elseif ($type == 'complete') //Tags that ends in 1 line '<tag />'
			{
				//See if the key is already taken.
				if (!isset($current[$tag])) //New Key
				{
					$current[$tag]                           = $result;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if ($priority == 'tag' && $attributes_data)
					{
						$current[$tag . '_attr'] = $attributes_data;
					}
				}
				else //If taken, put all things inside a list(array)
				{
					if (isset($current[$tag][0]) && is_array($current[$tag])) //If it is already an array...
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result; // ...push the new element into that array.
						if ($priority == 'tag' && $get_attributes && $attributes_data)
						{
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else //If it is not an array...
					{
						//Make it an array using using the existing value and the new value
						$current[$tag]                           = array(
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 1;
						if ($priority == 'tag' && $get_attributes)
						{
							if (isset($current[$tag . '_attr'])) //The attribute of the last(0th) tag must be moved as well
							{
								$current[$tag]['0_attr'] = $current[$tag . '_attr'];
								unset($current[$tag . '_attr']);
							}

							if ($attributes_data)
							{
								$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
					}
				}
			}
			elseif ($type == 'close') //End of </tag>
			{
				$current = &$parent[$level - 1];
			}
		}

		return ($xml_array);
	}


    /**
     * [toXml 数组转xml格式]
     * @param  array  $data         [数组]
     * @param  string $rootNodeName [根节点名]
     * @param  mixed $xml           [xml头]
     */
    public static function toXml($data, $rootNodeName = 'root', $xml=null)
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1)
        {
            ini_set ('zend.ze1_compatibility_mode', 0);
        }

        if ($xml == null)
        {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
        }

        foreach($data as $key => $value)
        {
            // no numeric keys in our xml please!
            if (is_numeric($key))
            {
                // 数组key转字符key
                //$key = "unknownNode_". (string) $key;
                $key = (string) $key;
            }

            // key过滤
            // $key = preg_replace('/[^a-z]/i', '', $key);

            // if there is another array found recrusively call this function
            if (is_array($value))
            {
                $node = $xml->addChild($key);
                // recrusive call.
                self::toXml($value, $rootNodeName, $node);
            }
            else
            {
                // add single node.
                $value = htmlentities($value);
                $xml->addChild($key,$value);
            }

        }
        // pass back as string. or simple xml object if you want!
        return $xml->asXML();
    }

     /**
     * [strToMap 字符串转数组]
     * @param  string $str [入参，待转换的字符串]
     */
    public static function strToMap($str)
    {
        $str = trim($str);
        $infoMap = array();
        $strArr = explode("&",$str);
        for($i=0; $i<count($strArr); $i++)
        {
            $infoArr = explode("=",$strArr[$i],2);
            if(count($infoArr) != 2)
            {
                continue;
            }
            $infoMap[$infoArr[0]] = $infoArr[1];
        }
        return $infoMap;
    }

     /**
     * [strToMap 数组转字符串]
     * @param  array $str [入参，待转换的数组]
     */
    public static function mapToStr($map)
    {
        $str = "";
        if(!empty($map))
        {
            foreach($map as $k=>$v)
            {
                if(is_array($v)){
                    $v = json_encode($v,JSON_UNESCAPED_UNICODE);
                    //$v = preg_replace( "/\"(\d+)\"/", '$1', $v );
                    $v = urlencode($v);
                }
                $str .= "&".$k."=".$v;
            }
        }

        if(strlen($str) > 0){
            $str = substr($str, 1);
        }

        return $str;
    }

	/**
	 * 获取来源页面中的搜索引擎的搜索的关键字
	 *
	 * @param string $ref_url 搜索的URL地址
	 * @return string 返回用户搜索的关键字
	 */
	public static function get_search_keyword($ref_url)
	{
		if (empty($ref_url))
		{
			return '';
		}

		$temp_array = parse_url(urldecode($ref_url));
		if (!isset($temp_array['query']) || !$temp_array['query'])
		{
			return '';
		}

		$host_array  = explode('.', $temp_array['host']);
		$host        = (count($host_array) >= 3) ? $host_array[1] : $host_array[0];
		$query_array = array();
		parse_str($temp_array['query'], $query_array);

		switch ($host)
		{
			case 'baidu' :
				$key_str = isset($query_array['wd']) ? $query_array['wd'] : $query_array['word'];
				$is_utf8 = false;
				break;
			case 'google':
				$key_str = $query_array['q'];
				$is_utf8 = ($query_array['ie'] == 'GB') ? false : true;
				break;
			case 'yahoo':
				$is_utf8 = (strtolower($query_array['ei']) == 'utf-8') ? true : false;
				$key_str = ($query_array['p']) ? $query_array['p'] : $query_array['keyword'];
				break;
			case 'zhongsou':
				$key_str = isset($query_array['word']) ? $query_array['word'] : $query_array['w'];
				$is_utf8 = false;
				break;
			case 'sogou':
				$key_str = $query_array['query'];
				$is_utf8 = false;
				break;
			case 'iask':
				$key_str = $query_array['k'];
				$is_utf8 = false;
				break;
			case '163' :
				$key_str = $query_array['q'];
				$is_utf8 = false;
				break;
			case '114' :
				$key_str = isset($query_array['keyword']) ? $query_array['keyword'] : $query_array['kw'];
				$is_utf8 = false;
				break;
			case 'live' :
				$key_str = $query_array['q'];
				$is_utf8 = true;
				break;
			case 'soso' :
				$key_str = $query_array['w'];
				$is_utf8 = false;
				break;
			case 'youdao':
				$key_str = $query_array['q'];
				$is_utf8 = (strtolower($query_array['ue']) == 'utf8') ? true : false;
				break;
			default:
				return '';
		}

		if (!$is_utf8)
		{
			$key_str = iconv('GBK', 'UTF-8', $key_str);
		}

		return $key_str;
	}

	/**
	 * 将获取的PHPINFO信息生成一维数组
	 *
	 * @return array
	 */
	public static function get_php_info()
	{
		ob_start();
		phpinfo();
		$s = ob_get_contents();
		ob_end_clean();
		$a = $mtc = array();
		if (preg_match_all('/<tr><td class="e">(.*?)<\/td><td class="v">(.*?)<\/td>(:?<td class="v">(.*?)<\/td>)?<\/tr>/', $s, $mtc, PREG_SET_ORDER))
		{
			foreach ($mtc as $v)
			{
				if ($v[2] == '<i>no value</i>')
				{
					continue;
				}
				$a[$v[1]] = $v[2];
			}
		}

		return $a;
	}

	/**
	 * hash数组，将数组中的某个字段值索引成数组的KEY
	 *
	 * @param array  $arr 需要HASH的数组
	 * @param string $key 要定的数组字段名
	 * @param string $pre 数组下标前缀
	 * @return array
	 */
	public static function &hash_array($arr, $key, $pre = '')
	{
		if (empty($arr) || !is_array($arr))
		{
			return $arr;
		}

		$rarr = array();
		foreach ($arr as $v)
		{
			if (isset($v[$key]))
			{
				$rarr["{$pre}{$v[$key]}"] = $v;
			}
		}

		return $rarr;
	}

	/**
	 * 将2维数组中某个字段值按分割符组合起来
	 *
	 * @param    array  $arr    要处理的数组
	 * @param    string $fkey   要选择的字段名
	 * @param    string $glue   分割符
	 * @param    string $prefix 组合前缀
	 * @param    string $suffix 组合后缀
	 * @return    string    组合好的字符串
	 */
	public static function array_implode($arr, $fkey, $glue, $prefix = '', $suffix = '')
	{
		if (!is_array($arr) || empty($arr))
		{
			return "{$prefix}{$suffix}";
		}

		$temp_arr = array();
		foreach ($arr as $v)
		{
			if (isset($v[$fkey]))
			{
				$temp_arr[] = $v[$fkey];
			}
		}
		$str = !empty($temp_arr) ? implode($glue, $temp_arr) : '';

		return "{$prefix}{$str}{$suffix}";
	}

	    /**
     * [gzip 字符串GZIP压缩：gzencode]
     * @param  string  $str   [源数据串]
     * @param  integer $level [压缩等级]
     * @param  integer  $code  [压缩算法]
     */
    public static function gzip($str,$level = -1,$code = FORCE_GZIP){
        if(empty($str) || !function_exists("gzencode")){
            return $str;
        }

        $startTime = microtime(TRUE);
        $retStr = gzencode($str,$level,$code);
        $gapTime = microtime(TRUE) - $startTime;

        OSS_LOG(__FILE__, __LINE__, LP_INFO, __METHOD__." before length:".strlen($str).", after length:".strlen($retStr).", spend time:".$gapTime."s\n");
        return $retStr;
    }


    /**
     * [ungzip 字符串GZIP解压：gzdecode]
     * @param  string  $str   [带解压数据串]
     */
    public static function ungzip($str){
        if(empty($str)  || !function_exists("gzdecode")){
            return $str;
        }

        $startTime = microtime(TRUE);
        $retStr = gzdecode($str);
        $gapTime = microtime(TRUE) - $startTime;

        OSS_LOG(__FILE__, __LINE__, LP_INFO, __METHOD__." before length:".strlen($str).", after length:".strlen($retStr).", spend time:".$gapTime."s\n");
        return $retStr;
    }

     /**
     * [encodeEX 新字符串编码函数（中文和常用英文数字不编码，特殊字符编码）]
     * @param  string $src [源字符串]
     */
    public static function encodeEX($src)
    {
        //$src = @iconv('GB18030','UTF-8',$src);
        $result = '';
        $len = strlen($src);
        $encode_buf = '';
        for($i = 0; $i < $len; $i++)
        {
            $sChar = substr($src,$i,1);
            switch($sChar)
            {
                case "~":
                case "`":
                case "!":
                case "@":
                case "#":
                case "$":
                case "%":
                case "^":
                case "&":
                case "*":
                case "(":
                case ")":
                case "-":
                case "_":
                case "+":
                case "=":
                case "{":
                case "}":
                case "[":
                case "]":
                case "|":
                case "\\":
                case ";":
                case ":":
                case "\"":
                case ",":
                case "<":
                case ">":
                case ".":
                case "?":
                case "/":
                case " ":
                case "'":
                case "\"":
                case "\n":
                case "\r":
                case "\t":
                    {
                        $encode_buf = sprintf("%%%s",bin2hex($sChar));
                        $result .= $encode_buf;
                    }
                    break;
                default:
                    $result .= $sChar;
                    break;
            }
        }
        //$result = @iconv('UTF-8','GB18030',$result);

        return $result;
    }

     /**
     * [decodeEX 新字符串解码函数（中文和常用英文数字不编码，特殊字符编码）]
     */
    public static function decodeEX($src)
    {
        $result = '';
        $len = mb_strlen($src);
        $chDecode;
        for($i = 0; $i < $len; $i++)
        {
            $sChar = mb_substr($src,$i,1);
            if($sChar == '%' && $i < $len - 2   &&
                    self::IsXDigit(mb_substr($src,$i+1,1)) &&
                    self::IsXDigit(mb_substr($src,$i+2,1)))
            {
                $chDecode = mb_substr($src,$i+1,2);
                $result .= pack("H*",$chDecode);
                $i += 2;
            }
            else
            {
                $result .= $sChar;
            }
        }
        return $result;
    }

    /**
     * [isXDigit 判断是否为16进制，由于PHP没有相关的API，所以折中处理）]
     */
    public static function isXDigit($src)
    {
        if(mb_strlen($src) < 1)
        {
            return false;
        }
        if (($src >= '0' && $src <= '9') ||
                ($src >= 'A' && $src <= 'F')||
                ($src >= 'a' && $src <= 'f'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * [tcpRequest TCP请求数据]
     * @param  string $in [数组]
     */
    public static function tcpRequest($ip, $port, $in, &$out)
    {
        $iRecv = "";
        $wbuff = self::mapToStr($in);
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);
        if(socket_connect($socket, $ip, $port) == false){
            return;
        };
        socket_send($socket, $wbuff, strlen($wbuff), 0);
        socket_recv($socket, $iRecv, 16384, 0);
        socket_close($socket);
        $out = self::strToMap($iRecv);
    }

    //根据原图地址求各种尺寸的图片
	//$picUrl，原图地址
	//$type 0,小图,1中图，2大图，3原图
	public static function getPicUrl($picUrl, $type)
	{
		//微信图片直接不处理
		if(strstr($picUrl,'mmsns.qpic.cn') !== false)
		{
			return $picUrl;
		}

		if(strstr($picUrl,'mblogpic') === false && strstr($picUrl,'cross.store.qq.com') === false)
		{
			if(strstr($picUrl,'ishow2') !== false ){
				if ($type == 0){
					/*
					$picurlArray = explode("/", $picUrl);
					$picurlArray[count($picurlArray)-1] = "thumb_".$picurlArray[count($picurlArray)-1];
					$picUrl = implode("/", $picurlArray);
					*/
				}
				return $picUrl;
			}
			return $picUrl;
		}

		if($picUrl == '')
		{
			return 'http://cross.store.qq.com/cross/2868efae1826b8e456f3c380d97aa8a3f5ff13cf5517223b6313926fcf180459b661516a17070e80/88';
		}

		if(substr($picUrl,strlen($picUrl)-1, strlen($picUrl)) == '/')
        {
        	$picUrl = CommUtils::deleteLastChar($picUrl, "/");
        }

		$fromCross = false;
		if(strstr($picUrl,'mblogpic') !== false)
		{
			$fromCross = true;
		}

		if($type == 0)
		{
			if(!$fromCross)
			{
				$picUrl = $picUrl."/88";
			}
			else
			{
				$picUrl = $picUrl."/120";
			}
		}
		else if($type == 1)
			{
				if(!$fromCross)
				{
					$picUrl = $picUrl."/220";
				}
				else
				{
					$picUrl = $picUrl."/460";
				}
			}
			else if($type == 2)
				{
					if(!$fromCross)
					{
						$picUrl = $picUrl."/420";
					}
					else
					{
						$picUrl = $picUrl."/460";
					}
				}
				else if($type == 3)
					{
						if(!$fromCross)
						{
							$picUrl = $picUrl."/0";
						}
						else
						{
							$picUrl = $picUrl."/2000";
						}
					}
		return $picUrl;
	}

	/*
	 * 防止script里面的 XSS
	 */
	public static function jsformat($str)
	{
		$str = trim($str);
		$str = str_replace('\\s\\s', '\\s', $str);
		$str = str_replace(chr(10), '', $str);
		$str = str_replace(chr(13), '', $str);
		$str = str_replace(' ', '', $str);
		$str = str_replace('\\', '\\\\', $str);
		$str = str_replace('"', '\\"', $str);
		$str = str_replace('\\\'', '\\\\\'', $str);
		$str = str_replace("'", "\'", $str);
		$str = str_replace(">", "", $str);
		$str = str_replace("<", "", $str);
		return $str;
	}

	/**
    * 转换UNICODE编码为UTF8
    *
    * @param mixed $ar
    */
    public static function unicode2Utf8($ar)
    {
        $c = '';
        foreach($ar as $val){
            $val = intval(substr($val, 2), 16);
            if ($val < 0x7F) {        // 0000-007F 单字节
                $c .= chr($val);
            } elseif ($val < 0x800) { // 0080-0800 双字节
                $c .= chr(0xC0 | ($val / 64));
                $c .= chr(0x80 | ($val % 64));
            } else {                // 0800-FFFF 三字节
                $c .= chr(0xE0 | (($val / 64) / 64));
                $c .= chr(0x80 | (($val / 64) % 64));
                $c .= chr(0x80 | ($val % 64));
            }
        }
        return $c;
    }

    //把QQ号码加密
	public static function uinEncodeOld($uin)
	{
		$uin = "q".$uin;
		$uin = str_replace("2", "k", $uin);
		$uin = str_replace("3", "p", $uin);
		$uin = str_replace("4", "a", $uin);
		$uin = str_replace("5", "g", $uin);
		$uin = str_replace("8", "yyy", $uin);
		$uin = str_replace("9", "cc", $uin);
		return $uin;
	}

	//把QQ号码解密
	public static function uinDecodeOld( $uin )
	{
		if( strlen($uin)>1)
		{
			$uin = str_replace("k", "2",$uin);
			$uin = str_replace("p", "3",$uin);
			$uin = str_replace("a", "4",$uin);
			$uin = str_replace("g", "5",$uin);
			$uin = str_replace("yyy", "8",$uin);
			$uin = str_replace("cc", "9",$uin);

			if( substr($uin, 0, 1)=='q')
			{
				return substr($uin, 1);
			}
		}
		return $uin;
	}

	/*
	*	安全的base64编码函数，处理+、/、=符号，解决做为url参数问题
	*/
	public static function urlsafe_base64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}

	/*
	*	安全的base64解码函数，处理+、/、=符号，解决做为url参数问题
	*/
	public static function urlsafe_base64decode($string) {
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}

	/**
	 * [createTicket 创建ticket]
	 * @param  string  $data           [待创建ticket的信息串]
	 * @param  integer $expired_second [cookie过期时间]
	 * @param  boolean $create_cookie  [是否需要创建cookie]
	 * @return [type]                  [description]
	 */
	public static function createTicket($data,$create_cookie = false,$expired_second = 3600)
	{
		$ticket_key = "stbk_ticket";
		$nowtime = time(0);
		$ticket_time = $nowtime;
		$ticket = md5($ticket_key.'@'.$ticket_time.'@'.$data);
		if($create_cookie){
			setcookie('_ticket_', $ticket, $nowtime + $expired_second, '/', '.stbk.com');
			setcookie('_ticket_time_', $ticket_time, $nowtime + $expired_second, '/', '.stbk.com');
			$_COOKIE['_ticket_'] = $ticket;
			$_COOKIE['_ticket_time_'] = $ticket;
		}

		return array("ticket"=>$ticket,"ticket_time"=>$ticket_time);
	}

	/**
	 * [checkTicket 校验ticket]
	 * @param  string  $data           [待校验ticket的信息串]
	 * @param  string  $ticket         [ticket值，可不传取cookie]
	 * @param  integer $ticket_time    [ticket创建时间值，可不传取cookie]
	 * @param  boolean $destroy_cookie [是否销毁cookie]
	 * @param  integer $expired_second [校验过期秒数]
	 */
	public static function checkTicket($data,$ticket = null,$ticket_time = null,$destroy_cookie = false,$expired_second = 3600)
	{
		$ticket_key = "stbk_ticket";
		$nowtime = time(0);
		$bCheck = false;

		//无传参数值则判断是否用cookie值
		if(empty($ticket)){
			$ticket = isset($_COOKIE['_ticket_']) ? $_COOKIE['_ticket_'] : $ticket;
		}
		if(empty($ticket_time)){
			$ticket_time = isset($_COOKIE['_ticket_time_']) ? $_COOKIE['_ticket_time_'] : $ticket_time;
		}

		if(empty($ticket) || empty($ticket_time)){
			return false;
		}

		//校验合法并且未过期则成功
		$ticket_forcheck = md5($ticket_key.'@'.$ticket_time.'@'.$data);
		$timegap = $nowtime - $ticket_time;
		if($ticket === $ticket_forcheck && $timegap <= $expired_second){
			$bCheck = true;
		}

		if($destroy_cookie){
			setcookie('_ticket_', "", $nowtime - 3600, '/', '.stbk.com');
			setcookie('_ticket_time_', "", $nowtime - 3600, '/', '.stbk.com');
			$_COOKIE['_ticket_'] = "";
			$_COOKIE['_ticket_time_'] = "";
		}

		return $bCheck;
	}
}
?>