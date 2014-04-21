<?php

namespace yii\platform\sandbox;

use yii\platform\P;
use yii\helpers\ArrayHelper;

class Sandbox extends \yii\base\Component
{
    public $configBasePaths = [];
    
    public $configFileNames = ['main.php', 'main-local.php'];
    
    public $projects;
    
    protected $projectId;
    
    protected $siteId;
    
    public function getProjectId()
    {
        return $this->projectId;
    }
    
    public function getSiteId()
    {
        return $this->siteId;
    }
    
    public function createApplication($config = [])
    {
        if(empty($config['class'])) {
            throw new \yii\base\InvalidParamException('Application class must be specified.');
        }
        
        $this->resolve();
        $class = $config['class'];
        unset($config['class']);
        
        $config = ArrayHelper::merge($this->getConfig(), $config);
        $application = new $class($config);
        $application->set('sandbox', $this);
        
        return $application;
    }
    
    private function resolve()
    {
        foreach($this->projects as $project) {
            if(!isset($project['rule'])) {
                throw new \yii\base\InvalidParamException('Invalid sandbox project config given.');
            }
            
            /* @var $rule Rule */
            $rule = P::createObject($project['rule']);
            if($rule->isValid()) {
                $this->projectId = $project['projectId'];
                $this->siteId = $project['siteId'];
                break;
            }
        }
        
        if(empty($this->projectId) || empty($this->siteId)) {
            echo "Error: project configuration is invalid or not found.\n";
            exit(1);
        }
    }
    
    private function getConfig()
    {
        $config = [];
        foreach($this->configBasePaths as $path) {
            foreach($this->configFileNames as $fileName) {
                $filePath = $this->getFilePath($path, $fileName);
                $config = ArrayHelper::merge($config, require($filePath));
            }
        }
        
        return $config;
    }
    
    private function getFilePath($basePath, $fileName, $type = 'config')
    {
        $path = sprintf('%s/%s/p%d/s%d/%s', $basePath, $type, $this->projectId, $this->siteId, $fileName);
        $filePath = realpath($path);
        if(!$filePath) {
            throw new \yii\base\InvalidParamException('File path is invalid or not exists [' . $path . '].');
        }
        return $filePath;
    }
}