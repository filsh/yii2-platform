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
        $dataColumns = ['country', 'region', 'timezone', 'create_time', 'update_time'];
        $dataDuplicates = ['country', 'region', 'timezone'];
        
        $dataRows = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if(!isset($data[0]) || count($data) !== 3 || $data[0] === 'country') {
                    continue;
                }
                
                $dataRows[] = [
                    trim($data[0]),
                    trim($data[1]),
                    trim($data[2]),
                    time(),
                    time()
                ];
                
                if(count($dataRows) === $this->maxExecuteRows) {
                    $this->batchInsertDuplicate(Timezones::tableName(), $dataColumns, $dataRows, $dataDuplicates)->execute();
                    $dataRows = [];
                }
            }
            
            if(count($dataRows) > 0) {
                $this->batchInsertDuplicate(Timezones::tableName(), $dataColumns, $dataRows, $dataDuplicates)->execute();
            }
            
            fclose($handle);
        }
    }
}