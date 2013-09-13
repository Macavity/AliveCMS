<?php

define('BUGPRIORITY_TRIVIAL',       1);
define('BUGPRIORITY_MINOR',         2);
define('BUGPRIORITY_MAJOR',         3);
define('BUGPRIORITY_CRITICAL',      4);
define('BUGPRIORITY_BLOCKER',       5);

define('BUGSTATE_OPEN', 1);
define('BUGSTATE_ACTIVE', 2);
define('BUGSTATE_REJECTED', 3);
define('BUGSTATE_WORKAROUND', 4);
define('BUGSTATE_DONE', 9);
define('BUGSTATE_ALL', 10);

define('BUGTYPE_GENERIC',       100);
define('BUGTYPE_GENERIC_ITEM',  101);
define('BUGTYPE_GENERIC_NPC',   102);

define('BUGTYPE_CLASS',         200);
define('BUGTYPE_CLASS_WARRIOR', 201);
define('BUGTYPE_CLASS_PALADIN', 202);
define('BUGTYPE_CLASS_HUNTER',  203);
define('BUGTYPE_CLASS_ROGUE',   204);
define('BUGTYPE_CLASS_PRIEST',  205);
define('BUGTYPE_CLASS_DK',      206);
define('BUGTYPE_CLASS_SHAMAN',  207);
define('BUGTYPE_CLASS_MAGE',    208);
define('BUGTYPE_CLASS_WARLOCK', 209);
define('BUGTYPE_CLASS_DRUID',   211);

define('BUGTYPE_QUEST',         300);
define('BUGTYPE_QUEST_ALL',     301);

define('BUGTYPE_PROFESSION',    400);
define('BUGTYPE_DUNGEON',       500);
define('BUGTYPE_RAID',          600);
define('BUGTYPE_ACHIEVEMENT',   700);
define('BUGTYPE_PVP',           800);


class Bug_model extends CI_Model
{
    var $tableName = 'bugtracker_entries';

    var $defaultProject = 1;
    var $defaultHomepageProject = 3;

    private $availableBugStates = array();

    /**
     * These Priorities are usable by all users
     * @var array
     */
    private $availablePriorities = array();
    private $priorityLabels = array();

    public function __construct(){

        $this->tableName = 'bugtracker_entries';
        $this->defaultProject = 1;
        $this->defaultHomepageProject = 3;

        $this->availableBugStates = array(
            BUGSTATE_OPEN => 'Offen',
            BUGSTATE_ACTIVE => 'Bearbeitung',
            BUGSTATE_WORKAROUND => 'Workaround',
            BUGSTATE_DONE => 'Erledigt',
            BUGSTATE_REJECTED => 'Abgewiesen'
        );

        $this->availablePriorities = array(
            BUGPRIORITY_TRIVIAL,
            BUGPRIORITY_MINOR,
            BUGPRIORITY_MAJOR,
            BUGPRIORITY_CRITICAL,
        );

        $this->priorityLabels = array(
            BUGPRIORITY_TRIVIAL => 'Trivial',
            BUGPRIORITY_MINOR => 'Niedrig',
            BUGPRIORITY_MAJOR => 'Hoch',
            BUGPRIORITY_CRITICAL => 'Kritisch',
            BUGPRIORITY_BLOCKER => 'Blocker',
        );
    }

    public function getBugStates(){
        return $this->availableBugStates;
    }

    public function getBugPriorities(){
        return $this->availablePriorities;
    }

    public function getPrioritiesWithLabel(){
        $priorities = array();
        foreach($this->availablePriorities as $piority){
            $priorities[$piority] = $this->getPriorityLabel($piority);
        }
        return $priorities;
    }

