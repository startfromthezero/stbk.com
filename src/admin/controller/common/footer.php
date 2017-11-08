<?php
class ControllerCommonFooter extends Controller
{
	public function index()
	{
		$this->registry->language('common/footer');
		$vrs                = $this->language->data;
		$vrs['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);

		return $this->view('template/foot.tpl', $vrs);
	}
}
?>