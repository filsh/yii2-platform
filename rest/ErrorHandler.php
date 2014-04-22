<?php

namespace yii\platform\rest;

use yii\platform\P;
use yii\web\Response;

class ErrorHandler extends \yii\web\ErrorHandler
{
    /**
     * @inheritdoc
     */
    protected function renderException($exception)
    {
        if (P::$app->has('response')) {
            $response = P::$app->getResponse();
        } else {
            $response = new Response();
        }
        
        if($this->errorAction !== null) {
            $result = P::$app->runAction($this->errorAction);
            if ($result instanceof Response) {
                $response = $result;
            } else {
                $response->data = $result;
            }
            
            if ($exception instanceof \yii\web\HttpException) {
                $response->setStatusCode($exception->statusCode);
            } else {
                $response->setStatusCode(500);
            }

            $response->send();
        } else {
            parent::renderException($exception);
        }
    }
}