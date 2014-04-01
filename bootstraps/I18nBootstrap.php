<?php

namespace yii\platform\bootstraps;

use yii\platform\P;
use yii\base\Event;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\i18n\GettextMessageSource;

class I18nBootstrap implements BootstrapInterface
{
    public function bootstrap(Application $app)
    {
        Event::on('yii\i18n\GettextMessageSource', GettextMessageSource::EVENT_MISSING_TRANSLATION, function ($event) {
            P::warning(sprintf(
                'Missing translation message "%s:%s:%s"', $event->category, $event->message, $event->language), get_class($event->sender));
        });
        
        $timezone = P::$app->getLocale()->detectTimezone(P::$app->timeZone);
        P::$app->setTimeZone($timezone);
    }
}