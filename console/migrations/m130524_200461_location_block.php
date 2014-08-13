<?php

use yii\db\Schema;
use yii\platform\geo\models\LocationBlock;

class m130524_200461_location_block extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(LocationBlock::tableName(), [
            'id' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL',
            'start' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL',
            'end' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL PRIMARY KEY',
            'KEY (`start`, `end`)',
            'FOREIGN KEY (`id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(LocationBlock::tableName());
    }
}
