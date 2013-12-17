<?php

use yii\db\Schema;

class m130524_200441_geo_locations extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('geo_locations', [
            'id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL PRIMARY KEY',
            'country' => Schema::TYPE_STRING . '(2) NOT NULL',
            'region' => Schema::TYPE_STRING . '(2) NOT NULL DEFAULT ""',
            'city' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT ""',
            'latitude' => Schema::TYPE_DECIMAL . '(10,7) NOT NULL',
            'longitude' => Schema::TYPE_DECIMAL . '(10,7) NOT NULL',
            'create_time' => Schema::TYPE_INTEGER.' NOT NULL',
            'update_time' => Schema::TYPE_INTEGER.' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('geo_locations');
    }
}
