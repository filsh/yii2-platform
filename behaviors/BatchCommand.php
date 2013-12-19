<?php

namespace yii\platform\behaviors;

use yii\base\Behavior;

/**
 * This is behavior with batch database commands
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
 */
class BatchCommand extends Behavior
{
    /**
     *
     * @var string the maximum rows that will be execute. Default is 1000.
     */
    public $maxExecuteRows = 1000;
    
    /**
     * Creates a batch replace rows command.
     * For example,
     * 
     * ~~~
     * $this->batchReplace(['id' => ['1', '2']], ['name', 'age'], [
     *     ['Tom', 30],
     *     ['Jane', 20],
     *     ['Linda', 25],
     * ])->execute();
     * ~~~
     *
     * Note that the values in each row must match the corresponding column names.
     *
     * @param string $table the table that rows will be replaced into.
     * @param array $columns the column names
     * @param array $rows the rows to be batch replace into the table
     * @param array|string $condition the condition that will be put in the WHERE part. That will be put in the WHERE part.
     * Please refer to [[Query::where()]] on how to specify condition.
     * @param array $params the parameters to be bound to the condition
     * @return Owner the owner object itself
     */
    public function batchReplace($table, $columns, $rows, $condition, $params = [])
    {
        if(count($rows) > $this->maxExecuteRows) {
            throw new yii\base\Exception('Too many rows for replace.');
        }
        
        $db = $this->owner->getDb();
        $transaction = $db->beginTransaction();
        
        try {
            $db->createCommand()->delete($table, $condition, $params)->execute();
            $db->createCommand()->batchInsert($table, $columns, $rows)->execute();
            $transaction->commit();
        } catch (yii\db\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
        
        return $this->owner;
    }
    
    /**
     * Generates a batch INSERT SQL statement whith ON DUPLICATE KEY condition.
     * For example,
     *
     * ~~~
     * $this->batchInsertDuplicate(['id' => ['1', '2']], ['name', 'age'], [
     *     ['Tom', 30],
     *     ['Jane', 20],
     *     ['Linda', 25],
     * ], ['name', 'age'])->execute();
     * ~~~
     *
     * Note that the values in each row must match the corresponding column names.
     *
     * @param string $table the table that rows will be inserted or updated into.
     * @param array $columns the column names.
     * @param array $rows the rows to be batch inserted or updated into the table.
     * @param array $duplicates column names to be updated ON DUPLICATE KEY.
     * @return string the batch INSERT ON DUPLICATE KEY SQL statement.
     */
    public function batchInsertDuplicate($table, $columns, $rows, $duplicates = [])
    {
        $db = $this->owner->getDb();
        
        if (($tableSchema = $db->getTableSchema($table)) !== null) {
            $columnSchemas = $tableSchema->columns;
        } else {
            $columnSchemas = [];
        }
        
        $sql = $db->getQueryBuilder()->batchInsert($table, $columns, $rows);
        
        if(!empty($duplicates)) {
            $columnDuplicates = [];
            foreach($duplicates as $i => $column) {
                if(isset($columnSchemas[$duplicates[$i]])) {
                    $column = $db->quoteColumnName($column);
                    $columnDuplicates[] = $column . ' = VALUES(' . $column . ')';
                }
            }
            
            if(!empty($columnDuplicates)) {
                $sql .= ' ON DUPLICATE KEY UPDATE ' . implode(',', $columnDuplicates);
            }
        }
        
        return $db->createCommand()->setSql($sql);
    }
}