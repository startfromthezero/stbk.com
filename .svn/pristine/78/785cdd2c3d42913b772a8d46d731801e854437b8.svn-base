<?php
class ModelSettingExtension extends Model
{
	public function getInstalled($type)
	{
		$extension_data = array();
		$extension_rows = $this->sdb()->fetch_all('SELECT * FROM ' . DB_PREFIX . "extension WHERE `type` = '{$type}'");
		foreach ($extension_rows as $result)
		{
			$extension_data[] = $result['code'];
		}

		return $extension_data;
	}

	public function install($type, $code)
	{
		$this->mdb()->query('INSERT INTO ' . DB_PREFIX . "extension SET `type` = '{$type}', `code` = '{$code}'");
	}

	public function uninstall($type, $code)
	{
		$this->mdb()->query('DELETE FROM ' . DB_PREFIX . "extension WHERE `type` = '{$type}' AND `code` = '{$code}'");
	}
}
?>