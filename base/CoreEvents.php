<?php

namespace yii\platform\base;

use yii\base\Behavior;
use yii\base\Event;
use yii\i18n\MissingTranslationEvent;
use yii\helpers\ArrayHelper;
use yii\platform\Platform;

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
        Platform::$app->language = Platform::$app->getLocale()->detectLanguage();
    }
    
    public function missingTranslation(MissingTranslationEvent $event)
    {
        Platform::warning(sprintf(
            'Missing translation message "%s:%s:%s"', $event->category, $event->message, $event->language), __CLASS__);
    }
}