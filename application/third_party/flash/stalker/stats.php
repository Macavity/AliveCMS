<?php

header("Content-Type: text/xml;");

$output_file = "stats.xml";

include($_SERVER['DOCUMENT_ROOT']."/application/config/database.php");


$server = $db["account"]["hostname"];

$db_user = $db["account"]["username"];
$db_passwd = $db["account"]["password"];

$base_XML =
<<<XML
<serverpage><sessions></sessions></serverpage>
XML;

/////// function block ///////

// connect to mysql server
function connectMySQL($HOST,$USER,$PASSWD) {
    return mysql_connect($HOST,$USER,$PASSWD);
}

// connect connection to a database
function selectMySQLDB($DB,$CONN) {
    return mysql_select_db($DB,$CONN);
}

// connect to character DB
function connectCharDB($HOST,$USER,$PASSWD) {    
    $connection = connectMySQL($HOST,$USER,$PASSWD);
    selectMySQLDB("live_char",$connection);
}

// close mysql connection
function closeMySQL() {
    mysql_close();
}

/////// function block end ///////


// build base xml tree
$xml_Tree = new SimpleXMLElement($base_XML);

// open sql connection
connectCharDB($server,$db_user,$db_passwd);

$sql = "SELECT
            name,
            race,
            class,
            map,
            zone,
            position_x,
            position_y,
            gender,
            level
        FROM
            characters
        WHERE
            online = 1";
        
// send sql request
$chars = mysql_query($sql);

// walk through the results and generate xml data
while ($char = mysql_fetch_array($chars)) {
    $plr = $xml_Tree->sessions[0]->addChild('plr');
    $plr->addChild('name', htmlentities($char["name"]));
    $plr->addChild('race', $char["race"]);
    $plr->addChild('class', $char["class"]);
    $plr->addChild('gender', $char["gender"]);
    $plr->addChild('level', $char["level"]);
    $plr->addChild('map', $char["map"]);
    $plr->addChild('areaid', $char["zone"]);
    $plr->addChild('xpos', $char["position_x"]);
    $plr->addChild('ypos', $char["position_y"]);
}

// close open mysql connection
closeMySQL();


// for debugging
//echo $xml_Tree->asXML();


// write the file
$file_handler = fOpen($output_file , "w");
fWrite($file_handler , $xml_Tree->asXML());
fClose($file_handler);

