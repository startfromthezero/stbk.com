<?php
class Template
{
	public $data = array();

	public function &fetch($filename)
	{
		$file = DIR_SITE . "/view/{$filename}";
		if (file_exists($file))
		{
			extract($this->data);
			ob_start();
			include($file);
			$content = ob_get_contents();
			ob_end_clean();

			return $content;
		}

		trigger_error('Error: Could not load template ' . $file . '!');
		exit();
	}
}
?>