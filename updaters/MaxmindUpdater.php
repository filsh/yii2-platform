<?php

namespace yii\platform\updaters;

use yii\platform\helpers\FileHelper;
use yii\platform\geo\models\GeoLocations;
use yii\platform\geo\models\GeoLocationBlock;
use yii\base\Exception;

class MaxmindUpdater extends BaseUpdater
{
    const CSV_FILE_LOCATION = 'GeoLiteCity-Location.csv';
    const CSV_FILE_BLOCKS = 'GeoLiteCity-Blocks.csv';
    
    public $tmpPath = '@runtime/updater/maxmind';
    
    public $sourceUrl;
    
    public function run()
    {
        FileHelper::loadFile($this->sourceUrl, [
            'destDir' => $this->tmpPath,
            'onLoad' => [$this, 'resolveZip']
        ]);
    }
    
    public function resolveZip($zipFile)
    {
        if(!is_file($zipFile)) {
            throw new Exception('Source file not found.');
        }
        
        if(!class_exists('\ZipArchive')) {
            throw new Exception('Not exist ZipArchive class, your must install PECL zip library.');
        }
        
        $z = new \ZipArchive();
        $z->open($zipFile);
        $z->extractTo($this->tmpPath);
        $z->close();
        
        $files = FileHelper::findFiles($this->tmpPath);
        $names = [self::CSV_FILE_LOCATION, self::CSV_FILE_BLOCKS];
        
        foreach($names as $name) {
            foreach($files as $file) {
                if(!(FileHelper::filterPath($file, ['only' => [$name]]))) {
                    continue;
                }
                $this->resolveCsv($name, $file);
            }
        }
        unlink($zipFile);
    }
    
    protected function resolveCsv($name, $file)
    {
        switch($name) {
            case self::CSV_FILE_LOCATION:
                $this->applyLocationFile($file);
                break;
            case self::CSV_FILE_BLOCKS:
                $this->applyBlockFile($file);
                break;
        }
        
        unlink($file);
    }
    
    protected function applyLocationFile($file)
    {
        $model = new GeoLocations();
        $columns = array_keys($model->attributeLabels());
        $rows = [];
        $keys = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if(!isset($data[0]) || intval($data[0]) === 0) {
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
                
                if(count($rows) === $model->maxExecuteRows) {
                    $model->batchReplace($columns, $rows, ['id' => $keys]);
//                    $model->batchReplace($columns, $rows, ['id' => $keys]);
                    $keys = $rows = [];
                }
            }
            
            if(count($rows) > 0) {
                $model->batchReplace($columns, $rows, ['id' => $keys]);
//                $model->batchReplace($columns, $rows, ['id' => $keys]);
            }
            
            fclose($handle);
        }
    }
    
    public function applyBlockFile($file)
    {
        $model = new GeoLocationBlock();
        $columns = array_keys($model->attributeLabels());
        $rows = [];
        $keys = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if(!isset($data[2]) || intval($data[2]) === 0) {
                    continue;
                }
                
                $keys[] = (int) $data[1];
                $rows[] = [
                    (int) $data[2],
                    (int) $data[0],
                    (int) $data[1]
                ];
                
                if(count($rows) === $model->maxExecuteRows) {
                    $model->batchReplace($columns, $rows, ['end' => $keys]);
                    $keys = $rows = [];
                }
            }
            
            if(count($rows) > 0) {
                $model->batchReplace($columns, $rows, ['end' => $keys]);
            }
            
            fclose($handle);
        }
    }
}