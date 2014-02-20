<?php

namespace yii\platform\validators;

use DateTime;
use yii\validators\CompareValidator;
use yii\base\ErrorException;

class CompareDateValidator extends CompareValidator
{
    public $format = 'Y-m-d';
    
    protected function compareValues($operator, $value, $compareValue)
    {
        $value = DateTime::createFromFormat($this->format, $value);
        $compareValue = DateTime::createFromFormat($this->format, $compareValue);
        
        if(!$value || !$compareValue) {
            return false;
        }
        
        $diff = ((int) $value->diff($compareValue)->format('%r%a%H%I%S')) * -1;
        switch ($operator) {
            case '==': return $diff == 0;
            case '===': return $diff === 0;
            case '!=': return $diff != 0;
            case '!==': return $diff !== 0;
            case '>': return $diff > 0;
            case '>=': return $diff >= 0;
            case '<': return $diff < 0;
            case '<=': return $diff <= 0;
            default: return false;
        }
    }
    
    public function clientValidateAttribute($object, $attribute, $view)
    {
        return null;
    }
}