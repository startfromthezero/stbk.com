<?php
require_once(DIR_ROOT . '/system/PhpRbac/src/PhpRbac/Rbac.php');
class ControllerCommonHome extends Controller
{
	public function flush()
	{
		$this->mem->flush();
		$this->registry->redirect($this->url->link('common/home'));
	}

	public function index()
	{
		$rbac = new \PhpRbac\Rbac();
		//news_index,links_index,systemdata_index,user_user_permission,tool_error_log,

		//$rbac->reset(true); //重置所有权限、角色和作业

		/**
		添加权限
		$perm_descriptions = array(
		     'home',
		     'tool_error_log',
		);
		$rbac->Permissions->addPath('/home/tool_error_log', $perm_descriptions);
		**/

		//$perm_id = $rbac->Permissions->returnId($title); //通过title返回权限ID
		//$rbac->Permissions->edit($perm_id, $new_title, $new_descriptions);//修改权限Title和Description
		//$rbac->Permissions->remove($perm_id);//仅仅删除单个权限
		//$rbac->Permissions->remove($perm_id, true);//删除该权限及所有子权限
		//$rbac->Permissions->unassign($role_title, $perm_title);
		//$rbac->Permissions->unassignRoles($perm_id);//取消分配与权限相关的所有权限/角色分配

		/**
		添加角色
		 $role_descriptions = array(
		     'supadmin',
		     'smalladmin',
		 );
		 $rbac->Roles->addPath('/supadmin/smalladmin', $role_descriptions);
		**/
		//$role_id = $rbac->Roles->returnId($title); //通过title返回角色ID
		//$rbac->Roles->unassignPermissions($role_id) //取消分配给角色的所有权限
		//$rbac->Roles->edit($role_id, $new_title, $new_descriptions);//修改角色Title和Description
		//$rbac->Roles->remove($role_id); //仅仅删除单个角色
		//$rbac->Roles->remove($perm_id, true);//删除该角色及所有子角色
		//$rbac->Roles->unassignPermissions($role_id);//取消分配给角色的所有权限

		//$rbac->assign($role_id, $perm_id);//创建权限/角色关联
		//$rbac->Users->assign($role_id, $userId);//创建用户/角色关联
		// $rbac->Permissions->unassign($role_title, $perm_title);//取消分配单个权限/角色分配
		// $rbac->Roles->unassign($role_title, $perm_title);//取消分配单个权限/角色分配

		/*******验证用户访问********/
		//$rbac->Users->hasRole($role_id, $userId);//确保用户有一个角色
		//$rbac->check($perm_title, $userId);//检查用户是否具有权限
		//$rbac->enforce($role_title, $userId);//强制执行用户权限


		$vrs['title'] = $this->document->getTitle();
		$vrs['route'] = isset($this->request->get['route']) ? $this->request->get['route'] : 'common/home';

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
			$vrs['base'] = 'https://' . DOMAIN_NAME . '/';
		}
		else
		{
			$vrs['base'] = 'http://' . DOMAIN_NAME . '/';
		}

		$vrs['description'] = $this->document->getDescription();
		$vrs['keywords']    = $this->document->getKeywords();
		$vrs['links']       = $this->document->getLinks();
		$vrs['styles']      = $this->document->getStyles();
		$vrs['scripts']     = $this->document->getScripts();
		$vrs['lang']        = $this->language->get('code');

		/**
		 * 系统菜单语言
		 */
		$this->registry->language('common/header');
		$lang_arr = array(
			'direction',
			'heading_title',
			'text_backup',
			'text_links',
			'text_oper_mem',
			'text_confirm',
			'text_no_checked',
			'text_currency',
			'text_documentation',
			'text_error_log',
			'text_extension',
			'text_feed',
			'text_front',
			'text_nation',
			'text_dashboard',
			'text_language',
			'text_localisation',
			'text_logout',
			'text_hecart',
			'text_payment',
			'text_ota',
			'text_setting',
			'text_system',
			'text_user',
			'text_user_org',
			'text_user_group',
			'text_users',
			'text_gprs',
			'text_gprs_card',
			'text_gprs_batch',
			'text_gprs_pack',
			'text_gprs_alert',
			'text_gprs_czk',
			'text_stats',
			'text_gprs_abnormal',
			'text_gprs_stats',
			'text_gprs_halt',
			'text_gprs_pay_pack',
			'text_finance',
			'text_gprs_report',
			'text_gprs_month_report',
			'text_gprs_paylog',
			'text_gprs_pay_report',
			'text_gprs_sell2pay',
			'text_gprs_card_used',
			'text_gprs_unicom_stat',
		);
		foreach ($lang_arr as $v)
		{
			$vrs[$v] = $this->language->get($v);
		}

