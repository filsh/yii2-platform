<?php

namespace yii\platform\locale;

use \yii\platform\Platform;

class HttpParamLocaleDetector implements LocaleDetector
{
    public $paramLang = 'lang';
    
    public function detect($languages = array())
    {
        $language = Platform::$app->getRequest()->get($this->paramLang);
        if(in_array($language, $languages)) {
            return $language;
        }
        
        return null;
    }    
}