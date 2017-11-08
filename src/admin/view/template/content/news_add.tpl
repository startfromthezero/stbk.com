<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>文章添加--layui后台管理模板</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="/public/layui/css/layui.css" media="all" />
	<style>
		.img-cover{
			width:100%;
			height:auto;
			border:1px solid #ddd;
		}
	</style>
</head>
<?php
	 $ticketBlock = wcore_utils::createTicket("stbkcom");
	 $ticketBlock['source'] = "stbkcom";
     $_hash = wcore_utils::urlsafe_base64encode(wcore_utils::mapToStr($ticketBlock));
	 ?>
<body class="childrenBody">
	<form class="layui-form">
		<?php if(isset($news_id)){ ?>
		<input class="newsId" type="hidden" value="<?php echo $news_id; ?>" />
		<?php } ?>
		<div class="layui-form-item">
			<label class="layui-form-label">文章标题</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input newsName" lay-verify="required" placeholder="请输入文章标题" value="<?php echo $news_name; ?>">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">自定义属性</label>
				<div class="layui-input-block">
					<input type="checkbox" name="tuijian" class="tuijian" title="推荐" <?php if($news_commend == 1){ ?> checked <?php } ?>>
					<input type="checkbox" name="shenhe" class="newsStatus" title="审核" <?php if($news_status == 1){ ?> checked <?php } ?>>
					<input type="checkbox" name="show" class="isShow" title="展示" <?php if($is_show == 1){ ?>checked <?php } ?>>
				</div>
			</div>
			<div class="layui-inline">		
				<label class="layui-form-label">文章作者</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input newsAuthor" lay-verify="required" placeholder="请输入文章作者" value="<?php echo $news_author; ?>">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">文章类别</label>
				<div class="layui-input-inline">
					<select name="news_type" class="newstype" lay-filter="newstype">
						<option value="-1">请选择类别</option>
						<?php foreach($news_types as $key=>$val){ ?>
						<option value="<?php echo $key; ?>"<?php if($type_id == $key){ ?> selected="selected" <?php } ?>><?php echo $val; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">浏览权限</label>
				<div class="layui-input-inline">
					<select name="browseLook" class="newsLook" lay-filter="browseLook">
				        <option value="0" <?php if($news_look == 0){ ?> selected="selected" <?php } ?>>开放浏览</option>
				        <option value="1" <?php if($news_look == 1){ ?> selected="selected" <?php } ?>>会员浏览</option>
				    </select>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">关键字</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input newsKeyword" placeholder="请输入文章关键字" value="<?php echo $news_keyword; ?>">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">内容摘要</label>
			<div class="layui-input-block">
				<textarea placeholder="请输入内容摘要" class="layui-textarea newsSynopsis"><?php echo $news_synopsis; ?></textarea>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">文章内容</label>
			<div class="layui-input-block">
				<textarea class="layui-textarea layui-hide newsContent" name="content" lay-verify="content" id="news_content"><?php echo $news_content; ?></textarea>
			</div>
		</div>
		<div class="layui-form-item" style="position:relative;">
			<label class="layui-form-label">封面</label>
			<div class="layui-input-inline">
				<input name="articleCoverSrc" type="hidden" id="articleCoverSrc" value="<?php echo $img_url; ?>">
				<img id="articleCoverImg" class="img-cover" src="<?php echo $img_url; ?>" alt="封面">
			</div>
			<div class="layui-input-inline" style="position:absolute;bottom:0;">
				<div class="layui-box layui-upload-button">
					<button type="button" class="layui-btn layui-btn-danger" hash="<?php echo $_hash;?>" id="img-upload"><i class="layui-icon"></i>上传图片</button>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit="" lay-filter="addNews">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
	<script type="text/javascript" src="/public/layui/layui.js"></script>
	<script type="text/javascript" src="/public/js/content/newsAdd.js"></script>
</body>
</html>