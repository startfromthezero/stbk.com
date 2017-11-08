<?php
class ModelSettingStore extends Model
{
	public function addStore($data)
	{
		$this->mdb()->query('INSERT INTO ' . DB_PREFIX . "store SET name = '{$data['config_name']}', `url` = '{$data['config_url']}', `ssl` = '{$data['config_ssl']}', `domain` = '{$data['config_domain']}'");

		return $this->mdb()->insert_id();
	}

	public function editStore($store_id, $data)
	{
		$this->mdb()->query('UPDATE ' . DB_PREFIX . "store SET name = '{$data['config_name']}', `url` = '{$data['config_url']}', `ssl` = '{$data['config_ssl']}', `domain` = '{$data['config_domain']}' WHERE store_id = '{$store_id}'");
	}

	public function deleteStore($store_id)
	{
		$this->mdb()->query('DELETE FROM ' . DB_PREFIX . "store WHERE store_id = '{$store_id}'");
	}

	public function getStore($store_id)
	{
		return $this->sdb()->fetch_row('SELECT DISTINCT * FROM ' . DB_PREFIX . "store WHERE store_id = '{$store_id}'");
	}

	public function getStores($data = array())
	{
		$stores = $this->mem_get('GET-STORES');
		if (empty($stores))
		{
			$stores = $this->sdb()->fetch_all('SELECT * FROM ' . DB_PREFIX . 'store');
			$this->mem_set('GET-STORES', $stores);
		}

		return $stores;
	}

	public function getTotalStores()
	{
		$row = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "store");

		return $row['total'];
	}

	public function getTotalStoresByLayoutId($layout_id)
	{
		$row = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "setting WHERE `key` = 'config_layout_id' AND `value` = '{$layout_id}' AND store_id != '0'");

		return $row['total'];
	}

	public function getTotalStoresByLanguage($language)
	{
		$row = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "setting WHERE `key` = 'config_language' AND `value` = '{$language}' AND store_id != '0'");

		return $row['total'];
	}

	public function getTotalStoresByCurrency($currency)
	{
		$row = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "setting WHERE `key` = 'config_currency' AND `value` = '{$currency}' AND store_id != '0'");

		return $row['total'];
	}

	public function getTotalStoresByCountryId($country_id)
	{
		$row = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "setting WHERE `key` = 'config_country_id' AND `value` = '{$country_id}' AND store_id != '0'");

		return $row['total'];
	}

	public function getTotalStoresByZoneId($zone_id)
	{
		$row = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "setting WHERE `key` = 'config_zone_id' AND `value` = '{$zone_id}' AND store_id != '0'");

		return $row['total'];
	}

	public function getTotalStoresByCustomerGroupId($customer_group_id)
	{
		$row = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "setting WHERE `key` = 'config_customer_group_id' AND `value` = '{$customer_group_id}' AND store_id != '0'");

		return $row['total'];
	}

	public function getTotalStoresByInformationId($information_id)
	{
		$account_row  = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "setting WHERE `key` = 'config_account_id' AND `value` = '{$information_id}' AND store_id != '0'");
		$checkout_row = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "setting WHERE `key` = 'config_checkout_id' AND `value` = '{$information_id}' AND store_id != '0'");

		return ($account_row['total'] + $checkout_row['total']);
	}

	public function getTotalStoresByOrderStatusId($order_status_id)
	{
		$row = $this->sdb()->fetch_row('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "setting WHERE `key` = 'config_order_status_id' AND `value` = '{$order_status_id}' AND store_id != '0'");

		return $row['total'];
	}
}
?>