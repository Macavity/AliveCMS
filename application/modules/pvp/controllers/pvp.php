<?php

define('PVP_ARENA_SIZE_2', 2);
define('PVP_ARENA_SIZE_3', 3);
define('PVP_ARENA_SIZE_5', 5);

class Pvp extends MX_Controller
{

    private $pvpModes;

    /**
     * Character Database Connection
     * @type object
     * @class Characters_model
     */
    private $dbChar = NULL;

    private $shownRealmId = 1;

    private $shownRealmName = '';

    private $shownArenaSize = 0
;
    private $allRealms = array();

    private $currentAction = 'summary';

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

        $this->theme_path = base_url().APPPATH.$this->template->theme_path;

        $this->template->addBreadcrumb('Server', site_url(array('server')));
        $this->template->addBreadcrumb('PVP', site_url(array('pvp')));

        $this->pvpModes = array(
            PVP_ARENA_SIZE_2 => '2v2',
            PVP_ARENA_SIZE_3 => '3v3',
            PVP_ARENA_SIZE_5 => '5v5'
        );

        $this->standingsClasses = array(
            1 => 'first',
            2 => 'second',
            3 => 'third',
        );

        /*
         * Cache Configuration
         */
        $this->useCaching = TRUE;
        $this->cacheDuration = CACHE_DURATION_1_DAY;

        /**
         * Configs
         */
        $this->maxHonorableKillers = 100;
        $this->maxHonorableKillersInSummary = 20;

        /**
         * Array of Realmnames
         * @type array
         */
        $realms = $this->realms->getRealms();

