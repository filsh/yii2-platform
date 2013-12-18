<?php

namespace yii\platform\updaters;

use yii\platform\Platform;
use yii\platform\helpers\FileHelper;
use yii\platform\geo\models\GeoLocations;
use yii\platform\geo\models\GeoLocationBlock;
use yii\base\Exception;

class MaxmindUpdater extends BaseUpdater
{
    const CSV_FILE_LOCATION = 'GeoLiteCity-Location.csv';
    const CSV_FILE_BLOCKS = 'GeoLiteCity-Blocks.csv';
    
    public $sourceUrl;
    
    public function run()
    {
        $this->addLog('process');
        
        FileHelper::loadFile($this->sourceUrl, [
            'destDir' => $this->tmpPath,
            'callback' => [$this, 'resolveZip']
        ]);
        
        $this->addLog('process');
    }
    
    public function resolveZip($file)
    {
        if(!is_file($file)) {
            throw new Exception('Source file not found.');
        }
        
        if(!class_exists('\ZipArchive')) {
            throw new Exception('Not exist ZipArchive class, your must install PECL zip library.');
        }
        
        $z = new \ZipArchive();
        $z->open($file);
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
    }
    
    protected function resolveCsv($name, $file)
    {
        switch($name) {
            case self::CSV_FILE_LOCATION:
                    $this->applyFile(new GeoLocations(), $file);
                break;
            case self::CSV_FILE_BLOCKS:
                    $this->applyFile(new GeoLocationBlock(), $file);
                break;
        }
    }
    
    protected function applyFile($model, $file)
    {
        $model->applyCsvFile($file);
    }
}