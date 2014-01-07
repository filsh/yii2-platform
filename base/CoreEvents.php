<?php

namespace yii\platform\base;

use yii\base\Behavior;
use yii\base\Event;
use yii\i18n\MissingTranslationEvent;
use yii\helpers\ArrayHelper;
use yii\platform\P;

class CoreEvents extends Behavior
{
    public $events = [];
    
    public function events()
    {
        $events = ArrayHelper::merge(parent::events(), $this->events);
        return $events;
    }
    
    public function beforeRequest(Event $event)
    {
        $language = P::$app->getLocale()->detectLanguage(P::$app->language);
        P::$app->setLanguage($language);
        
        $timezone = P::$app->getLocale()->detectTimezone(P::$app->getTimeZone());
        $timezoneLocation = $timezone->getLocation();
        P::$app->setTimeZone($timezone->getName());
        P::$app->setLatitude($timezoneLocation['latitude']);
        P::$app->setLongitude($timezoneLocation['longitude']);
    }
    
    public function missingTranslation(MissingTranslationEvent $event)
    {
        P::warning(sprintf(
            'Missing translation message "%s:%s:%s"', $event->category, $event->message, $event->language), __CLASS__);
    }
}