<?php

namespace yii\platform\helpers;

use \yii\base\Exception;

class FileHelper extends yii\helpers\FileHelper
{
    /**
     * Lead the files found under the http link and return tmp file.
     * @param string $fileUrl the file under which will be loaded.
     * @param string $dst the destination directory
     * @param array $options options for load file. Valid options are:
     *
     * - dirMode: integer, the permission to be set for newly created directories. Defaults to 0775.
     * - fileMode:  integer, the permission to be set for newly copied files. Defaults to the current environment setting.
     * @return strin path to temp loaded file.
     */
    public static function loadFile($fileUrl, $dst, $options = [])
    {
        if (!is_dir($dst)) {
            static::createDirectory($dst, isset($options['dirMode']) ? $options['dirMode'] : 0775, true);
        }
        
        $tmpfname = tempnam($dst);
        $handle = fopen($tmpfname, 'w');
        
        $options = array(
            CURLOPT_FILE    => $handle,
            CURLOPT_TIMEOUT => 10 * 60,
            CURLOPT_URL     => $fileUrl,
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);

        if(!class_exists('\ZipArchive')) {
            throw new Exception('Not found ZipArchive. Your must install PECL zip library.');
        }
        
        $z = new \ZipArchive();
        $z->open($destCsvFile);
        $z->extractTo($destFolder);
        $z->close();


        $list = [];
        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false) {
                if ($file === '.' || $file === '..') {
                        continue;
                }
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (static::filterPath($path, $options)) {
                        if (is_file($path)) {
                                $list[] = $path;
                        } elseif (!isset($options['recursive']) || $options['recursive']) {
                                $list = array_merge($list, static::findFiles($path, $options));
                        }
                }
        }
        closedir($handle);
        return $list;
    }
}