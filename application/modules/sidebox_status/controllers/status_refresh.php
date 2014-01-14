<?php

class Status_refresh extends MY_Controller
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
            /** @var Realm $realm */
            $values = array(
                "gm" => $realm->getOnline("gm"),
                "horde" => $realm->getOnline("horde"),
                "alliance" => $realm->getOnline("alliance"),
            );

            foreach($values as $key => $value){
                $values[$key] = intval($value);
            }

            switch($realm->getId()){
                case 1:
                    $cssClass = "color-ex2";
                    break;
                case 2:
                    $cssClass = "color-ex3";
                    break;
                default:
                    $cssClass = '';
            }

            $realmData[$realm->getId()] = array(
                "css" => $cssClass,
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