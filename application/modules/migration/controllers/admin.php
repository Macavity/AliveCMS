<?php

/**
 *
 * @property Administrator $administrator
 */
class Admin extends MY_Controller {

    private $mainTitle = "Migrations";

    public function __construct()
    {
        parent::__construct();

        // Dummys
        if(false){
            $this->migration_model = new Migration_Model();
        }

        // Make sure to load the administrator library!
        $this->load->library('administrator');
        $this->load->model('migration_model');

    }

    public function index($realmId = 1, $from = "-1")
    {
        requirePermission("canEditMigration");

        // Change the title
        $this->administrator->setTitle("Transferliste");

        if(!$this->realms->realmExists($realmId)){
            show_error("Dieser Realm existiert nicht.");
            return;
        }

        $realm = $this->realms->getRealm($realmId);

        $countAll = $this->migration_model->getRealmMigrationCount($realmId);

        $cachedRows = "";

        if($countAll > 1000){
            $paketCount = floor($countAll / 1000);

            for($i = 1; $i <= $paketCount; $i++){
                $from = (($i - 1) * 1000)+1;

                $cacheKey = $this->getCacheKey($realmId,$from - 1);
                $cacheData = $this->cache->get($cacheKey);

                if($cacheData === false){
                    // Renew the cache
                    $this->cache_gen($realmId, $from, 1000, false);
                    $cacheData = $this->cache->get($cacheKey);
                }

                $cachedRows .= $cacheData;

            }


        }

        $limit = $countAll % 1000;

        $query = $this->migration_model->getRealmMigrations($realmId, $limit);

        $migrations = array();

        foreach($query->result() as $row){

            $rowData = $this->prepareMigrationListRow($row);

            $migrations[] = $rowData;

        }

        // Prepare my data
        $templateData = array(
            'state_open' => MIGRATION_STATUS_OPEN,
            'state_done' => MIGRATION_STATUS_DONE,
            'state_declined' => MIGRATION_STATUS_DECLINED,
            'state_inprogress' => MIGRATION_STATUS_IN_PROGRESS,
            'url' => $this->template->page_url,
            'realm_id' => $realmId,
            'realm_name' => $realm->getName(),
            'migrations' => $migrations,
            'cached_rows' => $cachedRows,
            'count' => $countAll,
        );

        // Load my view
        $output = $this->template->loadPage("admin_list.tpl", $templateData);

        // Put my view in the main box with a headline
        $content = $this->administrator->box($this->mainTitle, $output);

        $this->administrator->view($content, false, "", "");
    }

