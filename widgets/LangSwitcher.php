<?php

namespace yii\platform\widgets;

use yii\platform\P;
use yii\widgets\Menu;
use yii\base\InvalidConfigException;

class LangSwitcher extends Menu
{
    public $options = ['class' => 'lang-switcher'];
    
    public $paramLang = 'lang';
    
    public function run()
    {
        $view = $this->getView();
        LangSwitcherAsset::register($view);
        parent::run();
    }
    
    protected function normalizeItems($items, &$active)
    {
        foreach($items as $i => $item) {
            if(!isset($item[$this->paramLang])) {
                throw new InvalidConfigException('The "' . $this->paramLang . '" element is required for each item.');
            }
            
            $this->params[$this->paramLang] = $item[$this->paramLang];
            $items[$i]['url'] = $this->params;
            array_unshift($items[$i]['url'], $this->route);
        }
        
        return parent::normalizeItems($items, $active);
    }
    
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            if (count($item['url']) > 1) {
                $lang = P::$app->locale->getLanguage(P::$app->language);
                $params = array_splice($item['url'], 1);
                
                if(!isset($params[$this->paramLang])) {
                    return parent::isItemActive($item);
                } else if($params[$this->paramLang] === $lang) {
                    return true;
                }
            }
        }
        
        return false;
    }
}