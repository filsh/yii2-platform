<?php

namespace yii\platform\updaters;

use yii\platform\helpers\FileHelper;
use yii\platform\geo\models\Regions;

class RegionsUpdater extends BaseUpdater
{
    public $tmpPath = '@runtime/updater/regions';
    
    public $sourceUrl;
    
    public function run()
    {
        FileHelper::loadFile($this->sourceUrl, [
            'destDir' => $this->tmpPath,
            'onLoad' => [$this, 'resolveFile']
        ]);
        
        FileHelper::removeDirectory($this->tmpPath);
    }
    
    public function resolveFile($file)
    {
        if(!is_file($file)) {
            throw new Exception('Source file not found.');
        }
        
        $this->applyCsv($file);
    }
    
    protected function applyCsv($file)
    {
        $regionColumns = ['country', 'region', 'name', 'create_time', 'update_time'];
        $regionDuplicates = ['country', 'region', 'name'];
        
        $regionRows = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if(!isset($data[0]) || count($data) !== 3) {
                    continue;
                }
                
                $regionRows[] = [
                    trim($data[0]),
                    trim($data[1]),
                    trim($data[2]),
                    time(),
                    time()
                ];
                
                if(count($regionRows) === $this->maxExecuteRows) {
                    $this->batchInsertDuplicate(Regions::tableName(), $regionColumns, $regionRows, $regionDuplicates)->execute();
                    $regionRows = [];
                }
            }
            
            if(count($regionRows) > 0) {
                $this->batchInsertDuplicate(Regions::tableName(), $regionColumns, $regionRows, $regionDuplicates)->execute();
            }
            
            fclose($handle);
        }
    }
}