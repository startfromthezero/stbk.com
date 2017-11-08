<?php
class ModelGprsCzk extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_gprs_czk';

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
	 * 获取充值卡记录
	 *
	 * @param array $data       查询与分页
	 * @param bool  $just_total 仅获取数量
	 * @return mixed
	 */
	public function getItems($data = array(), $just_total = false)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE ";
		$sql .= $this->user->getGroupId() <= 1 ? '1' : " org_id = '{$this->user->getOrgId()}'";

		if (isset($data['org_id']) && $data['org_id'] >= 0)
		{
			$sql .= " AND org_id = '{$data['org_id']}'";
		}

		if (!empty($data['zck_code']))
		{
			$sql .= " AND zck_code LIKE '%{$data['zck_code']}%'";
		}

		if (!empty($data['added_date_start']))
		{
			$sql .= " AND time_added >= '{$data['added_date_start']} 00:00:01'";
		}

		if (!empty($data['added_date_end']))
		{
			$sql .= " AND time_added <= '{$data['added_date_end']} 23:59:59'";
		}

		if (!empty($data['used_date_start']))
		{
			$sql .= " AND time_used >= '{$data['used_date_start']} 00:00:01'";
		}

		if (!empty($data['used_date_end']))
		{
			$sql .= " AND time_used <= '{$data['used_date_end']} 23:59:59'";
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