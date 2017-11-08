<?php
class ModelGprsBatch extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_gprs_batch';

	/**
	 * 插入数据
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function insert($data = array())
	{
		$data['time_added'] = 'dbf|NOW()';

		return $this->mdb()->insert($this->_opt, $data);
	}

	/**
	 * 获取数据
	 *
	 * @param int $batch_id
	 * @return bool|int
	 */
	public function get($batch_id)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE batch_id = '{$batch_id}'";
		$sql .= $this->user->getGroupId() <= 1 ? '' : " AND org_id = '{$this->user->getOrgId()}'";

		return $this->sdb()->fetch_row($sql);
	}

	/**
	 * 更新数据
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function update($data = array())
	{
		$data['time_modify'] = 'dbf|NOW()';

		return $this->mdb()->update($this->_opt, $data, "batch_id = '{$data['batch_id']}'");
	}

	/**
	 * 查询流量卡
	 *
	 * @param string $card_iccid
	 * @return bool|int
	 */
	public function getGprsCard($card_iccid)
	{
		$sql = "SELECT card_id FROM cc_gprs_card WHERE card_iccid = '{$card_iccid}'";

		return $this->sdb()->fetch_row($sql);
	}

	/**
	 * 删除流量卡批次
	 *
	 * @param $batch_id
	 * @return bool
	 */
	public function delByBatchId($batch_id)
	{
		$sql = "DELETE FROM {$this->_opt} WHERE batch_id = '" . (int)$batch_id . "'";

		return $this->mdb()->query($sql);
	}

	/**
	 * 更新批次入卡数量
	 *
	 * @return mixed
	 */
	public function getCardAmount($success_count, $bacth_id)
	{
		$sql = "UPDATE {$this->_opt} SET card_amount = {$success_count} WHERE batch_id = {$bacth_id}";

		return $this->mdb()->query($sql);
	}

	/*
	 * 获取区域
	 */
	public function getNation()
	{
		$sql = "SELECT ntid,ntname FROM cc_nation";

		return $this->mem_sql($sql, DB_GET_PAIRS);
	}

	/**
	 * 获取记录
	 *
	 * @param array $data       查询与分页
	 * @param bool  $just_total 仅获取数量
	 * @return mixed
	 */
	public function getItems($data = array(), $just_total = false)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE ";
		$sql .= $this->user->getGroupId() <= 1 ? '1' : " org_id = '{$this->user->getOrgId()}'";

		//org_id
		if (!empty($data['org_id']))
		{
			$sql .= " AND org_id = '{$data['org_id']}'";
		}

		//batch_sn
		if (!empty($data['batch_sn']))
		{
			$sql .= " AND batch_sn LIKE '%{$data['batch_sn']}%'";
		}

		//added_date_start
		if (!empty($data['added_date_start']))
		{
			$sql .= " AND time_added >= '{$data['added_date_start']} 00:00:01'";
		}

		//added_date_end
		if (!empty($data['added_date_end']))
		{
			$sql .= " AND time_added <= '{$data['added_date_end']} 23:59:59'";
		}

		//判断是否仅获取条数
		if ($just_total)
		{
			return $this->sdb()->fetch_one(str_replace(' * ', ' COUNT(*) ', $sql));
		}

		//根据分页情况获取数据
		$sql .= " ORDER BY time_added DESC";
		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}

}
?>