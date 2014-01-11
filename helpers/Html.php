<?php

namespace yii\platform\helpers;

class Html extends \yii\helpers\Html
{
    public static function skype($text, $skype = null, $options = [])
    {
        $options['href'] = 'skype:' . ($skype === null ? $text : $skype);
        return static::tag('a', $text, $options);
    }
    
    public static function tel($text, $tel = null, $options = [])
    {
        $options['href'] = 'tel:' . ($tel === null ? $text : $tel);
        return static::tag('a', $text, $options);
    }
}