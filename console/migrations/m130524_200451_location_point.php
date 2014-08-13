<?php

use yii\db\Schema;
use yii\platform\geo\models\LocationPoint;

class m130524_200451_location_point extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        } else {
            throw new \Exception('Platform support only mysql database.');
        }

        $this->createTable(LocationPoint::tableName(), [
            'id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL PRIMARY KEY',
            'point' => 'point NOT NULL',
            'SPATIAL KEY (`point`)'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(LocationPoint::tableName());
    }
}
