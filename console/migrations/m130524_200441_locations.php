<?php

use yii\db\Schema;
use yii\platform\geo\models\Locations;

class m130524_200441_locations extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(Locations::tableName(), [
            'id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL PRIMARY KEY',
            'country' => Schema::TYPE_STRING . '(2) NOT NULL',
            'region' => Schema::TYPE_STRING . '(2) NOT NULL DEFAULT ""',
            'city' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT ""',
            'postal' => Schema::TYPE_STRING . '(12) NOT NULL DEFAULT ""',
            'latitude' => Schema::TYPE_DECIMAL . '(10,7) DEFAULT NULL',
            'longitude' => Schema::TYPE_DECIMAL . '(10,7) DEFAULT NULL',
            'create_time' => Schema::TYPE_INTEGER.' NOT NULL',
            'update_time' => Schema::TYPE_INTEGER.' NOT NULL'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(Locations::tableName());
    }
}
