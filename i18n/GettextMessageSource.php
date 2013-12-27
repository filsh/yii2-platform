<?php

namespace yii\platform\i18n;

class GettextMessageSource extends \yii\i18n\GettextMessageSource
{
    public function behaviors()
    {
        return [
            'registerCoreEvents' => [
                'class' => 'yii\platform\base\CoreEvents',
                'events' => [
                    self::EVENT_MISSING_TRANSLATION => 'missingTranslation'
                ],
            ]
        ];
    }
}