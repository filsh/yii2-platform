<?php

namespace yii\platform\sandbox;

class HostRegexpConsoleRule extends HostRegexpRule
{
    const OPTION_APPHOST = 'apphost';
    
    public function getValue()
    {
        if (!empty($_SERVER['argv'])) {
            $option = '--' . self::OPTION_APPHOST . '=';
            foreach ($_SERVER['argv'] as $param) {
                if (strpos($param, $option) !== false) {
                    $host = substr($param, strlen($option));
                    return $host;
                }
            }
        }
        return false;
    }
}