<?php
class ControllerContentMessage extends Controller
{
	public function index()
	{
		return $this->view('template/content/message_list.tpl', $vrs);
	}

	public function reply(){
		return $this->view('template/content/message_reply.tpl', $vrs);
	}

}
?>