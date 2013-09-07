<?php

class Bugtracker extends MX_Controller{
    
    private $css = array();
    private $moduleTitle = 'Bugtracker';
    private $modulePath = 'modules/bugtracker/';
    
    public function __construct(){
        //Call the constructor of MX_Controller
        parent::__construct();
        
        requirePermission('view');

        // Dummys
        if(false){
            $this->bug_model = new Bug_model();
            $this->project_model = new Project_Model();
            $this->template = new Template();
            $this->external_account_model = new External_account_model();
        }
        
        $this->load->model('bug_model');
        $this->load->model('project_model');
        $this->load->helper('string');

        // Realm DB
        $this->connection = $this->external_account_model->getConnection();
        
        // Breadcrumbs
        $this->template->addBreadcrumb('Server', site_url('server/index'));
        $this->template->addBreadcrumb('Bugtracker', site_url('bugtracker/index'));

        $this->template->setJsAction('bugtracker');

        $this->template->hideSidebar();
    }

    /**
     * Shows all Bug Projects
     */
    public function index(){
        requirePermission('view');

        $this->template->setTitle($this->moduleTitle);
        $this->template->setSectionTitle($this->moduleTitle);
        $projectList = $this->project_model->getProjects();
        $projectCount = count($projectList);

        $projectChoices = array();
        $baseProjects = array();
        $projectsByParent = array();


        foreach($projectList as $l0project){

            $l0key = $l0project['id'];

            $projectData = $this->project_model->getAllProjectData($l0key, $l0project);

            $l0project['counts'] = array(
                'open' => $projectData['counts'][BUGSTATE_OPEN],
                'active' => $projectData['counts'][BUGSTATE_ACTIVE],
                'done' => $projectData['counts'][BUGSTATE_DONE],
                'all' => $projectData['counts']['all'],
                'percentage' => array(
                    'done' => $projectData['counts']['percentage'][BUGSTATE_DONE],
                    'active' => $projectData['counts']['percentage'][BUGSTATE_ACTIVE],
                ),
            );

            // Icons
            if(!empty($l0project['icon'])){
                $iconPath = $l0project['icon'];
                if(substr_count($iconPath, 'patch') > 0){
                    $iconPath = 'images/icons/patch/'.$iconPath.'.jpg';
                }
                else{
                    $iconPath = 'images/icons/36/'.$iconPath.'.jpg';
                }

                $localPath = APPPATH.'themes/'.$this->template->theme.'/'.$iconPath;
                $webPath = base_url().$localPath;
                //debug('local', $localPath);

                $l0project['icon'] = file_exists($localPath)
                    ? $webPath
                    : base_url().'themes/'.$this->template->theme.'/'.'images/icons/36/ability_creature_cursed_02.grey.jpg?'.$iconPath;
            }

            if($l0project['parent'] != 0){
                $projectsByParent[$l0project['parent']][$l0project['id']] = $l0project;
            }
            else{
                $baseProjects[$l0project['id']] = $l0project;
            }
        }

        // Level 0 Projects
        foreach($baseProjects as $l0key => $l0project){

            //$projectChoices[$l0key] = $l0project['title'];
            //debug('Level 0', $l0project);
            /*$l0all = $l0project['counts']['all'];
            $l0done = $l0project['counts']['done'];
            $l0open = $l0project['counts']['open'];*/
            $l1projects = array();

            if(!empty($projectsByParent[$l0key])){

                // Level 1 Projects of this project
                $l1projects = $projectsByParent[$l0key];

                // Foreach level 1 Project of this Level 1 project
                foreach($l1projects as $l1key => $l1project){

                    //debug('L1',$l1project);

                    $l1all = $l1project['counts']['all'];
                    $l1done = $l1project['counts']['done'];
                    $l1open = $l1project['counts']['open'];
                    $l2projects = array();

                    if(!empty($projectsByParent[$l1key])){

                        // Level 2 Projects
                        $l2projects = $projectsByParent[$l1key];

                        foreach($l2projects as $l2key => $l2project){
                            // Add 'done' and 'all' counts to the Level-1
                            $l1done += $l2project['counts']['done'];
                            $l1all += $l2project['counts']['all'];
                            $l1open += $l2project['counts']['open'];
                        }

                        // Save L2 back to L1 stack
                        $l1projects[$l1key]['projects'] = $l2projects;
                    }

                    $l1projects[$l1key]['projects'] = $l2projects;
                    $l1projects[$l1key]['counts']['all'] = $l1all;
                    $l1projects[$l1key]['counts']['done'] = $l1done;
                    $l1projects[$l1key]['counts']['open'] = $l1open;
                    if($l1all > 0){
                        //debug('L1 '.$l1project['title'], '$l1done/$l1all');
                        $l1projects[$l1key]['counts']['percentage']['done'] = round($l1done/$l1all*100);
                    }

                    // Add 'done' and 'all' counts to the L0
                    /*$l0done += $l1projects[$l1key]['counts']['done'];
                    $l0open += $l1projects[$l1key]['counts']['open'];
                    $l0all += $l1projects[$l1key]['counts']['all'];*/


                }

                /*$baseProjects[$l0key]['counts']['done'] = $l0done;
                $baseProjects[$l0key]['counts']['all'] = $l0all;
                $baseProjects[$l0key]['counts']['open'] = $l0open;*/
                $all = $l0project['counts']['all'];
                $done = $l0project['counts']['done'];
                //$baseProjects[$l0key]['counts']['percentage']['done'] = ($all > 0) ? round($done/$all*100) : 100;


                // Save L1 back to L0 stack (Base)
                $baseProjects[$l0key]['projects'] = $l1projects;
            }

        }

        //debug('base', $baseProjects);


        // Prepare my data
        $templateData = array(
            'url' => $this->template->page_url,
            'projects' => $baseProjects,
            'projectCount' => $projectCount,
            'projectChoices' => $projectChoices,
        );

        // Load my view
        $out = $this->template->loadPage('project_list.tpl', $templateData);

        $this->template->view($out, $this->css);
    }

