<?php

namespace yii\platform\runners;

class InlineRunner extends BaseRunner
{
    /**
     * @var array the callable runner method.
     */
    public $method;
    
    /**
     * @var array additional parameters for the runner.
     */
    public $config;

    /**
     * Run runner
     * 
     * @return string the result of runner
     */
    public function run()
    {
        return call_user_func_array($this->method, [$this->config]);
    }
}