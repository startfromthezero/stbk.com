<?php
/**
 * 此模块主要用于所有简单共用的页面展示
 * $Id: common.php 78 2016-04-26 08:22:48Z zhangsl $
 */
class ControllerWebCommon extends Controller
{
	/**
	 * 页头
	 *
	 * @return string html
	 */
	public function header()
	{
		$vrs['base']        = HTTP_STORE;
		$vrs['title']       = $this->document->getTitle();
		$vrs['description'] = $this->document->getDescription();
		$vrs['keywords']    = $this->document->getKeywords();
		$vrs['links']       = $this->document->getLinks();
		$vrs['styles']      = $this->document->getStyles();
		$vrs['scripts']     = $this->document->getScripts();
		$vrs['icon']        = ($this->config->get('config_icon') && file_exists(DIR_SITE . '/' . IMAGES_PATH . $this->config->get('config_icon'))) ? $this->registry->execdn($this->config->get('config_icon'), IMAGES_PATH) : '';

		$callback = array(
			&$this,
			'checkcdn'
		);
		$pattern  = '/ (src|href)=(\'|")([\d\w_\/\.\-]+\.(jpg|png|gif|css|js)\??[\d\w_\.\-\;\&\=]*)(\'|")/i';
		$content  = preg_replace_callback($pattern, $callback, $this->view('template/web/header.tpl', $vrs));

		return $content;
	}

	/**
	 * 页尾
	 *
	 * @return string html
	 */
	public function footer()
	{
		$vrs['base'] = HTTP_STORE;

		return $this->view('template/web/footer.tpl', $vrs);
	}

	/**
	 * 检测是否需要CDN处理
	 *
	 * @param array $m
	 * @return string 组合好的CDN地址
	 */
	protected function checkcdn($m)
	{
		return " {$m[1]}={$m[2]}" . $this->registry->execdn($m[3], '', ".{$m[4]}") . $m[5];
	}
}
?>