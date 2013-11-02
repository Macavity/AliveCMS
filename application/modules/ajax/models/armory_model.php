<?php

class Armory_model extends CI_Model
{
    public $realm;
    private $connection;
    private $characterGuid;
    private $realmId;
    private $charDb = null;
    private $professions;

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Assign the character ID to the model
     */
    public function setId($id)
    {
        $this->characterGuid = $id;
    }

    /**
     * Assign the realm object to the model
     */
    public function setRealm($id)
    {
        $this->realmId = $id;
        $this->realm = $this->realms->getRealm($id);
    }

    /**
     * Connect to the character database
     */
    public function connect()
    {
        $this->connection = $this->realm->getCharacters()->getConnection();
    }

    /**
     * Check if the current character exists
     */
    public function characterExists()
    {
        $this->connect();

        $realmId = $this->realmId;
        $characterGuid = $this->characterGuid;

        $query = $this->connection->query("SELECT COUNT(*) AS total FROM ".table("characters", $realmId)." WHERE ".column("characters", "guid", false, $realmId)."= ?", array($characterGuid));
        $row = $query->result_array();

        if($row[0]['total'] > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get the character data that belongs to the character
     */
    public function getCharacter()
    {
        $this->connect();

        $realmId = $this->realmId;
        $characterGuid = $this->characterGuid;

        $query = $this->connection->query(query('get_character', $realmId), array($characterGuid));
        
        if($query && $query->num_rows() > 0)
        {
            $row = $query->result_array();

            return $row[0];
        }
        else
        {
            return array(
                        "account" => "",
                        "name" => "",
                        "race" => "",
                        "class" => "",
                        "gender" => "",
                        "level" => ""
                    );
        }
    }

    /**
     * Get the character stats that belongs to the character
     */
    public function getStats()
    {
        $this->connect();

        $realmId = $this->realmId;
        $characterGuid = $this->characterGuid;

        $query = $this->connection->query("SELECT ".allColumns("character_stats", $realmId)." FROM ".table("character_stats", $realmId)." WHERE ".column("character_stats", "guid", false, $realmId)."= ?", array($characterGuid));

        if($query && $query->num_rows() > 0)
        {
            $row = $query->result_array();

            return $row[0];
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Load professions for the character
     */
    public function getProfessions()
    {
        $this->connect();
        $query = $this->connection->query("SELECT * FROM character_skills WHERE guid = ? AND skill IN (164, 165, 171, 182, 186, 197, 202, 333, 393, 755, 773)", array($this->characterGuid));

        if($query && $query->num_rows() > 1)
        {
            $row = $query->result_array();
            
            $label_pr = $this->getProfessionLabel($row["0"]["skill"]);            
            $row["0"]["name"] = $label_pr["name"];
            $row["0"]["icon"] = $label_pr["icon"];

            $label_se = $this->getProfessionLabel($row["1"]["skill"]);
            $row["1"]["name"] = $label_se["name"];
            $row["1"]["icon"] = $label_se["icon"];

            
            return $row;
        }
        elseif($query && $query->num_rows() > 0 && $query->num_rows() < 2)
        {
            $row = $query->result_array();
            
            $label_pr = $this->getProfessionLabel($row["0"]["skill"]);            
            $row["0"]["name"] = $label_pr["name"];
            $row["0"]["icon"] = $label_pr["icon"];
            
            $row["1"]["skill"] = "";
            $row["1"]["name"] = "";
            $row["1"]["value"] = "";
            $row["1"]["max"] = "";
            $row["1"]["icon"] = "";
            
            return $row;
        }
        else
        {
            return false;
        }
     }
        
     
     /**
      * Get profession labels
      */
      private function getProfessionLabel($professionId)
      {
          switch($professionId)
          {
              case 164:
                  $res["name"] = "Schmieden";
                  $res["icon"] = "trade_blacksmithing";
                  return $res;
              case 165:
                  $res["name"] = "Lederverarbeitung";
                  $res["icon"] = "trade_leatherworking";
                  return $res;
              case 171:
                  $res["name"] = "Alchimie";
                  $res["icon"] = "trade_alchemy";
                  return $res;
              case 182:
                  $res["name"] = "Kräuterkunde";
                  $res["icon"] = "trade_herbalism";
                  return $res;
              case 186:
                  $res["name"] = "Bergbau";
                  $res["icon"] = "inv_pick_02";
                  return $res;
              case 197:
                  $res["name"] = "Schneidern";
                  $res["icon"] = "trade_tailoring";
                  return $res;
              case 202:
                  $res["name"] = "Ingenieurskunst";
                  $res["icon"] = "trade_engineering";
                  return $res;
              case 333:
                  $res["name"] = "Verzaubern";
                  $res["icon"] = "trade_engraving";
                  return $res;
              case 393:
                  $res["name"] = "Kürschnerei";
                  $res["icon"] = "inv_misc_pelt_wolf_01";
                  return $res;
              case 755:
                  $res["name"] = "Juwelenschleifen";
                  $res["icon"] = "inv_misc_gem_01";
                  return $res;
              case 773:
                  $res["name"] = "Inschriftenkunde";
                  $res["icon"] = "inv_inscription_tradeskill01";
                  return $res;
          }
      }

    /**
     * Load items that belong to the character 
     */
    public function getItems()
    {
        $this->connect();

        $query = $this->connection->query(query("get_inventory_item", $this->realmId), array($this->characterGuid));

        if($query && $query->num_rows() > 0)
        {
            $row = $query->result_array();

            return $row;
        }
        else
        {
            return false;
        }
    }

    public function getGuild()
    {
        $this->connect();

        $query = $this->connection->query("SELECT ".column("guild_member", "guildid", true, $this->realmId)." FROM ".table("guild_member", $this->realmId)." WHERE ".column("guild_member", "guid", false, $this->realmId)."= ?", array($this->characterGuid));

        if($this->connection->_error_message())
        {
            die($this->connection->_error_message());
        }

        if($query && $query->num_rows() > 0)
        {
            $row = $query->result_array();

            return $row[0]['guildid'];
        }
        else
        {
            $query2 = $this->connection->query("SELECT ".column("guild", "guildid", true, $this->realmId)." FROM ".table("guild", $this->realmId)." WHERE ".column("guild", "leaderguid", false, $this->realmId)."= ?", array($this->characterGuid));

            if($this->connection->_error_message())
            {
                die($this->connection->_error_message());
            }

            if($query2 && $query2->num_rows() > 0)
            {

                $row2 = $query2->result_array();

                return $row2[0]['guildid'];
            }
            else
            {
                return false;
            }
        }
    }

    public function getGuildName($id)
    {
        if(!$id)
        {
            return '';
        }
        else
        {
            $this->connect();

            $query = $this->connection->query("SELECT ".column("guild", "name", true, $this->realmId)." FROM ".table("guild", $this->realmId)." WHERE ".column("guild", "guildid", false, $this->realmId)."= ?", array($id));

            if($query && $query->num_rows() > 0)
            {
                $row = $query->result_array();

                return $row[0]['name'];
            }
            else
            {
                return false;
            }
        }
    }

    public function findItem($searchString = "", $realmId = 1)
    {
        //Connect to the world database
        $world_database = $this->realms->getRealm($realmId)->getWorld();
        $world_database->connect();

        //Get the connection and run a query
        $query = $world_database->getConnection()->query("SELECT ".columns("item_template", array("entry", "name", "ItemLevel", "RequiredLevel", "InventoryType", "Quality", "class", "subclass"), $realmId)." FROM ".table("item_template", $realmId)." WHERE UPPER(".column("item_template", "name", false, $realmId).") LIKE ? ORDER BY ".column("item_template", "ItemLevel", false, $realmId)." DESC", array('%'.strtoupper($searchString).'%'));

        if($query->num_rows() > 0)
        {
            $row = $query->result_array();
            return $row;
        }
        else
        {
            return false;
        }
    }
}