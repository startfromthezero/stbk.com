<?php
class ControllerCommonHeader extends Controller
{
	public function index()
	{
		$this->registry->language('common/head');
		$vrs                = $this->language->data;
		return $this->view('template/head.tpl', $vrs);
	}
}
?>