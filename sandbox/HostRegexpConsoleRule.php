<?php

namespace yii\platform\sandbox;

use yii\platform\P;
use yii\platform\console\Application;
use yii\helpers\Console;

class HostRegexpConsoleRule extends HostRegexpRule
{
    public function getValue()
    {
        if (!empty($_SERVER['argv'])) {
            $option = '--' . Application::OPTION_APPHOST . '=';
            foreach ($_SERVER['argv'] as $param) {
                if (strpos($param, $option) !== false) {
                    $host = substr($param, strlen($option));
                    return $host;
                }
            }
        }
        
        $this->stdout(P::t('platform', 'Missing required option: --{name}', ['name' => Application::OPTION_APPHOST]), Console::FG_RED);
        die("\n\n");
    }
    
    public function stdout($string)
    {
        if (Console::streamSupportsAnsiColors(STDOUT)) {
            $args = func_get_args();
            array_shift($args);
            $string = Console::ansiFormat($string, $args);
        }

        return Console::stdout($string);
    }
}