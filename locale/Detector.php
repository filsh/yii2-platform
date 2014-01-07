<?php

namespace yii\platform\locale;

abstract class Detector
{
    /**
     * Enable to detect language
     * @var type 
     */
    public $detectLanguage = true;
    
    /**
     * Enable to detect timezone
     * @var type 
     */
    public $detectTimezone = true;
    
    /**
     * Detect language
     * @return string
     */
    abstract public function detectLanguage($languages = []);
    
    /**
     * Detect timezone
     * @return string
     */
    abstract public function detectTimezone($timezones = []);
}