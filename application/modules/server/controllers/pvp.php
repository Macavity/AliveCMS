<?php

class Pvp extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

        $this->theme_path = base_url().APPPATH.$this->template->theme_path;

        $this->template->addBreadcrumb("Server", site_url(array("server")));

    }

    public function index($page = "index")
    {
        // Site Title
        $this->template->setTitle("Der Server");

        // Template File
        $this->templateFile = "server.tpl";

        $pvpModes = array(2 => '2v2', 3 => '3v3', 5 => '5v5');

        $max_display_chars = 40; // Only top 40 in stats

        $arenaChars = array();
        $arenaTeams = array();
        $teams = array(
            '2v2' => array(),
            '3v3' => array(),
            '5v5' => array(),
        );
        $allianceKillers = $hordeKillers = array();

        $css_classes = array(
            1 => "first",
            2 => "second",
            3 => "third",
        );

        // Active Realm of selected user
        $activeRealmId = $this->user->getActiveRealmId();

        $charDb = $this->realms->getRealm($activeRealmId)->getCharacters()->getConnection();

        $css_classes = array(
            1 => "first",
            2 => "second",
            3 => "third",
        );


        foreach($pvpModes as $modeKey => $modeName){
            // Find Top 3 Mode Teams
            $query = $charDb->query('
              SELECT arenaTeamId, arena_team.name, arena_team.type, arena_team.captainGUID, arena_team.rating, characters.race
              FROM arena_team JOIN characters ON(captainGUID = characters.guid)
              WHERE TYPE = '.$modeKey.'
              ORDER BY rating DESC LIMIT 0,3');

            $i = 1;
            foreach($query->result_array() as $row){
                $row["faction"] = $this->realms->getFaction($row['race']);
                $row['factionLabel'] = ($row['faction'] == FACTION_HORDE) ? "Horde" : "Allianz";

                $row['css_rank'] = $css_classes[$i];

                $arenaTeams[$row["arenaTeamId"]] = $row;
                $teams[$modeName][$i] = $row["arenaTeamId"];
                $i++;
            }
        }

        // Find all characters
        $charRows = $charDb->query("
        	SELECT characters.guid, characters.name, characters.class, arena_team_member.arenaTeamId, arena_team_member.seasonGames
		    FROM characters JOIN arena_team_member ON(arena_team_member.guid = characters.guid)
		    WHERE arenaTeamId IN(".implode(", ", array_keys($arenaTeams)).") ORDER BY arena_team_member.seasonGames DESC;");

        //debug("Query Char Rows: ", $charDb->last_query());

        foreach($charRows->result_array() as $charRow){
            $teamId = $charRow["arenaTeamId"];

            $team = $arenaTeams[$teamId];

            if(!isset($team["members"]))
                $team["members"] = array();

            // Only the first 2/3/5 are to be shown
            if( count($team["members"]) < $team["type"] )
                $team["members"][] = $charRow;

            //$team["members"][] = $charRow;
            $arenaTeams[$teamId] = $team;
        }

        // Top 20 Kills - Alliance characters
        $charRows = $charDb->select('guid, name, class, race, gender, totalKills')
            ->from('characters')
            ->where_in('race', $this->realms->getAllianceRaces())
            ->order_by('totalKills', 'desc')
            ->limit(20)
            ->get();
        $i = 1;
        foreach($charRows->result_array() as $row)
        {
            $row["css"] = "row".(($i % 2)+1);
            $row['classLabel'] = $this->realms->getClass($row['class']);
            $allianceKillers[$i] = $row;
            $i++;
        }

        // Top 20 Kills - Horde characters
        $charRows = $charDb->select('guid, name, class, race, gender, totalKills')
            ->from('characters')
            ->where_in('race', $this->realms->getHordeRaces())
            ->order_by('totalKills', 'desc')
            ->limit(20)
            ->get();
        $i = 1;
        foreach($charRows->result_array() as $row)
        {
            $row["css"] = "row".(($i % 2)+1);
            $row['classLabel'] = $this->realms->getClass($row['class']);
            $hordeKillers[$i] = $row;
            $i++;
        }

        //debug("Arena Teams", $arenaTeams);
        //debug("Teams", $teams);

        $realms = $this->realms->getRealms();
        $allRealms = array();

        foreach($realms as $key => $realm){
            $allRealms[$key] = $realm->getName();
        }

        /*
         * Output of the template
         */
        $this->template->hideSidebar();

        $pageData = array(
            'pvpModes' => $pvpModes,
            'teams' => $teams,
            'arenaTeams' => $arenaTeams,
            'activeRealmId' => $activeRealmId,
            'allRealms' => $allRealms,
            'hordeKillers' => $hordeKillers,
            'allianceKillers' => $allianceKillers,
        );

        $out = $this->template->loadPage("pvp_index.tpl", $pageData);

        $this->template->view($out);
    }


}
