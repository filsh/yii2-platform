<?php

namespace yii\platform\runners;

use yii\platform\helpers\FileHelper;
use yii\platform\geo\models\Countries;

class CountriesRunner extends BaseRunner
{
    public $tmpPath = '@runtime/runner/countries';
    
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
    
    /**
     * @see https://github.com/debuggable/php_arrays/tree/master/generators
     * @param type $file
     */
    protected function applyCsv($file)
    {
        $dataColumns = ['iso_alpha2', 'iso_alpha3', 'iso_numeric', 'fips_code', 'name', 'capital', 'areainsqkm',
            'population', 'continent', 'tld', 'currency', 'currency_name', 'phone', 'postal_code_format', 'postal_code_regex',
            'languages', 'geoname_id', 'neighbours', 'equivalent_fips_code', 'create_time', 'update_time'];
        $dataDuplicates = ['iso_alpha2'];
        
        $dataRows = [];
        if(($handle = fopen($file, 'r')) !== false) {
            while(($data = fgetcsv($handle, 0, "\t")) !== false) {
                if(count($data) == 1 || preg_match('/^#/', $data[0])) {
                    continue;
                }
                
                $dataRows[] = array_values([
                    'iso_alpha2' => $data[0],
                    'iso_alpha3' => $data[1],
                    'iso_numeric' => $data[2],
                    'fips_code' => $data[3],
                    'name' => $data[4],
                    'capital' => $data[5],
                    'areainsqkm' => $data[6],
                    'population' => $data[7],
                    'continent' => $data[8],
                    'tld' => $data[9],
                    'currency' => $data[10],
                    'currency_name' => $data[11],
                    'phone' => $data[12],
                    'postal_code_format' => $data[13],
                    'postal_code_regex' => $data[14],
                    'languages' => $data[15],
                    'geoname_id' => $data[16],
                    'neighbours' => $data[17],
                    'equivalent_fips_code' => $data[18],
                    'create_time' => time(),
                    'update_time' => time()
                ]);
                
                if(count($dataRows) === $this->maxExecuteRows) {
                    $this->batchInsertDuplicate(Countries::tableName(), $dataColumns, $dataRows, $dataDuplicates)->execute();
                    $dataRows = [];
                }
            }
            
            if(count($dataRows) > 0) {
                $this->batchInsertDuplicate(Countries::tableName(), $dataColumns, $dataRows, $dataDuplicates)->execute();
            }
            
            fclose($handle);
        }
    }
}