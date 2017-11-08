<?php
class ControllerToolLeaveMessage extends Controller
{
	public function index()
	{
		return $this->view('template/tool/leave_message_list.tpl', $vrs);
	}
}
?>