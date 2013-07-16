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

    var $availableBugStates = array(
        BUGSTATE_OPEN => "Offen",
        BUGSTATE_ACTIVE => "Bearbeitung",
        BUGSTATE_DONE => "Erledigt",
        BUGSTATE_REJECTED => "Abgewiesen",
        BUGSTATE_IMPOSSIBLE => "Nicht umsetzbar"
    );

    public function getBugs()
    {
        $this->db->select('*')->from($this->tableName)->order_by('id', 'desc');
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

    public function importOldBugs(){

        // Send all not old bugs to the wotlk project by default
        $this->db->where("project", 0)->update($this->tableName, array(
            "project" => $this->defaultProject
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

        $this->db->select('count(state) as count, state')->from($this->tableName)->group_by('bug_state')->where('project', $projectId);

        if($type === 0){
        }
        else{
            $this->db->where('state', $type);
        }

        $results = $this->db->result();

        $result = $results[0];
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
}