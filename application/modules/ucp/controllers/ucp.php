<?php

class Ucp extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		//Make sure that we are logged in
		$this->user->userArea();

		$this->load->config('links');
	}
	
	public function index()
	{
		$this->template->setTitle("User panel");

		$cache = $this->cache->get("profile_characters_".$this->user->getId());

		if($cache !== false)
		{
			$characters = $cache;
		}
		else
		{
			$characters_data = array(
							"characters" => $this->realms->getTotalCharacters(),
							"realms" => $this->realms->getRealms(),
							"url" => $this->template->page_url,
							"realmObj" => $this->realms
						);

			$characters = $this->template->loadPage("ucp_characters.tpl", $characters_data);

			$this->cache->save("profile_characters_".$this->user->getId(), $characters, 60*60);
		}

		$data = array(
			"username" => $this->user->getNickname(),
			"expansion" => $this->realms->getEmulator()->getExpansionName($this->external_account_model->getExpansion()),
			"vp" => $this->internal_user_model->getVp(),
			"dp" => $this->internal_user_model->getDp(),
			"url" => $this->template->page_url,
			"location" => $this->internal_user_model->getLocation(),
			"rank" => $this->user->getUserGroup($this->external_account_model->getId()),
			"register_date" => $this->user->getRegisterDate(),
			"status" => $this->user->getAccountStatus(),
			"characters" => $characters,
			"avatar" => $this->user->getAvatar($this->user->getId()),
			"id" => $this->user->getId(),
			"is_gm" => $this->user->isGm(),
			"is_owner" => $this->user->isOwner(),
			"is_admin" => $this->user->isAdmin(),
			"config" => array(
				"vote" => $this->config->item('ucp_vote'),
				"donate" => $this->config->item('ucp_donate'),
				"store" => $this->config->item('ucp_store'),
				"settings" => $this->config->item('ucp_settings'),
				"expansion" => $this->config->item('ucp_expansion'),
				"teleport" => $this->config->item('ucp_teleport'),
				"gm" => $this->config->item('ucp_gm'),
				"admin" => $this->config->item('ucp_admin')
			)
		);

		$this->template->view($this->template->loadPage("page.tpl", array(
			"module" => "default", 
			"headline" => "User panel", 
			"content" => $this->template->loadPage("ucp.tpl", $data)
		)), "modules/ucp/css/ucp.css");
	}
    
    /**
     * Ajax: Changes the active character
     */
    public function changeCharacter(){
        // Login required and ajax request
        if(!$this->input->is_ajax_request() || !$this->user->isOnline()){
            redirect("ucp");
        }
        
        $newGUID = $this->input->post("index");
        $newRealm = $this->input->post("realm");
        $xsToken = $this->input->post("xstoken");
        $error = "";
        
        if($this->realms->realmExists($newRealm)){
            $realmCharDb = $this->realms->getRealm($newRealm)->getCharacters();
            
            if($realmCharDb->characterBelongsToAccount($newGUID, $this->user->getId())){
                $this->user->setActiveChar($newGUID, $newRealm);
            }
        }
        else{
            $error = "Realm nicht gefunden";
        }

        $content = $this->template->getUserplate();       
        
        // Damit wir später .find benutzen können muss der Content in einem Oberelement liegen.
        $content = "<div>".$content."</div>";
        
        $array = array(
            "content" => $content,
            "guid" => $newGUID,
            "error" => $error,
        );

        $this->outputJSON($array);    
    }
}
