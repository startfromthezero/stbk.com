<?php
class User extends modules_mem
{
	/**
	 * 用户编号
	 *
	 * @var int
	 */
	private $user_id = 0;

	/**
	 * 用户组编号
	 *
	 * @var int
	 */
	private $group_id = 0;

	/**
	 * 所属机构编号
	 *
	 * @var int
	 */
	private $org_id = 0;

	/**
	 * 用户名
	 *
	 * @var string
	 */
	private $username = '';

	/**
	 * 拥有的权限列表
	 *
	 * @var array|mixed
	 */
	private $permission = array();

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var wcore_session
	 */
	protected $session;

	public function __construct($registry)
	{
		parent::__construct();
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id']))
		{
			$this->permission = $this->_get_permission($this->session->data['user_group_id']);
			if (empty($this->permission))
			{
				$this->logout();
			}

			$this->user_id  = $this->session->data['user_id'];
			$this->username = $this->session->data['username'];
			$this->group_id = $this->session->data['user_group_id'];
			$this->org_id   = $this->session->data['org_id'];
		}
	}

	public function login($username, $password)
	{
		$salt = empty($this->session->data['login_salt']) ? mt_rand(1, 999) : $this->session->data['login_salt'];
		$sql  = 'SELECT * FROM ' . DB_PREFIX . "user WHERE username = '{$username}' AND MD5(CONCAT(password, '{$salt}')) = '{$password}' AND status = '1'";
		unset($this->session->data['login_salt']);
		$user_info = $this->sdb()->fetch_row($sql);
		if (!empty($user_info))
		{
			$this->user_id                        = $user_info['user_id'];
			$this->username                       = $user_info['username'];
			$this->session->data['user_id']       = $user_info['user_id'];
			$this->session->data['username']      = $user_info['username'];
			$this->session->data['user_group_id'] = $user_info['user_group_id'];
			$this->session->data['org_id']        = $user_info['org_id'];

			wcore_utils::set_cookie('language', $user_info['lang'], 365);
			wcore_utils::set_cookie('usergroup', $user_info['user_group_id'], 365);
			$this->permission = $this->_get_permission($this->session->data['user_group_id']);
			$this->mdb()->query('UPDATE ' . DB_PREFIX . "user SET ip = '{$this->request->server['REMOTE_ADDR']}', error_count = 0, date_last = NOW() WHERE user_id = '{$this->session->data['user_id']}'");

			return true;
		}

		return false;
	}

	private function _get_permission($user_group_id)
	{
		$mkey             = "USER-GROUP{$user_group_id}-PERMISSION";
		$group_permission = $this->mem_get($mkey);

		if (empty($group_permission))
		{
			$user_group_row = $this->sdb()->fetch_row("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '{$user_group_id}'");
			$permissions    = unserialize($user_group_row['permission']);
			if (is_array($permissions))
			{
				foreach ($permissions as $key => $value)
				{
					$group_permission[$key] = array_flip($value);
				}
			}
			$this->mem_set($mkey, $group_permission);
		}

		return $group_permission;
	}

	public function logout()
	{
		$this->user_id       = '';
		$this->username      = '';
		$this->session->data = array();
		session_destroy();
	}

	public function securityCheck($username, $reload = false)
	{
		$res = $this->mem_get($username);
		if (empty($res) || $reload)
		{
			$res = $this->sdb()->fetch_row('SELECT * FROM ' . DB_PREFIX . "user WHERE LOWER(username) = '{$username}' AND status = '1'");
		}

		if (!empty($res))
		{
			$res['error_count'] = intval($res['error_count']) + 1;
		}
		$this->mem_set($username, $res);

		return $res;
	}

	public function editUser($data, $user_id = 0)
	{
		$user_id = $user_id ? $user_id : $this->user_id;

		return $this->mdb()->update(DB_PREFIX . 'user', $data, "user_id = '{$user_id}'");
	}

	public function hasPermission($key, $value)
	{
		return isset($this->permission[$key][$value]) ? true : false;
	}

	public function isLogged()
	{
		return $this->user_id;
	}

	public function getId()
	{
		return $this->user_id;
	}

	public function getOrgId()
	{
		return $this->org_id;
	}

	public function getGroupId()
	{
		return $this->group_id;
	}

	public function getUserName()
	{
		return $this->username;
	}
}
?>