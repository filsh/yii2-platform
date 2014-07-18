<?php

namespace yii\platform\geo\models;

use yii\platform\helpers\GeoHelper;
use yii\base\Exception;
use yii\db\ActiveQuery;

class LocationQuery extends ActiveQuery
{
    /**
     * @param type $lat
     * @param type $lng
     * @param type $dist
     * @return type
     * @throws Exception
     */
    public function fromPoint($lat, $lng, $dist = 30)
    {
        if (!is_float($lat) || !is_float($lng)) {
            throw new Exception('Location coords is not valid.');
        }
        
        $this->from(Locations::tableName() . ' l');
        $this->innerJoin(LocationPoint::tableName() . ' p', 'l.id=p.id');
        $this->select('*, ' . GeoHelper::createDistanceCondition($lat, $lng) . ' AS distance');
        $this->andWhere(GeoHelper::createPoligonCriteria($lat, $lng, $dist));
        $this->orderBy('distance');
        return $this;
    }
    
    /**
     * @param type $addr
     * @throws AppException
     */
    public function fromBlock($addr)
    {
        if (!is_scalar($addr)) {
            throw new Exception('Location address is not valid.');
        }
        
        $block = LocationBlock::find()
                ->where(':addr BETWEEN start AND end', [':addr' => ip2long($addr)])
                ->one();
        
        if($block !== null) {
            $this->andWhere('id = :id', [':id' => $block->id]);
        } else {
            $this->andWhere(0);
        }
        return $this;
    }
}