<?php

namespace yii\platform\console\controllers;

use yii\platform\P;
use yii\console\Controller;

/**
 * This command manages application platform.
 */
class PlatformController extends Controller
{
    /**
     * Update platform
     */
    public function actionUpdate()
    {
        $migrate = P::$app->createController('migrate');
        if(!empty($migrate) && is_array($migrate)) {
            $migrate = $migrate[0];
            $migrate->interactive = false;
        }
        if($this->confirm('Do you want to apply migrations?')) {
            $migrate->runAction('up');
        }
        
        $updater = P::$app->createController('updater');
        if(!empty($updater) && is_array($updater)) {
            $updater = $updater[0];
        }
        foreach(['locations', 'regions', 'timezones'] as $action) {
            if($this->confirm('Do you want to update "' . $action . '"?')) {
                $updater->runAction($action);
            }
        }
        
        echo "\nPlatform updated successfully.\n";
    }
}