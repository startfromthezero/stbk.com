<?php
class Affiliate extends modules_mem
{
	private $affiliate_id;

	private $firstname;

	private $lastname;

	private $email;

	private $telephone;

	private $fax;

	private $code;

	public function __construct($registry)
	{
		$this->config  = $registry->get('config');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['affiliate_id']))
		{
			$affiliate_row = $this->sdb()->fetch_row('SELECT * FROM ' . DB_PREFIX . "affiliate WHERE affiliate_id = '{$this->session->data['affiliate_id']}' AND status = '1'");
			if (!empty($affiliate_row))
			{
				$this->affiliate_id = $affiliate_row['affiliate_id'];
				$this->firstname    = $affiliate_row['firstname'];
				$this->lastname     = $affiliate_row['lastname'];
				$this->email        = $affiliate_row['email'];
				$this->telephone    = $affiliate_row['telephone'];
				$this->fax          = $affiliate_row['fax'];
				$this->code         = $affiliate_row['code'];
				$this->mdb()->query('UPDATE ' . DB_PREFIX . "affiliate SET ip = '{$this->request->server['REMOTE_ADDR']}' WHERE affiliate_id = '{$this->session->data['affiliate_id']}'");
			}
			else
			{
				$this->logout();
			}
		}
	}

	public function login($email, $password)
	{
		$salt = empty($this->session->data['login_salt']) ? mt_rand(1, 999) : $this->session->data['login_salt'];
		$sql  = 'SELECT * FROM ' . DB_PREFIX . "affiliate WHERE email = '{$email}' AND status = '1' AND MD5(CONCAT(password, '{$salt}')) = '{$password}' AND approved = '1'";
		unset($this->session->data['login_salt']);
		$affiliate_row = $this->sdb()->fetch_row($sql);
		if (!empty($affiliate_row))
		{
			$this->affiliate_id = $affiliate_row['affiliate_id'];
			$this->firstname    = $affiliate_row['firstname'];
			$this->lastname     = $affiliate_row['lastname'];
			$this->email        = $affiliate_row['email'];
			$this->telephone    = $affiliate_row['telephone'];
			$this->fax          = $affiliate_row['fax'];
			$this->code         = $affiliate_row['code'];
			$this->session->data['affiliate_id'] = $affiliate_row['affiliate_id'];

			return true;
		}

		return false;
	}

	public function logout()
	{
		unset($this->session->data['affiliate_id']);
		$this->affiliate_id = '';
		$this->firstname    = '';
		$this->lastname     = '';
		$this->email        = '';
		$this->telephone    = '';
		$this->fax          = '';
	}

	public function isLogged()
	{
		return $this->affiliate_id;
	}

	public function getId()
	{
		return $this->affiliate_id;
	}

	public function getFirstName()
	{
		return $this->firstname;
	}

	public function getLastName()
	{
		return $this->lastname;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getTelephone()
	{
		return $this->telephone;
	}

	public function getFax()
	{
		return $this->fax;
	}

	public function getCode()
	{
		return $this->code;
	}
}
?>