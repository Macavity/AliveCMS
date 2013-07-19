<?php


define("BUGSTATE_OPEN", 1);
define("BUGSTATE_ACTIVE", 2);
define("BUGSTATE_REJECTED", 3);
define("BUGSTATE_DONE", 9);

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

    private $availableBugStates = array();

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

    public function getBugStates(){
        return $this->availableBugStates;
    }

    /**
     * Get all bugs
     * @param $projectId string
     * @return bool
     */
    public function getBugs($projectId = 0)
    {
        $this->db->select('id, project, project_path, bug_state, title, date as createdDate, date2 as changedDate, createdTimestamp, changedTimestamp');

        if($projectId != 0){
            $this->db->where("project", $projectId);
        }

        $this->db->from($this->tableName)->order_by('id', 'desc');

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
                ->select('id, bug_state, project, project_path, title, date as createdDate, date2 as changedDate, createdTimestamp, changedTimestamp')
                ->order_by('id', 'desc')
                ->where('project', $projectId)
                ->where_in('bug_state', array(BUGSTATE_DONE, BUGSTATE_ACTIVE, BUGSTATE_OPEN))
                ->from($this->tableName);
        }
        elseif($restriction == "none"){
            $this->db
                ->select('id, bug_state, project, project_path, title, date as createdDate, date2 as changedDate, createdTimestamp, changedTimestamp')
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
            ->where('class', "[Quest]")
            ->update($this->tableName, array(
                "project" => 5,
                "project_path" => json_encode(array("base" => 1, "parent" => 5)),
            ));

        // Instanz
        $this->db
            ->where('class', "[Instanz]")
            ->update($this->tableName, array(
                "project" => 9,
                "project_path" => json_encode(array("base" => 1, "parent" => 9)),
            ));

        // NPC
        $this->db
            ->where('class', "[NPC]")
            ->update($this->tableName, array(
                "project" => 43,
                "project_path" => json_encode(array("base" => 1, "parent" => 43)),
            ));


        // Erfolg
        $this->db
            ->where('class', "[Erfolg]")
            ->update($this->tableName, array(
                "project" => 11,
                "project_path" => json_encode(array("base" => 1, "parent" => 11)),
            ));


        // Item
        $this->db
            ->where('class', "[Item]")
            ->update($this->tableName, array(
                "project" => 45,
                "project_path" => json_encode(array("base" => 1, "parent" => 45)),
            ));


        // Homepage
        $this->db
            ->where('class', "[Homepage]")
            ->update($this->tableName, array(
                "project" => $this->defaultHomepageProject,
                "project_path" => json_encode(array("base" => $this->defaultHomepageProject, "parent" => $this->defaultHomepageProject)),
            ));

        // Charakter
        $this->db
            ->where('class', "[Charakter]")
            ->update($this->tableName, array(
                "project" => 49,
                "project_path" => json_encode(array("base" => 1, "parent" => 49)),
            ));

        // Charakter/Hexenmeister
        $this->db
            ->where('class', "[Charakter/Hexenmeister]")
            ->update($this->tableName, array(
                "project" => 31,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));

        // Charakter/Jäger
        $this->db
            ->where('class', "[Charakter/Jäger]")
            ->update($this->tableName, array(
                "project" => 25,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));

        // Charakter/Krieger
        $this->db
            ->where('class', "[Charakter/Krieger]")
            ->update($this->tableName, array(
                "project" => 23,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));

        // Charakter/Magier
        $this->db
            ->where('class', "[Charakter/Magier]")
            ->update($this->tableName, array(
                "project" => 30,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));

        // Charakter/Paladin
        $this->db
            ->where('class', "[Charakter/Paladin]")
            ->update($this->tableName, array(
                "project" => 24,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));

        // Charakter/Priester
        $this->db
            ->where('class', "[Charakter/Priester]")
            ->update($this->tableName, array(
                "project" => 27,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));

        // Charakter/Schamane
        $this->db
            ->where('class', "[Charakter/Schamane]")
            ->update($this->tableName, array(
                "project" => 29,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));

        // Charakter/Schurke
        $this->db
            ->where('class', "[Charakter/Schurke]")
            ->update($this->tableName, array(
                "project" => 26,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));

        // Charakter/Todesritter
        $this->db
            ->where('class', "[Charakter/Todesritter]")
            ->update($this->tableName, array(
                "project" => 28,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));

        // Charakter/Druide
        $this->db
            ->where('class', "[Charakter/Druide]")
            ->update($this->tableName, array(
                "project" => 32,
                "project_path" => json_encode(array("base" => 1, "parent" => 7)),
            ));



    }

    /**
     * Count how many Bugs a project has
     * @param $projectId
     * @param int $type
     */
    public function getBugCountByProject($projectId = 0, $type = 0){

        $this->db->select('count(bug_state) as count, bug_state');

        if($projectId != 0){
            $this->db->where('project', $projectId);
        }

        if($type === 0){
            $this->db
                ->where_in('bug_state', array(BUGSTATE_DONE, BUGSTATE_ACTIVE, BUGSTATE_OPEN))
                ->group_by("bug_state");
            $query = $this->db->from($this->tableName);
            $results = $query->get()->result_array();

            if(count($results) > 0){
                $data = array();
                foreach($results as $row){
                    $data[$row["bug_state"]] = $row["count"];
                }
            }

            $data[BUGSTATE_DONE] = empty($data[BUGSTATE_DONE]) ? 0 : $data[BUGSTATE_DONE] * 1;
            $data[BUGSTATE_ACTIVE] = empty($data[BUGSTATE_ACTIVE]) ? 0 : $data[BUGSTATE_ACTIVE] * 1;
            $data[BUGSTATE_OPEN] = empty($data[BUGSTATE_OPEN]) ? 0 : $data[BUGSTATE_OPEN] * 1;

            return $data;
        }
        else{
            $this->db->where('bug_state', $type);
            $query = $this->db->from($this->tableName);
            $results = $query->get()->result_array();

            if(count($results) > 0){
                return $results[0]["count"];
            }
            return 0;
        }

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
        $this->db->select("*")->where("id", $id)->from($this->tableName);
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            $result = $query->result_array();

            return $result[0];
        }
        return false;
    }

    /**
     * @param $bugId
     * @return bool
     */
    public function getBugComments($bugId){

        $this->db->select("*")->where("postid", $bugId)->order_by("id", "asc")->from("bugtracker_comments");

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    /**
     * @param $type
     * @return string
     */
    public function getTypeLabel($type){
        return "";  // TODO System umschreiben auf administrierbare Bug-Kategorien
    }

    public function getStateLabel($type){
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
        return array();

    }
}