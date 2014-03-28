<?php

namespace yii\platform\locale\detectors;

use yii\platform\P;

class BrowserDetector extends Detector
{
    public function detectLocale($locales = [])
    {
        $language = P::$app->getRequest()->getPreferredLanguage($locales);
        if(!empty($language)) {
            return $language;
        }
        
        return null;
    }

    public function detectTimezone($timezones = [])
    {
        // TODO: логика по определению часового пояса на клиенте(js скрипт с редиректом обратно на сервер)
        return null;
    }
}