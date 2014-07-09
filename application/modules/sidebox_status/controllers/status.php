<?php

class Status extends MY_Controller
{
	public function view()
	{
		// Perform ajax call to refresh if expired
		if($this->cache->hasExpired("online_*", "/online_([0-9]*)\.cache$/")
		&& $this->cache->hasExpired("isOnline_*", "/isOnline_([0-9]*)\.cache$/"))
		{

            // Prepare data
			$data = array(
						"module" => "sidebox_status",
						"image_path" => $this->template->image_path
					);

			// Load the template file and format
			$out = $this->template->loadPage("ajax.tpl", $data);
		}
		else
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
		}

		return $out;
	}
}
