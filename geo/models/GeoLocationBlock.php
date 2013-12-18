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
    public function getId0()
    {
        return $this->hasOne(GeoLocations::className(), ['id' => 'id']);
    }
    
    public function applyCsvFile($file)
    {
        if(!file_exists($file)) {
            throw new Exception('Source CSV file is not exist.');
        }
        
        $columns = ['id', 'start', 'end'];
        $rows = [];
        $keys = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if(!isset($data[2]) || intval($data[2]) === 0) {
                    continue;
                }
                
                $keys[] = (int) $data[2];
                $rows[] = [
                    (int) $data[2],
                    (int) $data[0],
                    (int) $data[1]
                ];
                
                if(count($rows) === $this->maxExecuteRows) {
                    $this->batchUpdate($columns, $rows, ['id' => $keys]);
                    $keys = $rows = [];
                }
            }
            
            if(count($rows) > 0) {
                $this->batchUpdate($columns, $rows, ['id' => $keys]);
            }
            
            fclose($handle);
        }
    }
}