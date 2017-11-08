<?php
class ControllerContentRecycle extends Controller
{
	public function index()
	{
		return $this->view('template/content/recycle_list.tpl', $vrs);
	}
}
?>