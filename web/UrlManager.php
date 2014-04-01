<?php

namespace yii\platform\web;

class UrlManager extends \yii\web\UrlManager
{
    protected $params = [];
    
    public function createUrl($params)
    {
        if(!is_array($params)) {
            $params = array($params);
        }
        return parent::createUrl($params + $this->params);
    }
    
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }
}