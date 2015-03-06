<?php

namespace yii\platform\helpers;

use yii\platform\P;

class MultiHelper
{
    public static function multipath($multipath, $projectId, $siteId, $prefix = '', $suffix = '', $separator = '/')
    {
        if($multipath === null) {
            $pattern = implode($separator, ['%s', 'p%ds%d', '%s']);
            $alias = sprintf($pattern, $prefix, $projectId, $siteId, $suffix);
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