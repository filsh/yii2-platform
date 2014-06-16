<?php

namespace yii\platform\web;

use \yii\platform\P;
use \yii\platform\helpers\MultilangHelper;

class Request extends \yii\web\Request
{
    private $_requestUri;
    
    protected function resolveRequestUri()
    {
        if ($this->_requestUri === null) {
            $this->_requestUri = parent::resolveRequestUri();
            
            if(P::$app->getUrlManager()->enableAppendLang) {
                $this->_requestUri = MultilangHelper::resolveLangFromUrl($this->_requestUri);
            }
        }
        
        return $this->_requestUri;
    }
    
    public function getOriginalUrl()
    {
        return MultilangHelper::addLangToUrl($this->getUrl());
    }
}