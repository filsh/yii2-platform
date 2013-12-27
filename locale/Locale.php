<?php

namespace yii\platform\locale;

use yii\base\InvalidConfigException;
use yii\platform\P;

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
     * @param LocaleDetector|array $detector
     * @return string
     * @throws InvalidConfigException
     */
    public function getLocaleDetector($detector)
    {
        if(isset($detector['class'])) {
            $class = $detector['class'];
        } else if($detector instanceof LocaleDetector) {
            $class = get_class($detector);
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
     * @return type
     */
    public function detectLanguage($default = 'en-US')
    {
        $language = null;
        foreach($this->detectors as $detector) {
            if($language === null) {
                $detector = $this->getLocaleDetector($detector);
                $language = $detector->detect(array_keys(self::$languageMap));
            }
        }
        
        if($language === null) {
            return $default;
        }
        
        return $this->format($language, $default);
    }
    
    /**
     * Format language
     * @param type $language
     * @param type $default
     * @return type
     */
    public function format($language, $default = 'en-US')
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
}