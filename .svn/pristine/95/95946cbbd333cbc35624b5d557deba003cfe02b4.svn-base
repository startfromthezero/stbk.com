<?php
class ModelSettingOta extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = '';

	public function __construct(Registry $registry)
	{
		parent::__construct($registry);
		$this->_opt = DB_PREFIX . 'ota';
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

		return $this->mdb()->insert("{$this->_opt}", $data);
	}

	/**
	 * 更新数据
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function update($data = array())
	{
		return $this->mdb()->update($this->_opt, $data, "ota_id = '{$data['ota_id']}'");
	}

	/**
	 * 获取记录
	 *
	 * @return mixed
	 */
	public function getItems()
	{
		$sql = "SELECT V.*, U.`firstname`, COUNT(V.ota_id) AS pack_count
		FROM (SELECT * FROM {$this->_opt} ORDER BY `ota_id` DESC) V
		LEFT JOIN " . DB_PREFIX . 'user U ON V.`org_id` = U.org_id GROUP BY U.org_id';

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 获取版本列表
	 *
	 * @param int $org_id
	 * @return mixed
	 */
	public function getOtas($org_id = 0)
	{
		$sql = "SELECT * FROM `{$this->_opt}` WHERE org_id = '{$org_id}' ORDER BY ota_id DESC";

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 获取机构记录
	 *
	 * @return mixed
	 */
	public function getOrgs()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'user ORDER BY org_id';

		return $this->mem_sql($sql, DB_GET_ALL);
	}

	/*
	 * 获取升级的设备记录
	 * @return mixed
	 */
	public function getOtaDevices($data, $just_total = false)
	{
		$sql = "SELECT * FROM {$this->_opt}_device WHERE otad_id != 0";

		if (!empty($data['org_id']))
		{
			$sql .= " AND org_id = '{$data['org_id']}'";
		}

		if (!empty($data['device_sn']))
		{
			$sql .= " AND device_sn LIKE '%{$data['device_sn']}%'";
		}
		$sql .= " ORDER BY `otad_id` DESC";

		//判断是否仅获取条数
		if ($just_total)
		{
			return $this->sdb()->fetch_one(str_replace(' * ', ' COUNT(*) ', $sql));
		}

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