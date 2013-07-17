<?php


define("BUGSTATE_OPEN", 1);
define("BUGSTATE_ACTIVE", 2);
define("BUGSTATE_REJECTED", 3);
define("BUGSTATE_DONE", 10);

define("BUGTYPE_GENERIC",       100);
define("BUGTYPE_GENERIC_ITEM",  101);
define("BUGTYPE_GENERIC_NPC",   102);

define("BUGTYPE_CLASS",         200);
define("BUGTYPE_CLASS_WARRIOR", 201);
define("BUGTYPE_CLASS_PALADIN", 202);
define("BUGTYPE_CLASS_HUNTER",  203);
define("BUGTYPE_CLASS_ROGUE",   204);
define("BUGTYPE_CLASS_PRIEST",  205);
define("BUGTYPE_CLASS_DK",      206);
define("BUGTYPE_CLASS_SHAMAN",  207);
define("BUGTYPE_CLASS_MAGE",    208);
define("BUGTYPE_CLASS_WARLOCK", 209);
define("BUGTYPE_CLASS_DRUID",   211);

define("BUGTYPE_QUEST",         300);
define("BUGTYPE_QUEST_ALL",     301);

define("BUGTYPE_PROFESSION",    400);
define("BUGTYPE_DUNGEON",       500);
define("BUGTYPE_RAID",          600);
define("BUGTYPE_ACHIEVEMENT",   700);
define("BUGTYPE_PVP",           800);


class Bug_model extends CI_Model
{
    var $tableName = "bugtracker_entries";

    var $defaultProject = 1;
    var $defaultHomepageProject = 3;

    var $availableBugStates = array();

    public function __construct(){

        $this->tableName = "bugtracker_entries";
        $this->defaultProject = 1;
        $this->defaultHomepageProject = 3;

        $this->availableBugStates = array(
            BUGSTATE_OPEN => "Offen",
            BUGSTATE_ACTIVE => "Bearbeitung",
            BUGSTATE_DONE => "Erledigt",
            BUGSTATE_REJECTED => "Abgewiesen"
        );
    }

    /**
     * Get all bugs
     * @return bool
     */
    public function getBugs()
    {
        $this->db->select('id, bug_type, bug_subtype, bug_state, title, date as createdDate, date2 as changedDate, createdTimestamp, changedTimestamp')->order_by('id', 'desc')->from($this->tableName);
        $query = $this->db->get();
            
        if($query->num_rows() > 0)
        {
            $result = $query->result_array();
    
            return $result;
        }
        else 
        {
            return false;
        }
    }

