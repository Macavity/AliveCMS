<?php

/**
 * Class Migration_ArsenalRanking
 * Docs: http://zacharyflower.com/getting-started-with-codeigniter-migrations/
 *
 * @property CI_DB_forge    $dbforge
 */
class Migration_ArsenalRanking extends CI_Migration {

    public function up(){
        $this->dbforge->add_field("id int(11) unsigned NOT NULL AUTO_INCREMENT");
        $this->dbforge->add_field("date_changed varchar(30) DEFAULT ''");
        $this->dbforge->add_field("character_guid int(11) DEFAULT '0'");
        $this->dbforge->add_field("realm int(2) NOT NULL DEFAULT '1'");
        $this->dbforge->add_field("value int(11) NOT NULL DEFAULT '0'");
        $this->dbforge->add_field("ranking int(11) NOT NULL DEFAULT '0'");

        $this->dbforge->add_key('id', TRUE);

        $this->dbforge->create_table('arsenal_ranking', TRUE);
    }

    public function down(){
        $this->dbforge->drop_table('arsenal_ranking');
    }
}