<?php
//require_once("Connect2.1/API/qqConnectAPI.php");
/**
 * 流量卡相关界面
 *
 * 此模块主要用于流量卡数据处理
 */
class ControllerWebMain extends modules_public
{
	/**
	 * @param Registry $registry 注册对象
	 */
	public function __construct($registry)
	{
		parent::__construct($registry);
	}

	public static $news_types = array('PHP', 'JAVA', 'C++', '.NET', 'AJAX', 'LINUX');

	public function index(){
		$this->registry->model('web/home/news');
		$page  = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('config_admin_limit');
		$data = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'news_status'=>1,
			'is_show'=>1
		);

		$news = $this->model_web_home_news->getNewsList($data);
		$vrs['news'] = $news;
		$vrs['news_types'] = self::$news_types;

		/**
		 * 模板处理
		 */
		$vrs['page_header'] = $this->registry->exectrl('web/common/header');
		$vrs['page_footer'] = $this->mem_ctrl('web/common/footer');

		return $this->view("template/web/home.tpl", $vrs);
	}

	public function about(){

		/**
		 * 模板处理
		 */
		$vrs['page_header'] = $this->registry->exectrl('web/common/header');
		$vrs['page_footer'] = $this->mem_ctrl('web/common/footer');

		return $this->view("template/web/about.tpl", $vrs);
	}

	public function article(){
		$this->registry->model('web/home/news');
		$page  = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('config_admin_limit');
		$type_id = $this->request->get['type_id'];
		$data  = array(
			'start'       => ($page - 1) * $limit,
			'limit'       => $limit,
			'news_status' => 1,
			'is_show'     => 1
		);
		if(!empty($type_id)){
			$data['type_id'] = $type_id;
		}

		$news        = $this->model_web_home_news->getNewsList($data);
		$vrs['news'] = $news;
		$vrs['news_types'] = self::$news_types;

		/**
		 * 模板处理
		 */
		$vrs['page_header'] = $this->registry->exectrl('web/common/header');
		$vrs['page_footer'] = $this->mem_ctrl('web/common/footer');

		return $this->view("template/web/article.tpl", $vrs);
	}

	public function resource(){


		/**
		 * 模板处理
		 */
		$vrs['page_header'] = $this->registry->exectrl('web/common/header');
		$vrs['page_footer'] = $this->mem_ctrl('web/common/footer');

		return $this->view("template/web/resource.tpl", $vrs);
	}

	public function timeline(){
		$this->registry->model('web/home/timer');
		$times = $this->model_web_home_timer->getTimerList();
		$res = array();
		foreach ($times as $key =>$val){
			$res[date('Y', strtotime($val['time_added']))][date('m', strtotime($val['time_added']))][] = array(
				'time'=> date('m-d H:i', strtotime($val['time_added'])),
				'content'=> $val['content']
			);
		}
		$vrs['times'] = $res;

		/**
		 * 模板处理
		 */
		$vrs['page_header'] = $this->registry->exectrl('web/common/header');
		$vrs['page_footer'] = $this->mem_ctrl('web/common/footer');

		return $this->view("template/web/timeline.tpl", $vrs);
	}

	public function detail()
	{
		$vrs['news_types'] = self::$news_types;

		/**
		 * 模板处理
		 */
		$vrs['page_header'] = $this->registry->exectrl('web/common/header');
		$vrs['page_footer'] = $this->mem_ctrl('web/common/footer');

		return $this->view("template/web/detail.tpl", $vrs);
	}
}
?>