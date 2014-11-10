<?php

namespace yii\platform\helpers;

class StringHelper extends \yii\helpers\StringHelper
{
    /**
     * Generates a random string. The key may contain uppercase and lowercase latin letters and digits.
     * @param strin $extra the extra chars
     * @param integer $length the length of the key that should be generated
     * @return string the generated random key
     */
    public static function generateRandomString($length = 32, $extra = '_-.')
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' . $extra;
        return substr(str_shuffle(str_repeat($chars, 5)), 0, $length);
    }
    
    public static function getDsnValue($dsn, $name, $default = null)
    {
        $pattern = sprintf('~%s=([^;]*)(?:;|$)~', preg_quote($name, '~'));

        $result = preg_match($pattern, $dsn, $matches);
        if ($result === false) {
            throw new \yii\base\Exception('Regular expression matching failed unexpectedly.');
        }

        return $result ? $matches[1] : $default;
    }
}