    /**
     * Show all Bugs of a project
     * @param $projectId
     */
    public function buglist($projectId){

        requirePermission('view');

        $project = $this->project_model->getProjectById($projectId, 'id,title,matpath');

        if(!$project){
            show_error("Das Bugtracker Projekt wurde nicht gefunden.");
            return;
        }
        $searchProjects = array($projectId);

        $this->template->setTitle($project['title']." - ".$this->moduleTitle);
        $this->template->setSectionTitle($this->moduleTitle.": ".$project['title']);

        $projects = array(
            $projectId => $project
        );

        $subProjects = $this->project_model->getSubProjects($projectId);

        if($subProjects){

            $subProjectIds = array();

            foreach($subProjects as $sub){
                $subProjectIds[] = $sub['id'];
                $projects[$sub['id']] = $sub;
            }

            $searchProjects = array_merge($searchProjects, $subProjectIds);
        }

        /*
         * Breadcrumbs via MatPath
         */
        $projectPath = explode('.',$project['matpath']);

        while(count($projectPath)){
            $currentBreadcrumb = array_pop($projectPath)*1;
            if($currentBreadcrumb == $projectId){
                $this->template->addBreadcrumb($project['title']);
            }
            else{
                $currentParent = $this->project_model->getProjectById($currentBreadcrumb, 'id,title');
                if($currentParent){
                    $this->template->addBreadcrumb($currentParent['title'], site_url('bugtracker/buglist/'.$currentParent['id']));
                }
            }
        }

        $this->template->enable_profiler(true);

        $bugRows = $this->bug_model->getBugsByProject($projectId, 'none');

        foreach($bugRows as $i => $row){
            $row['title'] = htmlentities($row['title'], ENT_QUOTES, 'UTF-8');

            $row['css'] = '';

            $row['changedSort'] = strftime('%Y-%m-%d', $row['changedTimestamp']);

            $row['type_string'] = $projects[$row['project']]['title'];

            $row['priorityClass'] = $this->bug_model->getPriorityCssClass($row['priority']);
            $row['priorityLabel'] = $this->bug_model->getPriorityLabel($row['priority']);


            switch($row['bug_state']){
                case BUGSTATE_DONE:
                    $row['css'] = 'done';
                    break;
                case BUGSTATE_ACTIVE:
                    $row['css'] = 'inprogress';
                    break;
                case BUGSTATE_REJECTED:
                    $row['css'] = 'disabled';
                    break;
                case BUGSTATE_OPEN:
                default:
                    $row['css'] = 'fresh';
                    $row['status'] = 0;
                    break;
            }

            $bugRows[$i] = $row;

        }

        $page_data = array(
            'module' => 'bugtracker',
            'bugRows' => $bugRows,
            'rowCount' => count($bugRows),
            'rowMax' => min($bugRows,50),
            'rowMin' => ($bugRows == 0) ? 0 : 1,
            'js_path' => $this->template->js_path,
            'image_path' => $this->template->image_path,
        );

        $out = $this->template->loadPage('list.tpl', $page_data);

        $this->template->view($out, $this->css);
    }

