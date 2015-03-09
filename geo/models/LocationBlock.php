<?php

namespace yii\platform\geo\models;

use yii\platform\P;

/**
 * This is the model class for table "location_block".
 *
 * @property integer $id
 * @property integer $start
 * @property integer $end
 *
 * @property Locations $id0
 */
class LocationBlock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%location_block}}';
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
        return $this->hasOne(Locations::className(), ['id' => 'id']);
    }
}