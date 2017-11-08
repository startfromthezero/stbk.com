<?php
class ControllerOtherError extends Controller
{
	public function index()
	{
		return $this->view('template/other/error.tpl', $vrs);
	}
}
?>