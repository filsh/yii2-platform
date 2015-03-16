<?php

namespace yii\platform\sandbox;

use yii\platform\P;
use yii\platform\helpers\MultiHelper;
use yii\helpers\ArrayHelper;

class Sandbox extends \yii\base\Component
{
    protected $projects;
    
    protected $settings;
    
    protected $configBasePaths = [];
    
    protected $configFileNames = ['main.php'];
    
    public function createApplication($config = [])
    {
        try {
            if(empty($config['class'])) {
                throw new \yii\base\InvalidParamException('Application class must be specified.');
            }
            
            $className = $config['class'];
            unset($config['class']);
            
            return new $className(ArrayHelper::merge($this->resolveConfig(), $config));
        } catch(NotDetectingException $e) {
            echo $e->getMessage();
            die("\n\n");
        }
    }
    
    public function resolveConfig($projectName = null)
    {
        $config = [
            'components' => [
                'sandbox' => $this
            ]
        ];
        
        $project = $this->resolve($projectName);
        foreach($this->configBasePaths as $path) {
            $configFileNames = isset($project['configFileNames']) ? $project['configFileNames'] : $this->configFileNames;
            foreach($configFileNames as $fileName) {
                $filePath = $this->multipath($project, $path, 'config/' . $fileName);
                $config = ArrayHelper::merge($config, require($filePath));
            }
        }
        return $config;
    }
    
    public function getSettings()
    {
        return $this->settings;
    }
    
    public function setSettings($config)
    {
        $this->settings = P::createObject($config);
    }
    
    public function setProjects($projects)
    {
        $this->projects = $projects;
    }
    
    public function getProjects()
    {
        return $this->projects;
    }
    
    public function setConfigBasePaths($paths)
    {
        $this->configBasePaths = $paths;
    }
    
    public function setConfigFileNames($names)
    {
        $this->configFileNames = $names;
    }
    
    protected function resolve($projectName = null)
    {
        if($projectName !== null && isset($this->projects[$projectName])) {
            $project = $this->projects[$projectName];
        } else {
            foreach($this->projects as $project) {
                if(!isset($project['rule'])) {
                    throw new \yii\base\InvalidConfigException('Invalid sandbox project config given.');
                }

                /* @var $rule Rule */
                $rule = P::createObject($project['rule']);
                if($rule->isValid()) {
                    break;
                }
            }
        }
        
        if(empty($project)) {
            throw new NotDetectingException('Not found project configuration.');
        } else if(!isset($project['projectId']) || !isset($project['siteId'])) {
            throw new \yii\base\InvalidConfigException('Invalid sandbox project config given.');
        }

        return $project;
    }
    
    public function multipath($project, $prefix = '', $suffix = '', $separator = '/')
    {
        if(!isset($project['multipath'])) {
            $pattern = implode($separator, ['%s', 'p%ds%d', '%s']);
            $alias = sprintf($pattern, $prefix, $project['projectId'], $project['siteId'], $suffix);
        } else {
            $pattern = implode($separator, ['%s', $project['multipath'], '%s']);
            $alias = sprintf($pattern, $prefix, $suffix);
        }
        
        $path = P::getAlias($alias);
        if(($realPath = realpath($path)) === false) {
            throw new \yii\base\InvalidParamException('File path is invalid or not exists [' . $path . '].');
        }
        
        return $realPath;
    }
}