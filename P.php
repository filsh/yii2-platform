<?php

namespace yii\platform;

use yii\platform\validators\Validator;

class P extends \Yii
{
}

P::setAlias('@platform', __DIR__);

if(!isset(Validator::$builtInValidators['compareDate'])) {
    Validator::$builtInValidators['compareDate'] = 'yii\platform\validators\CompareDateValidator';
}