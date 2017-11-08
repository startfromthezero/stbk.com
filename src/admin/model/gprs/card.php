<?php
class ModelGprsCard extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_gprs_card';

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
	 * @param int $card_id
	 * @return mixed
	 */
	public function get($card_id)
	{
		$sql = "SELECT *,(unicom_total -used_total) as difference FROM {$this->_opt} ";

		if (is_array($card_id))
		{
			$sql .= ' WHERE card_id in (' . implode(',', $card_id) . ')';

			return $this->sdb()->fetch_all($sql);
		}
		else
		{
			$sql .= " WHERE card_id = '{$card_id}'";

			return $this->sdb()->fetch_row($sql);
		}
	}

	/**
	 * 更新数据
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function update($data = array())
	{
		return $this->mdb()->update($this->_opt, $data, "card_id = '{$data['card_id']}'");
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
		$sql = "SELECT *,NOW() as today,(unicom_total -used_total) as difference FROM {$this->_opt} WHERE ";
		$sql .= $this->user->getGroupId() <= 1 ? '1' : " org_id = '{$this->user->getOrgId()}'";

		//filter_card_iccid
		if (!empty($data['filter_card_iccid']))
		{
			$sql .= " AND card_iccid LIKE '%{$data['filter_card_iccid']}%'";
		}
		//filter_org_id
		if (!empty($data['filter_org_id']))
		{
			$sql .= " AND org_id = '{$data['filter_org_id']}'";
		}

		//filter_time_expire
		if (!empty($data['filter_time_expire']))
		{
			$sql .= $data['filter_time_expire'] == 1 ? ' AND time_expire > NOW()' : ' AND time_expire <= NOW()';
		}

		//filter_difference
		if (!empty($data['filter_difference']))
		{
			$sql .= " AND unicom_total - used_total >= {$data['filter_difference']}";
		}

		//filter_unicom_stop
		if ($data['filter_unicom_stop'] !== '')
		{
			$sql .= " AND unicom_stop = {$data['filter_unicom_stop']}";
		}

		$sort_data = array(
			'difference',
			'unicom_total',
			'gprs_month',
			'used_month',
			'used_total',
			'max_unused',
			'pay_total',
			'time_last'
		);

		//判断是否仅获取条数
		if ($just_total)
		{
			return $this->sdb()->fetch_one(str_replace(' *,NOW() as today,(unicom_total -used_total) as difference ', ' COUNT(*) ', $sql));
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data))
		{
			$sql .= " ORDER BY {$data['sort']}";
			$sql .= (isset($data['order']) && ($data['order'] == 'DESC')) ? ' DESC' : ' ASC';
		}
		else
		{
			if (!empty($data['filter_difference']))
			{
				$sql .= " ORDER BY difference ASC";
			}
			else
			{
				$sql .= " ORDER BY time_added DESC";
			}
		}

		//根据分页情况获取数据
		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}

	public function getHalt($data = array(), $just_total = false)
	{
		$sql = "SELECT *,NOW() as today FROM {$this->_opt} WHERE (max_unused <= 0 OR time_expire < NOW())";

		//filter_card_iccid
		if (!empty($data['filter_card_iccid']))
		{
			$sql .= " AND card_iccid LIKE '%{$data['filter_card_iccid']}%'";
		}
		//filter_org_id
		if (!empty($data['filter_org_id']))
		{
			$sql .= " AND org_id = '{$data['filter_org_id']}'";
		}

		//filter_time_expire
		if (!empty($data['filter_time_expire']))
		{
			$sql .= $data['filter_time_expire'] == 1 ? ' AND time_expire > NOW()' : ' AND time_expire <= NOW()';
		}

		$sort_data = array(
			'used_month',
			'used_total',
			'max_unused',
			'time_last'
		);

		//判断是否仅获取条数
		if ($just_total)
		{
			return $this->sdb()->fetch_one(str_replace(' *,NOW() as today ', ' COUNT(*) ', $sql));
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data))
		{
			$sql .= " ORDER BY {$data['sort']}";
			$sql .= (isset($data['order']) && ($data['order'] == 'DESC')) ? ' DESC' : ' ASC';
		}
		else
		{
			$sql .= " ORDER BY time_added DESC";
		}

		//根据分页情况获取数据
		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 图表统计机构流量卡
	 *
	 * @return mixed
	 */
	public function getChart()
	{
		$sql = "SELECT COUNT(*) as tj, org_id, unicom_stop FROM {$this->_opt} WHERE ";

		$sql .= $this->user->getGroupId() <= 1 ? '1' : " org_id = '{$this->user->getOrgId()}'";

		$sql .= " GROUP BY org_id";

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 机构的充值明细
	 *
	 * @param array $data
	 * @param bool  $just_total 仅获取数量
	 * @return array mixed
	 */
	public function getPayDetail($data = array(), $just_total = false)
	{
		$sql = "SELECT P.card_id, C.card_sn, C.card_iccid, COUNT(P.pay_id) pay_count, SUM(P.gprs_amount) gprs_count, SUM(P.gprs_price) money_count FROM cc_gprs_pay P";
		$sql .= " LEFT JOIN cc_gprs_card C ON C.card_id = P.card_id WHERE P.is_paid = 1";
		$sql .= $this->user->getGroupId() <= 1 ? '' : " AND P.org_id = '{$this->user->getId()}'";

		if (!empty($data['filter_org_id']))
		{
			$sql .= " AND P.org_id = '{$data['filter_org_id']}'";
		}

		if (!empty($data['filter_date_start']))
		{
			$sql .= " AND P.time_paid >= '{$data['filter_date_start']} 00:00:01'";
		}

		if (!empty($data['filter_date_end']))
		{
			$sql .= " AND P.time_paid <= '{$data['filter_date_end']} 23:59:59'";
		}

		$sql .= " GROUP BY P.card_id";

		//判断是否仅获取条数
		if ($just_total)
		{
			return $this->sdb()->fetch_one("SELECT COUNT(DISTINCT card_id) FROM cc_gprs_pay WHERE is_paid = 1 AND org_id = '{$data['filter_org_id']}'");
		}

		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 获取机构售卡数量
	 */
	public function getSellCard($data = array(), $just_total = false)
	{
		$sql = "SELECT org_id,COUNT(*) FROM {$this->_opt} WHERE ";

		$sql .= $this->user->getGroupId() <= 1 ? '1' : " org_id = '{$this->user->getOrgId()}'";

		if (!empty($data['filter_org_id']))
		{
			$sql .= " AND org_id = '{$data['filter_org_id']}'";
		}

		$sql .= ' GROUP BY org_id';

		//判断是否仅获取条数
		$sdb = $this->sdb();
		if ($just_total)
		{
			$sdb->query($sql);

			return $sdb->num_rows();
		}

		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $sdb->fetch_pairs($sql);
	}

	/**
	 * 流量卡使用统计
	 *
	 * @param array $data
	 * @param bool  $just_total 获取分页
	 * @param bool  $just_group 获取总计
	 * @param bool  $subtotal   获取小计
	 * @return array mixed
	 */
	public function getCardUsed($data = array(), $just_total = false, $just_group = false, $subtotal = false)
	{
		$sql = '';
		if ($subtotal)
		{
			$sql .= 'SELECT SUM(card_count) as card_count,SUM(activated) as activated,SUM(nonactivated) as nonactivated, SUM(unused_count) as unused_count,SUM(used_count) as used_count FROM (';
		}

		$sql .= "SELECT org_id,COUNT(card_id) AS card_count,SUM(unicom_stop) as unicom_stop, COUNT(IFNULL(time_last,NULL)) as activated,SUM(IF (time_last IS NULL,1,0)) AS nonactivated, SUM(max_unused) as unused_count,
				SUM(used_total) as used_count FROM {$this->_opt} WHERE ";

		$sql .= $this->user->getGroupId() <= 1 ? '1' : " org_id = '{$this->user->getOrgId()}'";

		if (!empty($data['filter_org_id']))
		{
			$sql .= " AND org_id = '{$data['filter_org_id']}'";
		}

		if (!empty($data['filter_date_start']))
		{
			$sql .= " AND time_added >= '{$data['filter_date_start']} 00:00:01'";
		}

		if (!empty($data['filter_date_end']))
		{
			$sql .= " AND time_added <= '{$data['filter_date_end']} 23:59:59'";
		}

		if (!$just_group)
		{
			$sql .= ' GROUP BY org_id';
		}

		$sort_data = array(
			'card_count',
			'activated',
			'nonactivated'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data))
		{
			$sql .= " ORDER BY {$data['sort']}";
			$sql .= (isset($data['order']) && ($data['order'] == 'DESC')) ? ' DESC' : ' ASC';
		}

		//判断是否仅获取条数
		$sdb = $this->sdb();
		if ($just_total)
		{
			$sdb->query($sql);

			return $sdb->num_rows();
		}

		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}
		if ($subtotal)
		{
			$sql .= ') t';
		}

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 联通流量卡统计
	 *
	 * @param array $data
	 * @param bool  $just_total 获取分页
	 * @param bool  $just_group 获取总计
	 * @param bool  $subtotal   获取小计
	 * @return array mixed
	 */
	public function getUnicomStat($data = array(), $just_total = false, $just_group = false, $subtotal = false)
	{
		$sql = '';
		if ($subtotal)
		{
			$sql .= 'SELECT SUM(card_count) as card_count,SUM(activated) as activated, SUM(nonactivated) as nonactivated, SUM(unicom_count) as unicom_count,SUM(month_count) as month_count FROM (';
		}

		$sql .= "SELECT org_id,COUNT(card_id) AS card_count,SUM(IF (unicom_total = 0,0,1)) AS activated,SUM(IF (unicom_total = 0,1,0)) AS nonactivated,SUM(unicom_total) as unicom_count,
				SUM(unicom_month) as month_count FROM {$this->_opt} WHERE ";

		$sql .= $this->user->getGroupId() <= 1 ? '1' : " org_id = '{$this->user->getOrgId()}'";

		if (!empty($data['filter_org_id']))
		{
			$sql .= " AND org_id = '{$data['filter_org_id']}'";
		}

		if (!$just_group)
		{
			$sql .= ' GROUP BY org_id';
		}

		//判断是否仅获取条数
		$sdb = $this->sdb();
		if ($just_total)
		{
			$sdb->query($sql);

			return $sdb->num_rows();
		}

		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}
		if ($subtotal)
		{
			$sql .= ') t';
		}

		return $this->sdb()->fetch_all($sql);
	}

}
?>