<?php

namespace yii\platform\web;

use yii\platform\P;

class UrlManager extends \yii\web\UrlManager
{
    public $lang;
    
    public function init()
    {
        parent::init();
        
        if($this->lang === null) {
            $this->lang = locale_get_primary_language(P::$app->getLocale()->detectLanguage());
        }
    }
    
    public function createUrl($route, $params = [])
    {
        if($this->lang !== locale_get_primary_language(P::$app->language)) {
            $params['lang'] = $this->lang;
        }
        
        return parent::createUrl($route, $params);
    }
}