    /**
     * Get all Bugs of a project
     * @param $projectId
     * @return bool
     */
    public function getBugsByProject($projectId, $restriction = "normal")
    {

        if($restriction == "normal"){
            $this->db
                ->select('id, bug_type, bug_subtype, bug_state, title, date as createdDate, date2 as changedDate, createdTimestamp, changedTimestamp')
                ->order_by('id', 'desc')
                ->where('project', $projectId)
                ->where_in('bug_state', array(BUGSTATE_DONE, BUGSTATE_ACTIVE, BUGSTATE_OPEN))
                ->from($this->tableName);
        }
        elseif($restriction == "none"){
            $this->db
                ->select('id, bug_type, bug_subtype, bug_state, title, date as createdDate, date2 as changedDate, createdTimestamp, changedTimestamp')
                ->order_by('id', 'desc')
                ->where('project', $projectId)
                ->from($this->tableName);
        }

        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            $results = $query->result_array();

            return $results;
        }
        else
        {
            return false;
        }
    }

    public function importOldBugs(){

        $defaultProject = $this->defaultProject;

        // Send all not old bugs to the wotlk project by default
        $this->db->where("project", 0)->update($this->tableName, array(
            "project" => $defaultProject,
        ));

        // Old state to new state
        foreach($this->availableBugStates as $key => $oldValue){
            $this->db->where('bug_state', 0)->where('state', $oldValue);
            $this->db->update($this->tableName, array(
                'state' => "",
                'bug_state' => $key,
            ));
        }

        /*
         *  Old class to new structure
         */

        // Quests
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Quest]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_QUEST,
                "bug_subtype" => BUGTYPE_QUEST_ALL
            ));

        // Instanz
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Instanz]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_INSTANCE,
                "bug_subtype" => 0
            ));

        // NPC
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[NPC]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_GENERIC,
                "bug_subtype" => BUGTYPE_GENERIC_NPC,
            ));

        // Instanz
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Instanz]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_INSTANCE,
                "bug_subtype" => 0
            ));


        // Erfolg
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Erfolg]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_ACHIEVEMENT,
                "bug_subtype" => 0
            ));


        // Item
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Item]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_GENERIC,
                "bug_subtype" => BUGTYPE_GENERIC_ITEM,
            ));


        // Homepage
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Homepage]")
            ->update($this->tableName, array(
                "project" => $this->defaultHomepageProject,
                "bug_type" => BUGTYPE_GENERIC,
                "bug_subtype" => 0,
            ));

        // Charakter
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => 0
            ));

        // Charakter/Hexenmeister
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Hexenmeister]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_WARLOCK
            ));

        // Charakter/Jäger
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Jäger]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_HUNTER
            ));

        // Charakter/Krieger
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Krieger]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_WARRIOR
            ));

        // Charakter/Magier
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Magier]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_MAGE
            ));

        // Charakter/Paladin
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Paladin]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_PALADIN
            ));

        // Charakter/Priester
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Priester]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_PRIEST
            ));

        // Charakter/Schamane
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Schamane]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_SHAMAN
            ));

        // Charakter/Schurke
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Schurke]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_ROGUE
            ));

        // Charakter/Todesritter
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Todesritter]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_DK
            ));

        // Charakter/Druide
        $this->db
            ->where('bug_type', 0)
            ->where('class', "[Charakter/Druide]")
            ->update($this->tableName, array(
                "bug_type" => BUGTYPE_CLASS,
                "bug_subtype" => BUGTYPE_CLASS_DRUID
            ));



    }

    /**
     * Count how many Bugs a project has
     * @param $projectId
     * @param int $type
     */
    public function getBugCountByProject($projectId, $type = 0){

        if($type === 0){
            /**
             * Get the count for all states except REJECTED
             */
            $query = $this->db->select('count(bug_state) as count, bug_state')->group_by("bug_state")
                ->where('project', $projectId)
                ->where_in('bug_state', array(BUGSTATE_DONE, BUGSTATE_ACTIVE, BUGSTATE_OPEN))
                ->from($this->tableName);
            $results = $query->get()->result_array();

            if(count($results) > 0){
                $data = array();
                foreach($results as $row){
                    $data[$row["bug_state"]] = $row["count"];
                }
                return $data;
            }
        }
        else{
            $query = $this->db->select('count(bug_state) as count, bug_state')
                ->where('project', $projectId)
                ->where('bug_state', $type)
                ->from($this->tableName);
            $results = $query->get()->result_array();

            if(count($results) > 0){
                return $results[0]["count"];
            }
        }

        return 0;
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM {$this->tableName} WHERE id=?", array($id));
    }

    public function create($headline, $identifier, $rank_needed, $top_category, $content)
    {
        $data = array(
            'name' => $headline,
            'identifier' => $identifier,
            'rank_needed' => $rank_needed,
            'top_category' => $top_category,
            'content' => $content
        );

        $this->db->insert("bugs", $data);
    }

    public function update($id, $headline, $identifier, $rank_needed, $top_category, $content)
    {
        $data = array(
            'name' => $headline,
            'identifier' => $identifier,
            'rank_needed' => $rank_needed,
            'top_category' => $top_category,
            'content' => $content
        );

        $this->db->where('id', $id);
        $this->db->update("bugs", $data);
    }

    public function getBug($id)
    {
        $query = $this->db->query("SELECT * FROM bugs WHERE id=?", array($id));

        if($query->num_rows() > 0)
        {
            $result = $query->result_array();

            return $result[0];
        }
        else
        {
            return false;
        }
    }

    public function getTypeLabel($type){
        return (empty($this->availableBugStates[$type])) ? "" : $this->availableBugStates[$type];
    }

    public function findSimilarBugs($search, $bugId){
        $this->db->select('id, title')
            ->like('link', $search)
            ->where_in('bug_state', array(BUGSTATE_OPEN, BUGSTATE_ACTIVE))
            ->where('id <>', $bugId)
            ->from($this->tableName);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;

    }
}