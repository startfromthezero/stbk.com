<?php
function StartApp()
{
	header('Access-Control-Allow-Origin:*');
	$OUT = array(
		"r"   => -1,
		"msg" => "抱歉，上传图片失败"
	);
	//校验业务，ticket等由CommUtils::createTicket生成
	$source       = CommUtils::getInputValue('source');
	$ticket       = CommUtils::getInputValue('ticket');
	$ticket_time  = CommUtils::getInputValueNumber('ticket_time');
	$_hash        = CommUtils::getInputValue('_hash', "");
	$width_height = explode("_", CommUtils::getInputValue('width_height', ""));
	$size_ratio   = explode("_", CommUtils::getInputValue('size_ratio', ""));

	if (!empty($_hash))
	{
		$_hashArr = CommUtils::strToMap(CommUtils::urlsafe_base64decode($_hash));
		if (count($_hashArr) == 3)
		{
			$source      = $_hashArr["source"];
			$ticket      = $_hashArr["ticket"];
			$ticket_time = $_hashArr["ticket_time"];
		}
	}

	if (!CommUtils::checkTicket($source, $ticket, $ticket_time, false, 21600))
	{
		$OUT = array(
			"r"   => -996,
			"msg" => "抱歉，使用权限校验失败或过期"
		);
		$this->setOutput($OUT);

		return;
	}

	//图片数组 array('0'=>array('name'=>'01d13fd5652f384451da4b83.jpg','tmp_name'=>'/tmp/phppeCh4f'))
	$imgDataBundle = array($_FILES['upload_pic_input']);
	if ($imgDataBundle[0]['type'] != "image/png" && $imgDataBundle[0]['type'] != "image/jpg" && $imgDataBundle[0]['type'] != "image/jpeg" && $imgDataBundle[0]['type'] != "image/gif")
	{
		$OUT = array(
			"r"   => -997,
			"msg" => "抱歉，图片文件格式错误"
		);
		$this->setOutput($OUT);

		return;
	}

	set_time_limit(0);
	$uin = 615628109;
	$gid = 0;

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
	for ($i = 0, $l = count($imgDataBundle); $i < $l; $i++)
	{
		//解决上传图片为空的bug
		if (empty($imgDataBundle[$i]['name']))
		{
			continue;
		}
		$pinfo = pathinfo($imgDataBundle[$i]['name']); //文件路径信息
		$ftype = $pinfo['extension']; //旧文件后缀名
		if (!in_array($ftype, $uptypes))
		{
			$OUT = array(
				"r"   => -998,
				"msg" => "抱歉，图片文件格式错误"
			);
			$this->setOutput($OUT);

			return;
		}
	}
	//校验后缀名:E
	$result          = array();
	$UPLOAD_FILE_DIR = "/usr/local/userweb/htdocs/upload/";
	for ($i = 0; $i < count($imgDataBundle); $i++)
	{
		if (isset($imgDataBundle[$i]) && isset($imgDataBundle[$i]['name']) && ($imgDataBundle[$i]['name'] != ""))
		{
			//$newfile = $UPLOAD_FILE_DIR.$uin.'_'.$imgDataBundle[$i]['name'];  //文件名字为uin_filename 比如 615628103_01d13fd5652f384451da4b83.jpg
			$newfile = $UPLOAD_FILE_DIR . $uin . '_' . time(0) . '_' . $imgDataBundle[$i]['name'];  //文件名字为uin_filename 比如 615628103_01d13fd5652f384451da4b83.jpg

			move_uploaded_file($imgDataBundle[$i]["tmp_name"], $newfile);

			$imageInfo = getimagesize($newfile);
			if (count($width_height) == 2)
			{
				$limit_width  = $width_height[0];
				$limit_height = $width_height[1];

				if ($imageInfo[0] != $limit_width || $imageInfo[1] != $limit_height)
				{
					$OUT = array(
						"r"   => -999,
						"msg" => "抱歉，图片文件尺寸错误，必须为" . $limit_width . "*" . $limit_height
					);
					$this->setOutput($OUT);

					return;
				}
			}
			else if (count($size_ratio) == 2)
			{
				$imageSizeRatio = (double)($imageInfo[0] / $imageInfo[1]);
				$limitSizeRatio = (double)($size_ratio[0] / $size_ratio[1]);
				if ($limitSizeRatio != $imageSizeRatio)
				{
					$OUT = array(
						"r"   => -999,
						"msg" => "抱歉，图片文件尺寸比例错误，必须为" . $size_ratio[0] . ":" . $size_ratio[1]
					);
					$this->setOutput($OUT);

					return;
				}
			}

			$recVal = upload_tfs_pic($uin, $newfile, $gid, 0);
			if (!is_numeric($recVal))
			{
				if (file_exists($newfile))
				{
					unlink($newfile);    //上传到TFS成功后则删除本地的图片文件
				}
			}
			$result[$imgDataBundle[$i]['name']] = $recVal;
		}
	}

	if (!empty($result))
	{
		$OUT['r']   = 0;
		$OUT['url'] = $result[$_FILES['upload_pic_input']['name']];
	}
	$this->setOutput($OUT);
}
?>