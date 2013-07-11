<?php

class Status_refresh extends MX_Controller
{
	/**
	 * Called via AJAX
	 */
	public function index()
	{
		// Force refresh
		die($this->view());
	}

	public function view()
	{
		// Load realm objects
		$realms = $this->realms->getRealms();

        $realmData = array();

        foreach($realms as $realm){

            $values = array(
                "gm" => $realm->getOnline("gm"),
                "horde" => $realm->getOnline("horde"),
                "alliance" => $realm->getOnline("alliance"),
            );

            foreach($values as $key => $value){
                $values[$key] = intval($value);
            }

            $realmData[] = array(
                "online" => (bool) $realm->isOnline(),
                "name" => $realm->getName(),
                "gm" => $values['gm'],
                "horde" => $values['horde'],
                "alliance" => $values['alliance'],
            );
        }

		// Prepare data
		$data = array(
					"module" => "sidebox_status", 
					"realms" => $realmData,
					"realmlist" => $this->config->item('realmlist')
				);

		// Load the template file and format
		$out = $this->template->loadPage("status.tpl", $data);

		return $out;
	}
}