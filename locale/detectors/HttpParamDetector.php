<?php

namespace yii\platform\locale\detectors;

use yii\platform\P;

class HttpParamDetector extends Detector
{
    public $paramLang = 'lang';
    
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
        // TODO: логика по определению часового пояса на клиенте и передача его в GET параметре
        return null;
    }
}