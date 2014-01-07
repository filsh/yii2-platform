<?php

namespace yii\platform\locale;

use yii\platform\P;

class HttpParamDetector extends Detector
{
    public $paramLang = 'lang';
    
    public function detectLanguage($languages = [])
    {
        $language = P::$app->getRequest()->get($this->paramLang);
        if(in_array($language, $languages)) {
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