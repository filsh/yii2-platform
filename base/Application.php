<?php

namespace yii\platform\base;

use yii\platform\P;
use yii\base\Behavior;

class Application extends Behavior
{
    public function setLanguage($language)
    {
        P::$app->language = $language;
    }
    
    public function setLatitude($latitude)
    {
        ini_set('date.default_latitude', $latitude);
    }
    
    public function setLongitude($longitude)
    {
        ini_set('date.default_longitude', $longitude);
    }
}