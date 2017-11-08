<?php
class ControllerErrorNotFound extends Controller
{
	public function index()
	{
		$this->registry->language('error/not_found');
		$this->document->setTitle($this->language->get('heading_title'));
		$vrs['button_back']   = $this->language->get('button_back');
		$vrs['heading_title'] = $this->language->get('heading_title');
		$vrs['text_error']    = isset($this->session->data['text_error']) ? $this->session->data['text_error'] : $this->language->get('text_error');
		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

		/**
		 * 模板处理
		 */
		$vrs['page_footer'] = $this->registry->exectrl('app/common/footer');
		$vrs['page_header'] = $this->registry->exectrl('app/common/header');

		return $this->view('template/error/not_found.tpl', $vrs);
	}
}
?>