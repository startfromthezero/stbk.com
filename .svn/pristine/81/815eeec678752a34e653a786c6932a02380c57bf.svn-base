<?php
class ModelSettingSetting extends Model
{
	public function getSetting($group, $store_id = 0)
	{
		$data = array();
		$rows = $this->sdb()->fetch_all('SELECT * FROM ' . DB_PREFIX . "setting WHERE store_id = '{$store_id}' AND `group` = '{$group}'");
		foreach ($rows as $v)
		{
			if (!$v['serialized'])
			{
				$data[$v['key']] = $v['value'];
			}
			else
			{
				$data[$v['key']] = unserialize($v['value']);
			}
		}

		return $data;
	}

	public function editSetting($group, $data, $store_id = 0)
	{
		$this->mdb()->query('DELETE FROM ' . DB_PREFIX . "setting WHERE store_id = '{$store_id}' AND `group` = '{$group}'");
		foreach ($data as $key => $value)
		{
			if (!is_array($value))
			{
				$value = (strpos($value, '@') !== false) ? $value : ($value);
				$this->mdb()->query('INSERT INTO ' . DB_PREFIX . "setting SET store_id = '{$store_id}', `group` = '{$group}', `key` = '{$key}', `value` = '{$value}'");
			}
			else
			{
				$value = (serialize($value));
				$this->mdb()->query('INSERT INTO ' . DB_PREFIX . "setting SET store_id = '{$store_id}', `group` = '{$group}', `key` = '{$key}', `value` = '{$value}', serialized = '1'");
			}
		}

		$this->_emptyCache(); //因修改所以清空先前缓冲
	}

	public function deleteSetting($group, $store_id = 0)
	{
		$this->mdb()->query('DELETE FROM ' . DB_PREFIX . "setting WHERE store_id = '{$store_id}' AND `group` = '{$group}'");
		$this->_emptyCache();
	}

	/**
	 * 清空先前缓冲
	 */
	private function _emptyCache()
	{
		$_GET['nocache'] = 1;
		$this->mem_sql('SELECT * FROM ' . DB_PREFIX . "setting WHERE store_id = " . intval($this->config->get('config_store_id')), DB_GET_ALL, false);
		unset($_GET['nocache']);
	}
}
?>