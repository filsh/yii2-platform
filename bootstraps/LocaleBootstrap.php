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
        $timezone = P::$app->getLocale()->detectTimezone(P::$app->timeZone);
        
        if(($locale = $this->detectLocale())) {
            P::$app->setLanguage($locale);
            P::$app->setTimeZone($timezone);
        }
    }
    
    protected function detectLocale()
    {
        $needRedirect = false;
        $cookies = P::$app->getResponse()->cookies;
        $cookie = $cookies->get($this->paramLang);
        $params = P::$app->getRequest()->getQueryParams();
        
        $locale = P::$app->getLocale()->detectLocale(P::$app->language);
        
        if($cookie === null) {
            $cookie = new \yii\web\Cookie([
                'name' => $this->paramLang,
                'value' => $locale
            ]);
            $cookies->add($cookie);
            
            if(!isset($params[$this->paramLang]) && $locale !== P::$app->language) {
                $params[$this->paramLang] = $locale;
                $needRedirect = true;
            } else if(isset($params[$this->paramLang]) && $locale === P::$app->language) {
                unset($params[$this->paramLang]);
                $needRedirect = true;
            }
        } else {
            $locale = $cookie->value;
        }
        
        if($needRedirect) {
            array_unshift($params, $this->getRoute());
            $url = P::$app->getUrlManager()->createUrl($params);
            P::$app->getResponse()->redirect($url);
        }
        
        return $locale;
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
}