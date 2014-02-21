<?php

namespace yii\platform\geo\models;

/**
 * This is the model class for table "timezones".
 *
 * @property string $country
 * @property string $region
 * @property string $timezone
 * @property integer $create_time
 * @property integer $update_time
 */
class Timezones extends \yii\db\ActiveRecord
{
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
        return 'timezones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country', 'region', 'timezone', 'create_time', 'update_time'], 'required'],
            [['create_time', 'update_time'], 'integer'],
            [['country', 'region'], 'string', 'max' => 2],
            [['timezone'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country' => 'Country',
            'region' => 'Region',
            'timezone' => 'Timezone',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
