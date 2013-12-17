<?php

namespace yii\platform\console;

class Application extends \yii\console\Application
{
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
            'maxmind' => 'yii\platform\updaters\MaxmindUpdater',
        ];
    }
}