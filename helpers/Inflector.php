<?php

namespace yii\platform\helpers;

class Inflector extends \yii\helpers\Inflector
{
    public static function aliased($string)
    {
        $string = self::camel2words($string);
        return trim(preg_replace('/[^A-Za-z0-9-]/', '', self::slug($string)));
    }
}