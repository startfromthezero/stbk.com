<?php
class ControllerContentImg extends Controller
{
	public function index()
	{
		return $this->view('template/content/img_list.tpl', $vrs);
	}

	public function getImg(){
//		$page  = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
//		$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('config_admin_limit');
		$img_arr = array();
		$path = '/public/img';
		function scanFile($path)
		{
			$newpath = 'E:/stbk/src/admin'. $path;
			global $result;
			$files = scandir($newpath);
			foreach ($files as $file)
			{
				if ($file != '.' && $file != '..')
				{
					if (is_dir($newpath . '/' . $file))
					{
						scanFile($path . '/' . $file);
					}
					else
					{
						//$encode = mb_detect_encoding($file, array("ASCII", "UTF-8","GB2312","GBK","BIG5"));
						$img_url = $path . '/' . iconv('GB2312', 'UTF-8', $file);
						$img_name = pathinfo($img_url);
						$result[] = array(
							'imgSrc'   => $img_url,
							'imgTitle' => $img_name['filename'],
						);
					}
				}
			}

			return $result;
		};
		$img_arr = scanFile($path);
		//$data = array_slice($img_arr,$page* $limit, $limit);
		exit(json_encode($img_arr));
	}
}
?>