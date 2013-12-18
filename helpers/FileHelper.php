<?php

namespace yii\platform\helpers;

use yii\base\Exception;

class FileHelper extends \yii\helpers\FileHelper
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
    public static function loadFile($fileUrl, $options = [])
    {
//        $file = static::createUniqueFile(isset($options['destDir']) ? $options['destDir'] : null);
//        if($file === false) {
//            return false;
//        }
//        
//        $handle = @fopen($file, 'w');
//        $options = array(
//            CURLOPT_FILE    => $handle,
//            CURLOPT_TIMEOUT => 10 * 60,
//            CURLOPT_URL     => $fileUrl,
//        );
//
//        $ch = curl_init();
//        curl_setopt_array($ch, $options);
//        curl_exec($ch);
//        fclose($handle);
        $file = '/var/www/yii2-app-platform/console/runtime/updater/52b1b811a156d_1387378705';
        if (isset($options['callback'])) {
            $result = call_user_func($options['callback'], $file);
            if (is_bool($result)) {
                return $result;
            }
        } else {
            return $file;
        }
        
        return false;
    }
    
    public static function createUniqueFile($dst = null)
    {
        if($dst !== null) {
            if (!is_dir($dst)) {
                static::createDirectory($dst);
            }
        } else {
            $dst = sys_get_temp_dir();
        }
        
        $file = $dst.'/'.uniqid().'_'.time();
        
        if(!touch($file)) {
            return false;
        }
        
        return $file;
    }
}