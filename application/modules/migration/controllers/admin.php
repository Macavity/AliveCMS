<?php

class Admin extends CI_Controller {

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

    public function index($realmId = 1, $from = "-1"){
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
                    $this->cache_gen($realmId, $from-1, 1000, false);
                    $cacheData = $this->cache->get($cacheKey);
                }

                $cachedRows = $cacheData . $cachedRows;

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
            'count' => count($migrations),
        );

        // Load my view
        $output = $this->template->loadPage("admin_list.tpl", $templateData);

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

        /**
         * Get the data
         */
        $query = $this->migration_model->getRealmMigrations($realmId, $limit, $realFrom);

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

    private function prepareMigrationListRow($row){
        $classes = "";
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

        if(isset($actions[0]["by"])){
            $message = $actions[0]["by"];
        }
        else{
            $message = "";
        }
        $row->classes = $classes;
        $row->message = $message;
        $row->date = empty($row->date_done) ? $row->date_created : $row->date_done;

        return array(
            "id" => $row->id,
            "status" => $row->status,
            "classes" => $row->classes,
            "message" => $row->message,
            "character_name" => $row->character_name,
            "server_name" => $row->server_name,
            "date" => $row->date,
        );
    }
}