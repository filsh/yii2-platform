<?php

use yii\db\Schema;

class m130524_200461_geo_location_block extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('geo_location_block', [
            'id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL',
            'start' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL',
            'end' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL PRIMARY KEY',
            'KEY (`start`, `end`)',
            'FOREIGN KEY (`id`) REFERENCES `geo_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('geo_location_block');
    }
}
