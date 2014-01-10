<?php

namespace yii\platform\locale;

use yii\base\Object;

abstract class Detector extends Object
{
    /**
     * Enable to detect locale
     * @var bool 
     */
    public $detectLocale = true;
    
    /**
     * Enable to detect timezone
     * @var bool 
     */
    public $detectTimezone = true;
    
    /**
     * Detect locale
     * @return string
     */
    abstract public function detectLocale($locales = []);
    
    /**
     * Detect timezone
     * @return string
     */
    abstract public function detectTimezone($timezones = []);
}