    /**
     * Get all bugs
     * @deprecated
     * @param $projectId string
     * @return bool
     */
    public function getBugs($projectId = 0)
    {
        $this->db->select('id, project, priority, project_path, bug_state, title, date as createdDate, date2 as changedDate, createdTimestamp, changedTimestamp');

        if($projectId != 0){
            $this->db->where('project', $projectId);
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

    public function getRecentChanges($limit = 10){
        $this->db->select('id, priority, matpath, bug_state, title, createdDate, createdTimestamp, changedDate, changedTimestamp');

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
     * @param string $restriction Values 'normal': Get all Done/Active/Open Bugs; 'none': Get all bugs
     * @return bool|array
     */
    public function getBugsByProject($projectId, $restriction = 'normal'){

        $matpath = str_pad($projectId, 4, '0', STR_PAD_LEFT);
        $sql = '
SELECT
	be.id, be.bug_state, be.project, be.priority, be.title, be.createdDate, be.changedDate, be.changedTimestamp, be.posterData,
	cm.posterData as cmPosterData, cm.changedDate as cmChangedDate, cm.changedTimestamp as cmChangedTimestamp
FROM
	bugtracker_entries AS be
	LEFT JOIN bugtracker_comments AS cm ON be.id = cm.bug_entry
WHERE
	matpath like "%'.$matpath.'%"
ORDER BY
	cm.changedTimestamp DESC, be.changedTimestamp DESC';

        // Execute the query
        $query = $this->db->query($sql);

        if($query->num_rows() > 0){
            $results = $query->result_array();

            $bugs = array();

            foreach($results as $row){
                $comment = array();

                $lastChange = $row['changedDate'];

                $posterData = json_decode($row['posterData'], true);

                $by = array(
                    'type' => 'created',
                    'name' => (isset($posterData['name'])) ? $posterData['name']: '',
                    'gm' => (isset($posterData['gm']) && (bool) $posterData['gm']) ? true : false,
                );

                // Has a comment
                if(!empty($row['cmChangedDate']) && $row['cmChangedDate'] > 0){
                    $cmPosterData = json_decode($row['cmPosterData'], true);

                    if($row['cmChangedTimestamp'] > $row['changedTimestamp']){
                        $lastChange = $row['cmChangedTimestamp'];
                        $by = array(
                            'type' => 'commented',
                            'name' => $cmPosterData['name'],
                            'gm' => (isset($cmPosterData['gm']) && $cmPosterData['gm']) ? true : false,
                        );
                        /*if($row['id'] == 224){
                            debug("comment is fresher!", $lastChange);
                        }*/
                    }

                    $comment = array(
                        'changedDate' => $row['cmChangedDate'],
                        'changedTimestamp' => $row['cmChangedTimestamp'],
                        'gm' => (isset($cmPosterData['gm']) && $cmPosterData['gm']) ? true : false,
                        'name' => $cmPosterData['name'],
                        'class' => (!empty($cmPosterData['class'])) ? $cmPosterData['class'] : '',
                    );

                    /*if($row['id'] == 224){
                        debug("comment",$comment);
                    }*/
                }
                unset($row['cmChangedDate'], $row['cmChangedTimestamp'], $row['cmPosterData']);

                // Not in list yet?
                if(empty($bugs[$row['id']])){
                    $bugs[$row['id']] = $row;
                    $bugs[$row['id']]['lastChange'] = $lastChange;
                    $bugs[$row['id']]['by'] = $by;
                    $bugs[$row['id']]['lastComment'] = $comment;
                    $bugs[$row['id']]['commentCount'] = (count($comment)) ? 1 : 0;
                    /*if($row['id'] == 224){
                        debug("row", $row);
                        debug("first appearance",$bugs[$row['id']]);
                    }*/
                }
                // Is in list but we got another comment to check
                elseif(count($comment) > 0){
                    $bugs[$row['id']]['commentCount']++;

                    // Has no comment listed yet?
                    if(count($bugs[$row['id']]['lastComment']) == 0){
                        $bugs[$row['id']]['lastChange'] = $lastChange;
                        $bugs[$row['id']]['by'] = $by;
                        $bugs[$row['id']]['lastComment'] = $comment;

                        /*if($row['id'] == 224){
                            debug("first appearance with comment",$bugs[$row['id']]);
                        }*/
                    }
                    // but this comment was more recent?
                    elseif($bugs[$row['id']]['lastComment']['changedTimestamp'] < $comment['changedTimestamp']){
                        $bugs[$row['id']]['lastChange'] = $lastChange;
                        $bugs[$row['id']]['by'] = $by;
                        $bugs[$row['id']]['lastComment'] = $comment;
                        /*if($row['id'] == 224){
                            debug("another appearance",$bugs[$row['id']]);
                        }*/
                    }
                }

                if($bugs[$row['id']]['lastChange'] > 0){
                    $bugs[$row['id']]['changedDate'] = strftime("%d.%m.%Y %H:%M:%S", (int) $bugs[$row['id']]['lastChange']);
                    $bugs[$row['id']]['changedTimestamp'] = (int) $bugs[$row['id']]['lastChange'];
                }
            }

            return $bugs;
        }
        else{
            return array();
        }
    }

    public function importOldBugs(){
        $query = $this->db->select('*')
            ->from('bug')->get();

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $row){
                $bugId = $row['id'];

                if($this->bugExists($bugId)){
                    continue;
                }

                echo '<br>Bug Ticket #'.$bugId.': ';

                /**
                 * Project ID
                 */
                $project = $this->defaultProject;

                switch($row['class']){
                    case '[Quest]':
                        $project = 5;
                        break;
                    case '[Instanz]':
                        $project = 9;
                        break;
                    case '[NPC]':
                        $project = 43;
                        break;
                    case '[Erfolg]':
                        $project = 11;
                        break;
                    case '[Item]':
                        $project = 45;
                        break;
                    case '[Homepage]':
                        $project = $this->defaultHomepageProject;
                        break;
                    case '[Charakter]':
                        $project = 49;
                        break;
                    case '[Charakter/Hexenmeister]':
                        $project = 31;
                        break;
                    case '[Charakter/Jäger]':
                    case '[Charakter/JÃ¤ger]':
                        $project = 25;
                        break;
                    case '[Charakter/Krieger]':
                        $project = 23;
                        break;
                    case '[Charakter/Magier]':
                        $project = 30;
                        break;
                    case '[Charakter/Paladin]':
                        $project = 24;
                        break;
                    case '[Charakter/Priester]':
                        $project = 27;
                        break;
                    case '[Charakter/Schamane]':
                        $project = 29;
                        break;
                    case '[Charakter/Schurke]':
                        $project = 26;
                        break;
                    case '[Charakter/Todesritter]':
                        $project = 28;
                        break;
                    case '[Charakter/Druide]':
                        $project = 32;
                        break;
                }

                // Spezialfall Jäger
                if(substr_count($row['class'], 'Charakter/J') > 0){
                    $project = 25;
                }

                /**
                 * Materialized Category Path
                 */
                switch($project){
                    case '[Quest]':
                        $parents = array(1);
                        break;
                    case '[Instanz]':
                        $parents = array(1);
                        break;
                    case '[NPC]':
                        $parents = array(1);
                        break;
                    case '[Erfolg]':
                        $parents = array(1);
                        break;
                    case '[Item]':
                        $parents = array(1);
                        break;
                    case '[Homepage]':
                        $parents = array(1);
                        break;
                    case '[Charakter]':
                        $parents = array(1,7);
                        break;
                    case '[Charakter/Hexenmeister]':
                    case '[Charakter/Jäger]':
                    case '[Charakter/Krieger]':
                    case '[Charakter/Magier]':
                    case '[Charakter/Paladin]':
                    case '[Charakter/Priester]':
                    case '[Charakter/Schamane]':
                    case '[Charakter/Schurke]':
                    case '[Charakter/Todesritter]':
                    case '[Charakter/Druide]':
                        $parents = array(1,7);
                        break;
                    default:
                        $parents = array(1);
                }

                $parents[] = $project;
                $path = array();

                foreach($parents as $par){
                    $path[] = str_pad($par, 4, '0', STR_PAD_LEFT);
                }
                $matpath = implode('.',$path);

                /**
                 * Bug State
                 */
                $state = BUGSTATE_OPEN;

                switch($row['state']){
                    case 'Offen':
                        $state = BUGSTATE_OPEN; break;
                    case 'Bearbeitung':
                        $state = BUGSTATE_ACTIVE; break;
                    case 'nicht umsetzbar':
                        $state = BUGSTATE_REJECTED; break;
                    case 'Erledigt':
                        $state = BUGSTATE_DONE; break;
                    case 'Abgewiesen':
                        $state = BUGSTATE_REJECTED; break;
                }

                /*
                 * WoW ID
                 */
                $link = $row['link'];
                $wowId = 0;

                if(empty($row['link']) || $link == '-' || $link == 'Hier den Link von de.wowhead.com eintragen.'){
                    $link = '';
                }
                else{
                    if(preg_match("/[^\d]*(\d+)/", $link, $matches)){
                        $wowId = $matches[1];
                    }
                }

                $dateCreated = $row['date'];
                $dateDone = $row['date2'];
                $dateChanged = $dateDone;
                $dateCreatedTS = 0;
                $dateChangedTS = 0;

                if(!empty($dateCreated)){
                    $dateArray = explode('.', $dateCreated);
                    $date = new DateTime($dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0]);
                    $dateCreatedTS = $date->getTimestamp();
                }
                if(!empty($dateChanged)){
                    $dateArray = explode('.', $dateChanged);
                    $date = new DateTime($dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0]);
                    $dateChangedTS = $date->getTimestamp();
                }

                $data = array(
                    'id' => $row['id'],
                    'project' => $project,
                    'bug_state' => $state,
                    'priority' => BUGPRIORITY_MINOR,
                    'title' => $row['title'],
                    'desc' => $row['desc'],
                    'matpath' => $matpath,
                    'posterData' => $row['posterData'],
                    'posterAccountId' => $row['posterAccountId'],
                    'link' => json_encode(array($link)),
                    //'wowId' => $wowId,
                    'createdDate' => $dateCreated,
                    'createdTimestamp' => $dateCreatedTS,
                    'changedDate' => $dateChanged,
                    'changedTimestamp' => $dateChangedTS,

                );

                $this->db->insert($this->tableName, $data);

                echo ' <b>Importiert</b>';
            }
        }
    }

    public function importOldComments(){
        $query = $this->db->select('*')
            ->from('kommentar')->get();

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $row){

                if($this->commentExists($row['id'])){
                    continue;
                }

                echo '<br>Bug Comment #'.$row['id'].': ';

                $changedTimestamp = $row['changedTimestamp'];

                if(!empty($changedTimestamp) && $changedTimestamp != 0){
                    $changedDate = strftime("%d.%m.%Y %H:%M:%S", $changedTimestamp);
                }
                else{
                    $changedTimestamp = $row['timestamp'];
                    $changedDate = strftime("%d.%m.%Y %H:%M:%S", $row['timestamp']);
                }

                $posterData = json_decode($row['posterData'],true);

                if(empty($posterData['name'])){
                    echo " PosterData Empty: ".$posterData;
                    $posterData = array(
                        'name' => $row['name'],
                    );
                }

                if(!empty($row['action'])){
                    $action = json_decode($row['action'], true);

                    if(isset($action['state'])){
                        switch($action['state']){
                            case 'Offen':
                                $action['state'] = BUGSTATE_OPEN;
                                break;
                            case 'Bearbeitung':
                                $action['state'] = BUGSTATE_ACTIVE;
                                break;
                            case 'Workaround':
                                $action['state'] = BUGSTATE_WORKAROUND;
                                break;
                            case 'Erledigt':
                                $action['state'] = BUGSTATE_DONE;
                                break;
                            case 'Abgewiesen':
                                $action['state'] = BUGSTATE_REJECTED;
                                break;
                        }
                        $row['action'] = json_encode($action);
                    }
                    else{
                        $row['action'] = "";
                    }

                }

                $data = array(
                    'id' => $row['id'],
                    'bug_entry' => $row['postid'],
                    'text' => $row['text'],
                    'action' => $row['action'],
                    'changedActions' => $row['actions'],
                    'posterAccountId' => $row['posterAccountId'],
                    'posterData' => json_encode($posterData),
                    'createdTimestamp' => $row['timestamp'],
                    'createdDate' => strftime("%d.%m.%Y %H:%M:%S", $row['timestamp']),
                    'changedTimestamp' => $changedTimestamp,
                    'changedDate' => $changedDate,

                );

                $this->db->insert('bugtracker_comments', $data);

                echo ' <b>Importiert</b>';
            }
        }

    }

    /**
     * Count how many Bugs a project has
     * @param $projectId
     * @param int $type
     */
    public function getBugCountByProject($projectId = 0, $type = FALSE){

        $this->db->select('count(bug_state) as count, bug_state');

        if(is_array($projectId)){
            $this->db->where_in('project',$projectId);
        }
        elseif($projectId !== 0){
            $this->db->where('project', $projectId);
        }

        if($type === FALSE){
            $this->db
                //->where_in('bug_state', array(BUGSTATE_DONE, BUGSTATE_ACTIVE, BUGSTATE_OPEN))
                ->group_by('bug_state');
            $query = $this->db->from($this->tableName);
            $results = $query->get()->result_array();

            if(count($results) > 0){
                $data = array();
                foreach($results as $row){
                    $data[$row['bug_state']] = $row['count'];
                }
            }

            $data[BUGSTATE_DONE] = empty($data[BUGSTATE_DONE]) ? 0 : $data[BUGSTATE_DONE] * 1;
            $data[BUGSTATE_ACTIVE] = empty($data[BUGSTATE_ACTIVE]) ? 0 : $data[BUGSTATE_ACTIVE] * 1;
            $data[BUGSTATE_OPEN] = empty($data[BUGSTATE_OPEN]) ? 0 : $data[BUGSTATE_OPEN] * 1;
            $data[BUGSTATE_REJECTED] = empty($data[BUGSTATE_REJECTED]) ? 0 : $data[BUGSTATE_REJECTED] * 1;
            $data[BUGSTATE_WORKAROUND] = empty($data[BUGSTATE_WORKAROUND]) ? 0 : $data[BUGSTATE_WORKAROUND] * 1;
            $data[BUGSTATE_ALL] =
                $data[BUGSTATE_DONE] +
                $data[BUGSTATE_ACTIVE] +
                $data[BUGSTATE_OPEN] +
                $data[BUGSTATE_REJECTED] +
                $data[BUGSTATE_WORKAROUND];

            return $data;
        }
        else{
            $this->db->where('bug_state', $type);
            $query = $this->db->from($this->tableName);
            $results = $query->get()->result_array();

            if(count($results) > 0){
                return $results[0]['count'];
            }
            return 0;
        }

    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM {$this->tableName} WHERE id=?", array($id));
    }

    public function create($project, $priority, $title, $desc, $links)
    {
        requirePermission("canCreateBugs");

        $this->load->model('project_model');

        $activeChar = $this->user->getActiveCharacterData();

        if($activeChar == false){
            return false;
        }

        $posterData = $this->user->getCharacterData($this->user->getActiveRealmId(), $activeChar, $this->user->isGm());

        $matPath = $this->project_model->getMaterializedPath($project);

        $data = array(
            'project' => $project,
            'priority' => $priority,
            'bug_state' => BUGSTATE_OPEN,
            'matpath' => $matPath,

            'title' => $title,
            'desc' => $desc,
            'link' => json_encode($links),

            'posterData' => json_encode($posterData),
            'posterAccountId' => $this->user->getId(),

            'createdDate' => strftime("%d.%m.%Y %H:%M:%S"),
            'createdTimestamp' => time(),

            'changedDate' => strftime("%d.%m.%Y %H:%M:%S"),
            'changedTimestamp' => time(),
        );

        $this->db->insert($this->tableName, $data);

        return $this->db->insert_id();
    }

    public function createComment($bugId, $commentText, $action){

        $activeChar = $this->user->getActiveCharacterData();

        // Gather Poster Data of current user
        $posterData = $this->user->getCharacterData($this->user->getActiveRealmId(), $activeChar, $this->user->isStaff());

        $data = array(
            'bug_entry' => $bugId,
            'text' => $commentText,
            'action' => json_encode($action),

            'posterData' => json_encode($posterData),
            'posterAccountId' => $this->user->getId(),

            'createdDate' => strftime("%d.%m.%Y %H:%M:%S"),
            'createdTimestamp' => time(),

            'changedDate' => strftime("%d.%m.%Y %H:%M:%S"),
            'changedTimestamp' => time(),
        );

        $this->db->insert('bugtracker_comments', $data);

        return $this->db->insert_id();

    }

    public function update($bugId, $project, $priority, $bugState, $title, $desc, $links)
    {
        requirePermission("canEditBugs");

        $activeChar = $this->user->getActiveCharacterData();


        if($activeChar == false){
            return false;
        }

        // The current data
        $bug = $this->getBug($bugId);

        $action = array(
            'change' => true,
        );

        $data = array(
            'changedDate' => strftime("%d.%m.%Y %H:%M:%S"),
            'changedTimestamp' => time(),
        );

        if($project != $bug['project']){
            $action['project'] = array(
                'old' => $bug['project'],
                'new' => $project,
            );

            // Calculate matPath in case the project changed
            $matPath = $this->project_model->getMaterializedPath($project);

            $data['project'] = $project;
            $data['matpath'] = $matPath;
        }

        if($priority != $bug['priority']){
            $action['priority'] = array(
                'old' => $bug['priority'],
                'new' => $priority,
            );
            $data['priority'] = $priority;
        }

        if($bugState != $bug['bug_state']){
            $action['state'] = array(
                'old' => $bug['bug_state'],
                'new' => $bugState,
            );
            $data['bug_state'] = $bugState;
        }

        if($title != $bug['title']){
            $action['title'] = array(
                'old' => $bug['title'],
                'new' => $title,
            );
            $data['title'] = $title;
        }

        if($desc != $bug['desc']){
            $action['desc'] = array(
                'old' => $bug['desc'],
                'new' => $desc,
            );
            $data['desc'] = $desc;
        }

        $links = json_encode($links);
        if($links != $bug['link']){
            $action['link'] = array(
                'old' => $bug['link'],
                'new' => $links,
            );
            $data['link'] = $links;
        }

        $this->db->where('id', $bugId);
        $this->db->update($this->tableName, $data);

        /*
         * Create new comment
         */
        $this->createComment($bugId, "", $action);

        return true;
    }

    public function updateState($bugId, $newState)
    {
        $data = array(
            'bug_state' => $newState,
        );

        $this->db->where('id', $bugId);
        $this->db->update('bugtracker_entries', $data);
    }

    public function updateMatPathByProject($projectId, $matpath){
        $data = array(
            'matpath' => $matpath,
        );

        $this->db
            ->where('project', $projectId)
            ->update('bugtracker_entries', $data);

    }

    public function updateComment($commentId, $commentActions, $newCommentText){

        if(!empty($commentActions) && !is_array($commentActions)){
            $commentActions = json_decode($commentActions,true);
        }
        $commentActions[] = array(
            "action" => "change",
            "user" => $this->user->getId(),
            "name" => $this->user->getNickname(),
            "gm" => $this->user->isStaff(),
            "ts" => time(),
        );

        $data = array(
            'text' => $newCommentText,
            'changedActions' => json_encode($commentActions),
            'changedTimestamp' => time(),
            'changedDate' => strftime("%d.%m.%Y %H:%M:%S", time()),
        );

        $this->db
            ->where('id', $commentId)
            ->update('bugtracker_comments', $data);

    }

    public function getBug($id)
    {
        $this->db->select('*')->where('id', $id)->from($this->tableName);
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

        $this->db->select('*')->where('bug_entry', $bugId)->order_by('id', 'asc')->from('bugtracker_comments');

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return array();
    }

    /**
     * @param $type
     * @return string
     */
    public function getTypeLabel($type){
        return '';  // TODO System umschreiben auf administrierbare Bug-Kategorien
    }

    public function getPriorityCssClass($priority){
        switch($priority){
            case BUGPRIORITY_TRIVIAL:
                return 'icon-trivial';
                break;
            case BUGPRIORITY_MINOR:
                return 'icon-minor';
                break;
            case BUGPRIORITY_MAJOR:
                return 'icon-major';
                break;
            case BUGPRIORITY_CRITICAL:
                return 'icon-critical';
                break;
            case BUGPRIORITY_BLOCKER:
                return 'icon-blocker';
                break;
            default:
                return 'icon-trivial';
        }
    }

    public function getStateLabel($type){
        return (empty($this->availableBugStates[$type])) ? '' : $this->availableBugStates[$type];
    }

    public function getPriorityLabel($priority){
        switch($priority){
            case BUGPRIORITY_TRIVIAL:
            case BUGPRIORITY_MINOR:
            case BUGPRIORITY_MAJOR:
            case BUGPRIORITY_CRITICAL:
            case BUGPRIORITY_BLOCKER:
                return $this->priorityLabels[$priority];
                break;
        }
    }

    public function getComment($commentId, $select = '*'){
        $query = $this->db->select($select)
            ->from('bugtracker_comments')
            ->where('id', $commentId)
            ->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        else{
            return false;
        }
    }

    public function findSimilarBugs($search, $bugId = 0){
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

    /**
     * Checks if a bug exists
     * @param $bugId
     * @return bool
     */
    private function bugExists($bugId){
        $this->db->select('id')->where('id', $bugId)->from($this->tableName);

        $count = $this->db->count_all_results();

        if($count > 0){
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Checks if a comment exists
     * @param $commentId
     * @return bool
     */
    private function commentExists($commentId){
        $this->db->select('id')->where('id', $commentId)->from('bugtracker_comments');

        $count = $this->db->count_all_results();

        if($count > 0){
            return TRUE;
        }
        return FALSE;
    }
}