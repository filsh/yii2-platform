<?php

namespace yii\platform\locale\detectors;

use yii\platform\P;

class HttpParamDetector extends Detector
{
    public $paramLang = 'lang';
    
    public $paramTimezone = 'timezone';
    
    public function detectLocale($locales = [])
    {
        $language = P::$app->getRequest()->getQueryParam($this->paramLang);
        if(in_array($language, $locales)) {
            return $language;
        }
        
        return null;
    }
    
    public function detectTimezone($timezones = [])
    {
        $timezone = P::$app->getRequest()->getQueryParam($this->paramTimezone);
        if(in_array($timezone, $timezones)) {
            return $timezone;
        }
        
        return null;
    }
}