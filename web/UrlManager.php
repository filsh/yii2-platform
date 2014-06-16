<?php

namespace yii\platform\web;

use \yii\platform\helpers\MultilangHelper;

class UrlManager extends \yii\web\UrlManager
{
    public $enableAppendLang = false;
    
    public function createUrl($params)
    {
        $url = parent::createUrl($params);
        if($this->enableAppendLang) {
            $url = MultilangHelper::addLangToUrl($url);
        }
        
        return $url;
    }
}