		/**
		 * 系统菜单地址
		 */
		$stoken = isset($this->session->data['token']) ? $this->session->data['token'] : '';
		$ctoken = isset($this->request->cookie['token']) ? $this->request->cookie['token'] : '';
		if (!$this->user->isLogged() || $ctoken != $stoken || $ctoken != security_token(SITE_MD5_KEY))
		{
			$vrs['logged'] = '';
			$vrs['home']   = 'common/login';
		}
		else
		{
			$vrs['home']       = 'common/home';
			$vrs['home']       = 'common/index';
			$vrs['other']      = 'other/index';
			$vrs['links']      = 'links/links';
			$vrs['ota']        = 'setting/ota';
			$vrs['backup']     = 'tool/backup';
			$vrs['oper_mem']   = 'tool/memcache';
			$vrs['nation']     = 'localisation/nation';
			$vrs['error_log']  = 'tool/error_log';
			$vrs['currency']   = 'localisation/currency';
			$vrs['language']   = 'localisation/language';
			$vrs['logout']     = 'common/logout';
			$vrs['profile']    = 'common/profile';
			$vrs['module']     = 'extension/module';
			$vrs['payment']    = 'extension/payment';
			$vrs['setting']    = 'setting/store';
			$vrs['user']       = 'user/user';
			$vrs['user_org']   = 'user/org';
			$vrs['user_group'] = 'user/user_permission';
			$vrs['logged']            = sprintf($this->language->get('text_logged'), $this->user->getUserName());

			$vrs['store']  = HTTP_STORE;
			$vrs['stores'] = array();
			$this->registry->model('setting/store');
			$results = $this->model_setting_store->getStores();
			foreach ($results as $result)
			{
				$vrs['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
		}

		/**
		 * 系统菜单数组
		 */
		$vrs['menus'] = array(
			'news' => 'news_index',
			'links'   => 'links_index',
			'system' => 'systemdata_index',
			'users'   => array(
				'user',
				'user_group',
				'user_permission'
			),
		);

		return $this->view('template/home.tpl', $vrs);
	}

	public function getMenus(){
		$rbac = new \PhpRbac\Rbac();
		$userId = 1;
		$menus_end= array();
		$menus = array(
			array(
				"title"  => "后台首页",
				"icon"   => "icon-computer",
				"href"   => "index/index",
				"spread" => false
			),
			array(
				"title"  => "内容管理",
				"icon"   => "icon-text",
				"href"   => "",
				"spread" => false,
				"children" => array(
					array(
						"title"  => "文章列表",
						"icon"   => "&#xe705;",
						"href"   => "content/news",
						"spread" => false
					),
					array(
						"title"  => "资源管理",
						"icon"   => "&#xe857;",
						"href"   => "content/resource",
						"spread" => false
					),
					array(
						"title"  => "消息管理",
						"icon"   => "&#xe611;",
						"href"   => "content/message",
						"spread" => false
					),
					array(
						"title"  => "图片管理",
						"icon"   => "&#xe60d;",
						"href"   => "content/img",
						"spread" => false
					),
					array(
						"title"  => "时光轴管理",
						"icon"   => "&#xe60e;",
						"href"   => "content/timer",
						"spread" => false
					),
					array(
						"title"  => "回收站",
						"icon"   => "&#xe640;",
						"href"   => "content/recycle",
						"spread" => false
					),
				),
			),
			array(
				"title"  => "用户管理",
				"icon"   => "&#xe612;",
				"href"   => "",
				"spread" => false,
				"children" => array(
					array(
						"title"  => "用户列表",
						"icon"   => "&#xe613;",
						"href"   => "user/user",
						"spread" => false
					),
					array(
						"title"  => "权限管理",
						"icon"   => "&#xe735;",
						"href"   => "user/user_permission",
						"spread" => false
					),
					array(
						"title"  => "角色管理",
						"icon"   => "&#xe658;",
						"href"   => "user/role",
						"spread" => false
					),
					array(
						"title"  => "黑名单管理",
						"icon"   => "&#xe60f;",
						"href"   => "user/blacklist",
						"spread" => false
					)
				),
			),
			array(
				"title"  => "扩展管理",
				"icon"   => "&#xe631;",
				"href"   => "",
				"spread" => false,
				"children" => array(
					array(
						"title"  => "友情链接",
						"icon"   => "&#xe64c;",
						"href"   => "tool/links",
						"spread" => false
					),
					array(
						"title"  => "网站公告",
						"icon"   => "&#xe645;",
						"href"   => "tool/notice",
						"spread" => false
					),
					array(
						"title"  => "留言管理",
						"icon"   => "&#xe63a;",
						"href"   => "tool/leave_message",
						"spread" => false
					),
					array(
						"title"  => "更新日志",
						"icon"   => "&#xe6b2;",
						"href"   => "tool/log",
						"spread" => false
					)
				),
			),
			array(
				"title"  => "系统配置",
				"icon"   => "&#xe614;",
				"href"   => "",
				"spread" => false,
				"children" => array(
					array(
						"title"  => "系统基本参数",
						"icon"   => "&#xe631;",
						"href"   => "system/setting",
						"spread" => false
					),
				),
			),
			array(
				"title"    => "其他页面",
				"icon"     => "&#xe630;",
				"href"     => "",
				"spread"   => false,
				"children" => array(
					array(
						"title"  => "404页面",
						"icon"   => "&#xe61c;",
						"href"   => "other/error",
						"spread" => false
					),
					array(
						"title"  => "错误日志",
						"icon"   => "&#x1007;",
						"href"   => "other/error_log",
						"spread" => false
					)
				),
			),

		);
//		foreach($menus as $key => $val){
//			if(!$rbac->check(str_replace('/','_',$val['href']), $userId)){
//				unset($menus[$key]);
//			}
//		}

		exit(json_encode(array_merge($menus)));
	}

	/**
	 * 检查是否需要登录，如果需要登录，但没有登录，则强制登录
	 */
	public function login()
	{
		$stoken = isset($this->session->data['token']) ? $this->session->data['token'] : '';
		$ctoken = isset($this->request->cookie['token']) ? $this->request->cookie['token'] : '';
		if ($this->user->isLogged() && $stoken === $ctoken && $ctoken === security_token(SITE_MD5_KEY))
		{
			return true;
		}

		$route = '';
		if (isset($this->request->get['route']))
		{
			$part = explode('/', $this->request->get['route']);
			if (isset($part[0]))
			{
				$route .= $part[0];
			}
			if (isset($part[1]))
			{
				$route .= '/' . $part[1];
			}
		}

		/**
		 * 排除不需要登录可操作的地址
		 */
		$ignore        = array(
			'common/login',
			'common/reset',
			'common/logout',
			'common/forgotten',
			'error/not_found',
			'error/permission',
			'user/org/orgJson',
		);
		$config_ignore = array();
		if ($this->config->get('config_token_ignore'))
		{
			$config_ignore = unserialize($this->config->get('config_token_ignore'));
		}

		if (!in_array($route, array_merge($ignore, $config_ignore)))
		{
			exit($this->registry->exectrl('common/login'));
		}
	}

	/**
	 * 判断用户是否有查看权限
	 */
	public function permission()
	{
		if (isset($this->request->get['route']))
		{
			$route = '';
			$part  = explode('/', $this->request->get['route']);
			if (isset($part[0]))
			{
				$route .= $part[0];
			}
			if (isset($part[1]))
			{
				$route .= '/' . $part[1];
			}

			/**
			 * 排除以下操作路由不判断权限
			 */
			$ignore = array(
				'common/home',
				'common/login',
				'common/reset',
				'common/logout',
				'common/forgotten',
				'error/not_found',
				'error/permission'
			);

			/**
			 * 判断是否拥有访问权限
			 */
			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route))
			{
				exit($this->registry->exectrl('error/permission'));
			}

			$this->config->apermission = true; //访问权限
			$this->config->mpermission = $this->user->hasPermission('modify', $route); //检测是否有更改权限
		}
	}
}
?>