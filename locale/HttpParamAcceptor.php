<?php

namespace yii\platform\locale;

use yii\platform\P;

class HttpParamAcceptor extends Acceptor
{
    public $paramLang = 'lang';
    
    public function acceptLocale($locale)
    {
        $lang = P::$app->getRequest()->get($this->paramLang);
        if($lang === null) {
            return;
        }
        
        if($this->checkSimilarLocale($locale, P::$app->language)) {
            $resolved = P::$app->getRequest()->resolve();
            if(isset($resolved[1][$this->paramLang])) {
                unset($resolved[1][$this->paramLang]);
            }
            
            $url = P::$app->getUrlManager()->createUrl($resolved[0], $resolved[1]);
            P::$app->getResponse()->redirect($url);
        }
        
        P::$app->setLanguage($locale);
        P::$app->getUrlManager()->setParam($this->paramLang, P::$app->locale->getLanguage($locale));
        return $locale;
    }

    public function acceptTimezone($timezone)
    {
        P::$app->setTimeZone($timezone);
        return $timezone;
    }
    
    /**
     * Check of similars two locales
     * @param type $first
     * @param type $second
     * @return bool
     */
    protected function checkSimilarLocale($first, $second)
    {
        return P::$app->locale->getLanguage($first) === P::$app->locale->getLanguage($second);
    }
}