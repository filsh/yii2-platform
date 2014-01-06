<?php

namespace yii\platform\updaters;

use yii\platform\P;
use yii\helpers\ArrayHelper;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Updater extends Component
{
    /**
     * @var array an array of updaters default configurations (name=>config).
     */
    public $updaters = [];
    
    public function init()
    {
        parent::init();
        $this->updaters = ArrayHelper::merge(P::$app->coreUpdaters(), $this->updaters);
    }
    
    /**
     * Runs the updater with the given name.
     * 
     * @param string $updater the name of the formatter.
     * @param array $params initial values to be applied to the updater properties(merge with configuration).
     * @return string the formatted value.
     */
    public function run($name, $params = array())
    {
        return $this->createUpdater($name, $params)->run();
    }
    
    /**
     * Create the updater with the given name.
     * 
     * @param string $format the name or class of the formatter.
     * @param CModel $object the model.
     * @param array $config initial values to be applied to the formatter properties.
     * @return BaseFormatter the formatter instance.
     */
    protected function createUpdater($name, $config = array())
    {
        if(is_callable($name))
        {
            $updater = new InlineUpdater();
            $updater->method = $name;
            $updater->params = $config;
        }
        else
        {
            if(!isset($this->updaters[$name])) {
                throw new InvalidConfigException('Object updater not found.');
            }
            
            if(is_string($this->updaters[$name])) {
                $config['class'] = $this->updaters[$name];
            } else if(is_array($this->updaters[$name])) {
                $config = ArrayHelper::merge($this->updaters[$name], $config);
            } else {
                throw new InvalidConfigException('Object updater configuration is invalid or not found.');
            }
            $updater = P::createObject($config);
        }
        return $updater;
    }
}