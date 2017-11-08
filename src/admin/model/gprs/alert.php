<?php
class ModelGprsAlert extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_gprs_alert';

	public function getOrgId($org_id)
	{
		return $this->sdb()->fetch_row("SELECT * FROM {$this->_opt} WHERE org_id = {$org_id}");
	}

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
	 * 更新数据
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function update($gprs_alert_id, $data = array())
	{
		$sql    = "UPDATE {$this->_opt} SET alert_value = '{$data['alert_value']}',
		alert_tpl1 = '{$data['alert_tpl1']}', alert_tpl2 = '{$data['alert_tpl2']}',
		time_modify = NOW() WHERE gprs_alert_id = {$gprs_alert_id}";
		$result = $this->mdb()->query($sql);
		if (!empty($result))
		{
			$res = $this->mdb()->fetch_all('SELECT * FROM cc_gprs_alert');
			$this->mem_set('GPRS-ALERT', wcore_utils::hash_array($res, 'org_id'));
		}

		return $result;
	}

	/**
	 * 获取数据
	 *
	 * @param int $gprs_alert_id
	 * @return bool|int
	 */
	public function get($gprs_alert_id)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE gprs_alert_id = '{$gprs_alert_id}'";
		$sql .= $this->user->getGroupId() <= 1 ? '' : " AND org_id = '{$this->user->getOrgId()}'";

		return $this->sdb()->fetch_row($sql);
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

		if ($data['org_id'] !== '')
		{
			$sql .= " AND org_id = '{$data['org_id']}'";
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