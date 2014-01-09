<?php

namespace yii\platform\locale;

abstract class Acceptor
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
     * @return string return accepted locale
     */
    abstract public function acceptLocale($locale);
    
    /**
     * Accept timezone
     * @return string return accepted timezone
     */
    abstract public function acceptTimezone($timezone);
}