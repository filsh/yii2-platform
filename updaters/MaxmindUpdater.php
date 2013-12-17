<?php

namespace yii\platform\updaters;

use yii\platform\Platform;
use yii\platform\helpers\FileHelper;
use yii\base\Exception;

class MaxmindUpdater extends BaseUpdater
{
    public $sourceFile;
    
    private $_csvFiles;
    
    private $_write_rows = 1000;
    
    public function init()
    {
        parent::init();
        $this->tmpPath = Platform::getAlias($this->tmpPath);
        if (!is_dir($this->tmpPath)) {
            FileHelper::createDirectory($this->tmpPath, $this->tmpDirMode, true);
        }
    }
    /**
     * Загружает файлы данных в формате csv
     * 
     * @param array $csvFiles
     * @throws AppException
     */
    public function loadCsvFiles(array $csvFiles)
    {
        if(empty($csvFiles['csvLocationsFile']) || empty($csvFiles['csvBlocksFile'])) {
            throw new AppException('Not found source files.');
        }

        $this->_csvFiles = $csvFiles;
    }
    
    /**
     * Запускает обработку загруженных данных
     */
    public function run()
    {var_dump(1);return;
        $this->LogBehavior->addLog('process');
        
        $this->processLocationsFile($this->_csvFiles['csvLocationsFile']);
        $this->processBlocksFile($this->_csvFiles['csvBlocksFile']);
        
        $this->LogBehavior->addLog('process');
    }
    
    protected function processLocationsFile($csvFile)
    {
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
                
                if(count($raw) == $this->_write_rows) {
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
                
                if(count($raw) == $this->_write_rows) {
                    GeoIpBlocks::loadRawData($raw);
                    $raw = array();
                }
            }
            
            GeoIpBlocks::loadRawData($raw);
            fclose($handle);
        }
        
        $this->LogBehavior->addLog('Processed Blocks File', sprintf('%s rows', $rows));
    }
    
    protected function loadSourceFile($sourceFile, $destCsvFile)
    {
        $destFolder = dirname($destCsvFile);

        if(!is_dir($destFolder) && !mkdir($destFolder, 0777)) {
            throw new Exception('Unable to create destination folder.');
        }

        $destFile = @fopen($destCsvFile, 'w');

        if(!is_resource($destFile)) {
            throw new Exception('Unable to create destination resource.');
        }

        $options = array(
            CURLOPT_FILE    => $destFile,
            CURLOPT_TIMEOUT =>  10*60,
            CURLOPT_URL     => $sourceFile,
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);

        $z = new ZipArchive();
        $z->open($destCsvFile);
        $z->extractTo($destFolder);
        $z->close();

        // ищем нужные файлы
        $result = array(
            'csvLocationsFile' => null,
            'csvBlocksFile' => null
        );

        foreach (scandir($destFolder) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if(is_dir($destFolder . '/' . $item)) {
                $csvLocationsFile = $destFolder . '/' . $item . '/GeoLiteCity-Location.csv';
                $csvBlocksFile = $destFolder . '/' . $item . '/GeoLiteCity-Blocks.csv';

                if(file_exists($csvLocationsFile)) {
                    $result['csvLocationsFile'] = $csvLocationsFile;
                }

                if(file_exists($csvBlocksFile)) {
                    $result['csvBlocksFile'] = $csvBlocksFile;
                }
            }
        }

        if(empty($result['csvLocationsFile']) || empty($result['csvBlocksFile'])) {
            throw new AppException('Not found source files.');
        }

        return $result;
    }
}