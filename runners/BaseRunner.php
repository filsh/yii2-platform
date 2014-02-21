<?php

namespace yii\platform\runners;

use yii\platform\P;
use yii\platform\helpers\FileHelper;
use yii\base\Component;

abstract class BaseRunner extends Component
{
    public $tmpPath = '@runtime/runner';
    
    public $tmpDirMode = 0775;
    
    public function behaviors()
    {
        return [
            'log' => [
                'class' => 'yii\platform\behaviors\Log',
            ],
            'batchCommand' => [
                'class' => 'yii\platform\behaviors\BatchCommand'
            ]
        ];
    }
    
    public function init()
    {
        parent::init();
        $this->tmpPath = P::getAlias($this->tmpPath);
        if (!is_dir($this->tmpPath)) {
            FileHelper::createDirectory($this->tmpPath, $this->tmpDirMode, true);
        }
    }
    
    public function getDb()
    {
        return P::$app->getDb();
    }
    
    abstract public function run();
}