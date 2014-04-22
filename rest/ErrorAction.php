<?php

namespace yii\platform\rest;

use yii\platform\P;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;

class ErrorAction extends \yii\web\ErrorAction
{
    public function run()
    {
        if (($exception = P::$app->errorHandler->exception) === null) {
            return '';
        }
        return $this->convertExceptionToArray($exception);
    }
    
    /**
     * Converts an exception into an array.
     * @param \Exception $exception the exception being converted
     * @return array the array representation of the exception.
     */
    protected function convertExceptionToArray(\Exception $exception)
    {
        $error = [];
        if ($exception instanceof HttpException) {
            $error['code'] = $exception->statusCode;
        } else {
            $error['code'] = 500;
        }
        
        if ($exception instanceof Exception) {
            $error['name'] = $exception->getName();
        } else {
            $error['name'] = P::t('yii', 'Error');
        }
        
        if ($exception instanceof UserException) {
            $error['message'] = $exception->getMessage();
        } else {
            $error['message'] = P::t('yii', 'An internal server error occurred.');
        }
        
        if(YII_DEBUG) {
            $error['debug'] = [
                'code' => $exception->getCode(),
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'stack-trace' => explode("\n", $exception->getTraceAsString())
            ];
            
            if (($prev = $exception->getPrevious()) !== null) {
                $error['debug']['previous'] = $this->convertExceptionToArray($prev);
            }
        }
        
        return $error;
    }
}
