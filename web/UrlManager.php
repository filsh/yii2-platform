<?php

namespace yii\platform\web;

class UrlManager extends \yii\web\UrlManager
{
    protected $params = [];
    
    public function createUrl($route, $params = [])
    {
        return parent::createUrl($route, array_merge($this->params, $params));
    }
    
    public function setDefaultParam($key, $value)
    {
        $this->params[$key] = $value;
    }
}