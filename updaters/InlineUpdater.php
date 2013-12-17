<?php

namespace yii\platform\updaters;

class InlineUpdater extends BaseUpdater
{
    /**
     * @var array the callable updater method.
     */
    public $method;
    
    /**
     * @var array additional parameters for the updater.
     */
    public $config;

    /**
     * Run updater
     * 
     * @return string the result of updater
     */
    public function run()
    {
        return call_user_func_array($this->method, [$this->config]);
    }
}