<?php
class ControllerContentNews extends Controller
{
	public static $news_types = array('PHP','JAVA','C++','.NET','AJAX','LINUX');
	public function index()
	{
		return $this->view('template/content/news_list.tpl', $vrs);
	}

	public function getList()
	{
		$this->registry->model('content/news');
		$page  = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('config_admin_limit');
		$news_status = isset($this->request->get['news_status']) ? (int)$this->request->get['news_status'] : '';
		$data  = array(
			'start'      => ($page - 1) * $limit,
			'limit'      => $limit,
		);
		if($news_status !== ''){
			$data['news_status'] = $news_status;
		}
		$news = $this->model_content_news->getNewsList($data);
		foreach ($news as $k => $v)
		{
			$v['news_status'] = $v['news_status'] == 0 ? '待审核' : '审核通过';
			$v['news_look']   = $v['news_look'] == 0 ? '开放浏览' : '会员浏览';
			$v['is_show']     = $v['is_show'] == 0 ? '' : 'checked';
			$news[$k] = $v;
		}
		exit(json_encode($news));
	}

	public function oper()
	{
		$vrs      = array();
		$news_id = $this->request->get_var('news_id', 'i');
		$var['type_id'] = -1;
		$vrs['img_url'] = "/public/img/cover_default.jpg";
		if (isset($news_id) && !empty($news_id))
		{
			$this->registry->model('content/news');
			$vrs             = $this->model_content_news->get($news_id);
		}
		$vrs['news_types'] = self::$news_types;
		return $this->view('template/content/news_add.tpl', $vrs);
	}

	public function addNews()
	{
		$this->registry->model('content/news');
		$data = $this->request->post;
		if (isset($data['news_id']) && !empty($data['news_id'])){
		 	$this->model_content_news->update($data);
		}else{
			$this->model_content_news->insert($data);
		}
		exit(json_encode($data));
	}
}
?>