<?php

namespace yii\platform\behaviors;

use yii\base\Behavior;

class BatchCommand extends Behavior
{
    /**
     *
     * @var string the maximum rows that will be execute. Default is 1000.
     */
    public $maxExecuteRows = 1000;
    
    /**
     * Creates a batch UPDATE command.
     * For example,
     *
     * ~~~
     * public function behaviors()
     * {
     *     return [
     *         'batchCommand' => [
     *             'class' => 'yii\platform\behaviors\BatchCommand'
     *         ]
     *     ];
     * }
     * ~~~
     * 
     * ~~~
     * $thi->batchUpdate(['id' => ['1', '2']], ['name', 'age'], [
     *     ['Tom', 30],
     *     ['Jane', 20],
     *     ['Linda', 25],
     * ])->execute();
     * ~~~
     *
     * Note that the values in each row must match the corresponding column names.
     *
     * @param array $columns the column names
     * @param array $rows the rows to be batch updated into the table
     * @param array|string $condition the condition that will be put in the WHERE part. That will be put in the WHERE part.
     * Please refer to [[Query::where()]] on how to specify condition.
     * @param array $params the parameters to be bound to the condition
     * @return Owner the owner object itself
     */
    public function batchUpdate($columns, $rows, $condition, $params = [])
    {
        if(count($rows) > $this->maxExecuteRows) {
            throw new yii\base\Exception('Too many rows for process.');
        }
        
        $tableName = $this->owner->tableName();
        $command = $this->owner->getDb()->createCommand();
        $transaction = $this->owner->getDb()->beginTransaction();
        
        try {
            $command->delete($tableName, $condition, $params)->execute();
            $command->batchInsert($tableName, $columns, $rows)->execute();
            $transaction->commit();
        } catch (yii\db\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
        
        return $this->owner;
    }
}