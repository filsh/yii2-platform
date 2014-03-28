<?php

namespace yii\platform\locale;

use DateTimeZone;
use yii\platform\P;
use yii\base\InvalidConfigException;

/**
 * Locale provides features related with localization
 * 
 * Locale is configured as an application component in [[yii\platform\locale\Application]] by default.
 * You can access that instance via `P::$app->locale`.
 * 
 * Example configure detectors and acceptors
 * 
 * ~~~
 *  'locale' => [
 *      'detectors' => [
 *          [
 *              'class' => 'yii\platform\locale\HttpParamDetector',
 *              'detectTimezone' => false
 *          ],
 *          [
 *              'class' => 'yii\platform\locale\BrowserDetector',
 *              'detectTimezone' => false
 *          ]
 *      ]
 *  ]
 * ~~~
 */
class Locale extends \yii\base\Component
{
    /**
     * @var array $detectors a list of the locale detectors
     */
    public $detectors = [];
    
    /**
     * @var array $languages a list of the languages supported by the application. If this is empty,
     * the current application language will be used
     */
    public $locales = [];
    
    public function init()
    {
        parent::init();
        array_push($this->locales, 'en');
    }
    
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
    public function detectLocale($default = 'en')
    {
        $locale = null;
        foreach($this->detectors as $detector) {
            if($locale === null) {
                $detector = $this->getDetector($detector);
                $locale = $detector->detectLocale ? $detector->detectLocale($this->locales) : null;
            }
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
     * Format language with region id supported
     * @param type $locale
     * @param type $default
     * @return string
     */
    public function formatLocale($locale, $default = 'en')
    {
        if($locale === null) {
            return $default;
        }
        
        $lang = $this->getLanguage($locale);
        $region = $this->getRegion($locale);
        if(empty($region)) {
            $region = $this->getRegion(P::$app->language);
        }
        if(empty($region)) {
            $region = P::$app->geoLocator->getCountry();
        }

        $formated = implode('-', [$lang, $region]);
        if(in_array($formated, $this->locales)) {
            $locale = $formated;
        } else if(in_array($lang, $this->locales)) {
            $locale = $lang;
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
        if($timezone === null || !in_array($timezone, DateTimeZone::listIdentifiers())) {
            P::warning(sprintf(
                    'Formatted timezone \'%s\' is not supported, reset to default \'%s\'', $timezone, $default), __CLASS__);
            return $default;
        }
        
        return $timezone;
    }
    
    /**
     * Converts a locale Id to a language Id.
     * A language ID consists of only the first group of letters before an underscore or dash.
     * @param string $id the locale ID to be converted
     * @return string the language ID
     */
    public function getLanguage($locale = null)
    {
        if($locale === null) {
            $locale = P::$app->language;
        }
        return locale_get_primary_language($locale);
    }
    
    /**
     * Converts a locale Id to a region Id.
     * A region ID consists of only the first group of letters before an underscore or dash.
     * @param string $id the locale ID to be converted
     * @return string the region ID
     */
    public function getRegion($locale)
    {
        return locale_get_region($locale);
    }
}