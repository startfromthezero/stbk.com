<?php
class ControllerCommonLogin extends Controller
{
	private $error = array();

	public function loginCheck(){
		$this->registry->language('common/login');
		$username = strtolower(trim($this->request->get_var('username', 's', 'p')));
		$user_res = $this->user->securityCheck($username);

		if (empty($user_res))
		{
			$this->session->data['captcha'] =true;
			$error = array("r"=>-1,'msg'=> $this->language->get('error_login'));
			exit(json_encode($error));
		}
		$login_count_max    = intval($this->config->get('config_login_count_max')); //最大登录次数
		$login_locked_hours = floatval($this->config->get('config_login_locked_hours')); //锁定多长个小时
		$over_time          = strtotime($user_res['date_last']) + (3600 * $login_locked_hours); //限制时间
		$surplus_hour       = ($over_time - time()) / 3600; //剩余小时
		/**
		 * 判断锁定时间是否过期与错误最大次数
		 */
		$locked_tip = sprintf($this->language->get('error_locked'), $surplus_hour);
		if ($over_time > time() && $user_res['error_count'] > $login_count_max)
		{
			$error = array("r"   => -1, 'msg' => $locked_tip);
			exit(json_encode($error));
		}

		/**
		 * 当登录次数大于设定的次数时，说明限制的时间已过期，可以重新登录
		 */
		if ($user_res['error_count'] > $login_count_max || $surplus_hour <= 0)
		{
			$this->user->editUser(array(
				'error_count' => 0,
				'date_last'   => 'dbf|NOW()'
			), $user_res['org_id']);
			$this->user->securityCheck($username, true);
			$user_res['error_count'] = 1;
		}

		/**
		 * 密码输错一次将需要用户输入验证码
		 */
		if ($user_res['error_count'] > 1 || isset($this->session->data['captcha'])) //验证码判断
		{
			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != strtoupper($this->request->get_var('captcha'))))
			{
				$this->user->editUser(array(
					'error_count' => 'dbf|error_count + 1',
					'date_last'   => 'dbf|NOW()'
				), $user_res['org_id']);
				$error = array("r"   => -1, 'msg' => $this->language->get('error_captcha'));
				exit(json_encode($error));
			}
		}

		/**
		 * 判断用户登录密码是否正确
		 */
		if (!$this->user->login($username, $this->request->get_var('password', 's', 'p')))
		{
			$this->user->editUser(array(
				'error_count' => 'dbf|error_count + 1',
				'date_last'   => 'dbf|NOW()'
			), $user_res['org_id']);
			$this->session->data['captcha'] = true;
			$error = array("r"   => -1, 'msg' => sprintf($this->language->get('error_pwd'), $login_count_max - $user_res['error_count']));
			exit(json_encode($error));
		}else{
			$vtoken = security_token(SITE_MD5_KEY);
			$this->session->data['token'] = $vtoken;
			wcore_utils::set_cookie('token', $this->session->data['token']);
			$this->mem_del($username);
			unset($this->session->data['captcha']);
			if (isset($this->request->post['redirect']))
			{
				$redirect = $this->request->post['redirect'];
			}
			else
			{
				$redirect = $this->url->link('common/home');
			}
			exit(json_encode(array('r' => 0 ,'url'=> $redirect)));
		}
	}

	public function index()
	{
		$this->registry->language('common/login');
		$this->document->setTitle($this->language->get('heading_title'));

		$vtoken = security_token(SITE_MD5_KEY);
		$stoken = isset($this->session->data['token']) ? $this->session->data['token'] : '';
		$ctoken = isset($this->request->cookie['token']) ? $this->request->cookie['token'] : '';
		if ($this->user->isLogged() && $ctoken == $stoken && $ctoken == $vtoken)
		{
			$this->registry->redirect($this->url->link('common/home'));
		}

		$vrs['heading_title']  = $this->language->get('heading_title');
		$vrs['text_login']     = $this->language->get('text_login');
		$vrs['text_forgotten'] = $this->language->get('text_forgotten');
		$vrs['entry_username'] = $this->language->get('entry_username');
		$vrs['entry_captcha']  = $this->language->get('entry_captcha');
		$vrs['entry_password'] = $this->language->get('entry_password');
		$vrs['button_login']   = $this->language->get('button_login');

		if ($stoken && ($ctoken != $stoken || $ctoken != $vtoken))
		{
			$this->error['warning'] = $this->language->get('error_token');
		}

		$this->session->data['login_salt'] = $vrs['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);//登录安全码

		$vrs['action']        = $this->url->link('common/login', '', true);
		$vrs['forgotten']     = $this->url->link('common/forgotten', '', true);
		$vrs['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$vrs['error_captcha'] = isset($this->error['captcha']) ? $this->error['captcha'] : '';
		$vrs['password']      = '';
		$vrs['username']      = isset($this->request->post['username']) ? $this->request->post['username'] : '';

		$vrs['success'] = '';
		if (isset($this->session->data['success']))
		{
			$vrs['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		if (isset($this->request->get['route']))
		{
			$route = $this->request->get['route'];
			unset($this->request->get['route']);

			if (isset($this->request->cookie['token']))
			{
				unset($this->request->cookie['token']);
			}

			$url = '';
			if ($this->request->get)
			{
				$url .= http_build_query($this->request->get);
			}
			$vrs['redirect'] = $this->url->link($route, $url, true);
		}
		else
		{
			$vrs['redirect'] = '';
		}

		/**
		 * 模板处理
		 */
		$vrs['page_header'] = $this->registry->exectrl('common/page_header');
		$vrs['page_footer'] = $this->registry->exectrl('common/page_footer');

		return $this->view('template/login.tpl', $vrs);
	}

	/**
	 * 获取校验码
	 */
	public function captcha()
	{
		$captcha            = new wcore_verify();
		$captcha->font_size = 20;
		//$captcha->bgcolor               = '#FFFFFF';
		$this->session->data['captcha'] = strtoupper($captcha->generate_words());
		$captcha->draw(110, 40);
	}

}
?>