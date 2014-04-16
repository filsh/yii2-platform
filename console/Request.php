<?php

namespace yii\platform\console;

class Request extends \yii\console\Request
{
    public function resolve()
    {
        list($route, $params) = parent::resolve();
        if(!empty($params)) {
            foreach($params as $name => $param) {
                if($name === Application::OPTION_APPHOST) {
                    unset($params[$name]);
                    break;
                }
            }
        }
        return [$route, $params];
    }
}