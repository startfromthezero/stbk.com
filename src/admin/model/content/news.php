<?php
class ModelContentNews extends Model
{
	/**
	 * @var string 此库要操作的表
	 */
	private $_opt = 'cc_news';

	/**
	 * 获取数据
	 *
	 * @param int $org_id
	 * @return mixed
	 */
	public function get($news_id)
	{
		return $this->sdb()->fetch_row("SELECT * FROM {$this->_opt} WHERE news_id = '{$news_id}'");
	}

	/**
	 * 插入数据
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function insert($data = array())
	{
		$data['time_added']  = 'dbf|NOW()';
		return $this->mdb()->insert($this->_opt, $data);
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
		return $this->mdb()->update($this->_opt, $data, " news_id = '{$data['news_id']}'");
	}

	public function deleteNews($news_id)
	{
		$this->mdb()->query("DELETE FROM {$this->_opt} WHERE news_id = '{$news_id}'");
	}

	/**
	 * 获取链接列表
	 *
	 * @param array $data       查询与分页
	 * @param bool  $just_total 仅获取数量
	 * @return mixed
	 */
	public function getNewsList($data = array(), $just_total = false)
	{
		$sql = "SELECT * FROM {$this->_opt} WHERE 1";

		if (isset($data['news_status']))
		{
			$sql .= " AND news_status = {$data['news_status']}";
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
			$data['limit'] = ($data['limit'] < 1) ? 10 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}
}
?>