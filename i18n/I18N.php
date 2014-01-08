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
        
        /**
         * Временные изменения до получения разяснений по
         * http://yiiframework.ru/forum/viewtopic.php?f=19&t=16014
         * к этому же касаются и php файлы переводов
         */
        if (isset($this->translations['yii'])) {
            $this->translations['yii'] = array_merge($this->translations['yii'], ['basePath' => '@platform/messages']);
        }
    }
}