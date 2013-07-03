<?php

class Info_login extends MX_Controller implements Sidebox
{
	public function view()
	{
		if($this->user->isOnline())
		{
			$data = array(
						"module" => "sidebox_info_login",
						"url" => $this->template->page_url,
						"forum" => file_exists('application/modules/forum/'),
						"currentIp" => $_SERVER['REMOTE_ADDR'],
						"lastIp" => $this->user->getLastIp(),
						"vp" => $this->user->getVp(),
						"dp" => $this->user->getDp(),
						"expansion" => $this->realms->getEmulator()->getExpansionName($this->user->getExpansion())
					);
						
			$page = $this->template->loadPage("info.tpl", $data);
		}
		else
		{
			$this->load->helper('form');

			$data = array(
						"module" => "sidebox_info_login",
						"url" => $this->template->page_url,
					);
						
			$page = $this->template->loadPage("login.tpl", $data);
		}

		return $page;
	}
}
