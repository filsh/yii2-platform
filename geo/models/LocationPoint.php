<?php

namespace yii\platform\geo\models;

use yii\platform\P;

/**
 * This is the model class for table "location_point".
 *
 * @property integer $id
 * @property string $point
 */
class LocationPoint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'geoLocation' => [
                'class' => 'yii\platform\geo\behaviors\Location',
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        return P::$app->get('pdb');
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%location_point}}';
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
        return $this->hasOne(Locations::className(), ['id' => 'id']);
    }
}