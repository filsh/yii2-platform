<?php

namespace yii\platform\locale;

use yii\base\Object;

abstract class Acceptor extends Object
{
    /**
     * Enable to accept locale
     * @var bool 
     */
    public $acceptLocale = true;
    
    /**
     * Enable to accept timezone
     * @var bool 
     */
    public $acceptTimezone = true;
    
    /**
     * Accept locale
     * @return bool return true is locale accepted
     */
    abstract public function acceptLocale($locale);
    
    /**
     * Accept timezone
     * @return bool return true is timezone accepted
     */
    abstract public function acceptTimezone($timezone);
}