<?php

namespace yii\platform\i18n;

class I18N extends \yii\i18n\I18N
{
    public function init()
    {
        parent::init();
        if (!isset($this->translations['platform'])) {
            $this->translations['platform'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@yii/messages',
            ];
        }
    }
}