<?php

namespace yii\platform\locale;

use yii\platform\P;

class DefaultAcceptor extends Acceptor
{
    public function acceptLocale($locale)
    {
        P::$app->setLanguage($locale);
        return $locale;
    }

    public function acceptTimezone($timezone)
    {
        P::$app->setTimeZone($timezone);
        return $timezone;
    }    
}