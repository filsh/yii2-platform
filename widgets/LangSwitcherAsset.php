<?php

namespace yii\platform\widgets;
use yii\web\AssetBundle;

class LangSwitcherAsset extends AssetBundle
{
    public $sourcePath = '@yii/assets';
    
    public $css = [];
    
    public $js = [];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
