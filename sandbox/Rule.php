<?php

namespace yii\platform\sandbox;

abstract class Rule extends \yii\base\Object
{
    abstract function isValid();
}