    /**
     * Show detail page for a bug
     * @param $bugId
     */
    public function bug($bugId){
        requirePermission('view');


        if(!is_numeric($bugId)){
            show_404('Dieser Link ist ungültig');
            return;
        }

        $bug = $this->bug_model->getBug($bugId);

        if($bug === false){
            show_error('Der gesuchte Bug wurde nicht gefunden.');
            return;
        }

        /*
         * Title
         */
        $title = htmlentities($bug['title'], ENT_QUOTES, 'UTF-8');

        $this->template->setTitle('Bug #'.$bugId);
        $this->template->setSectionTitle('Bug #'.$bugId.' '.$title);

        /**
         * Project
         */
        $project = $bug['project'];

        $matpath = explode('.', $bug['matpath']);
        $baseProjectId = $matpath[0]*1;

        foreach($matpath as $pathId){
            $pathId *= 1;
            $pathProject = $this->project_model->getProjectById($pathId, 'id,title');

            // Add Breadcrumb
            $this->template->addBreadcrumb($pathProject['title'], site_url('bugtracker/buglist/'.$pathProject['id']));
        }

        // Last Breadcrumb for the actual Bug
        $this->template->addBreadcrumb('Bug #'.$bugId, site_url('bugtracker/bug/'.$bugId));

        /*
         * Description
         */
        $desc = $bug['desc'];
        // Find links in the description
        $desc = htmlentities($desc, ENT_QUOTES, 'UTF-8');
        $desc = makeWowheadLinks($desc);

        /**
         * Similar Bugs
         * @type {Array}
         */
        $similarBugs = array();

        /*
         * Links
         */
        $links = json_decode($bug['link'], true);

        $bugLinks = array();

        $openwowPrefix = $this->project_model->getOpenwowPrefix($baseProjectId);

        foreach($links as $link){
            if(empty($link) || substr_count($link, 'Hier den') > 0){
                continue;
            }

            if(preg_match('@http://(de|www|old|wotlk|cata).(wowhead|openwow|buffed).(com|de)/\??([^=]+)=(\d+).*@i', $link, $matches)){

                $linkType = $matches[4];
                $linkId = $matches[5];

                $search = $linkType.'='.$linkId;
                $searchLabel = ucfirst($linkType).': '.$linkId;

                // Wowhead
                $bugLinks[] = array(
                    'url' => 'http://de.wowhead.com/'.$linkType.'='.$linkId,
                    'label' => '[DE] Wowhead - '.$searchLabel,
                );

                // Openwow
                $bugLinks[] = array(
                    'url' => 'http://'.$openwowPrefix.'.openwow.com/'.$linkType.'='.$linkId,
                    'label' => '['.strtoupper($openwowPrefix).'] Openwow - '.$searchLabel,
                );

                // Alive
                if( $linkType == 'zone' ){
                    $bugLinks[] = array(
                        'url' => 'http://portal.wow-alive.de/game/zone/'.$linkId,
                        'label' => 'Alive - '.$searchLabel,
                    );
                }
                if( $linkType == 'item' ){
                    $bugLinks[] = array(
                        'url' => 'http://portal.wow-alive.de/item/'.$linkId,
                        'label' => 'Alive - '.$searchLabel,
                    );
                }

                // Similar Bugs for this link
                $linkSimilarBugs = $this->bug_model->findSimilarBugs($search, $bugId);

                foreach($linkSimilarBugs as $sim){
                    $similarBugs[] = array(
                        'url' => '/bugtracker/bug/'.$sim['id'],
                        'label' => 'Bug #'.$sim['id'].' - '.htmlentities($sim['title']),
                    );
                }
            }
            else{
                $bugLinks[] = array(
                    'url' => $link,
                    'label' => $link,
                );
            }
        }

        /*
         * Bug State
         */
        $state = $bug['bug_state'];

        switch($state){
            case BUGSTATE_DONE:
                $cssState = 'color-q2'; break;
            case BUGSTATE_OPEN:
            case BUGSTATE_ACTIVE:
                $cssState = 'color-q1'; break;
            case BUGSTATE_REJECTED:
                $cssState = 'color-q0'; break;
        }

        $stateLabel = $this->bug_model->getStateLabel($state);

        /*
         * Bug Priority
         */
        $priority = $bug['priority'];
        $priorityLabel = $this->bug_model->getPriorityLabel($priority);
        $priorityClass = $this->bug_model->getPriorityCssClass($priority);

        /*
         * Dates
         */
        $createdDate = $bug['createdDate'];
        $changedDate = $bug['changedDate'];

        /**
         * Time difference since creation
         */
        $createdDetail = '';
        if($bug['createdTimestamp'] > 0){
            $createdDetail = sec_to_dhms( time() - $bug['createdTimestamp'], true);
            if(!empty($createdDetail))
                $createdDetail = 'vor '.$createdDetail;
        }

        /**
         * Time difference since last change
         */
        $changedDetail = '';
        if($bug['changedTimestamp'] > 0){
            $changedDetail = sec_to_dhms( time() - $bug['changedTimestamp'], true);
            if(!empty($changedDetail))
                $changedDetail = 'vor '.$changedDetail;
        }

        /**
         * Log of all actions
         * @type {Array}
         */
        $bugLog = array();
        $accountComments = array();

        if(!empty($bug['posterData'])){
            $posterData = json_decode($bug['posterData']);
            //debug('posterData',$posterData);

            $bugPoster = array(
                'details' => true,
                'name' => $posterData->name,
                'class' => $posterData->class,
                'url' => '',    // TODO Link zur Armory integrieren
            );
        }
        else{
            $bugPoster = array(
                'details' => false,
            );
        }

        $commentRows = $this->bug_model->getBugComments($bugId);

        $commentCounter = 1;
        //$rowclass = 'row1';

        foreach($commentRows as $i => $row){
            $actionLog = array();

            $commentPoster = json_decode($row['posterData']);

            // State changes
            if(!empty($row['action'])){
                $actions = json_decode($row['action']);
                if(isset($actions->state)){
                    $actionLog[] = 'Status => '.$this->bug_model->getStateLabel($actions->state);
                }
            }
            // Content changes
            if(!empty($row['changedActions'])){
                $actions = json_decode($row['changedActions']);
                $lastEdit = '';
                foreach($actions as $action){
                    if($action->action == 'change'){
                        $name = ($action->gm) ? '[GM] '.$action->name : $action->name;
                        $lastEdit = '<span class="time">von '.$name.' bearbeitet vor <span data-tooltip="'.strftime('%d.%m.%Y',$action->ts).'">'.sec_to_dhms(time()-$action->ts,true).'</span></span>';
                    }
                }
                if(!empty($lastEdit)){
                    $actionLog[] = $lastEdit;
                }
            }

            if($row['posterAccountId'] == $this->user->getId() || hasPermission('canEditComments')){
                $canEditThisComment = true;
            }
            else{
                $canEditThisComment = false;
            }
            $commentRow = array(
                'id' => $row['id'],
                'n' => $commentCounter++,
                'isStaff' => false,
                'posterDetails' => false,
                'avatar' => false,
                'action' => $actionLog,
                'lastEdit' => '',
                'text' => nl2br(makeWowheadLinks(htmlentities($row['text'], ENT_QUOTES, 'UTF-8'))),
                'createdDetail' => ($row['createdTimestamp'] > 0) ? 'vor '.sec_to_dhms(time()-$row['createdTimestamp'],true):'',
                'canEditThisComment' => $canEditThisComment,
            );

            $posterData = json_decode($row['posterData']);

            if(isset($posterData->class)){

                if(empty($posterData->realmId)){
                    $posterData->realmId = 1;
                }

                $commentRow['avatar'] = $this->realms->formatAvatarPath(array(
                    'class' => $posterData->class,
                    'race' => $posterData->race,
                    'gender' => $posterData->gender,
                    'level' => $posterData->level
                ));

                $commentRow['posterDetails'] = true;
                $commentRow['char_url'] = $this->realms->getArmoryUrl($posterData->name, $posterData->realmId);
                $commentRow['char_class'] = $posterData->class;
            }

            if(isset($posterData->name)){
                $commentRow['name'] = $posterData->name;
            }

            // Comment By GameMaster
            if($posterData->gm){
                $commentRow['isStaff'] = true;
            }
            elseif(!empty($row['posterAccountId'])){

                $rank = $this->external_account_model->getRank($row['posterAccountId']);

                if($rank){
                    $commentRow['isStaff'] = true;
                }
            }
            //debug($commentRow);

            $bugLog[$row['createdTimestamp']] = $commentRow;

        }

        //debug($bugLog);

        // Combine Actions and Comments (later)
        $bugActionLog = array();

        if(!empty($bug['actions'])){
            $actions = json_decode($bug['actions']);

            foreach($actions as $action){
                $ts = $action->ts;
                $bugLog[$ts] = '<span class="time">'.sec_to_dhms(time()-$ts, true, 'vor ').'</span> '.$action->name.' => Bug Report bearbeitet';
            }

        }

        ksort($bugLog);

        /*
         * User Specific
         */
        $activeCharGuid = $this->user->getActiveCharacter();
        $activeRealmId = $this->user->getActiveRealmId();

        if($activeCharGuid > 0){
            $activeCharacter = $this->user->getActiveCharacterData();
            $activeCharacter['active'] = true;
            $activeCharacter['url'] = $this->realms->getArmoryUrl($activeCharacter['name'], $activeRealmId);
            $activeCharacter['avatar'] = $this->realms->formatAvatarPath($activeCharacter);
        }
        else{
            $activeCharacter = array(
                'active' => false,
            );
        }

        /*
         * Template Generation
         */
        $page_data = array(
            'module' => 'bugtracker',
            'canEditBugs' => hasPermission('canEditBugs'),
            'bugId' => $bugId,
            'bugStates' => $this->bug_model->getBugStates(),
            'typeString' => '',

            'title' => $title,

            'state' => $state,
            'stateLabel' => $this->bug_model->getStateLabel($state),
            'cssState' => $cssState,

            'priority' => $priority,
            'priorityLabel' => $priorityLabel,
            'priorityClass' => $priorityClass,

            'createdDate' => $createdDate,
            'createdDetail' => $createdDetail,
            'changedDate' => $changedDate,
            'changedDetail' => $changedDetail,
            'links' => $bugLinks,
            'bugPoster' => $bugPoster,
            'desc' => nl2br($desc),
            'similarBugs' => $similarBugs,

            'bugLog' => $bugLog,

            'activeCharacter' => $activeCharacter,
        );

        $out = $this->template->loadPage('bug_detail.tpl', $page_data);

        $this->template->view($out, $this->css);
    }

