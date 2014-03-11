<?php

namespace yii\platform\behaviors;

use yii\platform\P;
use yii\base\Behavior;
use yii\helpers\Console;
use yii\console\Controller;
use yii\console\Exception;

/**
 * This is behavior with help action for console commands
 * 
 * ~~~
 * public function behaviors()
 * {
 *     return [
 *         'helpCommand' => [
 *             'class' => 'yii\platform\behaviors\HelpCommand'
 *         ]
 *     ];
 * }
 * ~~~
 */
class HelpCommand extends Behavior
{
    /**
     * Displays available commands or the detailed information to the current command.
     * 
     * @throws Exception the exception throwed for unknown command or bad controller
     */
    public function help()
    {
        $result = P::$app->createController('help');
        
        /* @var $controller \yii\console\controllers\HelpController */
        $controller = $result !== false ? $result[0] : null;
        if ($controller === null || !($controller instanceof Controller)) {
            throw new Exception(P::t('platform', 'No help for this command.', [
                'command' => $this->owner->ansiFormat('help', Console::FG_YELLOW),
            ]));
        }
        
        $controller->actionIndex($this->owner->id);
    }
}