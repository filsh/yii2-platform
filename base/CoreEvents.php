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
        P::$app->setLanguage(P::$app->getLocale()->detectLanguage());
        P::$app->setTimeZone(P::$app->getGeoLocator()->getTimeZone());
    }
    
    public function missingTranslation(MissingTranslationEvent $event)
    {
        P::warning(sprintf(
            'Missing translation message "%s:%s:%s"', $event->category, $event->message, $event->language), __CLASS__);
    }
}