<?php

namespace yii\platform\sandbox;

class NotDetectingException extends \yii\base\Exception
{
    public function getName()
    {
        return 'Not Detecting Sandbox';
    }
}