    public function detail($migrationId){
        requirePermission("canEditMigration");

        // Change the title
        $this->administrator->setTitle("Transferdetail");

        $this->load->helper('form');

        /**
         * @var String
         */
        $message = array();

        /**
         * Migration
         */
        $migration = $this->migration_model->getMigration($migrationId);

        if(!$migration){
            show_error("Transfer nicht gefunden");
            return;
        }

        $migration['account_name'] = $this->user->getUsername($migration['account_id']);

        /**
         * @var Integer
         */
        $realmId = $migration['target_realm'];

        /**
         * @var Object
         */
        $realmObj = $this->realms->getRealm($realmId);

        /**
         * Actions
         */
        $migrationStates = $this->migration_model->getMigrationStates();

        $actions = json_decode($migration['actions'], true);

        if(!is_array($actions)){
            $actions = array();
        }

        /**
         * Save changes
         */
        if($this->input->post('change_detail') == "yes"){
            $charGuid = $this->input->post('character_guid');
            $newStatus = $this->input->post('new_status');
            $newComment = $this->input->post('new_comment');
            $transferChar = $this->input->post('transfer_to_account');

            $migration['character_guid'] = $charGuid;

            if($newStatus == MIGRATION_STATUS_DONE){
                if( !$charGuid || empty($charGuid) || $charGuid == 0 ){
                    $message = array(
                        "type" => "error",
                        "message" => "Bitte fülle zuerst das Feld für die Charakter GUID aus.",
                    );
                }
                else{
                    $char = $realmObj->getCharacters()->getCharacterByGUID($charGuid, "account,name");

                    if($char == false){
                        $message = array(
                            "type" => "error",
                            "message" => "Es existiert kein Charakter mit dieser GUID.",
                        );
                    }
                }
            }

            // Update
            if(empty($message)){

                $newAction = array(
                    "by" => ucfirst(strtolower($this->user->getUsername())),
                    "ts" => time(),
                    "reason" => $newComment,
                    "status" => $newStatus,
                );

                $actions[] = $newAction;

                /*
                 * Update database record
                 */
                $this->migration_model->updateMigrationDetail($migrationId, $newStatus, $charGuid, $actions);

                if($transferChar == "yes"){
                    $accountTransfer = $realmObj->getCharacters()->moveCharacterToAccount($charGuid, $migration['account_id'], 1);
                    if($accountTransfer){
                        $this->logger->createLog('Migration Move', 'Migration Id: '.$migrationId.', Char: '.$charGuid.', Account: '.$migration['account_id']);
                    }
                }

                /*
                 * Update Migration object
                 */
                $migration['status'] = $newStatus;

                $message = array(
                    "type" => "success",
                    "message" => "Die neuen Daten für diesen Transfer wurden gespeichert.",
                );
            }
        }

        /**
         * Prepare Actions for output
         */
        foreach($actions as $key => $action){
            $action['status_label'] = (empty($action['status']))
                // No state entered? Then use the current state.
                ? $this->migration_model->getStateLabel($migration['status'])
                : $this->migration_model->getStateLabel($action['status']) ;

            $action['date'] = (empty($action['ts']))
                ? 'Unbekannt'
                : strftime("%d.%m.%Y", $action['ts']);

            $actions[$key] = $action;
        }

        $migration['actions'] = $actions;



        debug("Mig",$migration);

        $migration["status_label"] = $this->migration_model->getStateLabel($migration["status"]);

        $migration["screenshots_link"] = "http://".str_replace("http://","", $migration["screenshots_link"]);
        $migration["server_link"] = "http://".str_replace("http://","", $migration["server_link"]);
        $migration["character_armory"] = "http://".str_replace("http://","", $migration["character_armory"]);

        $migration["gold"] = $migration["gold"] * 10000;

        $migration['class_label'] = $this->realms->getClass($migration['character_class'],0);
        $migration['race_label'] = $this->realms->getRace($migration['character_race'],0);

        /**
         * Other Migrations
         */
        $accountMigrations = $this->migration_model->getAccountMigrations($migration["account_id"]);

        $otherMigrations = array();

        foreach($accountMigrations as $mig){
            if($mig["id"] == $migration["id"]){
                continue;
            }

            $actions = json_decode($mig["actions"], true);
            $messageText = "";
            if(is_array($actions) && count($actions) > 0){
                $last_action = array_pop($actions);
                $messageText = $last_action["by"].( empty($last_action["reason"]) ? "" : ": ".$last_action["reason"] );
            }

            $mig["state_label"] = $this->migration_model->getStateLabel($mig["status"]);
            $mig["message"] = $messageText;

            $otherMigrations[] = $mig;
        }

        /**
         * Professions
         */
        $skills = json_decode($migration["skills"],true);

        foreach($skills as $key => $item){
            $migration[$key] = $item;
        }

        $migration['juwe_max'] = false;
        $migration['vz_max'] = false;
        $migration['ik_max'] = false;
        $migration['leder_max'] = false;
        $migration['koch_max'] = false;

        foreach($migration['professions'] as $i => $prof){
            $prof['label'] = $this->migration_model->getProfessionLabel($prof['skill']).", ";

            $prof['learn_spell'] = $this->migration_model->getProfessionBaseSpell($prof['skill']);

            $migration['professions'][$i] = $prof;

            if($prof['skill'] == 755 && $prof['skill_level'] >= 450){
                $migration['juwe_max'] = true;
            }
            if($prof['skill'] == 333 && $prof['skill_level'] >= 450){
                $migration['vz_max'] = true;
            }
            if($prof['skill'] == 773 && $prof['skill_level'] >= 450){
                $migration['ik_max'] = true;
            }
            if($prof['skill'] == 165 && $prof['skill_level'] >= 450){
                $migration['leder_max'] = true;
            }

        }

        if($migration['Cooking'] >= 450){
            $migration['koch_max'] = true;
        }

        if($migration['Riding'] == 75)
            $migration['Riding_learn'] = 33388;
        if($migration['Riding'] == 150)
            $migration['Riding_learn'] = 33391;
        if($migration['Riding'] == 225)
            $migration['Riding_learn'] = 34090;
        if($migration['Riding'] >= 300)
            $migration['Riding_learn'] = 34091;

        /**
         * Equipment
         */
        $items = json_decode($migration['items'], true);

        $equipmentSlots = $this->migration_model->getEquipmentSlots();
        $slots = array();

        foreach($equipmentSlots as $slotId => $slotLabel){

            $itemId = $items['equipment'][$slotId];

            if($itemId == 0){
                continue;
            }

            $item = $realmObj->getWorld()->getItem($itemId);

            if(!$item || $item == "empty"){
                continue;
            }

            $slots[$slotId] = $this->prepareSlotItem($slotLabel, $item);
        }
        $migration['slots'] = $slots;

        /**
         * Mounts
         */
        $mounts = array(
            "fly" => "Flugmount",
            "floor" => "Bodenmount"
        );
        $migration['items'] = array();

        foreach($mounts as $slotId => $slot){
            if(!empty($items['mounts'][$slotId])){
                $itemId = $items['mounts'][$slotId];

                if($itemId != 0){

                    $item = $realmObj->getWorld()->getItem($itemId);

                    $migration['items'][] = $this->prepareSlotItem($slot, $item);

                }

            }
        }

        /**
         * Random Items
         */
        foreach($items['random'] as $slotId => $itemId){

            if($itemId != 0){
                $item = $realmObj->getWorld()->getItem($itemId);

                if(!$item || $item == "empty"){
                    continue;
                }
                
                $migration['items'][] = $this->prepareSlotItem("#".$slotId, $item);
            }
        }

        /**
         * Reputations
         */
        $reputations = json_decode($migration['reputations'], true);

        foreach($reputations as $repId => $standing){

            switch($standing){
                case 1:
                    $value = 6001;
                    break;
                case 2:
                    $value = 12001;
                    break;
                case 3:
                    $value = 21000;
                    break;
                case 4:
                    $value = 99999;
                    break;
                case 0:
                default:
                    $value = 0;
                    break;
            }
            if($standing == 0){
                continue;
            }

            $migration['factions'][$repId] = array(
                'label' => $this->migration_model->getFactionLabel($repId),
                'standing' => $value,
            );
        }

        $faction = $this->realms->getFaction($migration['character_race']);

        if($faction == FACTION_HORDE){
            $migration['factions'][67] = array(
                'label' => $this->migration_model->getFactionLabel(469),
                'standing' => $this->migration_model->calcHordeRep($migration['reputations']),
            );
            $migration['factions'][1052] = array(
                'label' => $this->migration_model->getFactionLabel(1052),
                'standing' => $this->migration_model->calcHordeWotlkRep($migration['reputations']),
            );
        }
        else{
            $migration['factions'][469] = array(
                'label' => $this->migration_model->getFactionLabel(469),
                'standing' => $this->migration_model->calcAllianceRep($migration['reputations']),
            );
            $migration['factions'][1037] = array(
                'label' => $this->migration_model->getFactionLabel(1037),
                'standing' => $this->migration_model->calcAllianceWotlkRep($migration['reputations']),
            );
        }

        /**
         * Other Characters
         */
        $charactersData = array();

        foreach($this->realms->getRealms() as $realmRow){
            $realmCharacters = $realmRow->getCharacters()->getCharactersByAccount($migration['account_id']);

            foreach($realmCharacters as $charRow){
                $charactersData[] = array(
                    'guid' => $charRow['guid'],
                    'name' => $charRow['name'],
                    'level' => $charRow['level'],
                    'class' => $charRow['class'],
                    'class_label' => $this->realms->getClass($charRow['class'], $charRow['gender']),
                    'realm' => $realmRow->getName(),
                );
            }
        }


        //debug($characters);

        /**
         * Migration completed?
         */
        $migration['is_completed'] = false;
        $migration['current_name'] = "";

        if($migration['character_guid'] > 0){

            $char = $realmObj->getCharacters()->getCharacterByGUID($migration['character_guid'], "account,name");

            if($char != false){
                if($char['account'] == $migration['account_id']){
                    $migration['is_completed'] = true;
                }
                $migration['current_name'] = $char['name'];
            }
        }

        // Prepare my data
        $templateData = array(
            'form_action' => 'migration/admin/detail/'.$migrationId,
            'form_attributes' => array('class' => 'form-horizontal', 'id' => 'migrationDetailForm'),
            'state_open' => MIGRATION_STATUS_OPEN,
            'state_done' => MIGRATION_STATUS_DONE,
            'state_declined' => MIGRATION_STATUS_DECLINED,
            'state_inprogress' => MIGRATION_STATUS_IN_PROGRESS,
            'migration_states' => $migrationStates,
            'migration' => $migration,
            'migration_count' => count($accountMigrations),
            'other_migrations' => $otherMigrations,
            'gm_account_name' => $this->user->getUsername(),
            'characters' => $charactersData,
            'message' => $message,
        );

        // Load my view
        $output = $this->template->loadPage("admin_detail.tpl", $templateData);

        // Put my view in the main box with a headline
        $content = $this->administrator->box($this->mainTitle, $output);

        $this->administrator->view($content, false, "", "");
    }

