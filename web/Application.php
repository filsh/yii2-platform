<?php

namespace yii\platform\web;

class Application extends \yii\web\Application
{
    public function behaviors()
    {
        return [
            'registerCoreEvents' => [
                'class' => 'yii\platform\base\CoreEvents',
                'events' => [
                    self::EVENT_BEFORE_REQUEST => 'beforeRequest'
                ]
            ]
        ];
    }
    
    /**
     * Returns the geo locator component.
     * @return Locator the geo locator component
     */
    public function getGeoLocator()
    {
        return $this->getComponent('geoLocator');
    }
    
    /**
     * Returns the locale component.
     * @return Locator the geo locator component
     */
    public function getLocale()
    {
        return $this->getComponent('locale');
    }
    
    /**
     * Registers the core application components.
     * @see setComponents
     */
    public function registerCoreComponents()
    {
        parent::registerCoreComponents();
        $this->setComponents([
            'i18n' => ['class' => 'yii\platform\i18n\I18N'],
            'geoLocator' => ['class' => 'yii\platform\geo\Locator'],
            'locale' => ['class' => 'yii\platform\locale\Locale'],
        ]);
    }
    
    /**
     * Returns the configuration of the built-in updaters.
     * @return array the configuration of the built-in updaters.
     */
    public function coreUpdaters()
    {
        return [];
    }
}