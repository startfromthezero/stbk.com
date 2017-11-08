<?php
class ControllerUserBlacklist extends Controller
{
	public function index()
	{
		return $this->view('template/user/blacklist_list.tpl', $vrs);
	}
}
?>