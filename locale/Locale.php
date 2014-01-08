<?php

namespace yii\platform\locale;

use DateTimeZone;
use yii\platform\P;
use yii\base\InvalidConfigException;

class Locale extends \yii\base\Component
{
    public $detectors = [];
    
    public static $languageMap = [
        'en'    => 'en-US',
        'en-US' => 'en-US',
        'ru'    => 'ru-RU',
        'ru-RU' => 'ru-RU'
    ];
    
    /**
     * Returns the detector for the given configure.
     * @param Detector|array $detector
     * @return Detector
     * @throws InvalidConfigException
     */
    public function getDetector($detector)
    {
        if($detector instanceof Detector) {
            $class = get_class($detector);
        } else if(isset($detector['class'])) {
            $class = $detector['class'];
        } else {
            throw new InvalidConfigException("Unable to create locale detector '$detector'.");
        }
        
        if(!isset($this->detectors[$class])) {
            $this->detectors[$class] = P::createObject($detector);
        }
        
        return $this->detectors[$class];
    }
    
    /**
     * Run detecting location
     * @param type $default
     * @return string
     */
    public function detectLanguage($default = 'en-US')
    {
        $language = null;
        foreach($this->detectors as $detector) {
            if($language === null) {
                $detector = $this->getDetector($detector);
                $language = $detector->detectLanguage ? $detector->detectLanguage(array_keys(self::$languageMap)) : null;
            }
        }
        
        if($language === null) {
            return $default;
        }
        
        return $this->formatLanguage($language, $default);
    }
    
    /**
     * Run detecting timezone
     * @param type $default
     * @return string
     */
    public function detectTimezone($default = 'UTC')
    {
        $timezone = null;
        foreach($this->detectors as $detector) {
            if($timezone === null) {
                $detector = $this->getDetector($detector);
                $timezone = $detector->detectTimezone ? $detector->detectTimezone() : null;
            }
        }
        
        return $this->formatTimezone($timezone, $default);
    }
    
    /**
     * Format language
     * @param type $language
     * @param type $default
     * @return string
     */
    public function formatLanguage($language, $default = 'en-US')
    {
        $parts = explode('-', str_replace('_', '-', mb_strtolower($language)));
        
        switch(count($parts)) {
            case 1:
                $language = $parts[0];
                break;
            case 2:
                $language = $parts[0] . '-' . mb_strtoupper($parts[1]);
                break;
        }
        
        if(isset(self::$languageMap[$language])) {
            $language = self::$languageMap[$language];
        } else {
            P::warning(sprintf('Formatted language \'%s\' is not supported, reset to default \'%s\'', $language, $default), __CLASS__);
            $language = $default;
        }
        
        return $language;
    }
    
    /**
     * Format timezone
     * @param type $timezone
     * @param type $default
     * @return string
     */
    public function formatTimezone($timezone, $default = 'UTC')
    {
        if(!in_array($timezone, DateTimeZone::listIdentifiers())) {
            P::warning(sprintf(
                    'Formatted timezone \'%s\' is not supported, reset to default \'%s\'', $timezone, $default), __CLASS__);
            $timezone = $default;
        }
        
        return $timezone;
    }
}