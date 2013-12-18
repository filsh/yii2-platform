<?php

namespace yii\platform\behaviors;

use yii\base\Behavior;

class Log extends Behavior
{
    protected $logs = [];
    
    public function getLog()
    {
        $log = array();
        foreach ($this->logs as $key => $value) {
            $logStr = $key . ' - ';
            if (isset($value['end'])) {
                $logStr .= ($value['end'] - $value['start']) . ' seconds';
            }
            if (isset($value['message'])) {
                $logStr .= ' ' . $value['message'];
            }
            $checkpoints = array();
            foreach (array('start', 'end', 'time') as $timeCheckpoint) {
                if (isset($value[$timeCheckpoint])) {
                    $checkpoints[] = ucfirst($timeCheckpoint) . ': ' . $value[$timeCheckpoint];
                }
            }
            if (!empty($checkpoints)) {
                $logStr .= ' (' . implode(', ', $checkpoints) . ')';
            }
            $log[] = $logStr;
        }
        return implode(PHP_EOL, $log) . PHP_EOL;
    }
    
    public function addLog($key, $message = '')
    {
        if (empty($message)) {
            if (!isset($this->logs[$key])) {
                $this->logs[$key] = array(
                    'start' => microtime(1),
                );
            } else {
                $this->logs[$key]['end'] = microtime(1);
            }
        } else {
            $this->logs[$key] = array(
                'time' => microtime(1),
                'message' => $message,
            );
        }
    }
}