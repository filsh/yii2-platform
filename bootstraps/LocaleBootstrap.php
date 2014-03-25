<?php

namespace yii\platform\bootstraps;

use yii\platform\P;
use yii\base\Application;
use yii\base\BootstrapInterface;

class LocaleBootstrap implements BootstrapInterface
{
    public function bootstrap(Application $app)
    {
        P::$app->getRequest()->resolve();
        P::$app->getLocale()->detectLocale(P::$app->language);
        P::$app->getLocale()->detectTimezone(P::$app->getTimeZone());
    }
}