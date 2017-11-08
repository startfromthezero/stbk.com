<?php
class ControllerUserIndex extends Controller
{
	public function index()
	{
		return $this->view('template/user/links_list.tpl', $vrs);
	}

	public function info(){
		return $this->view('template/user/user_info.tpl', $vrs);
	}

	public function oper(){
		$vrs = array();
		$links_id = $this->request->get_var('links_id', 'i');
		if (isset($links_id) && !empty($links_id))
		{
			$this->registry->model('links/links');
			$vrs             = $this->model_links_links->get($links_id);
			$site            = explode(',', $vrs['show_site']);
			$vrs['homePage'] = $site[0];
			$vrs['subPage']  = $site[1];
		}
		return $this->view('template/links/links_add.tpl', $vrs);
	}

	public function addLinks(){
		$this->registry->model('links/links');
		$data = $this->request->post;
		if(isset($data['links_id'])&& !empty($data['links_id'])){
			$this->model_links_links->update($data);
		}else{
			$this->model_links_links->insert($data);
		}
		exit(json_encode($data));
	}

	public function delLinks(){
		$this->registry->model('links/links');
		$links_id = $this->request->get_var('links_id', 'i');
		$data = array(
			'links_id' => $links_id,
			'status' => 0,
		);
		$this->model_links_links->update($data);
		$out= array('r'=>0,'msg'=>'删除成功');
		exit(json_encode($out));
	}

	public function getList()
	{
		$this->registry->model('links/links');
		$page  = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('config_admin_limit');
		$data  = array(
			'start'      => ($page - 1) * $limit,
			'limit'      => $limit,
			'links_name' => $this->request->get_var('links_name', 's')
		);
		$links = $this->model_links_links->getLinksList($data);
		foreach($links as $k=>$v){
			$site = explode(',', $v['show_site']);
			if($site[0] == 1 && $site[1] == 1){
				$v['show_site'] = '首页，子页';
			}elseif($site[0] == 1 && $site[1] == 0){
				$v['show_site'] = '首页';
			}elseif($site[0] == 0 && $site[1] == 1){
				$v['show_site'] = '子页';
			}else{
				$v['show_site'] = '暂不展示';
			}
			$links[$k] = $v;
		}
		$total = $this->model_links_links->getLinksList($data, true);
		$out   = $links;
		exit(json_encode($out));
	}

}
?>