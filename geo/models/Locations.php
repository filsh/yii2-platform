<?php

namespace yii\platform\geo\models;

use yii\platform\helpers\GeoHelper;
use yii\base\Exception;

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
    public $distance;
    
    public $point;
    
    public $start;
    
    public $end;
    
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\AutoTimestamp',
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
        return 'locations';
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
        return $this->hasOne(LocationPoint::className(), ['id' => 'id']);
    }
    
    /**
     * @param ActiveQuery $query
     * @param type $lat
     * @param type $lng
     * @param type $dist
     * @return type
     * @throws Exception
     */
    public static function fromPoint($query, $lat, $lng, $dist = 30)
    {
        if (!is_float($lat) || !is_float($lng)) {
            throw new Exception('Location coords is not valid.');
        }
        
        $query->from(self::tableName() . ' l');
        $query->innerJoin(LocationPoint::tableName() . ' p', 'l.id=p.id');
        $query->select('*, ' . GeoHelper::createDistanceCondition($lat, $lng) . ' AS distance');
        $query->andWhere(GeoHelper::createPoligonCriteria($lat, $lng, $dist));
        $query->orderBy('distance');
    }
    
    /**
     * @param type $query
     * @param type $addr
     * @throws AppException
     */
    public static function fromBlock($query, $addr)
    {
        if (!is_scalar($addr)) {
            throw new AppException('Location address is not valid.');
        }
        
        $block = LocationBlock::find()
                ->where(':addr BETWEEN start AND end', [':addr' => ip2long($addr)])
                ->one();
        
        if($block !== null) {
            $query->andWhere('id = :id', [':id' => $block->id]);
        } else {
            $query->andWhere(0);
        }
    }
}