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
     * @var boolean whether to update all without confirmation dialog
     */
    public $all = false;
    
    /**
     * Initialize or update platform data
     */
    public function actionIndex()
    {
        $updater = P::$app->createController('updater');
        if(!empty($updater) && is_array($updater)) {
            $updater = $updater[0];
        }
        
        $actions = ['locations', 'regions', 'timezones'];
        foreach($actions as $action) {
            if($this->all || $this->confirm('Do you want to update "' . $action . '"?')) {
                $updater->runAction($action);
            }
        }
        
        echo "\nPlatform updated successfully.\n";
    }
    
    /**
     * Returns the names of the global options for this command.
     * @return array the names of the global options for this command.
     */
    public function globalOptions()
    {
        return array_merge(parent::globalOptions(), [
            'all'
        ]);
    }
}