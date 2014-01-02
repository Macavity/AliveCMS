<?php

/**
 * Class Realmcopy_model
 */
class Realmcopy_model extends MY_Model {

    private $tableName = 'migration_realmcopy_entries';

    public function __construct()
    {
        parent::__construct();
    }

    public function getRealmCharacters($userId)
    {
        /**
         * List of all Realms
         */
        $realms = $this->realms->getRealms();


        $validSourceRealms = explode(',', $this->config->item('migration_copy_source_realm_ids'));

        $realmChars = array();

        /**
         * Find already used characters of this user
         */
        $usedCharacterGuids = $this->getUsedCharacterGuids($this->user->getId());

        foreach($realms as $realm){

            $realmId = $realm->getId();

            /** @var Realm $realm */
            if(in_array($realmId, $validSourceRealms)){

                $realmChars[$realmId] = array();
                $charDb = $realm->getCharacters();

                //Open the connection to the databases
                $charDb->connect();

                //Excute queries on it by getting the connection
                $characters = $charDb->getCharactersByAccount($userId);

                foreach($characters as $row)
                {

                    $row['isUsable'] = ! (isset($usedCharacterGuids[$realmId])
                        && is_array($usedCharacterGuids[$realmId])
                        && in_array($row['guid'], $usedCharacterGuids[$realmId]));

                    $row['raceLabel'] = $this->realms->getRace($row['race'], $row['gender']);
                    $row['classLabel'] = $this->realms->getClass($row['class'], $row['gender']);

                    $row['realmName'] = $realm->getName();

                    $realmChars[$realmId][$row['guid']] = $row;
                }
            }

        }

        return $realmChars;


    }

    /**
     * Get all data of already copied characters of this user.
     *
     * @param $userId
     * @return array
     */
    public function getUsedCharactersData($userId)
    {
        $this->db->select('*')->from($this->tableName);

        if(!empty($userId))
        {
            $this->db->where('account_id', $userId);
        }

        $query = $this->db->get();

        if($query->num_rows())
        {
            $rows = $query->result_array();
            return $rows;
        }

        return array();
    }

    /**
     * Get all guids of already copied characters of this user,
     * sorted in arrays by realm
     *
     * @param $userId
     * @return array
     */
    public function getUsedCharacterGuids($userId)
    {
        $this->db->select('source_realm, character_guid')->from($this->tableName);

        if(!empty($userId))
        {
            $this->db->where('account_id', $userId);
        }

        $query = $this->db->get();

        if($query->num_rows())
        {
            $realmChars = array();

            $rows = $query->result_array();

            foreach($rows as $row)
            {
                $realmChars[$row['source_realm']][] = $row['character_guid'];
            }

            return $realmChars;
        }

        return array();
    }

} 