<?php

namespace yii\platform\updaters;

use yii\platform\Platform;
use yii\platform\helpers\FileHelper;
use yii\base\Component;

abstract class BaseUpdater extends Component
{
    public $tmpPath = '@runtime/updater';
    
    public $tmpDirMode = 0775;
    
    public function behaviors()
    {
        return [
            'log' => [
                'class' => 'yii\platform\console\behaviors\Log',
            ],
        ];
    }
    
    public function init()
    {
        parent::init();
        $this->tmpPath = Platform::getAlias($this->tmpPath);
        if (!is_dir($this->tmpPath)) {
            FileHelper::createDirectory($this->tmpPath, $this->tmpDirMode, true);
        }
    }
    
    abstract public function run();
}