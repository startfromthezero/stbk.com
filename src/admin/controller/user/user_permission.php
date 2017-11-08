<?php
class ControllerUserUserPermission extends Controller
{
	private $error = array();

	public function getList()
	{
		$this->registry->model('user/user_group');
		$page  = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('config_admin_limit');
		$data  = array(
			'start'      => ($page - 1) * $limit,
			'limit'      => $limit,
		);
		$groups = $this->model_user_user_group->getUserGroups($data);
		exit(json_encode($groups));
	}

	public function oper(){
		return $this->view('template/user/user_group_form.tpl', $vrs);
	}

	public function index()
	{
		return $this->view('template/user/user_group_list.tpl', $vrs);
	}

	public function insert()
	{
		$this->registry->language('user/user_group');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->registry->model('user/user_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_user_user_group->addUserGroup($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			$url .= isset($this->request->get['sort']) ? '&sort=' . $this->request->get['sort'] : '';
			$url .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] : '';
			$url .= isset($this->request->get['page']) ? '&page=' . $this->request->get['page'] : '';
			$this->registry->redirect($this->url->link('user/user_permission', $url, true));
		}

		return $this->getForm();
	}

	public function update()
	{
		$this->registry->language('user/user_group');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->registry->model('user/user_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_user_user_group->editUserGroup($this->request->get['user_group_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			$url .= isset($this->request->get['sort']) ? '&sort=' . $this->request->get['sort'] : '';
			$url .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] : '';
			$url .= isset($this->request->get['page']) ? '&page=' . $this->request->get['page'] : '';
			$this->registry->redirect($this->url->link('user/user_permission', $url, true));
		}

		return $this->getForm();
	}

	public function delete()
	{
		$this->registry->language('user/user_group');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->registry->model('user/user_group');

		if (isset($this->request->post['selected']) && $this->validateDelete())
		{
			foreach ($this->request->post['selected'] as $user_group_id)
			{
				$this->model_user_user_group->deleteUserGroup($user_group_id);
			}

			$this->session->data['success'] = $this->language->get('text_delete');

			$url = '';
			$url .= isset($this->request->get['sort']) ? '&sort=' . $this->request->get['sort'] : '';
			$url .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] : '';
			$url .= isset($this->request->get['page']) ? '&page=' . $this->request->get['page'] : '';
			$this->registry->redirect($this->url->link('user/user_permission', $url, true));
		}

		return $this->getList();
	}

	private function getForm()
	{
		$vrs['heading_title']     = $this->language->get('heading_title');
		$vrs['text_select_all']   = $this->language->get('text_select_all');
		$vrs['text_unselect_all'] = $this->language->get('text_unselect_all');
		$vrs['entry_name']        = $this->language->get('entry_name');
		$vrs['entry_access']      = $this->language->get('entry_access');
		$vrs['entry_modify']      = $this->language->get('entry_modify');
		$vrs['button_save']       = $this->language->get('button_save');
		$vrs['button_cancel']     = $this->language->get('button_cancel');
		$vrs['error_warning']     = isset($this->error['warning']) ? $this->error['warning'] : '';
		$vrs['error_name']        = isset($this->error['name']) ? $this->error['name'] : '';

		$url = '';
		$url .= isset($this->request->get['sort']) ? '&sort=' . $this->request->get['sort'] : '';
		$url .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] : '';
		$url .= isset($this->request->get['page']) ? '&page=' . $this->request->get['page'] : '';

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
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('user/user_permission', $url, true),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['user_group_id']))
		{
			$vrs['action'] = $this->url->link('user/user_permission/insert', $url, true);
		}
		else
		{
			$vrs['action'] = $this->url->link('user/user_permission/update', 'user_group_id=' . $this->request->get['user_group_id'] . $url, true);
		}
		$vrs['cancel'] = $this->url->link('user/user_permission', $url, true);

		if (isset($this->request->get['user_group_id']) && $this->request->server['REQUEST_METHOD'] != 'POST')
		{
			$user_group_info = $this->model_user_user_group->getUserGroup($this->request->get['user_group_id']);
		}

		if (isset($this->request->post['name']))
		{
			$vrs['name'] = $this->request->post['name'];
		}
		elseif (!empty($user_group_info))
		{
			$vrs['name'] = $user_group_info['name'];
		}
		else
		{
			$vrs['name'] = '';
		}

		$ignore = array(
			'common/home',
			'common/startup',
			'common/login',
			'common/logout',
			'common/forgotten',
			'common/reset',
			'error/not_found',
			'error/permission',
			'common/page_footer',
			'common/page_header'
		);

		$this->registry->language('user/user_permissions');
		$vrs['permissions_data'] = $this->language->data;
		$vrs['permissions']      = array();
		$files                   = glob(DIR_SITE . '/controller/*/*.php');
		foreach ($files as $file)
		{
			$data       = explode('/', dirname($file));
			$permission = end($data) . '/' . basename($file, '.php');
			if (!in_array($permission, $ignore))
			{
				$vrs['permissions'][] = $permission;
			}
		}

		if (isset($this->request->post['permission']['access']))
		{
			$vrs['access'] = $this->request->post['permission']['access'];
		}
		elseif (isset($user_group_info['permission']['access']))
		{
			$vrs['access'] = $user_group_info['permission']['access'];
		}
		else
		{
			$vrs['access'] = array();
		}

		if (isset($this->request->post['permission']['modify']))
		{
			$vrs['modify'] = $this->request->post['permission']['modify'];
		}
		elseif (isset($user_group_info['permission']['modify']))
		{
			$vrs['modify'] = $user_group_info['permission']['modify'];
		}
		else
		{
			$vrs['modify'] = array();
		}

		/**
		 * 模板处理
		 */
		$vrs['page_header'] = $this->registry->exectrl('common/page_header');
		$vrs['page_footer'] = $this->registry->exectrl('common/page_footer');

		return $this->view('template/user/user_group_form.tpl', $vrs);
	}

	private function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'user/user_permission'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((mb_strlen(trim($this->request->post['name'])) < 2) || (mb_strlen($this->request->post['name']) > 64))
		{
			$this->error['name'] = $this->language->get('error_name');
		}

		$gorup_info = $this->model_user_user_group->getGroupByName($this->request->post['name']);
		if ($gorup_info && $gorup_info['user_group_id'] != $this->request->get_var('user_group_id'))
		{
			$this->error['warning'] = $this->language->get('error_exists');
		}

		return !$this->error;
	}

	private function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'user/user_permission'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->registry->model('user/user');
		foreach ($this->request->post['selected'] as $user_group_id)
		{
			$user_total = $this->model_user_user->getTotalUsersByGroupId($user_group_id);
			if ($user_total)
			{
				$this->error['warning'] = sprintf($this->language->get('error_user'), $user_total);
			}
		}

		return !$this->error;
	}
}
?>