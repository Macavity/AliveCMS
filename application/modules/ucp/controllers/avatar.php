<?php

class Avatar extends MX_Controller
{
	public function index()
	{
		// Prepare data
		$data = array(
				'avatar' => $this->user->getAvatar(),
				'email' => $this->user->getEmail()
			);

		// Load the avatar page
		$content = $this->template->loadPage("avatar.tpl", $data);

		$title = "<span style='cursor:pointer;' onClick='window.location=\"".$this->template->page_url."ucp\"'>UCP</span> &rarr; Change avatar";

		// Put it in a content box
		$this->template->box($title, $content, true, "modules/ucp/css/avatar.css");
	}	
}