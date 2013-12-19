<?php

namespace yii\platform\geo\models;

/**
 * This is the model class for table "geo_location_block".
 *
 * @property integer $id
 * @property integer $start
 * @property integer $end
 *
 * @property GeoLocations $id0
 */
class GeoLocationBlock extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
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
        return 'geo_location_block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'start', 'end'], 'required'],
            [['id', 'start', 'end'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start' => 'Start',
            'end' => 'End',
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