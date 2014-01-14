<?php

/**
 * Class Migration_Realmcopy
 * Docs: http://zacharyflower.com/getting-started-with-codeigniter-migrations/
 *
 * @property CI_DB_forge    $dbforge
 */
class Migration_Realmcopy extends CI_Migration {

    public function up(){
        $this->dbforge->add_field("id int(11) unsigned NOT NULL AUTO_INCREMENT");
        $this->dbforge->add_field("date_created varchar(30) DEFAULT ''");
        $this->dbforge->add_field("date_done varchar(30) DEFAULT '0'");
        $this->dbforge->add_field("target_realm int(2) NOT NULL DEFAULT '1'");
        $this->dbforge->add_field("source_realm int(2) DEFAULT NULL");
        $this->dbforge->add_field("account_id int(11) unsigned NOT NULL DEFAULT '0'");
        $this->dbforge->add_field("character_guid int(11) unsigned NOT NULL DEFAULT '0'");
        $this->dbforge->add_field("character_created_guid int(11) DEFAULT NULL");
        $this->dbforge->add_field("skills text");
        $this->dbforge->add_field("reputations text");
        $this->dbforge->add_field("items text");
        $this->dbforge->add_field("actions text");
        $this->dbforge->add_field("comment text CHARACTER SET utf8");

        $this->dbforge->add_key('id', TRUE);

        $this->dbforge->create_table('migration_realmcopy_entries', TRUE);
    }

    public function down(){
        $this->dbforge->drop_table('migration_realmcopy_entries');
    }
}