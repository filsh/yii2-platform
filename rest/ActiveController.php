<?php

namespace yii\platform\rest;

class ActiveController extends \yii\rest\ActiveController
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