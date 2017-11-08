<?php
class ControllerCommonLogout extends Controller
{
	public function index()
	{
		$this->user->logout();
		wcore_utils::set_cookie('token', null);
		$this->registry->redirect($this->url->link('common/login', '', true));
	}
}
?>