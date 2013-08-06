<?php

class Tooltip extends MX_Controller
{
    private $itemEntry;
    private $itemGuid;
    
    private $charGuid;
    
    private $realmId;
    private $realm;
    private $world;
    
    function __construct()
    {
        parent::__construct();

        $this->load->model("item_model");
        
        $this->realm = $realm = $this->realms->getRealm(1); 
        $this->world = $this->realm->getWorld();
    }
    
    public function index()
    {
        $item = $this->world->getItem(51231);
        
        echo $item;
    }
    
    /**
     * Tooltip für das Item generieren
     * @param Numeric $itemEntry Die übergeben Item entry Id
     * @param Numeric $itemGuid Die Guid des Items, welches sich in der Tasche des Charakters befindet
     * @param Numeric $realmId Die übergebene Realm ID
     * @param Numeric $charGuid Die Guid des Charakters, welcher das Item besitzt
     **/
    public function getItemTooltip($itemEntry = false, $itemGuid = false, $realmId = false, $charGuid = false)
    {
        if ($itemGuid)
        {
            
        }
        else
        {
            
        }
    }
}