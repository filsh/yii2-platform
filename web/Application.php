<?php

namespace yii\platform\web;

class Application extends \yii\web\Application
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'application' => [
                'class' => 'yii\platform\behaviors\Application'
            ]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function coreComponents()
    {
        return array_merge(parent::coreComponents(), [
            'urlManager' => ['class' => 'yii\platform\web\UrlManager'],
            'i18n' => ['class' => 'yii\platform\i18n\I18N'],
            'geoLocator' => ['class' => 'yii\platform\geo\Locator'],
            'locale' => ['class' => 'yii\platform\locale\Locale'],
            'runner' => ['class' => 'yii\platform\runners\Runner'],
        ]);
    }
    
    /**
     * Returns the configuration of the built-in runners.
     * @return array the configuration of the built-in runners.
     */
    public function coreRunners()
    {
        return [];
    }
    
    /**
     * Returns the geo locator component.
     * @return Locator the geo locator component
     */
    public function getGeoLocator()
    {
        return $this->get('geoLocator');
    }
    
    /**
     * Returns the locale component.
     * @return Locator the geo locator component
     */
    public function getLocale()
    {
        return $this->get('locale');
    }
}