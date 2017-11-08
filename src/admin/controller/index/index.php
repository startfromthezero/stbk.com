<?php
class ControllerIndexIndex extends Controller
{
	public function index()
	{
		return $this->view('template/index.tpl', $vrs);
	}
}
?>