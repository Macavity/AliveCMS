<?php


class Zone_model extends CI_Model {

    var $id = 0;
    var $name = "";
    var $label = "";
    var $location = "";
    var $expansion = 0;
    var $boss_num = 0;
    var $levelMin = 0;
    var $levelMax = 0;
    var $is_heroic = false;
    var $heroic_closed = false;
    var $raid = false;
    var $patch = "";
    var $intro = "";
    var $lore = "";

    var $partySizes = array();
    var $difficulties = array();
    var $bosses = array();
    var $floors = array();
    var $wings = array();
    var $data = array();

    var $zone_data = array();
    var $npc_data = array();

    public function __construct(){
        $this->loadZoneData();
    }

    public function getZone($zoneName){

        $this->db->select("*")->from("game_zone_template");

        if(is_numeric($zoneName)){
            $this->db->where("id", $zoneName);
        }
        else{
            $this->db->where("label", $zoneName);
        }

        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            $results = $query->result_array();


            foreach($results as $row){
                $this->id = $row["id"];
                $this->expansion = (int) $row["expansion"];
                $this->boss_num = (int) $row["boss_num"];
                $this->levelMin = (int) $row["levelMin"];
                $this->levelMax = (int) $row["levelMax"];
                $this->raid = (bool) $row["raid"];
                $this->label = $row["label"];

                if($row["is_heroic"] == 1){
                    $this->is_heroic = true;
                }

                $this->partySizes[] = $row["partySize"];
                $this->difficulties[] = $row["difficulty"];

            }

            $this->data = empty($this->zone_data[$this->id]) ? array() : $this->zone_data[$this->id];

            if(isset($this->data["location"]))
                $this->location = $this->data["location"];
            if(isset($this->data["patch"]))
                $this->patch = $this->data["patch"];
            if(isset($this->data["intro"]))
                $this->intro = $this->data["intro"];
            if(isset($this->data["lore"]))
                $this->lore = $this->data["lore"];
            if(isset($this->data["heroic"]))
                $this->is_heroic = (bool) $this->data["heroic"];
            if(isset($this->data["heroic"]) && $this->data["heroic"] == "closed"){
                $this->heroic_closed = true;
            }
            return $this;
        }
        return false;

    }

    public function searchForZoneByName($term){
        $query = $this->db->select("id, name_de_de, partySize")
            ->from("game_zone_template")
            ->like('name_de_de', $term)
            ->order_by('name_de_de ASC, partySize ASC')
            ->get();

        if($query->num_rows() > 0){
            $results = $query->result_array();

            $zones = array();

            foreach($results as $row){
                $zones[$row['id']] = array(
                    'value' => $row['id'],
                    'label' => $row['name_de_de']
                );
            }

            return $zones;
        }
        else {
            return array();
        }


    }

    public function loadZoneDetails(){

        for($i = 1; $i <= 10; $i++){

            $localPath = APPPATH."themes/".$this->template->theme."/images/zone/maps-large/".$this->label.$i."-large.jpg";

            if(file_exists($localPath)){
                $this->floors[$i] = "Level ".$i;
            }
        }

        $this->db->select("*")->where("instance_id", $this->id)->order_by("id", "asc")->from("game_info_data");

        $bosses = array();
        $boss_keys = array();

        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            $results = $query->result_array();

            foreach($results as $row)
            {
                $key = $row["key"];

                // ignore double entries
                if(!in_array($key, $boss_keys)){
                    $boss_keys[] = $key;

                    $bossData = array(
                        "id" => ($row["type"] == "object") ? $row["name_id"] : $row["id"],
                        "name" => $row["name_de_de"],
                        "key" => $row["key"],
                        "type" => $row["type"],
                        "label" => $row["label"],
                        "lootid_1" => $row["lootid_1"],
                        "lootid_2" => $row["lootid_2"],
                        "lootid_3" => $row["lootid_3"],
                        "lootid_4" => $row["lootid_4"],
                        "closed" => false,
                    );

                    if(isset($data_npcs[$bossData["id"]]) && $data_npcs[$bossData["id"]]["closed"] == true)
                        $bossData["closed"] = true;

                    //self::debug($bossData);

                    if($row["type"] == "object"){
                        // The data in armory has to be correct. There is no real way to check correct values for gameobjects.
                        $bosses[$bossData["id"]] = $bossData;
                        continue;
                    }
                    $bossRow = $WSDB->selectRow("SELECT entry, lootid, difficulty_entry_1,difficulty_entry_2,difficulty_entry_3
					FROM creature_template
					WHERE entry = ?d LIMIT 1", $bossData["id"]);

                    if($bossRow){
                        //self::debug("Boss found: ".$bossData["id"]);
                        //self::debug($bossRow);
                        $updateRequired = false;

                        // lootid in armory table correct?
                        if($bossRow["lootid"] != $bossData["lootid_1"]){
                            $updateRequired = true;
                            $bossData["lootid_1"] = $bossRow["lootid"];
                        }


                        $diffRows = $WSDB->query("SELECT entry, lootid FROM creature_template WHERE entry IN(?a);", array($bossRow["difficulty_entry_1"],$bossRow["difficulty_entry_2"],$bossRow["difficulty_entry_3"]));

                        foreach($diffRows as $diffRow){
                            // lootid in armory table correct?
                            if($diffRow["entry"] == $bossRow["difficulty_entry_1"] && $diffRow["lootid"] != $bossData["lootid_2"]){
                                $updateRequired = true;
                                $bossData["lootid_2"] = $diffRow["lootid"];
                            }
                            if($diffRow["entry"] == $bossRow["difficulty_entry_2"] && $diffRow["lootid"] != $bossData["lootid_3"]){
                                $updateRequired = true;
                                $bossData["lootid_3"] = $diffRow["lootid"];
                            }
                            if($diffRow["entry"] == $bossRow["difficulty_entry_3"] && $diffRow["lootid"] != $bossData["lootid_4"]){
                                $updateRequired = true;
                                $bossData["lootid_4"] = $diffRow["lootid"];
                            }
                        }

                        if($updateRequired){
                            debug("Update armory_instance_data");
                            //self::debug($bossData);
                            $aDB->query("UPDATE armory_instance_data SET lootid_1 = ?d, lootid_2 = ?d, lootid_3 = ?d, lootid_4 = ?d WHERE `key` LIKE ?;",
                                $bossData["lootid_1"], $bossData["lootid_2"], $bossData["lootid_3"], $bossData["lootid_4"],
                                $bossData["key"]);
                        }
                        else{
                            debug("No update neccessary");
                        }
                        $bosses[$bossData["id"]] = $bossData;
                    }
                    else{
                        debug("Creature Template ".$row["id"]." not found.");
                    }
                }

            }
        }

        debug("Bosses",$bosses);

        if(isset($this->data["wings"])){
            $wings = array();

            foreach($this->data["wings"] as $wing_name => $data){
                $wing = array("name" => $wing_name, "bosses" => array());

                foreach($data as $bossId){
                    if(isset($bosses[$bossId]))
                        $wing["bosses"][] = $bosses[$bossId];
                }

                $wings[] = $wing;
            }
            $this->wings = $wings;
        }
        elseif(isset($this->data["boss_order"])){
            foreach($this->data["boss_order"] as $bossId){
                //self::debug("Boss: $bossId");
                if(isset($bosses[$bossId]))
                    $this->bosses[] = $bosses[$bossId];
            }
        }
        else{
            foreach($bosses as $boss){
                $this->bosses[] = $boss;
            }
        }


        //debug($this);

    }

    public function getZoneData(){

        return array(
            "id" => $this->id,
            "label" => $this->label,
            "name" => $this->name,
            "hasWings" => (count($this->wings) > 0),
            "wings" => $this->wings,
            "floors" => $this->floors,
            "type" => ($this->raid) ? "Schlachtzug" : "Dungeon",
            "size" => $this->getSize(),
            "level" => ($this->levelMin < $this->levelMax) ? $this->levelMin."-".$this->levelMax : $this->levelMax,
            "location" => $this->location,
            "patch" => $this->patch,
            "expansion" => $this->expansion,
            "intro" => $this->intro,
            "lore" => $this->lore,
            "bosses" => $this->bosses,
            "intro" => $this->intro,

            "isHeroic" => $this->is_heroic,
            "heroicClosed" => $this->heroic_closed,
        );

    }

    private function getSize(){

        if(isset($this->data["sizes"])){
            return $this->data["sizes"];
        }

        if(count($this->partySizes) > 0){
            sort($this->partySizes);
            return implode("/", $this->partySizes);
        }
        else implode("",$this->partySizes);
    }

    private function loadZoneData(){
        $this->zone_data = array(

            // ICC
            4812 => array(
                "name" => "Eiskronenzitadelle",
                "heroic" => "closed",
                "location" => "Eiskrone",
                "intro" => "Die Eiskronenzitadelle dominiert Nordend - sie ist weiter sichtbar als nahezu jedes andere Bauwerk und die Heimat der riesigsten Armee von Untoten in der gesamten bekannten Welt.",

                "wings" => array(
                    "Die Untere Spitze" => array(36612,36855,36948,37813),
                    "Die Seuchenwerke" => array(36626, 36627, 36678),
                    "Die Blutrote Halle" => array(37970, 37955),
                    "Die Frostschwingenhallen" => array(36789, 36853),
                    "Der Frostthron" => array(36597),
                ),

            ),

            // PDK
            4722 => array(
                "name" => "Prüfung des Kreuzfahrers",
                "patch" => "3.2",
                "intro" => "Die Zeit naht, der Geißel den Stoß ins Herz zu versetzen.",
                "lore" => "Wolken bedecken den Himmel über Azeroth und unter den von Krieg gezeichneten Bannern versammeln sich die Helden als Vorbereitung für den kommenden Sturm. Doch &quot;auf Regen folgt Sonnenschein&quot; so sagt man, und es ist diese Hoffnung, welche die Männer und Frauen des Argentumkreuzzugs antreibt: die Hoffnung, dass das Licht sie in diesen schwierigen Zeiten finden wird; die Hoffnung, dass Gut über Böse triumphieren wird; die Hoffnung, dass ein vom Lichte gesegneter Held kommen wird und der dunklen Herrschaft des Lichkönigs ein Ende setzt.",

                "boss_order" => array(34797,34780,34461,34496,34564),

                "bosses" => array(
                    34797 => array( "label" => "northrend-beasts", "name" => "Bestien von Nordend"),
                    34780 => array( "label" => "lord-jaraxxus", "name" => "Lord Jaraxxus"),
                    34461 => array( "label" => "faction-champions", "name" => "Fraktionschampions"),
                    34496 => array( "label" => "valkyr-twins", "name" => "Zwillingsval'kyr"),
                    34564 => array( "label" => "anubarak", "name" => "Anub'arak"),
                ),
                "floors" => array(
                    1 => "Das Argentumkolosseum",
                    2 => "Die Eisigen Tiefen"
                ),
            ),

            // Ulduar
            4273 => array(
                "name" => "Ulduar",
                "patch" => "3.1",
                "sizes" => "10/25",
                "location" => "Die Sturmgipfel",
                "intro" => "Seit der Entdeckung von Ulduar in den Sturmgipfeln haben sich Abenteurer in Richtung der Titanenstadt aufgemacht, um mehr über ihre mysteriöse Vergangenheit in Erfahrung zu bringen.",
                "lore" => "Im Angesicht der Gefahr, die durch Yogg-Sarons unmittelbar bevorstehende Freiheit droht, hat eine Gruppe Sterblicher Vorbereitungen für einen Angriff gegen die Stadt getroffen. Es wird einer gewaltigen Kraftanstrengung bedürfen, die eisernen Diener und die hoch aufragenden Verteidigungsanlagen zu bezwingen – und doch mag selbst ein solch großartiger Sieg nicht ausreichen, um den Wahnsinn, der in den Tiefen haust, zu vernichten.",

                "wings" => array(
                    "Die Belagerung von Ulduar" => array(33113,33186,33118,33293),
                    "Die Vorkammer von Ulduar" => array(32927,32930,33515),
                    "Die Hüter von Ulduar" => array(32845,32865,32906,33350),
                    "Der Abstieg in den Wahnsinn" => array(33271,33288),
                    "Das Himmlische Planetarium" => array(32871),
                ),

            ),

            4196 => array(
                "name" => "Feste von Drak&#39;Tharon",
                "heroic" => 1,
                "location" => "Grizzlyhügel",
                "intro" => "Einst diente die Feste Drak&#39;Tharon den Drakkari-Trollen als uneinnehmbarer Außenposten am Rande ihres Imperiums, Zul'Drak.",
            ),
            4416 => array(
                "name" => "Gundrak",
                "heroic" => 1,
                "level" => "74-80",
                "label" => "gundrak",
                "location" => "Zul&#39;Drak",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "In ihrer Verzweiflung, ihr Königreich vor dem Zusammenbruch zu bewahren, haben die Trolle von Zul&#39;Drak mit der Opferung ihrer uralten Götter begonnen.",
            ),

            4723 => array(
                "name" => "Prüfung des Champions",
                "heroic" => 1,
                "level" => "78-80",
                "label" => "trial-of-the-champion",
                "location" => "Eiskrone",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Die Zeit naht, der Geißel den Stoß ins Herz zu versetzen.",

            ),

            4415 => array(
                "name" => "Violette Festung",
                "heroic" => 1,
                "level" => "73-80",
                "label" => "violet-hold",
                "location" => "Dalaran",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Inmitten der kunstvoll verzierten Dächer und verzauberten Straßen von Dalaran erfüllt eine finstere Bedrohung die imposanten Mauern der Violetten Festung.",

            ),

            4494 => array(
                "name" => "Ahn&#39;kahet: Das Alte Königreich",
                "heroic" => 1,
                "level" => "71-80",
                "label" => "ahnkahet-the-old-kingdom",
                "location" => "Azjol-Nerub, Drachenöde",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Als der Lichkönig Nordend erreichte, war Azjol-Nerub ein mächtiges Reich.",

            ),

            4277 => array(
                "name" => "Azjol-Nerub",
                "heroic" => 1,
                "level" => "70-80",
                "label" => "azjolnerub",
                "location" => "Azjol-Nerub, Drachenöde",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Als der Lichkönig Nordend erreichte, war Azjol-Nerub ein mächtiges Reich.",

            ),

            206 => array(
                "name" => "Burg Utgarde",
                "heroic" => 1,
                "level" => "68-78",
                "heroic_level" => "80",
                "label" => "utgarde-keep",
                "location" => "Burg Utgarde, Der Heulende Fjord",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Lange Zeit dachte man, Burg Utgarde wäre verlassen, ein Relikt einer vergessenen Zivilisation, in den Klippen im Zentrum des Heulenden Fjords gelegen.",

            ),

            1196 => array(
                "name" => "Turm Utgarde",
                "heroic" => 1,
                "level" => "77-80",
                "label" => "utgarde-pinnacle",
                "location" => "Burg Utgarde, Der Heulende Fjord",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Lange Zeit dachte man, Burg Utgarde wäre verlassen, ein Relikt einer vergessenen Zivilisation, in den Klippen im Zentrum des Heulenden Fjords gelegen.",

            ),

            4228 => array(
                "name" => "Das Oculus",
                "heroic" => 1,
                "level" => "77-80",
                "label" => "the-oculus",
                "location" => "Der Nexus, Boreanische Tundra",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Um seine Herrschaft über die Magie wiederherzustellen, hat der blaue Drachenaspekt, Malygos, einen unbarmherzigen Feldzug in Gang gesetzt, der die Verbindungen zwischen den Sterblichen und den arkanen Energien, die Azeroth durchfließen, kappen soll.",

            ),

            4265 => array(
                "name" => "Der Nexus",
                "heroic" => 1,
                "level" => "69-79",
                "heroic_level" => "80",
                "label" => "the-nexus",
                "location" => "Der Nexus, Boreanische Tundra",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Um seine Herrschaft über die Magie wiederherzustellen, hat der blaue Drachenaspekt, Malygos, einen unbarmherzigen Feldzug in Gang gesetzt, der die Verbindungen zwischen den Sterblichen und den arkanen Energien, die Azeroth durchfließen, kappen soll.",

            ),

            4809 => array(
                "name" => "Die Seelenschmiede",
                "heroic" => 1,
                "level" => "80",
                "label" => "the-forge-of-souls",
                "location" => "Eiskronenzitadelle, Eiskrone",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Viele Jahre lang haben die Champions der Völker Azeroths sich gegen den Lichkönig aufgelehnt, nur um gnadenlos abgeschlachtet zu werden und dann in seiner Armee der untoten Schrecken dienen zu müssen.",

            ),

            4813 => array(
                "name" => "Grube von Saron",
                "heroic" => 1,
                "level" => "80",
                "label" => "pit-of-saron",
                "location" => "Eiskronenzitadelle, Eiskrone",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Viele Jahre lang haben die Champions der Völker Azeroths sich gegen den Lichkönig aufgelehnt, nur um gnadenlos abgeschlachtet zu werden und dann in seiner Armee der untoten Schrecken dienen zu müssen.",

            ),

            4820 => array(
                "name" => "Hallen der Reflexion",
                "heroic" => 1,
                "level" => "80",
                "label" => "halls-of-reflection",
                "location" => "Eiskronenzitadelle, Eiskrone",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Viele Jahre lang haben die Champions der Völker Azeroths sich gegen den Lichkönig aufgelehnt, nur um gnadenlos abgeschlachtet zu werden und dann in seiner Armee der untoten Schrecken dienen zu müssen.",

            ),

            4100 => array(
                "name" => "Das Ausmerzen von Stratholme",
                "heroic" => 1,
                "level" => "77-80",
                "label" => "the-culling-of-stratholme",
                "location" => "Höhlen der Zeit, Tanaris",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Tief in den Höhlen der Zeit, ist der brütende Drache Nozdormu erwacht.",

            ),

            4272 => array(
                "name" => "Hallen der Blitze",
                "heroic" => 1,
                "level" => "77-80",
                "label" => "halls-of-lightning",
                "location" => "Ulduar, Die Sturmgipfel",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Als die Titanen Azeroth verließen, betrauten sie loyale Wächter mit dem Schutz Ulduars, einer mysteriösen Stadt, eingebettet in die Gebirgshänge der Sturmgipfel.",

            ),

            4264 => array(
                "name" => "Hallen des Steins",
                "heroic" => 1,
                "level" => "75-80",
                "label" => "halls-of-stone",
                "location" => "Ulduar, Die Sturmgipfel",
                "type" => "Dungeon",
                "expansion" => 2,
                "intro" => "Als die Titanen Azeroth verließen, betrauten sie loyale Wächter mit dem Schutz Ulduars, einer mysteriösen Stadt, eingebettet in die Gebirgshänge der Sturmgipfel.",

            ),

            4603 => array(
                "name" => "Archavons Kammer",
                "sizes" => "10/25",
                "location" => "Tausendwintersee",
                "intro" => "Hoch über den gefrorenen Ebenen der großen Drachenöde und den unwirtlichen Weiten der boreanischen Tundra, liegt eine Region, die unter den Einheimischen Nordends als Tausendwinter bekannt ist.",

            ),

            4500 => array(
                "name" => "Das Auge der Ewigkeit",
                "heroic" => 0,
                "level" => "80",
                "label" => "the-eye-of-eternity",
                "location" => "Boreanische Tundra",
                "type" => "Schlachtzug",
                "expansion" => 2,
                "intro" => "Aus dem Schutz seines eigenen Reichs, dem Auge der Ewigkeit, heraus, führt Malygos einen Kreuzzug, um seine Herrschaft über die Magie, die Azeroth durchströmt, wiederzuerlangen.",

            ),

            4493 => array(
                "name" => "Das Obsidiansanktum",
                "heroic" => 0,
                "level" => "80",
                "label" => "the-obsidian-sanctum",
                "location" => "Drachenöde",
                "type" => "Schlachtzug",
                "expansion" => 2,
                "intro" => "Vor mehr als zehntausend Jahren versuchte der schwarze Drachenaspekt, Neltharion, durch eine List seine Drachenbrüder zu unterwerfen und mit ihrer Kraft Azeroth zu beherrschen.",

            ),
            4987 => array(
                "name" => "Das Rubinsanktum",
                "heroic" => 1,
                "level" => "80",
                "label" => "the-ruby-sanctum",
                "location" => "Drachenöde",
                "type" => "Schlachtzug",
                "expansion" => 2,
                "intro" => "Die heilige Kammer der Aspekte unter dem Wyrmruhtempel war über die Geschichte hinweg Zeuge von Aufstieg und Fall von Königreichen und Armeen.",
            ),

            3456 => array(
                "name" => "Naxxramas",
                "heroic" => 0,
                "level" => "80",
                "label" => "naxxramas",
                "location" => "Drachenöde",
                "type" => "Schlachtzug",
                "expansion" => 2,
                "intro" => "Vor Jahren führte der Gruftlord Anub&#39;arak eine Armee untoter Krieger in die uralte nerubische Ziggurat, heute besser bekannt als Naxxramas.",

            ),

            2159 => array(
                "name" => "Onyxias Hort",
                "heroic" => 0,
                "level" => "80",
                "label" => "onyxias-lair",
                "location" => "Düstermarschen",
                "type" => "Schlachtzug",
                "expansion" => 2,
                "intro" => "Onyxia ist die Tochter des mächtigen Drachen Todesschwinge und die Schwester Nefarians, des durchtriebenen Lords der Schwarzfelsspitze.",

            ),

            3805 => array(
                "name" => "Zul&#39;Aman",
                "heroic" => 0,
                "level" => "70",
                "label" => "zulaman",
                "location" => "Geisterlande",
                "type" => "Schlachtzug",
                "expansion" => 1,
                "intro" => "Echos unerfüllter Rache klingen in Zul&#39;Aman, der einstigen Hauptstadt des Amanistammes, nach.",

            ),
            1977 => array(
                "name" => "Zul&#39;Gurub",
                "heroic" => 0,
                "level" => "60",
                "label" => "zulgurub",
                "location" => "Geisterlande",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Zul&#39;Gurub war die Hauptstadt der Dschungeltrolle der Gurubashi - ein Stamm, der einst die weiten Dschungel des Südens kontrollierte.",

            ),

            4131 => array(
                "name" => "Terrasse der Magister",
                "heroic" => 1,
                "level" => "68-70",
                "label" => "magisters-terrace",
                "location" => "Insel von Quel&#39;Danas",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Nach seiner Niederlage in der Festung der Stürme, gab Kael&#39;thas Sonnenwanderer öffentlich sein Bündnis mit der skrupellosen Brennenden Legion bekannt.",

            ),

            3790 => array(
                "name" => "Auchenaikrypta",
                "heroic" => 1,
                "level" => "63-70",
                "label" => "auchenai-crypts",
                "location" => "Auchindoun, Wälder von Terokkar",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Die ursprünglichen Vertriebenen auf Draenor hielten den Tod für eine beunruhigende und unangenehme Folge des Lebens, daher verbargen die Draenei ihre Toten in der unterirdischen Totenstadt von Auchindoun, einem labyrinthartigen Wunderwerk, das sich unterhalb der Wälder Terokkars befindet.",

            ),

            3792 => array(
                "name" => "Managruft",
                "heroic" => 1,
                "level" => "62-70",
                "label" => "manatombs",
                "location" => "Auchindoun, Wälder von Terokkar",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Die ursprünglichen Vertriebenen auf Draenor hielten den Tod für eine beunruhigende und unangenehme Folge des Lebens, daher verbargen die Draenei ihre Toten in der unterirdischen Totenstadt von Auchindoun, einem labyrinthartigen Wunderwerk, das sich unterhalb der Wälder Terokkars befindet.",

            ),

            3789 => array(
                "name" => "Schattenlabyrinth",
                "heroic" => 1,
                "level" => "67-70",
                "label" => "shadow-labyrinth",
                "location" => "Auchindoun, Wälder von Terokkar",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Die ursprünglichen Vertriebenen auf Draenor hielten den Tod für eine beunruhigende und unangenehme Folge des Lebens, daher verbargen die Draenei ihre Toten in der unterirdischen Totenstadt von Auchindoun, einem labyrinthartigen Wunderwerk, das sich unterhalb der Wälder Terokkars befindet.",

            ),

            3791 => array(
                "name" => "Sethekkhallen",
                "heroic" => 1,
                "level" => "65-70",
                "label" => "sethekk-halls",
                "location" => "Auchindoun, Wälder von Terokkar",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Die ursprünglichen Vertriebenen auf Draenor hielten den Tod für eine beunruhigende und unangenehme Folge des Lebens, daher verbargen die Draenei ihre Toten in der unterirdischen Totenstadt von Auchindoun, einem labyrinthartigen Wunderwerk, das sich unterhalb der Wälder Terokkars befindet.",

            ),

            3716 => array(
                "name" => "Der Tiefensumpf",
                "heroic" => 1,
                "level" => "61-70",
                "label" => "the-underbog",
                "location" => "Der Echsenkessel, Zangarmarschen",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Der Echsenkessel ist der Name für ein von den Naga dominiertes Gelände unterhalb der tiefsten Gewässer der Zangarmarschen.",

            ),

            3715 => array(
                "name" => "Die Dampfkammer",
                "heroic" => 1,
                "level" => "67-70",
                "label" => "the-steamvault",
                "location" => "Der Echsenkessel, Zangarmarschen",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Der Echsenkessel ist der Name für ein von den Naga dominiertes Gelände unterhalb der tiefsten Gewässer der Zangarmarschen.",

            ),

            3717 => array(
                "name" => "Die Sklavenunterkünfte",
                "heroic" => 1,
                "level" => "60-69",
                "heroic_level" => "70",
                "label" => "the-slave-pens",
                "location" => "Der Echsenkessel, Zangarmarschen",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Der Echsenkessel ist der Name für ein von den Naga dominiertes Gelände unterhalb der tiefsten Gewässer der Zangarmarschen.",

            ),

            3848 => array(
                "name" => "Die Arkatraz",
                "heroic" => 1,
                "level" => "68-70",
                "label" => "the-arcatraz",
                "location" => "Festung der Stürme, Nethersturm",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Die mächtige Festung der Stürme wurde von den mysteriösen Naaru erschaffen: empfindungsfähigen Wesen aus reiner Energie und die Erzfeinde der Brennenden Legion.",

            ),

            3847 => array(
                "name" => "Die Botanika",
                "heroic" => 1,
                "level" => "67-70",
                "label" => "the-botanica",
                "location" => "Festung der Stürme, Nethersturm",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Die mächtige Festung der Stürme wurde von den mysteriösen Naaru erschaffen: empfindungsfähigen Wesen aus reiner Energie und die Erzfeinde der Brennenden Legion.",

            ),

            3849 => array(
                "name" => "Die Mechanar",
                "heroic" => 1,
                "level" => "67-70",
                "label" => "the-mechanar",
                "location" => "Festung der Stürme, Nethersturm",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Die mächtige Festung der Stürme wurde von den mysteriösen Naaru erschaffen: empfindungsfähigen Wesen aus reiner Energie und die Erzfeinde der Brennenden Legion.",

            ),

            2367 => array(
                "name" => "Die Flucht von Durnholde",
                "heroic" => 1,
                "level" => "64-70",
                "label" => "the-escape-from-durnholde",
                "location" => "Höhlen der Zeit, Tanaris",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Tief in den Höhlen der Zeit, ist der brütende Drache Nozdormu erwacht.",

            ),

            2366 => array(
                "name" => "Ã–ffnung des Dunklen Portals",
                "heroic" => 1,
                "level" => "68-70",
                "label" => "opening-of-the-dark-portal",
                "location" => "Höhlen der Zeit, Tanaris",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Tief in den Höhlen der Zeit, ist der brütende Drache Nozdormu erwacht.",

            ),

            3713 => array(
                "name" => "Der Blutkessel",
                "heroic" => 1,
                "level" => "59-68",
                "heroic_level" => "70",
                "label" => "the-blood-furnace",
                "location" => "Höllenfeuerzitadelle, Höllenfeuerhalbinsel",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Im Herzen der kargen Höllenfeuerhalbinsel der Scherbenwelt steht die Höllenfeuerzitadelle, eine nahezu uneinnehmbare Bastion, die der Horde als Operationsbasis während der Ersten und Zweiten Kriege diente.",

            ),

            3714 => array(
                "name" => "Die Zerschmetterten Hallen",
                "heroic" => 1,
                "level" => "67-70",
                "label" => "the-shattered-halls",
                "location" => "Höllenfeuerzitadelle, Höllenfeuerhalbinsel",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Im Herzen der kargen Höllenfeuerhalbinsel der Scherbenwelt steht die Höllenfeuerzitadelle, eine nahezu uneinnehmbare Bastion, die der Horde als Operationsbasis während der Ersten und Zweiten Kriege diente.",

            ),


            3562 => array(
                "name" => "Höllenfeuerbollwerk",
                "heroic" => 1,
                "level" => "57-67",
                "heroic_level" => "70",
                "label" => "hellfire-ramparts",
                "location" => "Höllenfeuerzitadelle, Höllenfeuerhalbinsel",
                "type" => "Dungeon",
                "expansion" => 1,
                "intro" => "Im Herzen der kargen Höllenfeuerhalbinsel der Scherbenwelt steht die Höllenfeuerzitadelle, eine nahezu uneinnehmbare Bastion, die der Horde als Operationsbasis während des Ersten und Zweiten Krieges diente.",

            ),

            3959 => array(
                "name" => "Der Schwarze Tempel",
                "heroic" => 0,
                "level" => "70",
                "label" => "black-temple",
                "location" => "Schattenmondtal",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Vor langer Zeit war auf Draenor der Tempel von Karabor das religiöse Zentrum für die Draenei.",

            ),

            4075 => array(
                "name" => "Der Sonnenbrunnen",
                "heroic" => 0,
                "level" => "70",
                "label" => "the-sunwell",
                "location" => "Insel von Quel&#39;Danas",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Vor langer Zeit strahlte der Sonnenbrunnen seine Magie für alle Hochelfen in ganz Azeroth aus.",

            ),

            3606 => array(
                "name" => "Die Schlacht um den Hyjal",
                "heroic" => 0,
                "level" => "70",
                "label" => "the-battle-for-mount-hyjal",
                "location" => "Höhlen der Zeit",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Tief in den Höhlen der Zeit, ist der brütende Drache Nozdormu erwacht.",

            ),

            3845 => array(
                "name" => "Festung der Stürme",
                "heroic" => 0,
                "level" => "70",
                "label" => "tempest-keep",
                "location" => "Nethersturm",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Die mächtige Festung der Stürme wurde von den mysteriösen Naaru erschaffen: empfindungsfähigen Wesen aus reiner Energie und die Erzfeinde der Brennenden Legion.",

            ),

            3923 => array(
                "name" => "Gruuls Unterschlupf",
                "heroic" => 0,
                "level" => "70",
                "label" => "gruuls-lair",
                "location" => "Schergrat",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Als der berüchtigte Drachenaspekt Todesschwinge das Dunkle Portal und Draenor entdeckte, war er sich sicher, dass nur wenige der Bewohner Draenors es wagen würden, die Drachen herauszufordern, und so zog er während des Zweiten Krieges nach Draenor und versteckte dort auf der ganzen Welt seine Gelege.",

            ),

            3607 => array(
                "name" => "Höhle des Schlangenschreins",
                "heroic" => 0,
                "level" => "70",
                "label" => "serpentshrine-cavern",
                "location" => "Zangarmarschen",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Wer auch immer das Wasser kontrolliert, kontrolliert die Scherbenwelt.",

            ),

            3457 => array(
                "name" => "Karazhan",
                "heroic" => 0,
                "level" => "70",
                "label" => "karazhan",
                "location" => "Gebirgspass der Totenwinde",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Medivh, der letzte Wächter, hatte den Turm von Karazhan am Pass der Totenwinde zu seiner Heimat gemacht.",

            ),

            3836 => array(
                "name" => "Magtheridons Kammer",
                "heroic" => 0,
                "level" => "70",
                "label" => "magtheridons-lair",
                "location" => "Höllenfeuerhalbinsel",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Nachdem die Legion in der Scherbenwelt eintraf, brachte Magtheridon alles bis auf einige wenige Winkel unter seine Kontrolle.",

            ),

            209 => array(
                "name" => "Burg Schattenfang",
                "level" => "16-26",
                "label" => "shadowfang-keep",
                "location" => "Silberwald",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Die verlassenen Ruinen der Burg Schattenfang überragen das Dorf Lohenscheit im Silberwald.",

            ),

            2437 => array(
                "name" => "Der Flammenschlund",
                "heroic" => 0,
                "level" => "15-21",
                "label" => "ragefire-chasm",
                "location" => "Orgrimmar",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Der Flammenschlund besteht aus einer Reihe vulkanischer Höhlen, die unter Orgrimmar verlaufen, der neuen Hauptstadt der Orcs.",

            ),

            2557 => array(
                "name" => "Düsterbruch",
                "heroic" => 0,
                "level" => "36-52",
                "label" => "dire-maul",
                "location" => "Feralas",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Vor fast zwölftausend Jahren errichtete eine geheime Sekte nachtelfischer Zauberer die uralte Stadt Eldre'Thalas, um die wertvollsten Geheimnisse von Königin Azshara zu schützen.",

            ),

            721 => array(
                "name" => "Gnomeregan",
                "heroic" => 0,
                "level" => "24-34",
                "label" => "gnomeregan",
                "location" => "Dun Morogh",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Gnomeregan war seit ungezählten Generationen die Hauptstadt der Gnome, eine Stadt, wie es sie davor noch nie in Azeroth gegeben hatte, wo selbst die kühnsten Träume der gnomischen Tüftler wahr wurden.",

            ),

            718 => array(
                "name" => "Höhlen des Wehklagens",
                "heroic" => 0,
                "level" => "15-25",
                "label" => "wailing-caverns",
                "location" => "Nördliches Brachland",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Vor nicht allzu langer Zeit entdeckte ein nachtelfischer Druide namens Naralex eine Reihe unterirdischer Höhlen im Herzen des Brachlands.",

            ),

            722 => array(
                "name" => "Hügel der Klingenhauer",
                "heroic" => 0,
                "level" => "40-50",
                "label" => "razorfen-downs",
                "location" => "Südliches Brachland",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Hügel der Klingenhauer, die von den selben dornigen Ranken wie der Kral der Klingenhauer dominiert werden, beherbergen seit jeher die Hauptstadt des Volks der Stacheleber.",

            ),

            491 => array(
                "name" => "Kral der Klingenhauer",
                "heroic" => 0,
                "level" => "30-40",
                "label" => "razorfen-kraul",
                "location" => "Südliches Brachland",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Vor zehntausend Jahren, zum Höhepunkt des Kriegs der Uralten, betrat der mächtige Halbgott Agamaggan das Schlachtfeld, um sich der Brennenden Legion entgegenzustellen.",

            ),

            2100 => array(
                "name" => "Maraudon",
                "heroic" => 0,
                "level" => "30-44",
                "label" => "maraudon",
                "location" => "Desolace",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Maraudon, eine der heiligsten Stätten in Desolace, wird von den wilden Maraudinezentauren beschützt.",

            ),

            796 => array(
                "name" => "Scharlachrotes Kloster",
                "heroic" => 0,
                "level" => "26-45",
                "label" => "scarlet-monastery",
                "location" => "Tirisfal",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Das Kloster war einst eine stolze Bastion der Priesterschaft von Lordaeron - ein Zentrum des Wissens sowie der Erleuchtung.",

            ),

            2057 => array(
                "name" => "Scholomance",
                "heroic" => 0,
                "level" => "38-48",
                "label" => "scholomance",
                "location" => "Westliche Pestländer",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Die Scholomance ist ein weitläufiges Netzwerk unterirdischer Krypten, das sich unter der verfallenen Darrowehr erstreckt.",

            ),

            1583 => array(
                "name" => "Schwarzfelsspitze",
                "heroic" => 0,
                "level" => "55-60",
                "label" => "blackrock-spire",
                "location" => "Der Schwarzfels",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Die mächtige Festung, die in die feurigen Eingeweide des Schwarzfels gehauen ist, wurde vom zwergischen Meistersteinmetz Franclorn Schmiedevater entworfen.",

            ),

            1584 => array(
                "name" => "Schwarzfelstiefen",
                "heroic" => 0,
                "level" => "47-60",
                "label" => "blackrock-depths",
                "location" => "Der Schwarzfels",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Die vulkanischen Schwarzfelstiefen sind Tunnel von ungeheurem Ausmaß unterhalb des Schwarzfels, Heimat der Dunkeleisenzwerge und Standort der Schwarzen Schmiede, den sie verwenden, um das gleichnamige Erz zu schmelzen.",

            ),


            2017 => array(
                "name" => "Stratholme",
                "heroic" => 0,
                "level" => "42-56",
                "label" => "stratholme",
                "location" => "Östliche Pestländer",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Die Stadt Stratholme war einst das Kronjuwel des nördlichen Lordaerons.",

            ),

            719 => array(
                "name" => "Tiefschwarze Grotte",
                "heroic" => 0,
                "level" => "20-30",
                "label" => "blackfathom-deeps",
                "location" => "Eschental",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Am Zoramstrand des Eschentals liegt die Tiefschwarze Grotte, einst ein glorreicher Tempel, der Mondgöttin der Nachtelfen, Elune, gewidmet.",

            ),

            1581 => array(
                "name" => "Todesminen",
                "level" => "15-21",
                "label" => "deadmines",
                "location" => "Westfall",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Es heißt, die Goldvorräte der Todesminen hätten einst ein Drittel der Schatzreserven von Sturmwind dargestellt.",

            ),

            1337 => array(
                "name" => "Uldaman",
                "heroic" => 0,
                "level" => "35-45",
                "label" => "uldaman",
                "location" => "Ödland",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Uldaman ist ein uraltes titanisches Verlies, das seit der Zeit der Titanen tief unter der Erde verborgen lag.",

            ),

            717 => array(
                "name" => "Verlies von Sturmwind",
                "heroic" => 0,
                "level" => "20-30",
                "label" => "stormwind-stockade",
                "location" => "Sturmwind",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Das Verlies ist ein Hochsicherheitsgefängnis, verborgen unterhalb des Kanaldistrikts von Sturmwind.",

            ),

            1477 => array(
                "name" => "Versunkener Tempel",
                "heroic" => 0,
                "level" => "50-60",
                "label" => "sunken-temple",
                "location" => "Sümpfe des Elends",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Vor mehr als tausend Jahren wurde das mächtige Reich der Gurubashi von einem gewaltigen Bürgerkrieg auseinandergerissen.",

            ),

            1176 => array(
                "name" => "Zul&#39;Farrak",
                "heroic" => 0,
                "level" => "44-54",
                "label" => "zulfarrak",
                "location" => "Tanaris",
                "type" => "Dungeon",
                "expansion" => 0,
                "intro" => "Unter der brennenden Sonne von Tanaris liegt die Hauptstadt der Trolle des Sandwüterstamms, die wegen ihrer Ruchlosigkeit und Grausamkeit gefürchtet sind.",

            ),

            2717 => array(
                "name" => "Geschmolzener Kern",
                "heroic" => 0,
                "level" => "60",
                "label" => "molten-core",
                "location" => "Der Schwarzfels",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Der geschmolzene Kern befindet sich am tiefsten Punkt des Schwarzfelsen.",

            ),

            2677 => array(
                "name" => "Pechschwingenhort",
                "heroic" => 0,
                "level" => "60",
                "label" => "blackwing-lair",
                "location" => "Der Schwarzfels",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Der Pechschwingenhort befindet sich ganz auf dem Gipfel der Schwarzfelsspitze, einer in den Himmel ragenden Zinne aus uraltem Gestein, behauen nach den Vorgaben eines Drachen.",

            ),
            3429 => array(
                "name" => "Ruinen von Ahn&#39;Qiraj",
                "heroic" => 0,
                "level" => "60",
                "label" => "ruins-of-ahnqiraj",
                "location" => "Silithus",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "In den letzten Stunden des Krieges der Sandstürme trugen die Nachtelfen und die vier Drachenschwärme die Schlacht in das Herz des Qiraji Reichs zurück: in die Festung von Ahn&#39;Qiraj.",

            ),

            3428 => array(
                "name" => "Tempel von Ahn&#39;Qiraj",
                "heroic" => 0,
                "level" => "60",
                "label" => "ahnqiraj-temple",
                "location" => "Silithus",
                "type" => "Schlachtzug",
                "expansion" => 0,
                "intro" => "Im Herzen Ahn'Qirajs liegt ein uralter Tempelkomplex.",

            ),
        );

        $this->npc_data = array(

            // ICC
            36948 => array(
                "name" => '<span class="float-right color-q0"> Allianz<span class="icon-faction-0"></span> </span>Muradin Bronzebart',
                "closed" => true,
                "desc" => "Die Luftschiffschlacht ist ein Gefecht in der Luft zwischen den Luftschiffen Himmelsbrecher und Ogrims Hammer rund um die Spitze der Eiskronenzitadelle.",
                "side_icons" => true,
            ),

            // PDK
            34797 => array(
                "name" => "Eisheuler",
                "hp" => "1,3Mio–3,5Mio",
                "hp_hero" => "6,7Mio–18,1Mio",
                "location" => "Prüfung des Kreuzfahrers, Eiskrone",
            ),
            34796 => array(
                "name" => "Gormok der Pfähler",
                "hp" => "4,2Mio",
                "hp_hero" => "26,5Mio",
                "location" => "Prüfung des Kreuzfahrers, Eiskrone",
            ),
            34780 => array(
                "name" => "Lord Jaraxxus",
                "hp" => "4,2Mio",
                "hp_hero" => "26,5Mio",
                "location" => "Prüfung des Kreuzfahrers, Eiskrone",
            ),

            34461 => array(
                "name" => '<span class="float-right color-q0"> Allianz<span class="icon-faction-0"></span> </span> Tyrius Dämmerklinge',
                "hp" => "403,2K",
                "hp_hero" => "3,2Mio",
                "type" => "Humanoid, Elementar",
                "location" => "Prüfung des Kreuzfahrers, Eiskrone",
            ),

            34496 => array(
                "name" => "Eydis Nachtbann",
                "hp" => "6,1Mio",
                "hp_hero" => "39Mio",
                "type" => "Untoter",
                "location" => "Prüfung des Kreuzfahrers, Eiskrone",
            ),

            34564 => array(
                "name" => "Anub&#39;arak",
                "closed" => true,
                "hp" => "4,2Mio",
                "hp_hero" => "27,2Mio",
                "type" => "Untoter",
                "location" => "Prüfung des Kreuzfahrers, Eiskrone",
            ),

            // Ulduar
            //"Die Vorkammer von Ulduar" => array(32927,32930,33515),
            32927 => array(
                "closed" => true,
            ),
            32930 => array(
                "closed" => true,
            ),
            33515 => array(
                "closed" => true,
            ),
            //"Die Hüter von Ulduar" => array(32845,32865,32906,33350),
            32845 => array(
                "closed" => true,
            ),
            32865 => array(
                "closed" => true,
            ),
            32906 => array(
                "closed" => true,
            ),
            33350 => array(
                "closed" => true,
            ),
            //"Der Abstieg in den Wahnsinn" => array(33271,33288),
            33271 => array(
                "closed" => true,
            ),
            33288 => array(
                "closed" => true,
            ),
            //"Das Himmlische Planetarium" => array(32871),
            32871 => array(
                "closed" => true,
            ),


        );
    }

}