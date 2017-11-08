<?php
class ModelCommonHome extends Model
{
	/**
	 * 获取各市销售的车辆数量
	 *
	 * @return mixed
	 */
	public function getCityCars()
	{
		$sql = 'SELECT n.ntname AS name,COUNT(d.city_id) AS value FROM `cc_device` d
				INNER JOIN `cc_nation` n ON d.city_id = n.ntid WHERE d.city_id > 0';
		$sql .= $this->user->getGroupId() <= 1 ? '' : " AND d.org_id = '{$this->user->getId()}'";
		$sql .= ' GROUP BY d.city_id';

		return $this->sdb()->fetch_all($sql);
	}

	/**
	 * 获取各省销售的车辆数量
	 *
	 * @return mixed
	 */
	public function getProvinceCars()
	{
		$sql = 'SELECT COUNT(d.province_id) AS value,CASE WHEN n.ntname = "内蒙古自治区" OR n.ntname ="黑龙江省" THEN
				SUBSTR(n.ntname,1,3) ELSE SUBSTR(n.ntname,1,2) END AS name FROM `cc_device` d INNER JOIN `cc_nation` n ON
				d.province_id = n.ntid WHERE d.province_id > 0';
		$sql .= $this->user->getGroupId() <= 1 ? '' : " AND d.org_id = '{$this->user->getId()}'";
		$sql .= ' GROUP BY d.province_id';

		return $this->sdb()->fetch_all($sql);
	}
}
?>