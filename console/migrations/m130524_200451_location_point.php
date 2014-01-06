<?php

use yii\db\Schema;

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

        $this->createTable('location_point', [
            'id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL PRIMARY KEY',
            'point' => 'point NOT NULL',
            'SPATIAL KEY (`point`)'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('location_point');
    }
}
