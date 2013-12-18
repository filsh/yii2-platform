<?php

namespace yii\platform\updaters;

use yii\platform\Platform;
use yii\platform\helpers\FileHelper;
use yii\platform\geo\models\GeoLocations;
use yii\base\Exception;

class MaxmindUpdater extends BaseUpdater
{
    const CSV_FILE_LOCATION = 'GeoLiteCity-Location.csv';
    const CSV_FILE_BLOCKS = 'GeoLiteCity-Blocks.csv';
    
    public $sourceFile;
    
    public function run()
    {
        $this->addLog('process');
        
        FileHelper::loadFile($this->sourceFile, [
            'destDir' => Platform::getAlias($this->tmpPath),
            'callback' => [$this, 'resolveArchiveZip']
        ]);
        
        $this->addLog('process');
    }
    
    public function resolveArchiveZip($file)
    {
        if(!class_exists('\ZipArchive')) {
            throw new Exception('Not exist ZipArchive class, your must install PECL zip library.');
        }
        
//        $z = new \ZipArchive();
//        $z->open($file);
//        $z->extractTo($this->tmpPath);
//        $z->close();
        
        $filesList = FileHelper::findFiles($this->tmpPath, [
            'recursive' => true,
            'filter' => [$this, 'resolveArchiveCsv']
        ]);
        
        $this->process($filesList);
    }
    
    public function resolveArchiveCsv($file)
    {
        $files = [self::CSV_FILE_LOCATION, self::CSV_FILE_BLOCKS];
        foreach($files as $name) {
            if(!(FileHelper::filterPath($file, ['only' => [$name]]))) {
                continue;
            }
            switch($name) {
                case self::CSV_FILE_LOCATION:
                    GeoLocations::loadCsvFile($file);
                    break;
                case self::CSV_FILE_BLOCKS:
                    var_dump(2);
                    break;
            }
        }
        return;
        if(!file_exists($csvFile)) {
            throw new AppException('CSV Locations source file is not exists.');
        }
        
        $rows = 0;
        $raw = array();
        if (($handle = fopen($csvFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // начинаем с третьей строки и до конца файла
                $rows++;
                if($rows < 3) {
                    continue;
                }

                $latitude = (float)$data[5];
                $longitude = (float)$data[6];

                if(empty($latitude) || empty($longitude)) {
                    continue;
                }

                $raw[] = array(
                    'geo_locations_id' => (int) $data[0],
                    'geo_locations_country' => trim($data[1]),
                    'geo_locations_region' => trim($data[2]),
                    'geo_locations_city' => trim($data[3]),
                    'geo_locations_latitude' => $latitude,
                    'geo_locations_longitude' => $longitude
                );
                
                if(count($raw) === 1000) {
                    GeoLocations::loadRawData($raw);
                    $raw = array();
                }
            }
            
            GeoLocations::loadRawData($raw);
            fclose($handle);
        }
        
        $this->LogBehavior->addLog('Processed Locations File', sprintf('%s rows', $rows));
    }
    
    protected function processBlocksFile($csvFile)
    {
        if(!file_exists($csvFile)) {
            throw new Exception('CSV Blocks source file is not exists.');
        }
        
        $rows = 0;
        $raw = array();
        if (($handle = fopen($csvFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // начинаем с третьей строки и до конца файла
                $rows++;
                if($rows < 3) {
                    continue;
                }

                $raw[] = array(
                    'geo_ipblocks_start' => (int) $data[0],
                    'geo_ipblocks_end' => (int) $data[1],
                    'geo_ipblocks_locations_id' => (int) $data[2]
                );
                
                if(count($raw) === 1000) {
                    GeoIpBlocks::loadRawData($raw);
                    $raw = array();
                }
            }
            
            GeoIpBlocks::loadRawData($raw);
            fclose($handle);
        }
        
        $this->LogBehavior->addLog('Processed Blocks File', sprintf('%s rows', $rows));
    }
}