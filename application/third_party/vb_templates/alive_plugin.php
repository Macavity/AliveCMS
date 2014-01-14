<?php // Erste Zeile nicht in vBulletin reinkopieren

/*
Speichert Vote-Punkte und Game-Account-ID in der Session
*/
global $vbulletin;

$chosen_char = array(
    "css_class" => 'nochars',
    "name" => "",
    "class" => 0,
    "gender" => 0,
    "race" => 0,
    "bb" => "init-"//print_r($vbulletin, true)."x"
);

global $chosen_char;


if ($vbulletin->session->vars['game_account_id'] <= 0 || $vbulletin->session->vars['game_character_id'] <= 0 || true)
{
    $vbulletin->session->set('gameaccount_id', -1);

    $vbulletin->session->db_fields = array_merge($vbulletin->session->db_fields, array(
        'game_account_id' => TYPE_INT,
        'game_character_id' => TYPE_INT,
        'game_can_vote' => TYPE_INT,
        'game_charname' => TYPE_NOHTML,
        'game_charclass' => TYPE_INT,
        'game_charrace' => TYPE_INT,
        'game_chargender' => TYPE_INT,
    ));

    $request_data = array();
    $conn = mysql_connect("178.63.89.20:19872", "takeshi", "DRqLnz45HeXGNcWD");
    if($conn && mysql_select_db("data_portal", $conn))
    {
        $result = mysql_query('SELECT *
            FROM account_data
            WHERE forum_account_id = "'.$vbulletin->userinfo['userid'].'";', $conn);

        if($request_data = mysql_fetch_array($result))
        {
            $activeRealm = (int) $request_data['active_realm_id'];
            $activeChar = mysql_real_escape_string($request_data['character_id']);

            if($request_data["active_char_guid"] > 0 && $activeRealm > 0)
            {

                $realmResult = mysql_query('SELECT id, char_database FROM realms WHERE id = '.$activeRealm.';');

                if($realmResult && $realmRow = mysql_fetch_array($realmResult)){
                    mysql_select_db($realmRow['char_database'], $conn);
                }
                else{
                    mysql_select_db('live_char', $conn);
                }

                $resultChar = mysql_query("SELECT name, class, race, gender FROM characters WHERE guid = ".$activeChar.";", $conn);
                if($rowChar = mysql_fetch_array($resultChar))
                {
                    $request_data = array_merge($request_data, $rowChar);
                }
                else{
                    $chosen_char["bb"] .= "-nc".mysql_error();
                }
            }
        }
        else{
            $chosen_char["bb"] .= "-!!".mysql_error();
        }
    }

    $chosen_char["bb"] .= print_r($request_data, true);

    if(isset($request_data["id"]) && is_numeric($request_data["id"]))
    {

        $chosen_char["bb"] .= "b";

        $vbulletin->session->set('game_account_id', $request_data["id"]);
        $vbulletin->session->set('game_character_id', $activeChar);
        $vbulletin->session->set('game_can_vote', true);

        // Wenn Character im Portal ausgewählt
        if( isset($request_data["name"]) )
        {
            $vbulletin->session->set('game_charname', $request_data["name"]);
            $vbulletin->session->set('game_charclass', $request_data["class"]);
            $vbulletin->session->set('game_charrace', $request_data["race"]);
            $vbulletin->session->set('game_chargender', $request_data["gender"]);
        }
    }
    $chosen_char["bb"] .= "x";
}
$chosen_char["bb"] .= "AA";

$game_acc_connected = ($vbulletin->session->vars['game_account_id'] > 0) ? 1 : 0;
$game_can_vote = $vbulletin->session->vars['game_can_vote'];

if($vbulletin->session->vars['game_character_id'] > 0)
{
    $race = $vbulletin->session->vars['game_charrace'];
    $css = ( in_array($race, array(1,3,4,7,11)) ) ? 'plate-alliance' : 'plate-horde';
    $chosen_char["css_class"] = $css;
    $chosen_char["url"] = "http://cms.wow-alive.de/character/1/".$vbulletin->session->vars['game_charname'];
    $chosen_char["name"] = $vbulletin->session->vars['game_charname'];
    $chosen_char["race"] = $vbulletin->session->vars['game_charrace'];
    $chosen_char["class"] = $vbulletin->session->vars['game_charclass'];
    $chosen_char["gender"] = $vbulletin->session->vars['game_chargender'];
}