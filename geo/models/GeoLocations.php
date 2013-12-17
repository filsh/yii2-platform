<?php

namespace yii\platform\geo\models;

/**
 * This is the model class for table "geo_locations".
 *
 * @property integer $id
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $latitude
 * @property string $longitude
 * @property integer $create_time
 * @property integer $update_time
 */
class GeoLocations extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\AutoTimestamp',
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    self::EVENT_BEFORE_UPDATE => 'update_time',
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_locations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country', 'latitude', 'longitude'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['create_time', 'update_time'], 'integer'],
            [['country', 'region'], 'string', 'max' => 2],
            [['city'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Country',
            'region' => 'Region',
            'city' => 'City',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}