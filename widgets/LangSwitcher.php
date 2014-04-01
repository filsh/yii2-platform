<?php

namespace yii\platform\widgets;

use yii\platform\P;
use yii\widgets\Menu;
use yii\base\InvalidConfigException;

class LangSwitcher extends Menu
{
    public $options = ['class' => 'lang-switcher'];
    
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }
    
    public function run()
    {
        $view = $this->getView();
        LangSwitcherAsset::register($view);
        parent::run();
    }
    
    protected function normalizeItems($items, &$active)
    {
        $currentUrl = ltrim(P::$app->getRequest()->getUrl(), '/');
        $langList = [];
        
        foreach (P::$app->getLocale()->enableLocales as $lang) {
            if ($lang !== P::$app->getLocale()->defaultLocale) {
                $langList[] = $lang;
            }
        }
        
        foreach($items as $i => $item) {
            if(!isset($item['lang'])) {
                throw new InvalidConfigException('The "lang" element is required for each item.');
            }
            
            if(in_array($item['lang'], $langList)) {
                $items[$i]['url'] = '/' . $item['lang'] . '/' . $currentUrl;
            } else {
                $items[$i]['url'] = $currentUrl;
            }
        }
        
        return parent::normalizeItems($items, $active);
    }
}