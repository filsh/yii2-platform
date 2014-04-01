<?php

namespace yii\platform\bootstraps;

use yii\platform\P;
use yii\base\Application;
use yii\base\BootstrapInterface;

class LocaleBootstrap implements BootstrapInterface
{
    public $paramLang = 'lang';
    
    protected $_resolvedRequest;
    
    public function bootstrap(Application $app)
    {
        $this->_resolvedRequest = P::$app->getRequest()->resolve();
        
        $lang = $this->detectLanguage();
        $this->checkRedirect($lang);
        
        if(P::$app->language !== $lang) {
            P::$app->setLanguage($lang);
            P::$app->getUrlManager()->setParam($this->paramLang, $lang);
        }
        
        $timezone = P::$app->getLocale()->detectTimezone(P::$app->timeZone);
        P::$app->setTimeZone($timezone);
    }
    
    protected function detectLanguage()
    {
        $locale = P::$app->getLocale();
        $detected = $locale->detectLocale(P::$app->language);
        return $locale->getLanguage($detected);
    }
    
    protected function checkRedirect($lang)
    {
        $params = $this->getRouteParams();
        
        if(P::$app->language === $lang) {
            if(isset($params[$this->paramLang])) {
                unset($params[$this->paramLang]);
                $this->redirectTo($params);
            }
        } else if(!isset($params[$this->paramLang]) || $params[$this->paramLang] !== $lang) {
            $params[$this->paramLang] = $lang;
            $this->redirectTo($params);
        }
    }
    
    protected function redirectTo($params)
    {
        array_unshift($params, $this->getRoute());
        $url = P::$app->getUrlManager()->createUrl($params);
        P::$app->getResponse()->redirect($url);
    }
    
    protected function getRoute()
    {
        if(empty(P::$app->controller)) {
            $route = $this->_resolvedRequest[0];
        } else {
            $route = P::$app->controller->getRoute();
        }
        return $route;
    }
    
    protected function getRouteParams()
    {
        return $this->_resolvedRequest[1];
    }
}