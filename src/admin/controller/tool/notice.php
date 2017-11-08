<?php
class ControllerToolNotice extends Controller
{
	public function index()
	{
		return $this->view('template/tool/notic_list.tpl', $vrs);
	}
}
?>