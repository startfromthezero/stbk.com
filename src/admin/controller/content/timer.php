<?php
class ControllerContentTimer extends Controller
{
	public function index()
	{
		return $this->view('template/content/timer_list.tpl', $vrs);
	}
	
	public function getList(){
		$this->registry->model('content/timer');
		$page        = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		$limit       = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('config_admin_limit');
		$data        = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
		);

		$timer = $this->model_content_timer->gettimerList($data);
		exit(json_encode($timer));
	}

	public function oper()
	{
		$vrs            = array();
		$timer_id        = $this->request->get_var('timer_id', 'i');
		if (isset($timer_id) && !empty($timer_id))
		{
			$this->registry->model('content/timer');
			$vrs = $this->model_content_timer->get($timer_id);
		}
		
		return $this->view('template/content/timer_add.tpl', $vrs);
	}

	public function addTimer()
	{
		$this->registry->model('content/timer');
		$data = $this->request->post;
		unset($data['file']);
		$out['r'] =-1;
		if($this->model_content_timer->insertOrUpdate($data)){
			$out['r'] = 0;
		}
		exit(json_encode($out));
	}

	public function del(){
		$this->registry->model('content/timer');
		$timer_id = $this->request->get_var('timer_id', 'i');
		$res = $this->model_content_timer->deleteTimer($timer_id);
		$out['r'] = -1;
		if($res){
			$out['r'] = 0;
		}
		exit(json_encode($out));
	}
}
?>