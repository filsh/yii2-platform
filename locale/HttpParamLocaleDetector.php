<?php

namespace yii\platform\locale;

use \yii\platform\P;

class HttpParamLocaleDetector implements LocaleDetector
{
    public $paramLang = 'lang';
    
    public function detect($languages = array())
    {
        $language = P::$app->getRequest()->get($this->paramLang);
        if(in_array($language, $languages)) {
            return $language;
        }
        
        return null;
    }    
}