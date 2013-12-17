<?php

use yii\db\Schema;

class m130524_200461_geo_location_ip extends \yii\db\Migration
{
//    protected $options = 'ENGINE=MyISAM CHARSET=utf8';
//    
//    public function up()
//    {
//        $this->createTable('{{geo_location_ip}}', array(
//            'geo_ipblocks_start' => 'int(10) unsigned NOT NULL',
//            'geo_ipblocks_end' => 'int(10) unsigned NOT NULL',
//            'geo_ipblocks_locations_id' => 'int(11) unsigned NOT NULL',
//            'PRIMARY KEY (`geo_ipblocks_end`)',
//            'KEY (`geo_ipblocks_start`, `geo_ipblocks_end`)',
//            'FOREIGN KEY (`geo_ipblocks_locations_id`) REFERENCES `{{geo_locations}}` (`geo_locations_id`) ON DELETE CASCADE ON UPDATE CASCADE'
//        ), $this->options);
//    }
//
//    public function down()
//    {
//        $this->dropTable('{{geo_ipblocks}}');
//    }
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('geo_location_ip', [
            'id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL',
            'start' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL',
            'end' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL PRIMARY KEY',
            'KEY (`start`, `end`)',
            'FOREIGN KEY (`id`) REFERENCES `geo_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('geo_location_ip');
    }
}
