<?php

class Server_Info extends MY_Controller implements Sidebox{

    public $overwriteDisplayName = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('sidebox_visitors/visitor_model');
    }
    
    public function view(){
        
        // Load realm objects
        $realms = $this->realms->getRealms();
            
        /*
         * Main Realm (first one)
         */
        $isOnline = $realms[0]->isOnline();
        
        if($isOnline){
            $this->overwriteDisplayName = 'Server Information: <span class="up">Online</span>';
        }
        else{
            $this->overwriteDisplayName = 'Server Information: <span class="down">Offline</span>';
        }

        /*
         * Total Accounts
         */
        $totalAccounts = $this->cache->get("total_accounts");

        if($totalAccounts === false){
            $totalAccounts = $this->external_account_model->getAccountCount();
            $this->cache->save("total_accounts", $totalAccounts, 60*60*24);
        }

        $realmDb = $this->load->database("account", true);

        /*
         * Uptime
         */
        $maxPlayers = 0;
        $uptime = $this->cache->get("realm_uptime_".$realms[0]->getId());

        $mainRealm = $realms[0];

        if($uptime === false || $mainRealm->isOnline() == false){
            $result = $realmDb->query("SELECT starttime FROM `uptime` WHERE realmid=".$mainRealm->getId()." ORDER BY starttime DESC LIMIT 1;");
        
            if($result){
                $uptime = $result->result();
                $uptime = $uptime[0]->starttime;
                $this->cache->save("realm_uptime_".$realms[0]->getId(), $uptime, 60*30);    // 30 Minutes
            }
        }
        
        /*
         * Maxplayers
         */
        $maxPlayers = $this->cache->get("maxplayers_".$realms[0]->getId());
        
        if($maxPlayers === false){
            $result = $realmDb->query("SELECT maxplayers FROM `uptime` WHERE realmid=".$realms[0]->getId()." ORDER BY starttime DESC LIMIT 1;");

            if($result){
                $maxPlayers = $result->result();
                $maxPlayers = $maxPlayers[0]->maxplayers;
                $this->cache->save("maxplayers_".$realms[0]->getId(), $maxPlayers, 60*60*24);
            }
        }

        $data = array(
            "module" => "sidebox_server_info",
            "url" => $this->template->page_url,
            "realmlist" => $this->config->item('realmlist'),
            "uptime" => $this->formatTime($uptime),
            "maxplayers" => $maxPlayers,
            "accounts" => $totalAccounts,
        );
        
        $page = $this->template->loadPage("server_info.tpl", $data);

        return $page;
    }
    
    /**
     * Returns a formatted time string out of a given duration from the given starting time until now
     * @param Integer $startTime
     * @return String
     */
    private function formatTime($startTime){
        $t = array( //suffixes
            'd' => 86400,
            'h' => 3600,
            'm' => 60,
        );
        $s = abs(time() - $startTime);
        $string = "";
        foreach($t as $key => &$val) {
            $$key = floor($s/$val);
            $s -= ($$key*$val);
            $string .= ($$key==0) ? '' : $$key . "$key ";
        }
        return $string . $s. 's';
    }
}
