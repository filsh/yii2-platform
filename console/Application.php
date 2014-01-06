<?php

namespace yii\platform\console;

class Application extends \yii\console\Application
{
    public function behaviors()
    {
        return [
            'application' => [
                'class' => 'yii\platform\base\Application'
            ]
        ];
    }
    
    /**
     * Registers the core application components.
     * @see setComponents
     */
    public function registerCoreComponents()
    {
        parent::registerCoreComponents();
        $this->setComponents([
            'i18n' => ['class' => 'yii\platform\i18n\I18N']
        ]);
    }
    
    /**
     * Returns the configuration of the built-in commands.
     * @return array the configuration of the built-in commands.
     */
    public function coreCommands()
    {
        return array_merge(parent::coreCommands(), [
            'migrate' => 'yii\platform\console\controllers\MigrateController',
            'updater' => 'yii\platform\console\controllers\UpdaterController',
        ]);
    }
    
    /**
     * Returns the configuration of the built-in updaters.
     * @return array the configuration of the built-in updaters.
     */
    public function coreUpdaters()
    {
        return [
            'locations' => 'yii\platform\updaters\LocationsUpdater',
            'regions' => 'yii\platform\updaters\RegionsUpdater',
            'timezones' => 'yii\platform\updaters\TimezonesUpdater',
        ];
    }
}