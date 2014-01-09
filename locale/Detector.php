<?php

namespace yii\platform\locale;

abstract class Detector
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