<?php

namespace yii\platform\i18n;

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
    }
}