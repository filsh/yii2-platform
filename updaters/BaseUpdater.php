<?php

namespace yii\platform\updaters;

use yii\base\Component;

abstract class BaseUpdater extends Component
{
    public $tmpPath = '@platform/console/runtime/updater';
    
    public $tmpDirMode = 0775;
    
    public function behaviors()
    {
        return [
            'log' => [
                'class' => 'yii\platform\console\behaviors\Log',
            ],
        ];
    }
    
    abstract public function run();
}