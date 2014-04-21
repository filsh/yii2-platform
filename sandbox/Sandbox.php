<?php

namespace yii\platform\sandbox;

use yii\platform\P;
use yii\platform\helpers\MultiHelper;
use yii\helpers\ArrayHelper;
use yii\base\Application;

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
        $app = new $class($config);
        $this->decorateApplication($app);
        
        return $app;
    }
    
    protected function decorateApplication(Application $app)
    {
        $app->set('sandbox', $this);
        
        if(($app instanceof \yii\platform\web\Application)) {
            $app->setViewPath(MultiHelper::multipath($this, $app->getViewPath()));
        }
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
                $this->projectId = $project['projectId'];
                $this->siteId = $project['siteId'];
                break;
            }
        }
        
        if(empty($this->projectId) || empty($this->siteId)) {
            throw new NotDetectingException('Project configuration is invalid or not found.');
        }
    }
    
    protected function getConfig()
    {
        $config = [];
        foreach($this->configBasePaths as $path) {
            foreach($this->configFileNames as $fileName) {
                $filePath = MultiHelper::multipath($this, $path . '/config', $fileName);
                $config = ArrayHelper::merge($config, require($filePath));
            }
        }
        
        return $config;
    }
}