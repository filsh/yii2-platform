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
 *      ],
 *      'acceptors' => [
 *          [
 *              'class' => 'yii\platform\locale\HttpParamAcceptor'
 *          ]
 *      ]
 *  ]
 * ~~~
 */
class Locale extends \yii\base\Component
{
    public $detectors = [];
    
    public $acceptors = [
        [
            'class' => 'yii\platform\locale\DefaultAcceptor'
        ]
    ];
    
    public $locales = [];
    
    public function init()
    {
        parent::init();
        
        $this->locales = array_merge($this->locales, ['en', 'en-US']);
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
     * Returns the acceptor for the given configure.
     * @param Detector|array $detector
     * @return Detector
     * @throws InvalidConfigException
     */
    public function getAcceptor($acceptor)
    {
        if($acceptor instanceof Acceptor) {
            $class = get_class($acceptor);
        } else if(isset($acceptor['class'])) {
            $class = $acceptor['class'];
        } else {
            throw new InvalidConfigException("Unable to create locale acceptor '$acceptor'.");
        }
        
        if(!isset($this->acceptors[$class])) {
            $this->acceptors[$class] = P::createObject($acceptor);
        }
        
        return $this->acceptors[$class];
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
        
        return $this->acceptLocale($this->formatLocale($locale, $default));
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
        
        return $this->acceptTimezone($this->formatTimezone($timezone, $default));
    }
    
    /**
     * Run accepting locale
     * @param type $default
     * @return string
     */
    public function acceptLocale($locale)
    {
        foreach($this->acceptors as $acceptor) {
            $acceptor = $this->getAcceptor($acceptor);
            if($acceptor->acceptLocale && $acceptor->acceptLocale($locale) === true) {
                return $locale;
            }
        }
        P::warning(sprintf('Locale \'%s\' is not accepted, still on default \'%s\'', $locale, P::$app->language), __CLASS__);
        return $locale;
    }
    
    /**
     * Run accepting timezone
     * @param type $default
     * @return string
     */
    public function acceptTimezone($timezone)
    {
        foreach($this->acceptors as $acceptor) {
            $acceptor = $this->getAcceptor($acceptor);
            if($acceptor->acceptTimezone && $acceptor->acceptTimezone($timezone) === true) {
                return $timezone;
            }
        }
        P::warning(sprintf('Timezone \'%s\' is not accepted, still on default \'%s\'', $timezone, P::$app->getTimeZone()), __CLASS__);
        return $timezone;
    }
    
    /**
     * Format language with region id
     * @param type $locale
     * @param type $default
     * @return string
     */
    public function formatLocale($locale, $default = 'en-US')
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
            $locale = $lang === 'en' ? 'en-US' : $lang;
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
        if($timezone === null) {
            return $default;
        }
        
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