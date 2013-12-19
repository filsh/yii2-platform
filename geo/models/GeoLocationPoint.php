<?php

namespace yii\platform\geo\models;

/**
 * This is the model class for table "geo_location_point".
 *
 * @property integer $id
 * @property string $point
 */
class GeoLocationPoint extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'geoLocation' => [
                'class' => 'yii\platform\geo\behaviors\GeoLocation',
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_location_point';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['point'], 'required'],
            [['point'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'point' => 'Point',
        ];
    }
    
    /**
     * @return \yii\db\ActiveRelation
     */
    public function getLocation()
    {
        return $this->hasOne(GeoLocations::className(), ['id' => 'id']);
    }
}