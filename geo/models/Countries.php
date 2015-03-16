<?php

namespace yii\platform\geo\models;

use yii\platform\P;

/**
 * This is the model class for table "countries".
 *
 * @property string $iso_alpha2
 * @property string $iso_alpha3
 * @property integer $iso_numeric
 * @property string $fips_code
 * @property string $name
 * @property string $capital
 * @property string $areainsqkm
 * @property integer $population
 * @property string $continent
 * @property string $tld
 * @property string $currency
 * @property string $currency_name
 * @property string $phone
 * @property string $postal_code_format
 * @property string $postal_code_regex
 * @property string $languages
 * @property integer $geoname_id
 * @property string $neighbours
 * @property string $equivalent_fips_code
 * @property integer $create_time
 * @property integer $update_time
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    self::EVENT_BEFORE_UPDATE => 'update_time',
                ],
            ]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iso_alpha2', 'create_time', 'update_time'], 'required'],
            [['iso_numeric', 'population', 'geoname_id', 'create_time', 'update_time'], 'integer'],
            [['areainsqkm'], 'number'],
            [['iso_alpha2', 'continent'], 'string', 'max' => 2],
            [['iso_alpha3', 'fips_code', 'tld', 'currency'], 'string', 'max' => 3],
            [['name', 'capital', 'languages'], 'string', 'max' => 255],
            [['currency_name', 'postal_code_format', 'postal_code_regex', 'neighbours'], 'string', 'max' => 20],
            [['phone', 'equivalent_fips_code'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'iso_alpha2' => 'Iso Alpha2',
            'iso_alpha3' => 'Iso Alpha3',
            'iso_numeric' => 'Iso Numeric',
            'fips_code' => 'Fips Code',
            'name' => 'Name',
            'capital' => 'Capital',
            'areainsqkm' => 'Areainsqkm',
            'population' => 'Population',
            'continent' => 'Continent',
            'tld' => 'Tld',
            'currency' => 'Currency',
            'currency_name' => 'Currency Name',
            'phone' => 'Phone',
            'postal_code_format' => 'Postal Code Format',
            'postal_code_regex' => 'Postal Code Regex',
            'languages' => 'Languages',
            'geoname_id' => 'Geoname ID',
            'neighbours' => 'Neighbours',
            'equivalent_fips_code' => 'Equivalent Fips Code',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}