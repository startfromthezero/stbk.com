<?php
class ControllerCommonProfile extends Controller
{
	private $error = array();
	public function index()
	{
		$this->registry->language('user/user');
		$this->registry->model('user/user');
		$this->document->setTitle($this->language->get('text_profile'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_user_user->editPassword($this->user->getId(), $this->request->post['password']);
			$this->session->data['success'] = $this->language->get('text_pwd_success');

			$this->registry->redirect($this->url->link('common/home'));
		}

		return $this->getForm();
	}
	private function getForm()
	{
		$vrs = $this->language->data;
		$vrs['error_warning']  = isset($this->error['warning']) ? $this->error['warning'] : '';
		$vrs['error_password'] = isset($this->error['password']) ? $this->error['password'] : '';
		$vrs['error_confirm']  = isset($this->error['confirm']) ? $this->error['confirm'] : '';
		$vrs['error_oldpwd']      = isset($this->error['oldpwd']) ? $this->error['oldpwd'] : '';

		/**
		 * 导航栏组合
		 */
		$vrs['breadcrumbs']   = array();
		$vrs['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$vrs['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_profile'),
			'href'      => $this->url->link('common/profile'),
			'separator' => ' :: '
		);

		$orgs   = $this->model_user_user->getOrgs();
		$groups   = $this->model_user_user->getGroups();
		$vrs['cancel'] = $this->url->link('common/home');
		$vrs['action'] = $this->url->link('common/profile');

		$user_info = $this->model_user_user->getUser($this->user->getId());
		$vrs['username'] = $user_info['username'];
		$vrs['org_name'] = $orgs[$user_info['org_id']];
		$vrs['group_name'] = $groups[$user_info['user_group_id']];

		/**
		 * 模板处理
		 */
		$vrs['page_header'] = $this->registry->exectrl('common/page_header');
		$vrs['page_footer'] = $this->registry->exectrl('common/page_footer');

		return $this->view('template/profile.tpl', $vrs);
	}

	private function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'common/profile'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->registry->model('user/user');

		if(empty($this->request->post['oldpwd']))
		{
			$this->error['oldpwd'] = $this->language->get('error_oldpwd_empty');
		}
		elseif($this->model_user_user->checkPassword($this->user->getId(),$this->request->post['oldpwd']))
		{
			if(empty($this->request->post['password']))
			{
				$this->error['password'] = $this->language->get('error_newpwd_empty');
			}
			elseif ($this->request->post['password'] != $this->request->post['oldpwd'])
			{
				if (mb_strlen($this->request->post['password']) < 6)
				{
					$this->error['password'] = $this->language->get('error_password');
				}
				if ($this->request->post['password'] != $this->request->post['confirm'])
				{
					$this->error['confirm'] = $this->language->get('error_confirm');
				}
			}
			else
			{
				$this->error['password'] = $this->language->get('error_same_pwd');
			}
		}
		else
		{
			$this->error['oldpwd'] = $this->language->get('error_oldpwd');
		}

		return !$this->error;
	}
}
?>