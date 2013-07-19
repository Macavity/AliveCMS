<?php

class Ucp extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->user->userArea();

		$this->load->config('links');
	}

	public function index()
	{
		requirePermission("view");

		$this->template->setTitle(lang("user_panel", "ucp"));

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
                "image_path" => $this->template->image_path,    /* @alive */
				"realmObj" => $this->realms,
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
			"groups" => $this->acl_model->getGroupsByUser($this->user->getId()),
			"register_date" => $this->user->getRegisterDate(),
			"status" => $this->user->getAccountStatus(),
			"characters" => $characters,
			"avatar" => $this->user->getAvatar($this->user->getId()),
			"id" => $this->user->getId(),

			"config" => array(
				"vote" => $this->config->item('ucp_vote'),
				"donate" => $this->config->item('ucp_donate'),
				"store" => $this->config->item('ucp_store'),
				"settings" => $this->config->item('ucp_settings'),
				"expansion" => $this->config->item('ucp_expansion'),
				"teleport" => $this->config->item('ucp_teleport'),
				"admin" => $this->config->item('ucp_admin'),
				"gm" => $this->config->item('ucp_gm')
			),

            /**
             * @alive
             */
            "image_path" => $this->template->image_path,
		);

		$this->template->view($this->template->loadPage("page.tpl", array(
			"module" => "default", 
			"headline" => lang("user_panel", "ucp"), 
			"content" => $this->template->loadPage("ucp.tpl", $data)
		)), "modules/ucp/css/ucp.css");
	}

    /**
     * Changes the active character
     * used via ajax
     * @alive
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
                $this->user->setActiveCharacter($newGUID, $newRealm);
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

        $this->outputJson($array);
    }

}
