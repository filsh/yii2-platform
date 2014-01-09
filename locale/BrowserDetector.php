<?php

namespace yii\platform\locale;

use yii\platform\P;

class BrowserDetector extends Detector
{
    public function detectLocale($locales = [])
    {
        return P::$app->getRequest()->getPreferredLanguage($locales);
    }

    public function detectTimezone($timezones = [])
    {
        // TODO: логика по определению часового пояса на клиенте(js скрипт с редиректом обратно на сервер)
        return null;
    }
}