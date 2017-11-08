<?php
class ControllerUserRole extends Controller
{
	public function index()
	{
		return $this->view('template/user/role_list.tpl', $vrs);
	}
}
?>