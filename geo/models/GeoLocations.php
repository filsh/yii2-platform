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
    
    public static function loadCsvFile($file)
    {
        if(!file_exists($file)) {
            throw new Exception('CSV Location source file is not exists.');
        }
        
        $columns = ['id', 'country', 'region', 'city', 'postal', 'latitude', 'longitude', 'create_time', 'update_time'];
        $rows = [];
        $keys = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if(intval($data[0]) === 0) {
                    continue;
                }
                
                $keys[] = (int) $data[0];
                $rows[] = [
                    (int) $data[0],
                    trim($data[1]),
                    trim($data[2]),
                    trim($data[3]),
                    trim($data[4]),
                    (float) $data[5],
                    (float) $data[6],
                    time(),
                    time()
                ];
                
                if(count($rows) === 1000) {
                    self::batchReplace($keys, $columns, $rows);
                    $keys = $rows = [];
                }
            }
            
            if(count($rows) > 0) {
                self::batchReplace($keys, $columns, $rows);
            }
            
            fclose($handle);
        }
    }
    
    public static function batchReplace($keys, $columns, $rows)
    {
        $keys = is_array($keys) ? $keys : [$keys];
        $connection = self::getDb();
        $transaction = $connection->beginTransaction();
        
        try {
            $connection->createCommand()->delete(self::tableName(), ['id' => $keys])->execute();
            $connection->createCommand()->batchInsert(self::tableName(), $columns, $rows)->execute();
            $transaction->commit();
        } catch (yii\db\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}