<?php

class Search extends MX_Controller{

    public function __construct(){
        parent::__construct();

        if(false){
            $this->template = new Template();
            $this->realms = new Realms();
            $this->bug_model = new Bug_model();
            $this->zone_model = new Zone_model();
        }
    }

    public function index($type, $realm, $term){

        $json = array();
        $json['msg'] = "Ungültige Anfrage";
        $this->template->handleJsonOutput($json);

    }

    public function quest($realmId, $term){

        $term = urldecode($term);

        $json = array(
            'term' => $term,
            'results' => array()
        );


        if(!empty($realmId) && !empty($term) && strlen($term) > 2){
            $realmId = (int) $realmId;

            if(!$this->realms->realmExists($realmId)){
                $json['msg'] = "Ungültige Anfrage, Realm nicht gefunden.";
            }
            else if(!$worldDb = $this->realms->getRealm($realmId)->getWorld()->getConnection()){
                $json['msg'] = "Ungültige Anfrage, Realm Datenbank ist offline.";
            }
            else{
                $query = $worldDb->select('Id, Title, RequiredRaces')
                    ->like('Title', $term)
                    ->limit(10)
                    ->from('quest_template')->get();

                if($query->num_rows() > 0){
                    foreach($query->result_array() as $row){

                        $label = $row['Title'];

                        $json['results'][] = array(
                            'label' => $label,
                            'required_races' => $row['RequiredRaces'],
                            'value' => $row['Id'],
                        );
                    }
                }
            }
        }
        else{
            $json['msg'] = "Ungültige Anfrage";
        }

        $this->template->handleJsonOutput($json);
    }

    /**
     * @depends_on Module/Bugtracker
     * @param $term
     *
     */
    public function bugs($type, $term){

        $term = urldecode($term);

        $json = array(
            'type' => $type,
            'term' => $term,
            'results' => array()
        );

        if(strlen($term) < 3){
            $json['msg'] = "Ungültige Anfrage";
        }
        else {
            $this->load->model('bugtracker/bug_model');
            $results = $this->bug_model->findSimilarBugs($type.'='.$term);

            $json['results'] = $results;
        }

        $this->template->handleJsonOutput($json);
    }

    /**
     * @param $realmId
     * @param $term
     */
    public function npc($realmId, $term){

        $term = urldecode($term);

        $json = array(
            'term' => $term,
            'results' => array()
        );

        if(!empty($realmId) && !empty($term) && strlen($term) > 2){
            $realmId = (int) $realmId;

            if(!$this->realms->realmExists($realmId)){
                $json['msg'] = "Ungültige Anfrage, Realm nicht gefunden.";
            }
            else if(!$worldDb = $this->realms->getRealm($realmId)->getWorld()->getConnection()){
                $json['msg'] = "Ungültige Anfrage, Realm Datenbank ist offline.";
            }
            else{
                $query = $worldDb->select('entry, name')
                    ->like('name', $term)
                    ->limit(15)
                    ->from('creature_template')->get();

                if($query->num_rows() > 0){
                    foreach($query->result_array() as $row){
                        $json['results'][] = array(
                            'value' => $row['entry'],
                            'label' => $row['name'],
                        );
                    }
                }
            }
        }
        else{
            $json['msg'] = "Ungültige Anfrage";
        }

        $this->template->handleJsonOutput($json);
    }

    /**
     * @depends_on Module/Game
     */
    public function zone($realmId, $term){

        $term = urldecode($term);

        $json = array(
            'term' => $term,
            'results' => array()
        );

        if(strlen($term) < 3){
            $json['msg'] = "Ungültige Anfrage";
        }
        else{
            $this->load->model('game/zone_model');
            $json['results'] = $this->zone_model->searchForZoneByName($term);

        }

        $this->template->handleJsonOutput($json);
    }
}