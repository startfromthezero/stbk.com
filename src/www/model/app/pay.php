<?php
class ModelAppPay extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_gprs_pay';

	/**
	 * 获取流量卡已付款的充值记录
	 *
	 * @param array $data       查询与分页
	 * @param bool  $just_total 仅获取数量
	 * @return array mixed      充值的列表记录
	 */
	public function getPayList($data = array(), $just_total = false)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE is_paid = 1";

		//filter_date_start
		if (!empty($data['filter_card_id']))
		{
			$sql .= " AND card_id = '{$data['filter_card_id']}'";
		}

		//判断是否仅获取条数
		if ($just_total)
		{
			return $this->sdb()->fetch_one(str_replace(' * ', ' COUNT(*) ', $sql));
		}

		//根据分页情况获取数据
		$sql .= ' ORDER BY time_paid DESC';
		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 获取流量卡的充值记录
	 *
	 * @param array $data       查询与分页
	 * @param bool  $just_total 仅获取数量
	 * @return array mixed      充值的列表记录
	 */
	public function getPayAll($data = array(), $just_total = false)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE pay_id > 0";

		//filter_date_start
		if (!empty($data['filter_card_id']))
		{
			$sql .= " AND card_id = '{$data['filter_card_id']}'";
		}

		/* 是否支付 */
		if (!empty($data['filter_is_paid']) && $data['filter_is_paid'])
		{
			$sql .= " AND is_paid = '{$data['filter_is_paid']}'";
		}

		//判断是否仅获取条数
		if ($just_total)
		{
			return $this->sdb()->fetch_one(str_replace(' * ', ' COUNT(*) ', $sql));
		}

		//根据分页情况获取数据
		$sql .= ' ORDER BY time_paid DESC';
		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 获取流量充值记录
	 *
	 * @param int $pay_id
	 * @return bool|int
	 */
	public function getPayInfo($pay_id)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE pay_id = '{$pay_id}'";

		return $this->sdb()->fetch_row($sql);
	}

	/**
	 * 流量充值记录保存
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function insertPay($data)
	{
		$data['time_added'] = 'dbf|NOW()';

		return $this->mdb()->insert($this->_opt, $data);
	}

	/**
	 * 获取充值卡信息
	 *
	 * @param int $zck_code
	 * @return mixed
	 */
	public function getCzkInfo($zck_code)
	{
		$sql = "SELECT * FROM cc_gprs_czk WHERE zck_code = '{$zck_code}'";

		return $this->sdb()->fetch_row($sql);
	}

	/**
	 * 充值卡扫描充值流量，需同时操作表：cc_gprs_pay，cc_gprs_czk，cc_gprs_card，添加事务回滚
	 *
	 * @param $czk_info
	 * @param $card_info
	 * @return bool|int
	 */
	public function qrcode_pay($czk_info, $card_info)
	{
		$mdb = $this->mdb();
		$mdb->query('SET AUTOCOMMIT=0');
		$mdb->query('START TRANSACTION');

		/*
		 * 充值支付记录保存
	     */
		$data = array(
			'card_id'     => $card_info['card_id'],
			'org_id'      => $card_info['org_id'],
			'gprs_amount' => $czk_info['zck_gprs'],
			'gprs_price'  => $czk_info['zck_value'],
			'pay_method'  => 3,
			'transfer_id' => $czk_info['zck_code'],
			'is_paid'     => 1,
			'time_paid'   => 'dbf|NOW()',
			'time_added'  => 'dbf|NOW()',
		);
		$mdb->query('INSERT INTO cc_gprs_pay ' . $mdb->make_sql($data));
		if (!$mdb->insert_id())
		{
			$mdb->query('ROLLBACK');

			return false;
		}

		/*
		 * 充值卡已充值信息修改
		 */
		$czk_data = array(
			'card_id'   => $card_info['card_id'],
			'time_used' => 'dbf|NOW()',
		);
		$mdb->query("UPDATE cc_gprs_czk SET {$mdb->make_sql($czk_data, 'u')} WHERE zck_code = '{$czk_info['zck_code']}'");
		if (!$mdb->affected_rows())
		{
			$mdb->query('ROLLBACK');

			return false;
		}

		/*
		 * 流量卡的充值流量增加
		 * 可使用充值流量计算规则：
		 * 计算后的最大可使用流量小于0时，则不更新，为0
		 * 计算前的最大可使用流量大于0时，则加上充值的套餐流量
		 * 计算前的最大可使用流量小于0时，则等于计算后的最大可使用流量
		 */
		$card_data               = $card_info;
		$card_data['pay_total']  = $card_info['pay_total'] + $czk_info['zck_gprs'];
		$card_data['max_unused'] = $card_info['max_unused'] + $czk_info['zck_gprs'];
		$card_data['pay_unused'] = $card_data['max_unused'] < 0 ? 0 : ($card_info['max_unused'] > 0 ? ($card_info['pay_unused'] + $czk_info['zck_gprs']) : $card_data['max_unused']);

		$card_data['time_paid'] = date('Y-m-d H:i:s');

		/**
		 * 充值后过期时间延长
		 */
		if (strtotime($card_info['time_expire']) <= time())
		{
			$card_data['time_expire'] = date('Y-m-d H:i:s', strtotime("+ {$czk_info['live_month']}month"));
		}
		else
		{
			$card_data['time_expire'] = date('Y-m-d H:i:s', strtotime("+ {$czk_info['live_month']}month", strtotime($card_data['time_expire'])));
		}

		/**
		 * 先在缓存中更新数据，如缓存更新失败则直接更新到数据库
		 */
		$result = $this->mem_set("GPRS-CARD-{$card_data['card_iccid']}", $card_data, 0);
		if (!$result)
		{
			$mdb->query("UPDATE cc_gprs_card SET {$mdb->make_sql($card_data, 'u')} WHERE card_id = '{$card_info['card_id']}'");
			$result = $mdb->affected_rows();
		}
		else
		{
			$this->mem->push($this->mem_type_res, 'GPRS-QUEUE', $card_info['card_iccid']);    //将流量卡ICCID加入到队列中
		}

		/**
		 * 缓存或数据库都更新失败，事务回滚
		 */
		if (!$result)
		{
			$mdb->query('ROLLBACK');

			return false;
		}

		$mdb->query('COMMIT');

		return true;
	}

	/**
	 * 微信支付成功后充值流量，需同时操作表：cc_gprs_pay，cc_gprs_card，添加事务回滚
	 *
	 * @param $card_info
	 * @param $pay_info
	 * @return bool|int
	 */
	public function online_pay($card_info, $pay_info, $pack_info)
	{
		$mdb = $this->mdb();
		$mdb->query('SET AUTOCOMMIT=0');
		$mdb->query('START TRANSACTION');

		/*
		 * 订单支付成功，状态修改
		 */
		$pay_data = array(
			'transfer_id' => $pay_info['transfer_id'],
			'is_paid'     => 1,
			'time_paid'   => $pay_info['time_paid'],
		);
		$mdb->query("UPDATE {$this->_opt} SET {$mdb->make_sql($pay_data, 'u')} WHERE pay_id = '{$pay_info['pay_id']}'");
		if (!$mdb->affected_rows())
		{
			$mdb->query('ROLLBACK');

			return false;
		}

		/**
		 * 增加流量套餐
		 */
		$allot    = array(
			'card_id'        => $card_info['card_id'],
			'gprs_total'     => $pack_info['gprs_amount'],
			'allot_month'    => $pack_info['allot_month'],
			'allot_value'    => $pack_info['allot_value'],
			'allot_reset'    => $pack_info['allot_reset'],
			'assigned_month' => 0,
			'time_expire'    => date('Y-m-d H:i:s', strtotime("+ {$pack_info['live_month']} month")),
			'time_added'     => date('Y-m-d H:i:s'),
		);
		$allot_id = $this->mdb()->insert('cc_gprs_allot', $allot);
		if (empty($allot_id))
		{
			$mdb->query('ROLLBACK');

			return false;
		}

		$mem = new modules_mem();
		modules_funs::gprsAllot($mem, $card_info['card_id']); //根据套餐分配流量

		/*
		 * 流量卡的充值流量增加
		 */
		$card_data               = $card_info;
		$sql                     = "SELECT SUM(balance_dval) FROM cc_gprs_value WHERE card_id = {$card_info['card_id']} AND how_month = " . date('Ym');
		$card_data['max_unused'] = $this->sdb()->fetch_one($sql);
		$card_data['time_paid']  = $pay_data['time_paid'];

		/**
		 * 如该卡已被停号，并且充值后最大可使用流量大于0，需重新开启流量卡
		 */
		if ($card_info['unicom_stop'] == 1 && $card_data['max_unused'] > 0)
		{
			$params = array(
				'cmd'          => 'action',
				'serialNumber' => $card_info['card_sn'],
				'timestamp'    => time(),
				'token'        => md5(UNICOM_KEY . date('dHYm')),
				'opFlag'       => 0
			);
			$res    = json_decode(wcore_utils::curl(UNICOM_URL, $params, true), true);
			if ($res['status'] == 1)
			{
				$card_data['unicom_stop'] = 0;
			}
			else
			{
				$mdb->query('ROLLBACK');

				return false;
			}
		}

		/**
		 * 先在缓存中更新数据，如缓存更新失败则直接更新到数据库
		 */
		$result = $this->mem_set("GPRS-CARD-{$card_data['card_iccid']}", $card_data, 0);
		if (!$result)
		{
			$mdb->query("UPDATE cc_gprs_card SET {$mdb->make_sql($card_data, 'u')} WHERE card_id = '{$card_info['card_id']}'");
			$result = $mdb->affected_rows();
		}
		else
		{
			$this->mem->push($this->mem_type_res, 'GPRS-QUEUE', $card_info['card_iccid']);    //将流量卡ICCID加入到队列中
		}

		/**
		 * 缓存或数据库都更新失败，事务回滚
		 */
		if (!$result)
		{
			$mdb->query('ROLLBACK');

			return false;
		}

		$mdb->query('COMMIT');

		return true;
	}

}
?>