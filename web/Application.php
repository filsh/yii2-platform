<?php

namespace yii\platform\web;

class Application extends \yii\web\Application
{
    /**
     * Returns the geo locator component.
     * @return Locator the geo locator component
     */
    public function getGeoLocator()
    {
        return $this->getComponent('geoLocator');
    }
    
    /**
     * Registers the core application components.
     * @see setComponents
     */
    public function registerCoreComponents()
    {
        parent::registerCoreComponents();
        
        $this->setComponents([
            'geoLocator' => ['class' => 'yii\platform\geo\Locator'],
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