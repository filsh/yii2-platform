<?php

namespace yii\platform\rest;

class Controller extends \yii\rest\Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\platform\rest\ErrorAction',
            ]
        ];
    }
}