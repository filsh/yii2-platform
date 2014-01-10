<?php

namespace yii\platform\locale;

use yii\platform\P;

class HttpParamAcceptor extends Acceptor
{
    public $paramLang = 'lang';
    
    public function acceptLocale($locale)
    {
        $route = $this->getRoute();
        if(!isset($_GET[$this->paramLang])) {
            return;
        }
        
        $lang = P::$app->locale->getLanguage($locale);
        $appLang = P::$app->locale->getLanguage(P::$app->language);
        if($lang === $appLang)
        {
            unset($_GET[$this->paramLang]);
            $url = P::$app->getUrlManager()->createUrl($route, $_GET);
            P::$app->getResponse()->redirect($url);
        }
        
        P::$app->setLanguage($locale);
        P::$app->getUrlManager()->setParam($this->paramLang, $lang);
        return true;
    }

    public function acceptTimezone($timezone)
    {
        return false;
    }
    
    protected function getRoute()
    {
        if(empty(P::$app->controller)) {
            $route = P::$app->getRequest()->resolve()[0];
        } else {
            $route = P::$app->controller->getRoute();
        }
        return $route;
    }
}