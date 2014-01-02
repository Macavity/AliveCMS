<?php

class Status extends MX_Controller
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

                $realmData[] = array(
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
		}

		return $out;
	}
}
