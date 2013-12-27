<?php

namespace yii\platform\locale;

use \yii\platform\Platform;

class BrowserLocaleDetector implements LocaleDetector
{
    public function detect($languages = [])
    {
        $request = Platform::$app->getRequest();
        return strtolower($request->getPreferredLanguage($languages));
    }    
}