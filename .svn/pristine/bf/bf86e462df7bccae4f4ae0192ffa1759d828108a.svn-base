<?php
class ModelAppGprs extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_gprs_card';

	/**
	 * 通过流量卡编号获取流量卡数据
	 *
	 * @param int $card_id
	 * @return bool|int
	 */
	public function get($card_id)
	{
		/**
		 * 如果缓存里有该流量卡数据，则优先使用缓存
		 */
		$result = $this->sdb()->fetch_row("SELECT * FROM {$this->_opt} WHERE card_id = '{$card_id}'");
		if ($result && $data = $this->mem_get("GPRS-CARD-{$result['card_iccid']}"))
		{
			/**
			 * unicom_stop字段添加，需更新到缓存
			 */
			if (!isset($data['unicom_stop']))
			{
				$data['unicom_stop'] = $result['unicom_stop'];
			}

			return $data;
		}

		return $result;
	}

	/**
	 * 通过ICCID获取流量卡数据
	 *
	 * @param int $iccid
	 * @return mixed
	 */
	public function getByIccid($iccid)
	{
		/**
		 * 如果缓存里有该流量卡数据，则优先使用缓存
		 */
		$data = $this->mem_get("GPRS-CARD-{$iccid}");
		if (!$data)
		{
			return $this->sdb()->fetch_row("SELECT * FROM {$this->_opt} WHERE card_iccid = '{$iccid}'");
		}

		/**
		 * unicom_stop字段添加，需更新到缓存
		 */
		if (!isset($data['unicom_stop']))
		{
			$result              = $this->sdb()->fetch_row("SELECT unicom_stop FROM {$this->_opt} WHERE card_iccid = '{$iccid}'");
			$data['unicom_stop'] = $result['unicom_stop'];
		}

		return $data;
	}

	/**
	 * 更新数据,在缓存中更新
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function update($data = array())
	{
		/**
		 * 在缓存中更新流量卡数据，另有定时任务从缓存中读取数据更新到数据库中
		 * 如更新缓存失败，则直接更新到数据库
		 */
		if (!$this->mem_set("GPRS-CARD-{$data['card_iccid']}", $data, 0))
		{
			return $this->mdb()->update($this->_opt, $data, "card_id = '{$data['card_id']}'");
		}

		$this->mem->push($this->mem_type_res, 'GPRS-QUEUE', $data['card_iccid']);    //将流量卡ICCID加入到队列中
		return true;
	}

	/**
	 * 获取流量卡预警信息
	 *
	 * @param int $org_id
	 * @return mixed
	 */
	public function getAlertInfo($org_id)
	{
		$mkey   = 'GPRS-ALERT';
		$result = $this->mem_get($mkey);
		if (empty($result))
		{
			$result = $this->sdb()->fetch_all('SELECT * FROM cc_gprs_alert');
			$this->mem_set($mkey, wcore_utils::hash_array($result, 'org_id'));
		}

		return isset($result[$org_id]) ? $result[$org_id] : $result[0];
	}

	/**
	 * 流量日志保存,使用消息队列
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function insertLog($data)
	{
		$data['time_added'] = date('Y-m-d H:i:s');
		$sql                = 'INSERT INTO cc_gprs_log ' . wcore_db::make_sql($data, 'i');

		/**
		 * 将新增流量日志sql语句加入到消息队列中，如失败直接插入数据库
		 */
		if (!$this->mem->push($this->mem_type_sql, 'SQL-QUEUE', $sql))
		{
			return $this->mdb()->insert('cc_gprs_log', $data);
		}

		return true;
	}

	/**
	 * 获取流量卡的流量过期时间
	 *
	 * @param $card_id 流量卡编号
	 * @return string
	 */
	public function getTimeExpire($card_id)
	{
		$sql = "SELECT MAX(time_expire) FROM cc_gprs_value WHERE card_id = {$card_id} AND how_month = " . date('Ym');

		return $this->sdb()->fetch_one($sql);
	}

	/**
	 * 获取流量卡的当月使用流量
	 *
	 * @param $card_id 流量卡编号
	 * @return string
	 */
	public function getSumMonthGprs($card_id)
	{
		$sql = "SELECT SUM(gprs_value - balance_dval) FROM cc_gprs_value WHERE card_id = {$card_id} AND how_month = " . date('Ym');

		return $this->sdb()->fetch_one($sql);
	}
}
?>