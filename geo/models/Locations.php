<?php

namespace yii\platform\geo\models;

use yii\platform\P;

/**
 * This is the model class for table "locations".
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
class Locations extends \yii\db\ActiveRecord
{
    const FIND_REGION_DISTANCE = 100;
    
    public $distance;
    
    public $point;
    
    public $start;
    
    public $end;
    
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
    public static function getDb()
    {
        return P::$app->get('pdb');
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%locations}}';
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
     * @inheritdoc
     */
    public static function find()
    {
        return new LocationQuery(get_called_class());
    }
    
    /**
     * @return \yii\db\ActiveRelation
     */
    public function getPoint()
    {
        return $this->hasOne(LocationPoint::className(), ['id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveRelation
     */
    public function getTimezone(array $timezones = [])
    {
        $region = $this->region;
        if(empty($region)) {
            $location = self::find()
                ->fromPoint((float)$this->latitude, (float)$this->longitude, self::FIND_REGION_DISTANCE)
                ->andWhere('region IS NOT NULL AND region <> \'\'')
                ->andWhere('country = :country', [':country' => $this->country])
                ->limit(1)
                ->one();
            
            $region = $location->region;
        }
        
        $query = Timezones::find()->where('country = :country', [':country' => $this->country]);
        if(!empty($region)) {
            $cloned = clone $query;
            $cloned->andWhere('region = :region', [':region' => $region]);
            $cloned->orderBy('region DESC');
            
            if($cloned->exists()) {
                $query = $cloned;
            }
        }
        if(!empty($timezones)) {
            $query->andWhere(['timezone' => $timezones]);
        }
        
        return $query;
    }
}