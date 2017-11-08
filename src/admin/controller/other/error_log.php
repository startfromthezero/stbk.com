<?php
class ControllerOtherErrorLog extends Controller
{
	private $error = array();

	public function index()
	{
		$vrs['log']   = '';
		$vrs['clear'] = $this->url->link('other/error_log/clear');
		$file         = DIR_ROOT . '/system/logs/' . $this->config->get('config_error_filename');
		if (file_exists($file))
		{
			$vrs['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		}
		return $this->view('template/other/error_log.tpl', $vrs);
	}

	public function clear()
	{
		$file   = DIR_ROOT . '/system/logs/' . $this->config->get('config_error_filename');
		$handle = fopen($file, 'w+');
		fclose($handle);
		$out['r'] = 0;
		exit(json_encode($out));
	}
}
?>