<?php

namespace yii\platform\runners;

use yii\platform\P;
use yii\helpers\ArrayHelper;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Runner extends Component
{
    /**
     * @var array an array of runners default configurations (name=>config).
     */
    public $runners = [];
    
    public function init()
    {
        parent::init();
        $this->runners = ArrayHelper::merge(P::$app->coreRunners(), $this->runners);
    }
    
    /**
     * Runs the runner with the given name.
     * 
     * @param string $runner the name of the formatter.
     * @param array $params initial values to be applied to the runner properties(merge with configuration).
     * @return string the formatted value.
     */
    public function run($name, $params = array())
    {
        return $this->createRunner($name, $params)->run();
    }
    
    /**
     * Create the runner with the given name.
     * 
     * @param string $format the name or class of the formatter.
     * @param CModel $object the model.
     * @param array $config initial values to be applied to the formatter properties.
     * @return BaseFormatter the formatter instance.
     */
    protected function createRunner($name, $config = array())
    {
        if(is_callable($name))
        {
            $runner = new InlineRunner();
            $runner->method = $name;
            $runner->params = $config;
        }
        else
        {
            if(!isset($this->runners[$name])) {
                throw new InvalidConfigException('Object runner not found.');
            }
            
            if(is_string($this->runners[$name])) {
                $config['class'] = $this->runners[$name];
            } else if(is_array($this->runners[$name])) {
                $config = ArrayHelper::merge($this->runners[$name], $config);
            } else {
                throw new InvalidConfigException('Object runner configuration is invalid or not found.');
            }
            $runner = P::createObject($config);
        }
        return $runner;
    }
}