    public function create(){

        requirePermission('canCreateBugs');

        // Helper
        $this->load->helper('form');

        /**
         * Post Form
         */
        $formData = array(
            'project' => '',
            'title' => '',
            'desc' => '',
            'priority' => BUGPRIORITY_MINOR,
            'links' => array(),
        );

        foreach($formData as $fieldName => $defaultValue){
            $postData = $this->input->post($fieldName);

            if($fieldName == 'priority' && !hasPermission("canPrioritize")){
                continue;
            }

            if(!empty($postData)){
                $formData[$fieldName] = $postData;
            }
        }
        //debug("formData", $formData);

        if(!empty($formData['project']) && !empty($formData['title']) && !empty($formData['desc'])){
            $newBugId = $this->bug_model->create($formData['project'], $formData['priority'], $formData['title'], $formData['desc'], $formData['links']);

            if($newBugId){
                // Show Detail Page of the newly created Bug
                $this->bug($newBugId);
                return;
            }
        }

        /**
         * Title & Breadcrumbs
         */
        $this->template->setTitle($this->moduleTitle);
        $this->template->setSectionTitle('Neuen Bug eintragen');
        $this->template->addBreadcrumb('Neuen Bug eintragen', site_url('bugtracker/create'));


        /**
         * Bug Categories
         */
        $projectTree = $this->project_model->getProjectTree();

        $baseProjects = array();
        $projectPaths = array();

        foreach($projectTree as $baseRow){
            $projectPaths[$baseRow['id']] = explode('.',$baseRow['matpath']);
            $children = array();

            foreach($baseRow['children'] as $child1){
                $projectPaths[$child1['id']] = explode('.', $child1['matpath']);
                $children[$child1['id']] = $child1['prefix'].$child1['title'];

                foreach($child1['children'] as $child2){
                    $projectPaths[$child2['id']] = explode('.', $child2['matpath']);
                    $children[$child2['id']] = $child2['prefix'].$child2['title'];

                    foreach($child2['children'] as $child3){
                        $projectPaths[$child3['id']] = explode('.', $child3['matpath']);
                        $children[$child3['id']] = $child3['prefix'].$child3['title'];
                    }
                }
            }

            $baseProjects[$baseRow['id']] = array(
                'title' => $baseRow['prefix'].$baseRow['title'],
                'children' => $children,
            );
        }

        $idTypes = array(
            'quest' => 'Quest',
            'npc' => 'NPC',
            'zone' => 'Dungeon/Raid/Zone',
        );

        $bugPriorities = $this->bug_model->getPrioritiesWithLabel();

        $page_data = array(
            'module' => 'bugtracker',
            'form_attributes' => array('class' => 'form-horizontal', 'id' => 'bugtrackerCreateForm'),
            'js_path' => $this->template->js_path,
            'image_path' => $this->template->image_path,
            'baseProjects' => $baseProjects,
            'idTypes' => $idTypes,
            'projectPaths' => $projectPaths,
            'bugLinks' => array(),
            'bugPriorities' => $bugPriorities,
            'post' => $formData,
        );
        
        $out = $this->template->loadPage('bug_create.tpl', $page_data);
        
        $this->template->view($out, $this->css);
    }

