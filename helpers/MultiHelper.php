<?php

namespace yii\platform\helpers;

use yii\platform\P;
use yii\platform\sandbox\Sandbox;

class MultiHelper
{
    public static function multipath(Sandbox $sandbox, $prefix = '', $suffix = '', $separator = '/')
    {
        $multipath = $sandbox->getMultipath();
        if($multipath === null) {
            $pattern = implode($separator, ['%s', 'p%ds%d', '%s']);
            $alias = sprintf($pattern, $prefix, $sandbox->getProjectId(), $sandbox->getSiteId(), $suffix);
        } else {
            $pattern = implode($separator, ['%s', $multipath, '%s']);
            $alias = sprintf($pattern, $prefix, $suffix);
        }
        
        $path = P::getAlias($alias);
        if(($realPath = realpath($path)) === false) {
            throw new \yii\base\InvalidParamException('File path is invalid or not exists [' . $path . '].');
        }
        
        return $realPath;
    }
}