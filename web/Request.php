<?php

namespace yii\platform\web;

use \yii\platform\helpers\MultilangHelper;

class Request extends \yii\web\Request
{
    private $_requestUri;
    
    protected function resolveRequestUri()
    {
        if ($this->_requestUri === null) {
            $this->_requestUri = MultilangHelper::resolveLangFromUrl(parent::resolveRequestUri());
        }
        
        return $this->_requestUri;
    }
    
    public function getOriginalUrl()
    {
        return MultilangHelper::addLangToUrl($this->getUrl());
    }
}