<?php
class ModelContentTimer extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_timer';

	/**
	 * 获取数据
	 *
	 * @param int $org_id
	 * @return mixed
	 */
	public function get($timer_id)
	{
		return $this->sdb()->fetch_row("SELECT * FROM {$this->_opt} WHERE timer_id = '{$timer_id}'");
	}

	/**
	 * 插入或修改数据
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function insertOrUpdate($data = array()){
		$data['time_added'] = 'dbf|NOW()';
		if(isset($data['timer_id']) && !empty($data['timer_id'])){
			return $this->mdb()->update($this->_opt, $data, " timer_id = {$data['timer_id']}");
		}else{
			return $this->mdb()->insert($this->_opt, $data);
		}
	}

	public function deleteTimer($timer_id)
	{
		return $this->mdb()->query("DELETE FROM {$this->_opt} WHERE timer_id = '{$timer_id}'");
	}

	/**
	 * 获取链接列表
	 *
	 * @param array $data       查询与分页
	 * @param bool  $just_total 仅获取数量
	 * @return mixed
	 */
	public function getTimerList($data = array(), $just_total = false)
	{
		$sql = "SELECT * FROM {$this->_opt}";

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
			$data['limit'] = ($data['limit'] < 1) ? 10 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}
}
?>