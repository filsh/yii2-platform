<?php

namespace yii\platform\helpers;

use yii\platform\P;

class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * Lead the files found under the http link and return tmp file.
     * @param string $fileUrl the file under which will be loaded.
     * @param string $dst the destination directory.
     * @param array $options options for load file. Valid options are:
     *
     * - destDir: string, the destination directory
     * - onLoad: callback, a PHP callback that is called for on file loaded.
     * @return strin path to temp loaded file.
     */
    public static function loadFile($fileUrl, $options = [])
    {
        $file = static::createUniqueFile(isset($options['destDir']) ? $options['destDir'] : null);
        if($file === false) {
            return false;
        }
        
        $handle = @fopen($file, 'w');
        $curlOptions = [
            CURLOPT_FILE            => $handle,
            CURLOPT_TIMEOUT         => 10 * 60,
            CURLOPT_URL             => $fileUrl,
            CURLOPT_FOLLOWLOCATION  => true
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);
        curl_exec($ch);
        fclose($handle);
        
        if (isset($options['onLoad'])) {
            $result = call_user_func($options['onLoad'], $file);
            if (is_bool($result)) {
                return $result;
            }
        } else {
            return $file;
        }
        
        return false;
    }
    
    /**
     * Create temp file
     * @param string $dst the destination directory. If not provided should use system temp directory.
     * @return boolean|string path to created file or false.
     */
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
    
    public static function getExtension($file)
    {
        return (($ext = pathinfo($file, PATHINFO_EXTENSION)) !== '') ? strtolower($ext) : null;
    }
    
    public static function getMimeTypeFromExternal($fileUrl, $checkExtension = true)
    {
        $mimeType = null;
        $fn = tmpfile();
        if($fn) {
            $metaData = stream_get_meta_data($fn);
            if(!empty($metaData['uri']) && file_exists($metaData['uri'])) {
                file_put_contents($metaData['uri'], file_get_contents($fileUrl));
                $mimeType = self::getMimeType($metaData['uri']);
            }
            fclose($fn);
            if(!empty($mimeType)) {
                return $mimeType;
            }
        }
        
        return $checkExtension ? static::getMimeTypeByExtension($fileUrl) : null;
    }
}