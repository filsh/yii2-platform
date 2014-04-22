<?php

namespace yii\platform\web;

use yii\helpers\Json;

class JsonResponseFormatter extends \yii\web\JsonResponseFormatter
{
    public $prettyPrint = false;
    
    /**
     * @inheritdoc
     */
    protected function formatJson($response)
    {
        $response->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');
        $response->content = Json::encode($response->data, $this->prettyPrint ? JSON_PRETTY_PRINT : 0);
    }
}