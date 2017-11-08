<?php
class ControllerCommonPageHeader extends Controller
{
	public function index()
	{
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
			$vrs['home']              = 'common/home';
			$vrs['ota']               = 'setting/ota';
			$vrs['backup']            = 'tool/backup';
			$vrs['oper_mem']          = 'tool/memcache';
			$vrs['nation']            = 'localisation/nation';
			$vrs['error_log']         = 'tool/error_log';
			$vrs['currency']          = 'localisation/currency';
			$vrs['language']          = 'localisation/language';
			$vrs['logout']            = 'common/logout';
			$vrs['profile']           = 'common/profile';
			$vrs['module']            = 'extension/module';
			$vrs['payment']           = 'extension/payment';
			$vrs['setting']           = 'setting/store';
			$vrs['user']              = 'user/user';
			$vrs['user_org']          = 'user/org';
			$vrs['user_group']        = 'user/user_permission';
			$vrs['logged']            = sprintf($this->language->get('text_logged'), $this->user->getUserName());
			$vrs['gprs_pack']         = 'gprs/pack';
			$vrs['gprs_card']         = 'gprs/card';
			$vrs['gprs_report']       = 'gprs/report';
			$vrs['gprs_month_report'] = 'gprs/month_report';
			$vrs['gprs_batch']        = 'gprs/batch';
			$vrs['gprs_alert']        = 'gprs/alert';
			$vrs['gprs_abnormal']     = 'gprs/abnormal';
			$vrs['gprs_czk']          = 'gprs/czk';
			$vrs['gprs_paylog']       = 'gprs/paylog';
			$vrs['gprs_pay_report']   = 'gprs/pay_report';
			$vrs['gprs_stats']        = 'gprs/stats';
			$vrs['gprs_pay_pack']     = 'gprs/pay_pack';
			$vrs['gprs_halt']         = 'gprs/halt';
			$vrs['gprs_sell2pay']     = 'gprs/sell2pay';
			$vrs['gprs_card_used']    = 'gprs/card_used';
			$vrs['gprs_unicom_stat']  = 'gprs/unicom_stat';

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
			'gprs'    => array(
				'gprs_card',
				'gprs_batch',
				'gprs_pack',
				'gprs_alert',
				'gprs_czk',
			),
			'stats'   => array(
				'gprs_abnormal',
				'gprs_stats',
				'gprs_halt',
				'gprs_sell2pay',
				'gprs_card_used',
				'gprs_unicom_stat'
			),
			'finance' => array(
				'gprs_report',
				'gprs_month_report',
				'gprs_paylog',
				'gprs_pay_report',
				'gprs_pay_pack',
			),
			'users'   => array(
				'user',
				'user_org',
				'user_group',
			),
			'system'  => array(
				'payment',
				'language',
				'currency',
				'nation',
				'setting',
				'backup',
				'error_log',
				'oper_mem'
			),
		);

		return $this->view('template/header.tpl', $vrs);
	}
}
?>