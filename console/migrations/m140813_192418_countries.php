<?php

use yii\db\Schema;
use yii\platform\geo\models\Countries;

class m140813_192418_countries extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
	
        $this->createTable(Countries::tableName(), [
            'iso_alpha2' => Schema::TYPE_STRING . '(2)',
            'iso_alpha3' => Schema::TYPE_STRING . '(3)',
            'iso_numeric' => Schema::TYPE_INTEGER,
            'fips_code' => Schema::TYPE_STRING . '(3)',
            'name' => Schema::TYPE_STRING . '(255)',
            'capital' => Schema::TYPE_STRING . '(255)',
            'areainsqkm' => Schema::TYPE_DECIMAL,
            'population' => Schema::TYPE_INTEGER,
            'continent' => Schema::TYPE_STRING . '(2)',
            'tld' => Schema::TYPE_STRING . '(3)',
            'currency' => Schema::TYPE_STRING . '(3)',
            'currency_name' => Schema::TYPE_STRING . '(20)',
            'phone' => Schema::TYPE_STRING . '(10)',
            'postal_code_format' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'postal_code_regex' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'languages' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'geoname_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'neighbours' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'equivalent_fips_code' => Schema::TYPE_STRING . '(DEFAULT NULL) DEFAULT NULL',
            'create_time' => Schema::TYPE_INTEGER.' NOT NULL',
            'update_time' => Schema::TYPE_INTEGER.' NOT NULL',
            'PRIMARY KEY (`iso_alpha2`)'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(Countries::tableName());
    }
}
