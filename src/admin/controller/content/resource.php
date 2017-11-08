<?php
class ControllerContentResource extends Controller
{
	public function index()
	{
		return $this->view('template/content/resource_list.tpl', $vrs);
	}
}
?>