    public function cache($realmSuccess = false, $realmFrom = false){

        requirePermission("canAdministrate");

        $realms = $this->realms->getRealms();

        $templateData = array(
            "realms" => array(

            ),
        );

        foreach($realms as $realm){

            $countAll = $this->migration_model->getRealmMigrationCount($realm->getId());
            $realmId = $realm->getId();

            if($countAll > 2000){

                $paketCount = floor($countAll / 1000);
                $pakets = array ();

                for($i = 1; $i <= $paketCount; $i++){
                    $from = (($i - 1) * 1000)+1;

                    $cacheKey = $this->getCacheKey($realmId,$from - 1);

                    $cacheData = $this->cache->get($cacheKey);

                    if($realmId == $realmSuccess && $from == $realmFrom){
                        $state = "new";
                    }
                    elseif($cacheData === false){
                        $state = "renew";
                    }
                    else{
                        $state = "existing";
                    }

                    unset($cacheData);

                    $pakets[] = array(
                        "i" => $i,
                        "from" => $from,
                        "to" => ($i * 1000),
                        "state" =>  $state,
                    );
                }

                $templateData["realms"][$realmId] = array(
                    "id" => $realmId,
                    "name" => $realm->getName(),
                    "count" => $countAll,
                    "pakets" => $pakets,
                );

            }

        }

        // Load my view
        $output = $this->template->loadPage("admin_cache.tpl", $templateData);

        // Put my view in the main box with a headline
        $content = $this->administrator->box($this->mainTitle, $output);

        $this->administrator->view($content, false, "", "");

    }

