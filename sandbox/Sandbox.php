<?php

namespace yii\platform\sandbox;

use yii\platform\P;
use yii\platform\helpers\MultiHelper;
use yii\helpers\ArrayHelper;

class Sandbox extends \yii\base\Component
{
    public $multipath;
    
    public $projectId;
    
    public $siteId;
    
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
    
    public function resolveConfig()
    {
        $config = [
            'components' => [
                'sandbox' => $this
            ]
        ];
        
        $this->resolve();
        
        foreach($this->configBasePaths as $path) {
            foreach($this->configFileNames as $fileName) {
                $filePath = MultiHelper::multipath($this, $path, 'config/' . $fileName);
                $config = ArrayHelper::merge($config, require($filePath));
            }
        }
        
        return $config;
    }
    
    protected function resolve()
    {
        foreach($this->projects as $project) {
            if(!isset($project['rule'])) {
                throw new \yii\base\InvalidConfigException('Invalid sandbox project config given.');
            }
            
            /* @var $rule Rule */
            $rule = P::createObject($project['rule']);
            if($rule->isValid()) {
                unset($project['rule']);
                P::configure($this, $project);
                break;
            }
        }
        
        if(empty($project) || empty($this->projectId) || empty($this->siteId)) {
            throw new NotDetectingException('Project configuration is invalid or not found.');
        }
        
        return $project;
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
}