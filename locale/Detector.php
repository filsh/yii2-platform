<?php

namespace yii\platform\locale;

abstract class Detector
{
    /**
     * Enable to detect locale
     * @var type 
     */
    public $detectLocale = true;
    
    /**
     * Enable to detect timezone
     * @var type 
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