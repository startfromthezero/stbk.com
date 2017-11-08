<?php
class ControllerToolLog extends Controller
{
	public function index()
	{
		return $this->view('template/tool/log_list.tpl', $vrs);
	}
}
?>