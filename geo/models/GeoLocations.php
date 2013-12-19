<?php

namespace yii\platform\geo\models;

use yii\base\Exception;

/**
 * This is the model class for table "geo_locations".
 *
 * @property integer $id
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $postal
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
            'batchCommand' => [
                'class' => 'yii\platform\behaviors\BatchCommand'
            ]
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
            [['country'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['create_time', 'update_time'], 'integer'],
            [['country', 'region'], 'string', 'max' => 2],
            [['city'], 'string', 'max' => 255],
            [['postal'], 'string', 'max' => 12]
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
            'postal' => 'Postal',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
    
    /**
     * @return \yii\db\ActiveRelation
     */
    public function getPoint()
    {
        return $this->hasOne(GeoLocationPoint::className(), ['id' => 'id']);
    }
}