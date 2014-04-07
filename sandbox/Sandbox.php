<?php

namespace yii\platform\sandbox;

use yii\platform\P;

class Sandbox extends \yii\base\Component
{
    public $projectId;
    
    public $siteId;
    
    public $config;
    
    public static function factory(array $config)
    {
        list($config, $projectId, $siteId) = self::resolveConfig($config);
        if(!isset($config['class'])) {
            $config['class'] = get_called_class();
        }
        
        return P::createObject([
            'class' => $config['class'],
            'config' => $config['config'],
            'projectId' => $projectId,
            'siteId' => $siteId
        ]);
    }
    
    public static function resolveConfig(array $config)
    {
        foreach($config as $projectId => $project) {
            foreach($project as $siteId => $site) {
                if(!isset($site['rule']) || !isset($site['config'])) {
                    throw new \yii\base\InvalidParamException('Invalid config given.');
                }
                
                /* @var $rule Rule */
                $rule = P::createObject($site['rule']);
                if($rule->isValid()) {
                    return [$site, $projectId, $siteId];
                }
            }
        }
        
        throw new \yii\base\InvalidConfigException('Application sandbox configuration not found.');
    }
    
    public function getConfig()
    {
        return $this->config;
    }
}