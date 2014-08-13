<?php

namespace yii\platform\runners;

use yii\platform\helpers\FileHelper;
use yii\platform\geo\models\Countries;
use yii\platform\i18n\models\Message;

class MessageCountriesRunner extends BaseRunner
{
    public $tmpPath = '@runtime/runner/message-countries';
    
    public $sourceUrl;
    
    public $language;
    
    public $category;
    
    public $targetAttribute;
    
    public function run()
    {
        if(empty($this->language)) {
            throw new \yii\base\Exception('Language must be specified.');
        }
        if(empty($this->category)) {
            throw new \yii\base\Exception('Message category must be specified.');
        }
        if(empty($this->targetAttribute)) {
            throw new \yii\base\Exception('Target model attribute must be specified.');
        }
        
        FileHelper::loadFile($this->sourceUrl, [
            'destDir' => $this->tmpPath,
            'onLoad' => [$this, 'resolveFile']
        ]);
        
        FileHelper::removeDirectory($this->tmpPath);
    }
    
    public function resolveFile($file)
    {
        if(!is_file($file)) {
            throw new \yii\base\Exception('Source file not found.');
        }
        
        $data = json_decode(file_get_contents($file), true);
        foreach(Countries::find(1)->all() as $country) {
            $countryCode = strtoupper($country->iso_alpha2);
            if(isset($data[$countryCode])) {
                if(($message = $country->getAttribute($this->targetAttribute)) === null) {
                    throw new \yii\base\Exception('Bad target attribute value.');
                }
                Message::initDbMessage($this->category, $this->language, $message, $data[$countryCode]);
            }
        }
    }
}