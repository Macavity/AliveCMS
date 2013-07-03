<?php

class Info extends MX_Controller implements Sidebox
{
	public function view()
	{
		$data = array(
					"module" => "sidebox_info",
					"url" => $this->template->page_url,
					"forum" => file_exists('application/modules/forum/'),
					"currentIp" => $_SERVER['REMOTE_ADDR'],
					"lastIp" => $this->user->getLastIp(),
					"vp" => $this->user->getVp(),
					"dp" => $this->user->getDp(),
					"expansion" => $this->realms->getEmulator()->getExpansionName($this->user->getExpansion())
				);
					
		$page = $this->template->loadPage("info.tpl", $data);

		return $page;
	}
}
