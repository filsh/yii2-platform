<?php

namespace yii\platform\locale;

use \yii\platform\P;

class BrowserLocaleDetector implements LocaleDetector
{
    public function detect($languages = [])
    {
        $request = P::$app->getRequest();
        return strtolower($request->getPreferredLanguage($languages));
    }    
}