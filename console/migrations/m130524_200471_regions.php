<?php

use yii\db\Schema;
use yii\platform\geo\models\Regions;

class m130524_200471_regions extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(Regions::tableName(), [
            'country' => Schema::TYPE_STRING . '(2) NOT NULL',
            'region' => Schema::TYPE_STRING . '(2) NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'create_time' => Schema::TYPE_INTEGER.' NOT NULL',
            'update_time' => Schema::TYPE_INTEGER.' NOT NULL',
            'PRIMARY KEY (`country`, `region`)'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(Regions::tableName());
    }
}
