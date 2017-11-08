<?php
class ModelGprsPack extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_gprs_pack';

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
	 * @param int $pack_id
	 * @return bool|int
	 */
	public function get($pack_id)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE pack_id = '{$pack_id}'";
		$sql .= $this->user->getGroupId() <= 1 ? '' : " AND org_id = '{$this->user->getOrgId()}'";

		return $this->sdb()->fetch_row($sql);
	}

	/**
	 * 判断机构某流量数的套餐是否重复
	 *
	 * @param int   $org_id
	 * @param float $gprs_amount
	 * @return bool|int
	 */
	public function isRepeat($org_id, $gprs_amount)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE org_id = '{$org_id}' AND gprs_amount = '{$gprs_amount}'";

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

		return $this->mdb()->update($this->_opt, $data, "pack_id = '{$data['pack_id']}'");
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

		//filter_date_start
		if ($data['filter_org_id'] !== '')
		{
			$sql .= " AND org_id = '{$data['filter_org_id']}'";
		}
		if (!empty($data['filter_date_added']))
		{
			$sql .= " AND DATE(time_added) >= DATE('{$data['filter_date_added']}')";
		}

		//判断是否仅获取条数
		if ($just_total)
		{
			return $this->sdb()->fetch_one(str_replace(' * ', ' COUNT(*) ', $sql));
		}

		//根据分页情况获取数据
		$sql .= " ORDER BY org_id ASC";
		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}

	public function getPack()
	{
		$sql = 'SELECT pack_id,gprs_amount FROM `' . DB_PREFIX . 'gprs_pack`';
		$sql .= $this->user->getGroupId() <= 1 ? '' : " WHERE org_id = '{$this->user->getOrgId()}'";
		$sql .= ' GROUP BY gprs_amount';
		return $this->sdb()->fetch_pairs($sql);
	}

}
?>