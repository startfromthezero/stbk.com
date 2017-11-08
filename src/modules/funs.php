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
	 * @param string $url URL地址
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
	 * 流量值自动单位转换
	 *
	 * @param float $gprs 流量值
	 * @return string 带单位流量值
	 */
	public static function gpgsFormat($gprs)
	{
		$GB = 1024;
		$TB = 1024 * 1024;

		if (abs($gprs) < $GB)
		{
			$gprs = round($gprs, 2) . '<b style="color:#008000"> M</b>';
		}
		else if (abs($gprs) >= $GB && $gprs < $TB)
		{
			$gprs = round($gprs / $GB, 2) . '<b style="color:#0000FF"> G</b>';
		}
		else if (abs($gprs) >= $TB)
		{
			$gprs = round($gprs / $TB, 2) . '<b style="color:#FF0000"> T</b>';
		}

		return $gprs;
	}

	/**
	 * 判断流量套餐包年或包月
	 *
	 * @param $gprs_total
	 * @param $allot_month
	 * @param $allot_value
	 * @return string
	 */
	public static function packShow($gprs_total, $allot_month, $allot_value)
	{
		if ($allot_month == 1)
		{
			$result = self::gpgsFormat($gprs_total) . " 包年套餐";
		}
		else
		{
			$result = self::gpgsFormat($gprs_total) . " 包月套餐";
		}

		return $result;
	}

	/**
	 * 根据套餐分配流量
	 *
	 * @param modules_mem $mem     缓存操作对象
	 * @param integer     $card_id 流量卡编号
	 */
	public static function gprsAllot(&$mem, $card_id = 0)
	{
		$mdb = $mem->mdb();
		$mdb->query('SET AUTOCOMMIT=0');

		/**
		 * 获取所有未过期的套餐记录,根据这些记录处理流量分配
		 */
		$where = !empty($card_id) ? "card_id = '{$card_id}' AND assigned_month = 0 AND " : '';
		$sql   = "SELECT * FROM cc_gprs_allot WHERE {$where} time_expire > NOW() ORDER BY allot_id ASC";
		$lres  = $mdb->fetch_all($sql);
		if (!empty($lres))//分配流量
		{
			$curt_month = date('Ym');//当月
			$last_month = date('Ym', strtotime('last month'));//上月
			foreach ($lres as $lv)
			{
				$sql = "SELECT * FROM cc_gprs_value WHERE card_id = '{$lv['card_id']}' AND allot_id = '{$lv['allot_id']}'	AND how_month IN ({$last_month}, {$curt_month})";
				$res = $mem->hash_sql($sql, 'how_month');
				if (isset($res[$curt_month]))//判断当前月是否已分配流量,如果已分配则无需分配
				{
					continue;
				}

				/**
				 * 流量分配计算
				 */
				$drs = array(
					'card_id'       => $lv['card_id'],
					'allot_id'      => $lv['allot_id'],
					'time_expire'   => $lv['time_expire'],
					'gprs_value'    => 0,
					'balance_dval'  => 0,
					'balance_value' => 0,
				);
				$res = isset($res[$last_month]) ? $res[$last_month] : $drs;
				unset($res['gprs_vid']);
				$res['time_added']  = 'dbf|NOW()';
				$res['time_modify'] = 'dbf|NULL';
				$res['how_month']   = $curt_month;

				/**
				 * 判断是否还有未分配的流量
				 */
				if ($lv['assigned_month'] < $lv['allot_month'])
				{
					$res['gprs_value']    = ($lv['allot_reset'] && $res['gprs_value'] >= 0) ? $lv['allot_value'] : ($res['gprs_value'] + $lv['allot_value']);
					$res['balance_dval']  = ($lv['allot_reset'] && $res['balance_dval'] >= 0) ? $lv['allot_value'] : ($res['balance_dval'] + $lv['allot_value']);
					$res['balance_value'] = ($lv['allot_reset'] && $res['balance_value'] >= 0) ? $lv['allot_value'] : ($res['balance_value'] + $lv['allot_value']);

					/**
					 * 流量分配存储与更新分配次数
					 */
					$mdb->query('START TRANSACTION');
					if ($mdb->insert('cc_gprs_value', $res))
					{
						if ($mdb->query("UPDATE cc_gprs_allot SET assigned_month = assigned_month + 1 WHERE allot_id = '{$lv['allot_id']}'"))
						{
							$mdb->query('COMMIT');
							file_put_contents(DIR_ROOT . '/system/logs/allot.log', date('Y-m-d H:i:s') . " card id: {$lv['card_id']} and allot id: {$lv['allot_id']} allot succeed\n");
						}
					}
					$mdb->query('ROLLBACK');
				}
				else
				{
					/**
					 * 判断年叠加包是否为0，0代表已使用完
					 */
					if ($res['balance_dval'] != 0 || $res['balance_value'] != 0)
					{
						$mdb->insert('cc_gprs_value', $res);
					}
				}
			}
		}
	}

	/**
	 * 根据所传流量计算流量卡的当月流量
	 *
	 * @param modules_mem $mem       缓存操作对象
	 * @param db          $mdb       主数据库连接操作
	 * @param array       $card_info 流量卡信息
	 * @param array       $gprs      当前流量数据
	 * @return bool
	 */
	public static function gprsCalculate(&$mem, &$mdb, $card_info, $gprs)
	{
		/*
		 * 设置流量修改锁，流量计算修改完成后释放
		 * 防止流量充值时，接口流量计算产生数据混乱
		 */
		$mkey = "GPRS-CARD-LOCK-{$card_info['card_iccid']}";
		$mem->mem_set($mkey, 1);

		$data               = $card_info;//需要更新的数据组
		$data['used_total'] = $gprs['total'];//累计使用流量
		$data['used_month'] = $gprs['month'];//当前月使用流量

		/**
		 * 传入的数据为联通数据，因联通脚本是计算的昨天的流量数据，则当为该月第一天时，计算时需以上个月的查询为准
		 */
		if ($gprs['is_unicom'])
		{
			$data['unicom_total'] = $gprs['total'];//累计使用流量
			$data['unicom_month'] = $gprs['month'];//当前月使用流量
			$gprs_diff            = $gprs['month'] - $card_info['unicom_month']; //距离上次月流量的差异值
			$month                = date('j') == 1 ? date('Ym', strtotime('last month')) : date('Ym');
		}
		else
		{
			$data['time_last'] = date('Y-m-d H:i:s');//最后更新时间，车机上报时则更新
			$gprs_diff         = $gprs['month'] - $card_info['used_month']; //距离上次月流量的差异值
			$month             = date('Ym');
		}

		/**
		 * 查询该卡当月的流量值列表
		 */
		$sql        = "SELECT * FROM cc_gprs_value WHERE card_id = {$card_info['card_id']} AND how_month = {$month} ORDER BY time_expire ASC";
		$value_list = $mdb->fetch_all($sql);

		if (empty($value_list))
		{
			$mem->mem_del($mkey);//删除流量锁缓存
			$data['max_unused'] = $card_info['max_unused'] - $gprs_diff;

			return self::updateCard($mem, $mdb, $data) ? $data : false;
		}

		$value_num   = count($value_list); //当月分配的流量值数量总和
		$balance_num = 0; //剩余流量为0的数量
		/**
		 * 循环流量值列表计算剩余的流量
		 */
		foreach ($value_list as $k => $value)
		{
			/**
			 * 已过期的需把剩余流量置为0
			 */
			if (strtotime($value['time_expire']) <= time())
			{
				$update_value = array(
					'gprs_vid'      => $value['gprs_vid'],
					'balance_dval'  => 0,
					'balance_value' => 0,
					'time_modify'   => date('Y-m-d H:i:s'),
				);
				self::updateValue($mdb, $update_value);
				$balance_num++;
				continue;
			}

			$value['balance_dval'] = $gprs['is_unicom'] ? $value['balance_value'] : $value['balance_dval'];
			if ($value['balance_dval'] == 0)
			{
				$balance_num++;
				continue;
			}

			$balance_dval                  = $value['balance_dval'] - $gprs_diff;
			$update_value                  = array(
				'gprs_vid'     => $value['gprs_vid'],
				'balance_dval' => ($balance_dval > 0 || $k == $value_num) ? $balance_dval : 0,
				'time_modify'  => date('Y-m-d H:i:s'),
			);
			$update_value['balance_value'] = $gprs['is_unicom'] ? $update_value['balance_dval'] : $value['balance_value'];
			self::updateValue($mdb, $update_value);

			/**
			 * 剩余值小于0时，继续计算下一个套餐流量值
			 */
			if ($balance_dval < 0)
			{
				$gprs_diff = -$balance_dval;
				continue;
			}
			break;
		}

		/**
		 * 如果所有流量值记录剩余流量都为0，则把最后一个记录的剩余流量减为负数
		 */
		if ($value_num == $balance_num)
		{
			$value                         = $value_list[$value_num - 1];
			$value['balance_dval']         = $gprs['is_unicom'] ? $value['balance_value'] : $value['balance_dval'];
			$update_value                  = array(
				'gprs_vid'     => $value['gprs_vid'],
				'balance_dval' => $value['balance_dval'] - $gprs_diff,
				'time_modify'  => date('Y-m-d H:i:s'),
			);
			$update_value['balance_value'] = $gprs['is_unicom'] ? $update_value['balance_dval'] : $value['balance_value'];
			self::updateValue($mdb, $update_value);
		}

		/**
		 * 计算该卡的总剩余流量
		 */
		$sql                = "SELECT SUM(balance_dval) FROM cc_gprs_value WHERE card_id = {$card_info['card_id']} AND time_expire > NOW() AND how_month = {$month}";
		$data['max_unused'] = $mdb->fetch_one($sql);

		$mem->mem_del($mkey);//删除流量锁缓存
		return self::updateCard($mem, $mdb, $data) ? $data : false;
	}

	/**
	 * 更新流量卡信息
	 *
	 * @param modules_mem $mem  缓存操作对象
	 * @param db          $mdb  主数据库连接操作
	 * @param array       $data 需更新的流量卡数据
	 * @return bool
	 */
	protected static function updateCard(&$mem, &$mdb, $data = array())
	{
		if (!$mem->mem_set("GPRS-CARD-{$data['card_iccid']}", $data, 0))
		{
			return $mdb->update('cc_gprs_card', $data, "card_id = '{$data['card_id']}'");
		}

		$mem->mem->push($mem->mem_type_res, 'GPRS-QUEUE', $data['card_iccid']);//将流量卡ICCID加入到队列中
		return true;
	}

	/**
	 * 更新流量卡的流量值数据
	 *
	 * @param db    $mdb  主数据库连接操作
	 * @param array $data 需更新的流量值数据
	 * @return mixed
	 */
	protected static function updateValue(&$mdb, $data = array())
	{
		return $mdb->update('cc_gprs_value', $data, "gprs_vid = '{$data['gprs_vid']}'");
	}
}
?>