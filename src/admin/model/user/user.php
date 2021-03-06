<?php
class ModelUserUser extends Model
{
	private $_opt = 'cc_user';

	public function addUser($data)
	{
		$s2p = $this->salt2pwd($data['password']);
		$this->mdb()->query("INSERT INTO {$this->_opt} SET
		username = '{$data['username']}', salt = '{$s2p['salt']}', password = '{$s2p['pwd']}',sex = '{$data['sex']}',
		email = '{$data['email']}', tel = '{$data['tel']}',lang = 'cn', user_group_id = '{$data['user_group_id']}', status = '{$data['status']}', date_added = NOW()");
		$this->createTables($this->mdb()->insert_id());
	}

	/**
	 * 更新用户状态
	 *
	 * @param array $data
	 * @return bool|int
	 */
	public function update($data = array())
	{
		$data['date_last'] = 'dbf|NOW()';

		return $this->mdb()->update($this->_opt, $data, "user_id = '{$data['user_id']}'");
	}

	public function editUser($user_id, $data)
	{
		$sql = "UPDATE {$this->_opt} SET username = '{$data['username']}',
			 email = '{$data['email']}', tel = '{$data['tel']}', sex = '{$data['sex']}',
			status = '{$data['status']}', error_count = 0";

		if(isset($data['user_group_id']))
		{
			$sql .= ", user_group_id = '{$data['user_group_id']}'";
		}
		$sql .= " WHERE user_id = '{$user_id}'";
		$this->mdb()->query($sql);

		if ($data['password'])
		{
			$this->editPassword($user_id, $data['password']);
		}

		$this->mem_del(trim($data['username']));
	}

	public function checkPassword($user_id, $password)
	{
		$s2p = $this->salt2pwd($password);

		return $this->sdb()->fetch_all("SELECT * FROM {$this->_opt} WHERE salt = '{$s2p['salt']}' AND password = '{$s2p['pwd']}'AND user_id = '{$user_id}'");
	}

	public function editPassword($user_id, $password)
	{
		$s2p = $this->salt2pwd($password);
		$this->mdb()->query("UPDATE {$this->_opt} SET salt = '{$s2p['salt']}', password = '{$s2p['pwd']}', code = '' WHERE user_id = '{$user_id}'");
	}

	public function editCode($email, $code)
	{
		$this->mdb()->query("UPDATE {$this->_opt} SET code = '{$code}' WHERE email = '{$email}'");
	}

	public function deleteUser($user_id)
	{
		$this->mdb()->query("DELETE FROM {$this->_opt} WHERE user_id = '{$user_id}'");
	}

	public function getUser($user_id)
	{
		return $this->sdb()->fetch_row("SELECT * FROM {$this->_opt} WHERE user_id = '{$user_id}'");
	}

	public function getUserByUsername($username)
	{
		return $this->sdb()->fetch_row("SELECT * FROM {$this->_opt} WHERE username = '{$username}'");
	}

	public function getUserByCode($code)
	{
		return $this->sdb()->fetch_row("SELECT * FROM {$this->_opt} WHERE code = '{$code}' AND code != ''");
	}

	public function getUsers($data = array())
	{
		$sort_data = array(
			'username',
			'user_group_id',
			'org_id',
			'status',
			'date_added'
		);

		$sql = "SELECT * FROM {$this->_opt} ";
		$sql .= (isset($data['sort']) && in_array($data['sort'], $sort_data)) ? " ORDER BY {$data['sort']}" : ' ORDER BY username';
		$sql .= (isset($data['order']) && ($data['order'] == 'DESC')) ? ' DESC' : ' ASC';
		if (isset($data['start']) || isset($data['limit']))
		{
			$data['start'] = ($data['start'] < 0) ? 0 : (int)$data['start'];
			$data['limit'] = ($data['limit'] < 1) ? 20 : (int)$data['limit'];
			$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		}

		return $this->sdb()->fetch_all($sql);
	}

	public function getTotalUsers()
	{
		$row = $this->sdb()->fetch_row("SELECT COUNT(*) AS total FROM {$this->_opt}");

		return $row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id)
	{
		$row = $this->sdb()->fetch_row("SELECT COUNT(*) AS total FROM {$this->_opt} WHERE user_group_id = '{$user_group_id}'");

		return $row['total'];
	}

	public function getTotalUsersByEmail($email)
	{
		$row = $this->sdb()->fetch_row("SELECT COUNT(*) AS total FROM {$this->_opt} WHERE email = '{$email}'");

		return $row['total'];
	}

	public function getTotalUsersByOrg($org_id)
	{
		$row = $this->sdb()->fetch_row("SELECT COUNT(*) AS total FROM {$this->_opt} WHERE org_id = '{$org_id}'");

		return $row['total'];
	}

	public function getOrgs()
	{
		$sql = 'SELECT org_id, name FROM `' . DB_PREFIX . 'org`';
		$sql .= $this->user->getGroupId() <= 1 ? '' : " WHERE org_id = '{$this->user->getOrgId()}'";

		return $this->sdb()->fetch_pairs($sql);
	}

	public function getGroups()
	{
		$sql = 'SELECT user_group_id, name FROM `' . DB_PREFIX . 'user_group`';

		return $this->sdb()->fetch_pairs($sql);
	}

	public function getOrgIdByUserId($user_id)
	{
		$sql = "SELECT user_id,org_id FROM {$this->_opt} WHERE user_id = '{$user_id}'";

		return $this->sdb()->fetch_pairs($sql);
	}
}
?>