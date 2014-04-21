<?php

namespace yii\platform\runners;

use yii\platform\helpers\FileHelper;
use yii\platform\geo\models\Timezones;

class TimezonesRunner extends BaseRunner
{
    public $tmpPath = '@runtime/runner/timezones';
    
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
            throw new \yii\base\Exception('Source file not found.');
        }
        
        $this->applyCsv($file);
    }
    
    protected function applyCsv($file)
    {
        $regionColumns = ['country', 'region', 'timezone', 'create_time', 'update_time'];
        $regionDuplicates = ['country', 'region', 'timezone'];
        
        $regionRows = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if(!isset($data[0]) || count($data) !== 3 || $data[0] === 'country') {
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
                    $this->batchInsertDuplicate(Timezones::tableName(), $regionColumns, $regionRows, $regionDuplicates)->execute();
                    $regionRows = [];
                }
            }
            
            if(count($regionRows) > 0) {
                $this->batchInsertDuplicate(Timezones::tableName(), $regionColumns, $regionRows, $regionDuplicates)->execute();
            }
            
            fclose($handle);
        }
    }
}