        foreach($realms as $key => $realm){
            $this->allRealms[$realm->getId()] = $realm->getName();
        }
    }

    /**
     * Summary Page that lists all
     * if nor realmName is given it takes the
     * selected Realm of the User or the Standard Realm
     *
     * @param $realmName
     */
    public function index($realmName = '')
    {
        $this->currentAction = 'summary';

        $arenaChars = array();

        $arenaTeams = array();

        $teams = array(
            '2v2' => array(),
            '3v3' => array(),
            '5v5' => array(),
        );

        /*
         * Get the Realm
         */
        if(empty($realmName)){
            $this->shownRealmId = 1;
        }
        else{
            $this->shownRealmId = $this->getRealmIdByName($realmName);
        }
        debug($this->shownRealmId);
        debug($realmName);

        $realm = $this->realms->getRealm($this->shownRealmId);
        $this->shownRealmName = $realm->getName();

        /**
         * Make Name of the Realm accessible for all member functions
         */
        $this->shownRealmName = $this->allRealms[$this->shownRealmId];

        // Site Title
        $this->template->setTitle("{$this->shownRealmName} PvP");
        $this->template->addBreadcrumb("{$this->shownRealmName} Zusammenfassung", site_url(array('pvp', 'summary', $this->shownRealmName)));

        /**
         * Cache Data
         */
        $cacheId = 'pvp_summary-'.$this->shownRealmName;

        $cacheData = $this->cache->get($cacheId);

        if($this->useCaching && $cacheData){
            $out = $cacheData;
        }
        else{

            /**
             * Database Connection to the active realm characters database
             * @type Character_model
             */
            $this->dbChar = $this->getRealmCharacterConnection($this->shownRealmId);

            $topData = $this->getTopArenaTeams(3);

            $arenaTeams = $topData['arenaTeams'];
            $modeTeams = $topData['modeTeams'];

            $arenaTeams = $this->populateArenaTeamCharacters($arenaTeams);

            // Top 20 Kills - Alliance characters
            $allianceKillers = $this->getTopHonorableKillCharacters(FACTION_ALLIANCE, $this->maxHonorableKillersInSummary);

            // Top 20 Kills - Horde characters
            $hordeKillers = $this->getTopHonorableKillCharacters(FACTION_HORDE, $this->maxHonorableKillersInSummary);

            /*
             * Get the PVP Sidebar
             */
            $pvpSidebar = $this->getPvpSidebar();

            /*
             * Generate Output of the template
             */
            $pageData = array(
                'pvpSidebar' => $pvpSidebar,
                'pvpModes' => $this->pvpModes,
                'modeTeams' => $modeTeams,
                'arenaTeams' => $arenaTeams,
                'shownRealmId' => $this->shownRealmId,
                'shownRealmName' => $this->shownRealmName,
                'allRealms' => $this->allRealms,
                'hordeKillers' => $hordeKillers,
                'allianceKillers' => $allianceKillers,
            );

            $out = $this->template->loadPage('pvp_index.tpl', $pageData);

            $this->cache->save($cacheId, $out, $this->cacheDuration);
        }

        $this->template->hideSidebar();

        $this->template->view($out);
    }

    /**
     * Alias for index function
     * @param $realmName
     */
    public function summary($realmName){
        $this->index($realmName);
        exit;
    }

    /**
     * Get the Top Honorable Killers of both factions
     * @param $realmName
     */
    public function honor_list($realmName){

        $this->currentAction = 'honor-list';

        $this->shownRealmId = $this->getRealmIdByName($realmName);

        if(empty($realmName) || $this->shownRealmId === FALSE){
            redirect('pvp');
            exit;
        }

        $maxShownCharacters = 100;

        /*
         * Get the Realm
         */
        $realm = $this->realms->getRealm($this->shownRealmId);
        $this->shownRealmName = $realm->getName();

        // Site Title
        $this->template->setTitle("{$this->shownRealmName} Ehrenhafte Tötungen");
        $this->template->addBreadcrumb($this->shownRealmName, site_url(array('pvp','summary', $this->shownRealmName)));
        $this->template->addBreadcrumb('Ehrenhafte Tötungen', site_url(array('pvp','honor-list', $this->shownRealmName)));

        /**
         * Cache Data
         */
        $cacheId = 'pvp_honor_list-'.$this->shownRealmName.'-'.$this->shownArenaSize;

        $cacheData = $this->cache->get($cacheId);

        if($this->useCaching && $cacheData){
            $out = $cacheData;
        }
        else{
            /**
             * Database Connection to the active realm characters database
             * @class Character_model
             */
            $this->dbChar = $this->getRealmCharacterConnection($this->shownRealmId);

            // Top 20 Kills - Alliance characters
            $allianceKillers = $this->getTopHonorableKillCharacters(FACTION_ALLIANCE, $this->maxHonorableKillers);

            // Top 20 Kills - Horde characters
            $hordeKillers = $this->getTopHonorableKillCharacters(FACTION_HORDE, $this->maxHonorableKillers);

            /**
             * Get the PVP Sidebar
             * @type String
             */
            $pvpSidebar = $this->getPvpSidebar();

            /*
             * Generate Output of the template
             */
            $pageData = array(
                'pvpSidebar' => $pvpSidebar,
                'pvpModes' => $this->pvpModes,
                'shownRealmId' => $this->shownRealmId,
                'shownRealmName' => $this->shownRealmName,
                'allRealms' => $this->allRealms,
                'hordeKillers' => $hordeKillers,
                'allianceKillers' => $allianceKillers,
            );

            $out = $this->template->loadPage('honor_list.tpl', $pageData);

            $this->cache->save($cacheId, $out, $this->cacheDuration);
        }

        $this->template->hideSidebar();

        $this->template->view($out);
    }

    /**
     * Get a list of all Teams of the given Realm ordered by their ranking
     *
     * @param String $realmName
     * @param Integer $arenaSize
     */
    public function arena_list($realmName, $arenaSize){

        $this->currentAction = 'arena-list';

        $this->shownRealmId = $this->getRealmIdByName($realmName);

        if(empty($arenaSize) || empty($realmName) || $this->shownRealmId === FALSE){
            redirect('pvp');
            exit;
        }

        $shownPerPage = 50;

        /*
         * Get the Realm
         */
        $realm = $this->realms->getRealm($this->shownRealmId);
        $this->shownRealmName = $realm->getName();

        /*
         * Determine Arena Team Size
         */
        $this->shownArenaSize = PVP_ARENA_SIZE_2;

        foreach($this->pvpModes as $modeKey => $modeName){
            if($arenaSize == $modeKey || $arenaSize == $modeName){
                $this->shownArenaSize = $modeKey;
            }
        }

        $shownArenaSizeLabel = $this->pvpModes[$this->shownArenaSize];

        // Site Title
        $this->template->setTitle("{$this->shownRealmName} {$shownArenaSizeLabel} Arena Teams");
        $this->template->addBreadcrumb($this->shownRealmName, site_url(array('pvp','summary', $this->shownRealmName)));
        $this->template->addBreadcrumb("{$shownArenaSizeLabel} Arena Teams", site_url(array('pvp','arena-list', $this->shownRealmName, $shownArenaSizeLabel)));

        /**
         * Cache Data
         */
        $cacheId = 'pvp_arena_list-'.$this->shownRealmName.'-'.$this->shownArenaSize;

        $cacheData = $this->cache->get($cacheId);

        if($this->useCaching && $cacheData){
            $out = $cacheData;
        }
        else{
            /**
             * Database Connection to the active realm characters database
             * @class Character_model
             */
            $this->dbChar = $this->getRealmCharacterConnection($this->shownRealmId);

            /*
             * Get the Arena Teams
             */
            $topData = $this->getTopArenaTeams(0, $this->shownArenaSize);

            $arenaTeams = $topData['arenaTeams'];
            $modeTeams = $topData['modeTeams'];

            /**
             * Get the PVP Sidebar
             * @type String
             */
            $pvpSidebar = $this->getPvpSidebar();

            /*
             * Generate Output of the template
             */
            $pageData = array(
                'pvpSidebar' => $pvpSidebar,
                'pvpModes' => $this->pvpModes,
                'modeTeams' => $modeTeams,
                'arenaTeams' => $arenaTeams,
                'shownArenaSize' => $this->shownArenaSize,
                'shownArenaSizeLabel' => $shownArenaSizeLabel,
                'shownRealmId' => $this->shownRealmId,
                'shownRealmName' => $this->shownRealmName,
                'allRealms' => $this->allRealms,
                'arenaTeamCount' => count($arenaTeams),
                'arenaTeamFirst' => count($arenaTeams) ? 1 : 0,
                'arenaTeamLast' => min($shownPerPage, count($arenaTeams)),
                'shownPerPage' => $shownPerPage,
            );

            $out = $this->template->loadPage('arena_list.tpl', $pageData);

            $this->cache->save($cacheId, $out, $this->cacheDuration);
        }

        $this->template->hideSidebar();

        $this->template->view($out);

    }

    public function arena_team($realmName, $arenaSize, $teamName){

        $this->currentAction = 'arena-team';

        $this->shownRealmId = $this->getRealmIdByName($realmName);

        if(empty($realmName) || $this->shownRealmId === FALSE){
            redirect('pvp');
            exit;
        }

        /*
         * Get the Realm
         */
        $realm = $this->realms->getRealm($this->shownRealmId);
        $this->shownRealmName = $realm->getName();

        /**
         * Database Connection to the active realm characters database
         * @class Character_model
         */
        $this->dbChar = $this->getRealmCharacterConnection($this->shownRealmId);

        /*
         * Get the Arena Team
         */
        $arenaTeamId = $this->getArenaTeamId(urldecode($teamName));

        if($arenaTeamId == 0){
            debug("team $teamName not found");
            $this->index();
            exit;
        }

        /**
         * Cache Data
         */
        $cacheId = 'pvp_arena_team-'.$this->shownRealmName.'-'.$arenaTeamId;

        $cacheData = $this->cache->get($cacheId);

        if($this->useCaching && $cacheData){
            $out = $cacheData;
        }
        else{

            $arenaTeam = $this->getArenaTeam($arenaTeamId, $arenaSize);
            $arenaTeamName = $arenaTeam['name'];

            $this->shownArenaSize = $arenaTeam['type'];
            $shownArenaSizeLabel = $this->pvpModes[$this->shownArenaSize];

            // Load cached ranking
            $cacheQuery = $this->db
                ->select('*')
                ->where('id', $arenaTeamId)
                ->from('pvp_arenateam_cache')
                ->get();

            $arenaTeam['rank'] = 0;
            $arenaTeam['lastweek_rank'] = 0;

            if($cacheQuery->num_rows() > 0){
                $cacheRow = $cacheQuery->row_array();

                $arenaTeam['rank'] = $cacheRow['rank'];
                $arenaTeam['lastweek_rank'] = $cacheRow['lastweek_rank'];
                $arenaTeam['rankPage'] = ceil($arenaTeam['rank']/50);
            }

            // Emblem
            $arenaTeam['backgroundColor'] = dechex($arenaTeam['backgroundColor']);
            $arenaTeam['borderColor'] = dechex($arenaTeam['borderColor']);
            $arenaTeam['emblemColor'] = dechex($arenaTeam['emblemColor']);

            // Find the Members
            $arenaTeam = $this->populateArenaTeam($arenaTeam);

            foreach($arenaTeam['members'] as $i => $row){

                $arenaTeam['faction'] = $this->realms->getFaction($row['race']);

                $row['weekLosses'] = 0;
                $row['weekPercentage'] = 0;
                $row['weekAttendance'] = 0;

                $row['seasonLosses'] = 0;
                $row['seasonPercentage'] = 0;
                $row['seasonAttendance'] = 0;

                if($row['weekGames'] > 0){
                    $row['weekLosses'] = $row['weekGames'] - $row['weekWins'];
                    $row['weekPercentage'] = round(($row['weekWins'] / $row['weekGames']) * 100, 2);
                    $row['weekAttendance'] = round(($row['weekGames'] / $arenaTeam['weekGames']) * 100, 2);
                }

                if($row['seasonGames']){
                    $row['seasonLosses'] = $row['seasonGames'] - $row['seasonWins'];
                    $row['seasonPercentage'] = round(($row['seasonWins'] / $row['seasonGames']) * 100, 2);
                    $row['seasonAttendance'] = round(($row['seasonGames'] / $arenaTeam['seasonGames']) * 100, 2);
                }

                $arenaTeam['members'][$i] = $row;
            }

            $arenaTeam['weekLosses'] = $arenaTeam['weekGames'] - $arenaTeam['weekWins'];
            $arenaTeam['weekPercentage'] = ($arenaTeam['weekGames'] > 0) ? round(($arenaTeam['weekWins'] / $arenaTeam['weekGames']) * 100, 2) : 0;

            $arenaTeam['seasonLosses'] = $arenaTeam['seasonGames'] - $arenaTeam['seasonWins'];
            $arenaTeam['seasonPercentage'] = round(($arenaTeam['seasonWins'] / $arenaTeam['seasonGames']) * 100, 2);

            $arenaTeam['factionLabel'] = ($arenaTeam['faction'] == FACTION_ALLIANCE) ? lang('Alliance', 'pvp') : lang('Horde', 'pvp');
            $arenaTeam['factionCss'] = ($arenaTeam['faction'] == FACTION_ALLIANCE) ? 'alliance' : 'horde';

            /**
             * Get the PVP Sidebar
             * @type String
             */
            $pvpSidebar = $this->getPvpSidebar();

            /*
             * Generate Output of the template
             */
            $pageData = array(
                'arenaTeam' => $arenaTeam,
                'pvpSidebar' => $pvpSidebar,
                'pvpModes' => $this->pvpModes,
                'shownRealmId' => $this->shownRealmId,
                'shownRealmName' => $this->shownRealmName,
                'shownArenaSize' => $this->shownArenaSize,
                'shownArenaSizeLabel' => $shownArenaSizeLabel,
                'allRealms' => $this->allRealms,
            );

            $out = $this->template->loadPage('arena_team.tpl', $pageData);

            $this->cache->save($cacheId, $out, $this->cacheDuration);
        }

        // Site Title
        $this->template->setTitle("{$arenaTeamName} @ {$this->shownRealmName}");
        $this->template->addBreadcrumb($this->shownRealmName, site_url(array('pvp','summary', $this->shownRealmName)));
        $this->template->addBreadcrumb($shownArenaSizeLabel, site_url(array('pvp','arena-list', $this->shownRealmName, $shownArenaSizeLabel)));
        $this->template->addBreadcrumb($arenaTeamName, site_url(array('pvp','arena-team', $this->shownRealmName, $shownArenaSizeLabel, $arenaTeamName)));

        $this->template->hideSidebar();

        $this->template->view($out);
    }

    private function getArenaTeam($teamId){

        $query = $this->dbChar
            ->select('*')
            ->where('arenaTeamId', $teamId)
            ->from('arena_team')
            ->get();

        if($query->num_rows() > 0){
            return $query->row_array();
        }
        else{
            return FALSE;
        }
    }

    /**
     * Find an arena team with a given name
     * @param $teamName
     * @return int
     */
    private function getArenaTeamId($teamName){
        $query = $this->dbChar
            ->select('arenaTeamId')
            ->like('name', $teamName)
            ->from('arena_team')
            ->get();

        if($query->num_rows() > 0){
            return $query->row()->arenaTeamId;
        }
        return 0;
    }

    private function getPvpSidebar(){
        return $this->template->loadPage('pvp_sidebar.tpl', array(
            'action' => $this->currentAction,
            'pvpModes' => $this->pvpModes,
            'allRealms' => $this->allRealms,
            'shownArenaSize' => $this->shownArenaSize,
            'shownRealmId' => $this->shownRealmId,
            'shownRealmName' => $this->shownRealmName,
        ));
    }

    /**
     * Get the Character database of the given Realm
     *
     * @param $realmId
     *
     * @return
     */
    private function getRealmCharacterConnection($realmId){
        return $this->realms->getRealm($realmId)->getCharacters()->getConnection();
    }

    private function getRealmIdByName($realmName){

        foreach($this->allRealms as $key => $name){
            if($name == $realmName){
                return $key;
            }
        }
        return FALSE;
    }

    /**
     * Get the characters of the shown realm with the most honorable kills
     *
     * @returns array
     */
    private function getTopHonorableKillCharacters($faction = FACTION_ALLIANCE, $limit = 20){

        $raceArray = ($faction == FACTION_ALLIANCE) ? $this->realms->getAllianceRaces() : $this->realms->getHordeRaces();

        $killers = array();

        $charRows = $this->dbChar->select('guid, name, class, race, gender, totalKills')
            ->from('characters')
            ->where_in('race', $raceArray)
            ->order_by('totalKills', 'desc')
            ->limit($limit)
            ->get();

        if($charRows->num_rows() > 0){
            $i = 1;

            foreach($charRows->result_array() as $row){
                $row['classLabel'] = $this->realms->getClass($row['class']);
                $row['realmName'] = $this->shownRealmName;
                $killers[$i] = $row;
                $i++;
            }

        }
        return $killers;
    }

    /**
     * Get the top teams of a given Size
     *
     * @param int  $limit
     * @param bool|array|integer $arenaSizes
     *
     * @return array
     */
    private function getTopArenaTeams($limit = 3, $arenaSizes = FALSE){
        $arenaTeams = array();
        $modeTeams = array();

        if($arenaSizes === FALSE){
            $arenaSizes = $this->pvpModes;
        }

        if(!is_array($arenaSizes) && in_array($arenaSizes, array_keys($this->pvpModes))){
            $arenaSizes = array(
                $arenaSizes => $this->pvpModes[$arenaSizes]
            );
        }

        foreach($arenaSizes as $modeKey => $modeName){

            $sql = '
              SELECT
                arenaTeamId,
                arena_team.name, arena_team.type, arena_team.captainGUID, arena_team.rating,
                arena_team.seasonGames, arena_team.seasonWins,
                characters.race
              FROM arena_team JOIN characters ON(captainGUID = characters.guid)
              WHERE TYPE = '.$modeKey.'
              ORDER BY rating DESC';

            if($limit > 0){
                $sql .= ' LIMIT 0,'.$limit;
            }

            $query = $this->dbChar->query($sql);

            if($query->num_rows() > 0){

                $i = 1;
                foreach($query->result_array() as $row){
                    $row['faction'] = $this->realms->getFaction($row['race']);
                    $row['factionLabel'] = ($row['faction'] == FACTION_HORDE) ? 'Horde' : 'Allianz';

                    $row['rank'] = $i;
                    $row['css_rank'] = ($i <= 3) ? $this->standingsClasses[$i] : '';

                    $arenaTeams[$row['arenaTeamId']] = $row;
                    $modeTeams[$modeName][$i] = $row['arenaTeamId'];
                    $i++;
                }
            }
            else{
                debug('No results');
                debug($query);
            }

        }

        return array(
            'arenaTeams' => $arenaTeams,
            'modeTeams' => $modeTeams
        );
    }

    /**
     * Find all characters that are in at least one of the teams a member
     * ordered by games of the current season
     *
     * @param array $arenaTeams
     *
     * @return array
     */
    private function populateArenaTeamCharacters($arenaTeams = array()){

        $arenaTeamIds = array_keys($arenaTeams);

        $dbChar = $this->getRealmCharacterConnection($this->shownRealmId);

        // Find all characters that are part of one of the teams
        $query = $dbChar->query('
        	SELECT
        	    characters.guid,
        	    characters.name,
        	    characters.class,
        	    characters.level,
        	    characters.race,
        	    characters.gender,
        	    arena_team_member.arenaTeamId,
        	    arena_team_member.weekGames,
        	    arena_team_member.weekWins,
        	    arena_team_member.seasonGames,
        	    arena_team_member.seasonWins,
        	    arena_team_member.personalRating
		    FROM characters JOIN arena_team_member ON(arena_team_member.guid = characters.guid)
		    WHERE arenaTeamId IN('.implode(', ', $arenaTeamIds).') ORDER BY arena_team_member.seasonGames DESC;');

        if ($query->num_rows() > 0){
            foreach($query->result_array() as $charRow){
                //debug($charRow);
                $teamId = $charRow['arenaTeamId'];

                $team = $arenaTeams[$teamId];

                $team['members'] = isset($team['members']) ? $team['members'] : array();

                // Only the first 2/3/5 are to be shown
                if( count($team['members']) < $team['type'] )
                    $team['members'][] = $charRow;

                $arenaTeams[$teamId] = $team;
            }
        }

        return $arenaTeams;
    }

    private function populateArenaTeam($team){
        $arenaTeams = array(
            $team['arenaTeamId'] => $team
        );
        $arenaTeams = $this->populateArenaTeamCharacters($arenaTeams);
        return $arenaTeams[$team['arenaTeamId']];
    }


}
