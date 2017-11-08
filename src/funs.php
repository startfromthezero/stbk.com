<?php
/**
 * 公共类函数
 * $Id$
 */
class modules_funs
{
	/**
	 * 获取完整的URL地址
	 *
	 * @param stgring $url URL地址
	 * @return string 完整URL地址
	 */
	public static function getFullUrl($url)
	{
		if (empty($url))
		{
			return '';
		}

		if (strpos($url, '://') === false)
		{
			$url = OSS_IMG_DOMAIN . $url;
		}
		else
		{
			$url .= '?';
		}

		return $url;
	}

	/**
	 * 发送命令给设备
	 *
	 * @param string  $sn    设备唯一编码
	 * @param string  $cmd   要执行的命令
	 * @param integer $ptype 推送类型：0信鸽 1极光
	 * @param string  $data  要执行的命令的附加数据
	 * @return bool
	 */
	public static function pushCmd($sn, $ptype, $cmd, $data = '')
	{
		/**
		 * 推送指令与数据到设备
		 */
		$data = '&token=' . md5($cmd . date('dHYm') . XG_SECRET_KEY) . '&timestamp=' . time() . $data;//组合完整数据
		if ($ptype == 1 || defined('USE_JPUSH'))
		{
			require_once(DIR_ROOT . '/system/JPush/JPush.php');
			$pcls   = new JPush(PUSH_APPID, PUSH_APPKEY, DIR_ROOT . '/system/logs/jpush.log');
			$result = $pcls->push()->setPlatform('android')->addAlias($sn)->setMessage("cmd={$cmd}{$data}")->send();
			$ret    = isset($result->data->sendno) ? true : false;
		}
		else
		{
			require_once(DIR_ROOT . '/system/XingeApp.php');
			$result = XingeApp::PushAccountAndroid(XG_ACCESS_ID, XG_SECRET_KEY, 'EXEC CMD', "cmd={$cmd}{$data}", $sn);
			$ret    = (isset($result['ret_code']) && $result['ret_code'] == 0) ? true : false;
		}

		/**
		 * 记录推送日志
		 */
		if (defined('DEBUG_LOG') && DEBUG_LOG)
		{
			$data = " - SN: {$sn} -> DATA: cmd={$cmd}{$data}\n" . var_export($result, true) . "\n\n";
			file_put_contents(DIR_ROOT . '/system/logs/push.log', date('Y-m-d H:i:s') . $data, FILE_APPEND);
		}

		return $ret;
	}

	/**
	 * 调用用百度地图Geocoding API根据经纬度获取地址
	 *
	 * @param float $longitude 经度
	 * @param float $latitude  纬度
	 * @return string    物理地址
	 */
	public static function getLocation($longitude, $latitude)
	{
		if ($longitude <= 0)
		{
			return '';
		}

		$ctype = defined('MAP_GEOCONV') ? 'gcj02ll' : 'bd09ll';
		$url   = "http://api.map.baidu.com/geocoder/v2/?ak=1wqYBEw458zCuiVxEWUoygr1lOvM5A3G&location={$latitude},{$longitude}&output=json&coordtype={$ctype}";
		$res   = @json_decode(wcore_utils::curl($url), true);
		if (isset($res['status']) && $res['status'] == 0)
		{
			return $res['result']['formatted_address'];
		}

		return '';
	}

	/**
	 * 调用JAVA后台接口
	 *
	 * @param string $sn     车机SN
	 * @param string $cmd    指令
	 * @param array  $params 参数
	 * @return bool
	 */
	public static function callJavaApi($sn, $cmd, $params = array())
	{
		$api_url               = JAVA_API_URL . "app/main/{$cmd}.ihtml";
		$params['sn']          = $sn;
		$params['method']      = $api_url;
		$params['clientType']  = 4;
		$params['timestamp']   = time() . '000';
		$params['accessToken'] = md5(JAVA_API_SECRET . date('YmdHis'));
		$result                = @json_decode(wcore_utils::curl($api_url, array('queryParams' => json_encode($params)), true), true);

		/**
		 * 记录日志
		 */
		if (defined('DEBUG_LOG') && DEBUG_LOG)
		{
			file_put_contents(DIR_ROOT . '/system/logs/java-api.log', date('Y-m-d H:i:s') . " - SN: {$sn} - CMD: {$cmd}\n" . var_export($result, true) . "\n\n", FILE_APPEND);
		}

		return (isset($result['status']) && $result['status'] == '0000') ? true : false;
	}

	/**
	 * 通过接口获取车辆品牌和型号
	 *
	 * @param string $detailId 车型id
	 * @return mixed
	 */
	public static function getCarList($detailId = '')
	{
		$url = 'http://apps.api.dbscar.com/?lan_id_or_name=cn';
		if (empty($detailId))
		{
			$url .= '&action=mine_car_service.query_x431_car_series';
			$key = 'CAR-BRAND';
		}
		else
		{
			$url .= "&action=mine_car_service.query_market_car_type&detailId={$detailId}";
			$key = "CAR-MODEL-{$detailId}";
		}

		$mem  = new modules_mem();
		$data = $mem->mem_get($key);
		if (empty($data))
		{
			$result = @json_decode(wcore_utils::curl($url), true);
			if (isset($result['code']) && $result['code'] == 0)
			{
				$data = empty($detailId) ? $mem->mem_hash($result['data'], 'detailId', $key) : $result['data'];
				$mem->mem_set($key, $data);
			}
		}

		return $data;
	}

	/**
	 * 提交obd信息
	 *
	 * @param $params
	 * @return bool
	 */
	public static function sendObd($params)
	{
		$url    = 'http://apps.api.dbscar.com/?action=mine_car_service.update_mine_car';
		$result = @json_decode(wcore_utils::curl($url, $params, true));

		return (isset($result['code']) && $result['code'] == '0') ? true : false;
	}
}
?>