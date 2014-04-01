<?php

namespace yii\platform\web;

use \yii\platform\helpers\MultilangHelper;

class UrlManager extends \yii\web\UrlManager
{
    public function createUrl($params)
    {
        $url = parent::createUrl($params);
        return MultilangHelper::addLangToUrl($url);
    }
}