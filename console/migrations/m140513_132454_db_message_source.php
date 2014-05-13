<?php

use yii\db\Schema;
use yii\platform\i18n\models\Message;
use yii\platform\i18n\models\MessageSource;

class m140513_132454_db_message_source extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable(MessageSource::tableName(), [
            'id' => Schema::TYPE_PK,
            'category' => Schema::TYPE_STRING . '(32) NOT NULL',
            'message' => Schema::TYPE_TEXT . ' NOT NULL',
            'create_time' => Schema::TYPE_INTEGER . ' NOT NULL',
            'update_time' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);
        
        $this->createTable(Message::tableName(), [
            'id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'language' => Schema::TYPE_STRING . '(16) NOT NULL',
            'translation' => Schema::TYPE_TEXT . ' NOT NULL',
            'create_time' => Schema::TYPE_INTEGER . ' NOT NULL',
            'update_time' => Schema::TYPE_INTEGER . ' NOT NULL',
            'PRIMARY KEY(`id`, `language`)',
            'FOREIGN KEY (`id`) REFERENCES ' . MessageSource::tableName() . ' (`id`) ON DELETE CASCADE ON UPDATE RESTRICT'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(Message::tableName());
        $this->dropTable(MessageSource::tableName());
    }
}
