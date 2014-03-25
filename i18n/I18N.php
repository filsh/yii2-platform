<?php

namespace yii\platform\i18n;

use yii\platform\P;

class I18N extends \yii\i18n\I18N
{
    public function init()
    {
        parent::init();
        if (!isset($this->translations['platform']) && !isset($this->translations['platform*'])) {
            $this->translations['platform'] = [
                'class' => 'yii\i18n\GettextMessageSource',
                'sourceLanguage' => P::$app->sourceLanguage,
                'basePath' => '@platform/messages',
            ];
        }
    }
}