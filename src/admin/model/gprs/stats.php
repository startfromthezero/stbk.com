<?php
class ModelGprsStats extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_gprs_stats';

	/**
	 * 获取月使用流量总计和月超标流量总计
	 */
	public function getNum($data = array())
	{
		$sql = "SELECT SUM(S.month_used) AS mu,SUM(S.month_over) AS mo FROM {$this->_opt} S
				INNER JOIN cc_gprs_card C ON S.card_id = C.card_id WHERE ";

		$sql .= $this->user->getGroupId() <= 1 ? '1' : " C.org_id = '{$this->user->getOrgId()}'";

		//filter_date_start
		if ($data['filter_org_id'] > 0)
		{
			$sql .= " AND C.org_id = '{$data['filter_org_id']}'";
		}

		if (!empty($data['filter_card_iccid']))
		{
			$sql .= " AND C.card_iccid LIKE '%{$data['filter_card_iccid']}%'";
		}

		if (isset($data['filter_mdate']) && $data['filter_mdate'] !== '')
		{
			$sql .= " AND S.how_month ='{$data['filter_mdate']}'";
		}

		return $this->mem_sql($sql, DB_GET_ROW);
	}

	/**
	 * 获取月流量统计数据
	 *
	 * @param array $data
	 * @param bool  $just_total
	 * @param bool  $subtotal 仅统计每页月使用流量和月超标流量的总和
	 * @return mixed
	 */
	public function getItems($data = array(), $just_total = false, $subtotal = false)
	{
		$sql = '';

		/**
		 * 判断是否仅获取小计
		 */
		if($subtotal)
		{
			$sql = 'SELECT SUM(month_used) as mu,SUM(month_over) as mo FROM (';
		}

		$sql .= "SELECT S.*,C.card_iccid,C.org_id FROM {$this->_opt} S
				INNER JOIN cc_gprs_card C ON S.card_id = C.card_id
				WHERE ";

		$sql .= $this->user->getGroupId() <= 1 ? '1' : " C.org_id = '{$this->user->getOrgId()}'";

		//filter_date_start
		if ($data['filter_org_id'] > 0)
		{
			$sql .= " AND C.org_id = '{$data['filter_org_id']}'";
		}

		if (!empty($data['filter_card_iccid']))
		{
			$sql .= " AND C.card_iccid LIKE '%{$data['filter_card_iccid']}%'";
		}

		if (isset($data['filter_mdate']) && $data['filter_mdate'] !== '')
		{
			$sql .= " AND S.how_month ='{$data['filter_mdate']}'";
		}

		$sort_data = array(
			'org_id',
			'how_month',
			'month_used',
			'month_over'
		);

		//判断是否仅获取条数
		if ($just_total)
		{
			return $this->sdb()->fetch_one(str_replace(' S.*', ' COUNT(*)', $sql));
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data))
		{
			$sql .= " ORDER BY {$data['sort']}";
			$sql .= (isset($data['order']) && ($data['order'] == 'DESC')) ? ' DESC' : ' ASC';
		}
		else
		{
			//根据分页情况获取数据
			$sql .= " ORDER BY S.time_modify DESC";
		}

		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		/**
		 * 判断是否仅获取小计
		 */
		if ($subtotal)
		{
			$sql .= ') t';
		}
		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 获取流量卡的所属机构
	 *
	 * @return mixed
	 */
	public function getOrgs($how_month)
	{
		$sql = "SELECT C.org_id,O.name FROM {$this->_opt} S LEFT JOIN `cc_gprs_card` C ON S.`card_id`= C.card_id
				LEFT JOIN `cc_org` O ON C.org_id = O.org_id WHERE S.how_month = {$how_month}";

		$sql .= $this->user->getGroupId() <= 1 ? '' : " AND C.org_id = '{$this->user->getOrgId()}'";

		$sql .= " GROUP BY C.org_id ORDER BY C.org_id ASC";

		return $this->sdb()->fetch_pairs($sql);
	}

	/**
	 * 获取月份
	 *
	 * @return mixed
	 */
	public function getMonths()
	{
		$sql = "SELECT how_month FROM {$this->_opt} GROUP BY how_month ORDER BY how_month ASC";

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 获取月使用流量
	 *
	 * @return mixed
	 */
	public function getMonthUsed($how_month)
	{
		$sql = "SELECT C.org_id,SUM(S.month_used) AS mu FROM {$this->_opt} S
				LEFT JOIN `cc_gprs_card` C ON S.`card_id`= C.card_id WHERE how_month = {$how_month}";

		$sql .= $this->user->getGroupId() <= 1 ? '' : " AND C.org_id = '{$this->user->getOrgId()}'";

		$sql .= " GROUP BY C.org_id ORDER BY C.org_id ASC";

		return $this->sdb()->fetch_pairs($sql);
	}

	/**
	 * 获取月超标流量
	 *
	 * @return mixed
	 */
	public function getMonthOver($how_month)
	{
		$sql = "SELECT C.org_id,SUM(S.month_over) AS mo FROM {$this->_opt} S
				LEFT JOIN `cc_gprs_card` C ON S.`card_id`= C.card_id WHERE how_month = {$how_month}";

		$sql .= $this->user->getGroupId() <= 1 ? '' : " AND C.org_id = '{$this->user->getOrgId()}'";

		$sql .= " GROUP BY C.org_id ORDER BY C.org_id ASC";

		return $this->sdb()->fetch_pairs($sql);
	}
}
?>