    public function cache_gen($realmId = false, $from = false, $limit = 1000, $redirect = true){

        requirePermission("canAdministrate");

        if(!is_numeric($realmId) || !is_numeric($from) || !is_numeric($limit)){
            show_error("Ungültiger Seitenaufruf");
            return;
        }

        if(!$this->realms->realmExists($realmId)){
            show_error("Dieser Realm existiert nicht.");
            return;
        }


        //$realm = $this->realms->getRealm($realmId);

        $realFrom = $from - 1;
        if($realFrom < 0){
            $realFrom = 0;
        }

        /**
         * Get the data
         */
        $query = $this->migration_model->getRealmMigrations($realmId, $limit, $realFrom, "asc");

        $migrations = array();

        foreach($query->result() as $row){

            $rowData = $this->prepareMigrationListRow($row);

            $migrations[] = $rowData;

        }

        /**
         * The Caching Part
         */
        $cacheKey = $this->getCacheKey($realmId,$realFrom);

        $output = $this->template->loadPage('admin_list_row.tpl', array(
            "migrations" => $migrations
        ));

        /**
         * 31536000 = 1 Year (365 Tage)
         */
        $this->cache->save($cacheKey, $output, 31536000);

        // Show Cache Index Page.
        if($redirect){
            $this->cache($realmId, $from);
        }
        else{
            return true;
        }

    }

    /**
     * Imports data entries from migration_archive to migration_entries
     * skips over items & reputation
     */
    public function import(){

        requirePermission("canImportArchive");

        $this->migration_model->importMigrationArchive();

        return;
    }

    private function getCacheKey($realmId, $realFrom){
        return 'migration_list_r'.$realmId.'_'.$realFrom;
    }

    private function prepareSlotItem($slotName, $item){

        if(empty($item['entry'])){
            return false;
        }

        $itemMessage = "";
        $itemName = "";
        $itemLevel = "";

        if(!$item || $item == "empty"){
            $itemMessage = lang("unknown_item", "item");
        }
        else{
            $itemName = $item['name'];
            $itemLevel = $item['ItemLevel'];
        }

        return array(
            "name" => $slotName,
            "message" => $itemMessage,
            "item_id" => $item['entry'],
            "item_level" => $itemLevel,
            "item_name" => $itemName,
        );

    }

    private function prepareMigrationListRow($row){
        $classes = "";
        $message = "";
        $actions = json_decode($row->actions, true);

        if($row->status == MIGRATION_STATUS_DONE){
            $classes = "done";
        }
        elseif($row->status == MIGRATION_STATUS_IN_PROGRESS){
            $classes = "inprogress";
        }
        elseif($row->status == MIGRATION_STATUS_DECLINED){
            $classes = "deleted disabled";
        }
        else{
            $classes = "open";
        }

        if(is_array($actions)){
            $lastAction = array_pop($actions);
            if(isset($lastAction["by"])){
                $message = $lastAction["by"];
            }

        }

        $row->classes = $classes;
        $row->message = $message;
        $row->date = empty($row->date_done) ? $row->date_created : $row->date_done;

        return array(
            "id" => $row->id,
            "account_id" => $row->account_id,
            "status" => $row->status,
            "classes" => $row->classes,
            "message" => $row->message,
            "character_name" => $row->character_name,
            "server_name" => $row->server_name,
            "date" => $row->date,
        );
    }
}