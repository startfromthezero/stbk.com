<?php
class ControllerSystemSetting extends Controller
{
	public function index()
	{
		return $this->view('template/system/setting.tpl', $vrs);
	}
}
?>