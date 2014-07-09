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

            /*
             * Check if access to this realm is allowed for the current user
             */
            $accessAllowed = true;
            $requiredAccess = $realm->getRequiredAccess();

            if($requiredAccess > 0){
                // Not logged in? Then false.
                if($this->user->getOnline() == false){
                    $accessAllowed = false;
                }
                // Logged in but GM? Then true
                else if(hasPermission("view", "gm")){
                    $accessAllowed = true;
                }
                else {
                    $accessAllowed = false;
                }
            }

            $realmData[$realm->getId()] = array(
                "css" => $cssClass,
                "online" => (bool) $realm->isOnline(),
                "name" => $realm->getName(),
                "accessAllowed" => $accessAllowed,
                "onlinePlayers" => $realm->getOnline(),
                "onlinePercentage" => $realm->getPercentage(),
                "gm" => $realm->getOnline("gm"),
                "horde" => $realm->getOnline("horde"),
                "alliance" => $realm->getOnline("alliance"),
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