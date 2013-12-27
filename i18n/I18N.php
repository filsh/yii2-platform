<?php

namespace yii\platform\i18n;

use yii\platform\Platform;
use yii\i18n\MessageSource;
use yii\i18n\MissingTranslationEvent;

class I18N extends \yii\i18n\I18N
{
    public function init()
    {
        parent::init();
        if (!isset($this->translations['platform'])) {
            $this->translations['platform'] = [
                'class' => 'yii\i18n\GettextMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@platform/messages',
            ];
        }
        
        $this->getMessageSource('platform')->on(
            MessageSource::EVENT_MISSING_TRANSLATION,
            function (MissingTranslationEvent $event) {
                Platform::warning(sprintf(
                        'Missing translation message "%s:%s:%s"', $event->category, $event->message, $event->language), __CLASS__);
            }
        );
    }
}