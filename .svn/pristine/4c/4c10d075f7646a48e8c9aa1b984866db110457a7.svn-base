<?php
/**
 * ROM系统OTA升级接口
 *
 * 此模块主要用于服务器与设备之间的通信处理
 */
class ControllerAppOta extends Controller
{
	public function index()
	{
		$opt_name   = 'cc_ota';//要操作的表名
		$now_time   = date('Y-m-d H:i:s');//当前系统时间
		$log_file   = DIR_ROOT . '/system/logs/ota.log';//日志文件路径
		$pass_min   = 15;//时间戳多少分钟范围内调用接口有效
		$device_sn  = $this->request->get_var('sn');//设备SN编号
		$device_ip  = $this->request->get_var('ip');//设备IP地址
		$device_mac = $this->request->get_var('mac');//设备MAC地址
		$org_code   = $this->request->get_var('orgCode');//机构代码
		$hard_code  = $this->request->get_var('hardCode');//硬件版本
		$soft_code  = $this->request->get_var('softCode');//软件版本
		$timestamp  = $this->request->get_var('timestamp', 'i', '', time());
		$ota_cmd    = $this->request->get_var('cmd');//OTA升级指令 check:检测升级 succeed:升级成功登记
		$device_tip = "{$device_sn} {$org_code}_{$hard_code}_{$soft_code}";//设备信息串日志提示

		/**
		 * 校验时间戳为系统当前时间前后多少分钟以内才有效
		 */
		if ($timestamp < (time() - 60 * $pass_min) || $timestamp > (time() + 60 * $pass_min))
		{
			file_put_contents($log_file, "{$now_time} - [{$device_tip}] timestamp error\n", FILE_APPEND);
			exit();
		}

		/**
		 * 校验密码安全
		 */
		$vtoken = md5(XG_SECRET_KEY . date('dHYm', $timestamp));//MD5(密钥+2位数日+2位数时+4位数年+2位数月份)
		if ($vtoken != $this->request->get_var('token'))
		{
			file_put_contents($log_file, "{$now_time} - [{$device_tip}] token signature error\n", FILE_APPEND);
			exit();
		}

		/**
		 * 判断是否为升级成功登记请求
		 */
		$mdb = $this->mdb();
		if (strtolower($ota_cmd) == 'succeed')
		{
			$organ_code = (int)$mdb->fetch_one("SELECT organ_code FROM {$opt_name} WHERE org_code = '{$org_code}' AND hard_code = '{$hard_code}'");
			$ota_sql    = "INSERT INTO {$opt_name}_device SET organ_code = '{$organ_code}', org_code = '{$org_code}', hard_code = '{$hard_code}',
			soft_code = '{$soft_code}', device_sn = '{$device_sn}', device_ip = '{$device_ip}', device_mac = '{$device_mac}', upgrade_count = 1,
			upgrade_time = NOW(), added_time = NOW() ON DUPLICATE KEY UPDATE soft_code = '{$soft_code}', device_ip = '{$device_ip}',
			device_mac = '{$device_mac}', upgrade_count = upgrade_count + 1, upgrade_time = NOW()";
			$mdb->query($ota_sql);
			exit($mdb->affected_rows() > 0 ? 'ok' : 'error');
		}

		/**
		 * 检测并获取最新可升级的版本
		 */
		$ota_sql = "SELECT org_code AS orgCode, hard_code AS hardCode, soft_code AS softCode, soft_desc AS softDesc,
		is_forced AS isForced, pack_url AS packUrl, pack_md5 AS packMd5, pack_size AS packSize, time_publish AS timePublish FROM {$opt_name}
		WHERE is_valid = 1 AND org_code = '{$org_code}' AND hard_code = '{$hard_code}' AND soft_code > '{$soft_code}' AND soft_for = '{$soft_code}'
		ORDER BY soft_code DESC";
		$result  = $mdb->fetch_row($ota_sql);
		if (empty($result))
		{
			file_put_contents($log_file, "{$now_time} - [{$device_tip}] no version can upgrade\n", FILE_APPEND);
			exit();
		}

		exit(json_encode($result));
	}
}
?>