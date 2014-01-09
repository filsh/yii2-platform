<?php

namespace yii\platform\locale;

use DateTimeZone;
use yii\platform\P;
use yii\base\InvalidConfigException;

class Locale extends \yii\base\Component
{
    public $detectors = [];
    
    public static $localeMap = [
        'en'    => 'en-US',
        'en-US' => 'en-US',
        'ru'    => 'ru-RU',
        'ru-RU' => 'ru-RU',
        'ua'    => 'ua-UA',
        'ua-UA' => 'ua-UA'
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
     * Run detecting locale
     * @param type $default
     * @return string
     */
    public function detectLocale($default = 'en-US')
    {
        $locale = null;
        foreach($this->detectors as $detector) {
            if($locale === null) {
                $detector = $this->getDetector($detector);
                $locale = $detector->detectLocale ? $detector->detectLocale() : null;
            }
        }
        
        if($locale === null) {
            return $default;
        }
        
        return $this->formatLocale($locale, $default);
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
     * @param type $locale
     * @param type $default
     * @return string
     */
    public function formatLocale($locale, $default = 'en-US')
    {
        $parts = explode('-', str_replace('_', '-', mb_strtolower($locale)));
        
        switch(count($parts)) {
            case 1:
                $locale = $parts[0];
                break;
            case 2:
                $locale = $parts[0] . '-' . mb_strtoupper($parts[1]);
                break;
        }
        
        if(isset(self::$localeMap[$locale])) {
            $locale = self::$localeMap[$locale];
        } else {
            P::warning(sprintf('Formatted language \'%s\' is not supported, reset to default \'%s\'', $locale, $default), __CLASS__);
            $locale = $default;
        }
        
        return $locale;
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
    
    /**
     * Converts a locale Id to a language Id.
     * A language ID consists of only the first group of letters before an underscore or dash.
     * @param string $id the locale ID to be converted
     * @return string the language ID
     */
    public function getLanguage($locale)
    {
        return locale_get_primary_language($locale);
    }
    
    /**
     * Check of similars two locales
     * @param type $first
     * @param type $second
     * @return bool
     */
    public function getIsSimilarLocales($first, $second)
    {
        return locale_get_primary_language($first) === locale_get_primary_language($second);
    }
}