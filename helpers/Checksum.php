<?php

namespace yii\platform\helpers;

class Checksum
{
    public static function fromContent($content, $key = null, $algorithm = 'sha256')
    {
        if($key === null) {
            return md5($content);
        } else {
            return hash_hmac($algorithm, $content, $key);
        }
    }
    
    public static function fromFile($filename, $key = null, $algorithm = 'sha256')
    {
        if($key === null) {
            return md5_file($filename);
        } else {
            return hash_hmac_file($algorithm, $filename, $key);
        }
    }
}