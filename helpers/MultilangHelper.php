<?php

namespace yii\platform\helpers;

use yii\platform\P;

class MultilangHelper
{
    public static function enabled()
    {
        return count(P::$app->getLocale()->enableLocales) > 1;
    }
    
    public static function resolveLangFromUrl($url)
    {
        if (self::enabled())
        {
            $domains = explode('/', ltrim($url, '/'));
            $isLangExists = in_array($domains[0], P::$app->getLocale()->enableLocales);
            $isDefaultLang = $domains[0] == P::$app->getLocale()->defaultLocale;
 
            if ($isLangExists && !$isDefaultLang) {
                $lang = array_shift($domains);
                P::$app->setLanguage($lang);
            }
 
            $url = '/' . implode('/', $domains);
        }
 
        return $url;
    }

    public static function addLangToUrl($url)
    {
        if (self::enabled())
        {
            $domains = explode('/', ltrim($url, '/'));
            $isLangExists = in_array($domains[0], P::$app->getLocale()->enableLocales);
            $isDefaultLang = P::$app->language === P::$app->getLocale()->defaultLocale;
 
            if ($isLangExists && $isDefaultLang) {
                array_shift($domains);
            }
 
            if (!$isLangExists && !$isDefaultLang) {
                array_unshift($domains, P::$app->language);
            }
            
            $url = '/' . implode('/', $domains);
        }
 
        return $url;
    }
}