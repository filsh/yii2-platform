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
        $runner = P::$app->createController('runner');
        if(!empty($runner) && is_array($runner)) {
            $runner = $runner[0];
        }
        
        $actions = ['locations', 'regions', 'timezones'];
        foreach($actions as $action) {
            if($this->all || $this->confirm('Do you want to update "' . $action . '"?')) {
                $runner->runAction($action);
            }
        }
        
        echo "\nPlatform updated successfully.\n";
    }
    
    /**
     * Returns the names of the global options for this command.
     * @return array the names of the global options for this command.
     */
    public function options($id)
    {
        return array_merge(parent::options($id), [
            'all'
        ]);
    }
}