    public function add_comment(){
        $bugId = $this->input->post('bug', true);
        $newState = $this->input->post('change-state', true);
        $commentText = $this->input->post('detail', true);

        $bug = $this->bug_model->getBug($bugId);

        if($bug){

            if($bug['bug_state'] != $newState && hasPermission('canEditBugs')){
                $action = array(
                    'state' => $newState
                );
                $this->bug_model->updateState($bugId, $newState);
            }
            else{
                $action = '';
            }

            $this->bug_model->createComment($bugId, $commentText, $action);

            $this->bug($bugId);
        }
        else{
            show_error('Der Bug #'.$bugId.' wurde nicht gefunden.');
        }
        return;

    }

    public function edit_comment(){

        $json = array();
        $action = $this->input->post('action', true);
        $commentId = $this->input->post('comment', true);

        $comment = $this->bug_model->getComment($commentId, 'posterAccountId, text, changedActions');

        if($comment != false){

            if($this->user->getId() == $comment->posterAccountId || hasPermission('canEditComments')){
                if($action == "get"){
                    $json['text'] = $comment->text;
                }
                if($action == "edit"){
                    $newText = $this->input->post('content');

                    $this->bug_model->updateComment($commentId, $comment->changedActions, $newText);

                    $json['text'] = nl2br($newText);
                    $json['username'] = ucfirst($this->user->getNickname());
                }
            }
            else{
                $json['msg'] = "Du hast keine Berechtigung dies zu tun.";
            }

        }
        else{
            $json['msg'] = "Der Kommentar wurde nicht gefunden.";
        }

        $this->template->handleJsonOutput($json);
    }

}
    