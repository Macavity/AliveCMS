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

    private $shownRealmName = "";

    private $allRealms = array();

    private $currentAction = "summary";

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

        $this->theme_path = base_url().APPPATH.$this->template->theme_path;

        $this->template->addBreadcrumb("Server", site_url(array("server")));
        $this->template->addBreadcrumb("PVP", site_url(array("pvp_ranking")));

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
    public function index($realmName = "")
    {

        // Site Title
        $this->template->setTitle("Spieler gegen Spieler");

        $this->currentAction = "summary";

        $maxDisplayHonorChars = 20; // Only top 40 in stats

        $arenaChars = array();

        $arenaTeams = array();

        $teams = array(
            '2v2' => array(),
            '3v3' => array(),
            '5v5' => array(),
        );
        $allianceKillers = $hordeKillers = array();

        /**
         * Array of Realmnames
         * @type array
         */
        $shownRealmId = 1;

        foreach($this->allRealms as $key => $realm){
            if(!empty($realmName) && $realmName == $realmName->getName()){
                $shownRealmId = $key;
            }
        }

        if(empty($realmName) && $this->user->getActiveRealmId() != 0){
            $shownRealmId = $this->user->getActiveRealmId();
        }

        /**
         * Make Name of the Realm accessible for all member functions
         */
        $this->shownRealmName = $this->allRealms[$shownRealmId];

        /**
         * Database Connection to the active realm characters database
         * @type Character_model
         */
        $this->dbChar = $this->getRealmCharacterConnection($shownRealmId);

        $topData = $this->getTopArenaTeams(3);

        $arenaTeams = $topData['arenaTeams'];
        $modeTeams = $topData['modeTeams'];

        $arenaTeams = $this->populateArenaTeamCharacters($arenaTeams);

        // Top 20 Kills - Alliance characters
        $allianceKillers = $this->getTopHonorableKillCharacters(FACTION_ALLIANCE, $maxDisplayHonorChars);

        // Top 20 Kills - Horde characters
        $hordeKillers = $this->getTopHonorableKillCharacters(FACTION_HORDE, $maxDisplayHonorChars);

        /*
         * Get the PVP Sidebar
         */
        $pvpSidebar = $this->getPvpSidebar();

        /*
         * Output of the template
         */
        $this->template->hideSidebar();

        $pageData = array(
            'pvpSidebar' => $pvpSidebar,
            'pvpModes' => $this->pvpModes,
            'modeTeams' => $modeTeams,
            'arenaTeams' => $arenaTeams,
            'shownRealmId' => $shownRealmId,
            'shownRealmName' => $this->shownRealmName,
            'allRealms' => $this->allRealms,
            'hordeKillers' => $hordeKillers,
            'allianceKillers' => $allianceKillers,
        );

        $out = $this->template->loadPage("pvp_index.tpl", $pageData);

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


    public function honor_list($realmName){

        $realmId = $this->getRealmIdByName($realmName);

        if(empty($arenaSize) || empty($realmName) || $realmId === FALSE){
            redirect('pvp_stats');
        }

    }

    /**
     * Get a list of all Teams of the given Realm ordered by their ranking
     *
     * @param $realmName
     * @param $arenaSize
     */
    public function arena_list($realmName, $arenaSize){

        $shownRealmId = $this->getRealmIdByName($realmName);

        if(empty($arenaSize) || empty($realmName) || $shownRealmId === FALSE){
            redirect('pvp_stats');
        }

        $maxDisplayChars = 50;

        /*
         * Get the Realm
         */
        $realm = $this->realms->getRealm($shownRealmId);
        $this->shownRealmName = $realm->getName();

        /**
         * Database Connection to the active realm characters database
         * @type Character_model
         */
        $this->dbChar = $this->getRealmCharacterConnection($shownRealmId);

        /*
         * Determine Arena Team Size
         */
        $shownArenaSize = PVP_ARENA_SIZE_2;
        $shownArenaSizeLabel = "";

        foreach($this->pvpModes as $modeKey => $modeName){
            if($arenaSize == $modeKey || $arenaSize == $modeName){
                $shownArenaSize = $modeKey;
            }
        }

        $shownArenaSizeLabel = $this->pvpModes[$shownArenaSize];

        // Site Title
        $this->template->setTitle($realmName." ".$shownArenaSizeLabel." Arena Teams");
        $this->template->setSectionTitle($realmName." ".$shownArenaSizeLabel." Arena Teams");

        /*
         * Get the Arena Teams
         */
        $topData = $this->getTopArenaTeams(3, $shownArenaSize);

        $arenaTeams = $topData['arenaTeams'];
        $modeTeams = $topData['modeTeams'];

        /*
         * Get the PVP Sidebar
         */
        $pvpSidebar = $this->getPvpSidebar();

        /*
         * Output of the template
         */
        $this->template->hideSidebar();

        $pageData = array(
            'pvpSidebar' => $pvpSidebar,
            'pvpModes' => $this->pvpModes,
            'modeTeams' => $modeTeams,
            'arenaTeams' => $arenaTeams,
            'shownArenaSize' => $shownArenaSize,
            'shownArenaSizeLabel' => $shownArenaSizeLabel,
            'shownRealmId' => $shownRealmId,
            'shownRealmName' => $this->shownRealmName,
            'allRealms' => $this->allRealms,
        );

        $out = $this->template->loadPage("arena_list.tpl", $pageData);

        $this->template->view($out);


    }

    public function arena_team($realmName, $arenaSize, $teamName){

        if(empty($realmName) || empty($arenaSize) || empty($teamName)){
            redirect('pvp_stats');
        }
    }

    private function getPvpSidebar(){
        return $this->template->loadPage('pvp_sidebar.tpl', array(
            'action' => $this->currentAction,
            'pvpModes' => $this->pvpModes,
            'allRealms' => $this->allRealms,
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

            debug($this->dbChar);

            if($query->num_rows() > 0){

                $i = 1;
                foreach($query->result_array() as $row){
                    $row['faction'] = $this->realms->getFaction($row['race']);
                    $row['factionLabel'] = ($row['faction'] == FACTION_HORDE) ? "Horde" : "Allianz";

                    $row['rank'] = $i;
                    $row['css_rank'] = $this->standingsClasses[$i];

                    $arenaTeams[$row['arenaTeamId']] = $row;
                    $modeTeams[$modeName][$i] = $row['arenaTeamId'];
                    $i++;
                }
            }
            else{
                debug("No results");
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

        // Find all characters that are part of one of the teams

        $dbChar = $this->getRealmCharacterConnection($this->shownRealmId);

        $query = $dbChar->query("
        	SELECT characters.guid, characters.name, characters.class, arena_team_member.arenaTeamId, arena_team_member.seasonGames
		    FROM characters JOIN arena_team_member ON(arena_team_member.guid = characters.guid)
		    WHERE arenaTeamId IN(".implode(", ", $arenaTeamIds).") ORDER BY arena_team_member.seasonGames DESC;");

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


}
