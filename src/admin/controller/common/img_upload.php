<?php
class ControllerCommonImgUpload extends Controller
{
	public function index()
	{
		$OUT = array(
			"r"   => -1,
			"msg" => "抱歉，上传图片失败"
		);

		//校验业务，ticket等由wcore_utils::createTicket生成
		$source       = $this->request->get_var('source');
		$ticket       = $this->request->get_var('ticket');
		$ticket_time  = $this->request->get_var('ticket_time', 'i');
		$_hash        = $_POST['_hash'];
		if (!empty($_hash))
		{
			$_hashArr = wcore_utils::strToMap(wcore_utils::urlsafe_base64decode($_hash));
			if (count($_hashArr) == 3)
			{
				$source      = $_hashArr["source"];
				$ticket      = $_hashArr["ticket"];
				$ticket_time = $_hashArr["ticket_time"];
			}
		}

		if (!wcore_utils::checkTicket($source, $ticket, $ticket_time, false, 21600))
		{
			$OUT = array(
				"r"   => -996,
				"msg" => "抱歉，使用权限校验失败或过期"
			);
			exit(json_encode($OUT));
		}
		//exit(json_encode($_FILES['file']));
		//图片数组 array('0'=>array('name'=>'01d13fd5652f384451da4b83.jpg','tmp_name'=>'/tmp/phppeCh4f'))
		$imgDataBundle = $_FILES['file'];
		if ($imgDataBundle['type'] != "image/png" && $imgDataBundle['type'] != "image/jpg" && $imgDataBundle['type'] != "image/jpeg" && $imgDataBundle['type'] != "image/gif")
		{
			$OUT = array(
				"r"   => -997,
				"msg" => "抱歉，图片文件格式错误"
			);
			exit(json_encode($OUT));
		}

		set_time_limit(0);
		$uin = $this->user->isLogged();

		//校验后缀名:S
		//能够上传的类型
		$uptypes = array(
			'jpg',
			'jpeg',
			'png',
			'gif',
			'bmp',
			'x-png',
			'JPG',
			'JPEG',
			'PNG',
			'GIF',
			'BMP',
			'X-PNG'
		);
		$pinfo = pathinfo($imgDataBundle['name']);
		$ftype = $pinfo['extension'];
		if (!in_array($ftype, $uptypes))
		{
			$OUT = array(
				"r"   => -998,
				"msg" => "抱歉，图片文件格式错误"
			);
			exit(json_encode($OUT));
		}
//		for ($i = 0, $l = count($imgDataBundle); $i < $l; $i++)
//		{
//			//解决上传图片为空的bug
//			if (empty($imgDataBundle[$i]['name']))
//			{
//				continue;
//			}
//			$pinfo = pathinfo($imgDataBundle[$i]['name']); //文件路径信息
//			$ftype = $pinfo['extension']; //旧文件后缀名
//			if (!in_array($ftype, $uptypes))
//			{
//				$OUT = array(
//					"r"   => -998,
//					"msg" => "抱歉，图片文件格式错误"
//				);
//				exit(json_encode($OUT));
//			}
//		}
		//校验后缀名:E
		$result          = array();
		$UPLOAD_FILE_DIR = "/public/img/upload/";
		if (isset($imgDataBundle) && isset($imgDataBundle['name']) && ($imgDataBundle['name'] != ""))
		{
			//$newfile = $UPLOAD_FILE_DIR.$uin.'_'.$imgDataBundle[$i]['name'];  //文件名字为uin_filename 比如 615628103_01d13fd5652f384451da4b83.jpg
			$newfile = $UPLOAD_FILE_DIR . $uin . '_' . time(0) . '_' . $imgDataBundle['name'];  //文件名字为uin_filename 比如 615628103_01d13fd5652f384451da4b83.jpg

			move_uploaded_file($imgDataBundle["tmp_name"], 'E:/stbk/src/admin'. iconv('utf-8', 'gbk', $newfile));
			//iconv('gbk', 'utf-8', $imgDataBundle['name']);
			//$imageInfo = getimagesize($newfile);
//			if (count($width_height) == 2)
//			{
//				$limit_width  = $width_height[0];
//				$limit_height = $width_height[1];
//
//				if ($imageInfo[0] != $limit_width || $imageInfo[1] != $limit_height)
//				{
//					$OUT = array(
//						"r"   => -999,
//						"msg" => "抱歉，图片文件尺寸错误，必须为" . $limit_width . "*" . $limit_height
//					);
//					exit(json_encode($OUT));
//				}
//			}
//			else if (count($size_ratio) == 2)
//			{
//				$imageSizeRatio = (double)($imageInfo[0] / $imageInfo[1]);
//				$limitSizeRatio = (double)($size_ratio[0] / $size_ratio[1]);
//				if ($limitSizeRatio != $imageSizeRatio)
//				{
//					$OUT = array(
//						"r"   => -999,
//						"msg" => "抱歉，图片文件尺寸比例错误，必须为" . $size_ratio[0] . ":" . $size_ratio[1]
//					);
//					exit(json_encode($OUT));
//				}
//			}

			$result[$imgDataBundle['name']] = $newfile;
		}
//		for ($i = 0; $i < count($imgDataBundle); $i++)
//		{
//			if (isset($imgDataBundle[$i]) && isset($imgDataBundle[$i]['name']) && ($imgDataBundle[$i]['name'] != ""))
//			{
//				//$newfile = $UPLOAD_FILE_DIR.$uin.'_'.$imgDataBundle[$i]['name'];  //文件名字为uin_filename 比如 615628103_01d13fd5652f384451da4b83.jpg
//				$newfile = $UPLOAD_FILE_DIR . $uin . '_' . time(0) . '_' . $imgDataBundle[$i]['name'];  //文件名字为uin_filename 比如 615628103_01d13fd5652f384451da4b83.jpg
//
//				move_uploaded_file($imgDataBundle[$i]["tmp_name"], $newfile);
//
//				$imageInfo = getimagesize($newfile);
//				if (count($width_height) == 2)
//				{
//					$limit_width  = $width_height[0];
//					$limit_height = $width_height[1];
//
//					if ($imageInfo[0] != $limit_width || $imageInfo[1] != $limit_height)
//					{
//						$OUT = array(
//							"r"   => -999,
//							"msg" => "抱歉，图片文件尺寸错误，必须为" . $limit_width . "*" . $limit_height
//						);
//						exit(json_encode($OUT));
//					}
//				}
//				else if (count($size_ratio) == 2)
//				{
//					$imageSizeRatio = (double)($imageInfo[0] / $imageInfo[1]);
//					$limitSizeRatio = (double)($size_ratio[0] / $size_ratio[1]);
//					if ($limitSizeRatio != $imageSizeRatio)
//					{
//						$OUT = array(
//							"r"   => -999,
//							"msg" => "抱歉，图片文件尺寸比例错误，必须为" . $size_ratio[0] . ":" . $size_ratio[1]
//						);
//						exit(json_encode($OUT));
//					}
//				}
//
//				$result[$imgDataBundle[$i]['name']] = $newfile;
//			}
//		}

		if (!empty($result))
		{
			$OUT = array('r'=>0,'url'=> $result[$_FILES['file']['name']]);
		}
		exit(json_encode($OUT));
	}
}
?>