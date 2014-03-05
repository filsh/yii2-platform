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
}
