<?php

/**
 * Class Arsenal_model
 *
 * based on character/armory_model..roughly
 *
 * @extends CI_Model
 */
class Arsenal_model extends CI_Model{

    private $realmId;

    /**
     * @var Realm
     */
    private $realm = NULL;

    /**
     * @var Realms
     */
    private $realms;

    /**
     * @var CI_DB_driver
     */
    private $connection;


    private $errorType = "";
    private $errorMessage = "";

    /**
     * @var int
     */
    private $characterGuid = 0;

    /**
     * @var Characters_model $charDbModel
     */
    private $charDbModel;
    private $itemLevel = 0;
    private $itemLevelEquipped = 0;

    public function __construct()
    {
        parent::__construct();

        $this->realms = new Realms();
    }

    /**
     * @param $realm
     * @param $character
     */
    public function initialize($realm, $character)
    {
        /*
         * Find the Realm
         */

        if(is_numeric($realm))
        {
            if($this->realms->realmExists($realm))
            {
                $realm = $this->realms->getRealm($realm);
            }
            else
            {
                $realm = false;
            }
        }
        else{
            $realm = $this->realms->getRealmByName($realm);
        }

        if(!$realm)
        {
            $this->setErrorType('error_realm');
            return;
        }

        $this->realm = $realm;

        if(!$this->realm){
            $this->setErrorType('error_realm');
            return;
        }

        $this->realmId = $this->realm->getId();

        /**
         * Connection to the Character DB
         * @var Characters_Model
         */
        $this->charDbModel = $this->realm->getCharacters();

        /*
         * Find the Character
         */
        if(!is_numeric($character))
        {
            $charGuid = $this->realm->getCharacters()->getGuidByName($character);
        }
        else{
            $charGuid = $character;
        }

        /**
         * @var Characters_model $charDbModel
         */
        $charDbModel = $this->charDbModel;

        if($charDbModel->characterExists($charGuid))
        {
            $this->characterGuid = $charGuid;
        }
        else
        {
            $this->setErrorType('error_character_not_found');
            return;
        }

        return;
    }

    /**
     * Assign the character ID to the model
     */
    public function setCharacterGuid($id)
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
        $this->realm->getCharacters()->connect();

        /**
         * @var CI_DB_driver
         */
        $this->connection = $this->realm->getCharacters()->getConnection();
    }

    /**
     * Check if the current character exists
     */
    public function characterExists()
    {
        $this->connect();

        /** @var CI_DB_result $query */
        $query = $this->connection->query("SELECT COUNT(*) AS total FROM ".table("characters", $this->realmId)." WHERE ".column("characters", "guid", false, $this->realmId)."= ?", array($this->characterGuid));
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

        /** @var CI_DB_result $query */
        $query = $this->connection->query(query('get_character', $this->realmId), array($this->characterGuid));

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

    public function getCharacterGUID()
    {
        return $this->characterGuid;
    }

    /**
     * Get the character stats that belongs to the character
     */
    public function getStats()
    {
        $this->connect();

        /** @var CI_DB_result $query */
        $query = $this->connection->query("SELECT ".allColumns("character_stats", $this->realmId)." FROM ".table("character_stats", $this->realmId)." WHERE ".column("character_stats", "guid", false, $this->realmId)."= ?", array($this->characterGuid));

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
     * Load items that belong to the character
     */
    public function getItems()
    {
        $this->connect();

        /** @var CI_DB_result $query */
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

    public function getItemLevel()
    {
        return $this->itemLevel;
    }

    public function getItemLevelEquipped()
    {
        return $this->itemLevelEquipped;
    }

    public function getGuild()
    {
        $this->connect();

        /** @var CI_DB_result $query */
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
            /** @var CI_DB_result $query2 */
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

            /** @var CI_DB_result $query */
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

    /**
     * @return \Realm
     */
    public function getRealm()
    {
        return $this->realm;
    }

    /**
     * @param string $errorType
     */
    public function setErrorType($errorType)
    {
        $this->errorType = $errorType;
        $this->errorMessage = lang($errorType, 'arsenal');
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        if(empty($this->errorType))
        {
            return false;
        }
        return $this->errorMessage;
    }
}