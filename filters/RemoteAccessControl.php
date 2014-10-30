<?php

namespace yii\platform\filters;

use yii\platform\P;
use yii\web\ForbiddenHttpException;

class RemoteAccessControl extends \yii\base\ActionFilter
{
    public $allowedIPs = ['127.0.0.1', '::1'];
    
    public function beforeAction($action)
    {
        if(!parent::beforeAction($action)) {
            return false;
        }
        
        $ip = P::$app->getRequest()->getUserIP();
        foreach ($this->allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                return true;
            }
        }
        $this->denyAccess($ip);
        
        return false;
    }
    
    protected function denyAccess($ip)
    {
        P::warning('Access to install controller is denied due to IP address restriction. The requested IP is ' . $ip, __METHOD__);
        throw new ForbiddenHttpException('You are not allowed to access this page.');
    }
}