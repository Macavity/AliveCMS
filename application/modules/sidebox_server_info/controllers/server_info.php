<?php

class Server_Info extends MX_Controller implements Sidebox{

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

        $connection = $this->load->database("account", true);
        
        /*
         * Total Accounts
         */
        $totalAccounts = $this->cache->get("total_accounts");

        if($totalAccounts === false){
            $totalAccounts = $this->external_account_model->getAccountCount();
            $this->cache->save("total_accounts", $totalAccounts, 60*60*24);
        }
        
        /*
         * Uptime
         */
        $maxPlayers = 0;
        $uptime = $this->cache->get("realm_uptime_".$realms[0]->getId());

        if($uptime === false || $realms[0]->isOnline() == false){
            $result = $connection->query("SELECT starttime FROM `uptime` WHERE realmid=? ORDER BY starttime DESC LIMIT 1;", array(
                $realms[0]->getId()
            ));
        
            if($result){
                $uptime = $result->result();
                $uptime = $uptime[0]->starttime;
                $this->cache->save("realm_uptime_".$realms[0]->getId(), $uptime, 60*60*24);
            }
        }
        
        /*
         * Maxplayers
         */
        $maxPlayers = $this->cache->get("maxplayers_".$realms[0]->getId());
        
        if($maxPlayers === false){
            $result = $connection->query("SELECT maxplayers FROM `uptime` WHERE realmid=? ORDER BY maxplayers DESC LIMIT 1;", array(
                $realms[0]->getId()
            ));
            
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
     * Returns a formatted time string out of a given duration from the given starting time untill now
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
