<?php

namespace yii\platform\sandbox;

use yii\platform\P;

class Sandbox extends \yii\base\Component
{
    public $projects;
    
    protected $config;
    
    protected $projectId;
    
    protected $siteId;
    
    public function resolve()
    {
        foreach($this->projects as $project) {
            if(!isset($project['rule'])) {
                throw new \yii\base\InvalidParamException('Invalid project config given.');
            }
            
            /* @var $rule Rule */
            $rule = P::createObject($project['rule']);
            if($rule->isValid()) {
                $this->config = $project['config'];
                $this->projectId = $project['project_id'];
                $this->siteId = $project['site_id'];
            }
        }
        
        if(empty($this->projectId) || empty($this->siteId) || empty($this->config)) {
            throw new \yii\base\InvalidParamException('Invalid project config given.');
        }
    }
    
    public function getProjectId()
    {
        return $this->projectId;
    }
    
    public function getSiteId()
    {
        return $this->siteId;
    }
    
    public function getConfig()
    {
        return $this->config;
    }
}