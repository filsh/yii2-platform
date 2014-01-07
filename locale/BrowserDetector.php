<?php

namespace yii\platform\locale;

use yii\platform\P;

class BrowserDetector extends Detector
{
    public function detectLanguage($languages = [])
    {
        $request = P::$app->getRequest();
        return strtolower($request->getPreferredLanguage($languages));
    }

    public function detectTimezone($timezones = [])
    {
        // TODO: логика по определению часового пояса на клиенте(js скрипт с редиректом обратно на сервер)
        return null;
    }
}