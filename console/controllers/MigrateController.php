<?php

namespace yii\platform\console\controllers;

/**
 * This command manages application migrations.
 *
 * A migration means a set of persistent changes to the application environment
 * that is shared among different developers. For example, in an application
 * backed by a database, a migration may refer to a set of changes to
 * the database, such as creating a new table, adding a new table column.
 *
 * This command provides support for tracking the migration history, upgrading
 * or downloading with migrations, and creating new migration skeletons.
 *
 * The migration history is stored in a database table named
 * as [[migrationTable]]. The table will be automatically created the first time
 * this command is executed, if it does not exist. You may also manually
 * create it as follows:
 *
 * ~~~
 * CREATE TABLE tbl_migration (
 *     version varchar(180) PRIMARY KEY,
 *     apply_time integer
 * )
 * ~~~
 *
 * Below are some common usages of this command:
 *
 * ~~~
 * # creates a new migration named 'create_user_table'
 * yii migrate/create create_user_table
 *
 * # applies ALL new migrations
 * yii migrate
 *
 * # reverts the last applied migration
 * yii migrate/down
 * ~~~
 */
class MigrateController extends \yii\console\controllers\MigrateController
{
    /**
     * @var string the directory storing the migration classes. This can be either
     * a path alias or a directory.
     */
    public $migrationPlatformPath = '@platform/console/migrations';
    
    /**
     * @inheritdoc
     */
    public $db = 'pdb';
    
    public function beforeAction($action)
    {
        $path = \Yii::getAlias($this->migrationPlatformPath);
        if (!is_dir($path)) {
            throw new \Exception("The migration directory \"{$this->migrationPlatformPath}\" does not exist.");
        }
        $this->migrationPlatformPath = $path;
        
        return parent::beforeAction($action);
    }
    
    /**
     * Creates a new migration instance.
     * @param string $class the migration class name
     * @return \yii\db\Migration the migration instance
     */
    protected function createMigration($class)
    {
        $file = $this->migrationPath . DIRECTORY_SEPARATOR . $class . '.php';
        if(!file_exists($file)) {
            $file = $this->migrationPlatformPath . DIRECTORY_SEPARATOR . $class . '.php';
        }
        require_once($file);
        return new $class(['db' => $this->db]);
    }

    /**
     * Returns the migrations that are not applied.
     * @return array list of new migrations
     */
    protected function getNewMigrations()
    {
        $applied = [];
        foreach ($this->getMigrationHistory(-1) as $version => $time) {
             $applied[substr($version, 1, 13)] = true;
        }

        $migrations = parent::getNewMigrations();
        $handle = opendir($this->migrationPlatformPath);
        while (($file = readdir($handle)) !== false) {
             if ($file === '.' || $file === '..') {
                 continue;
             }
             $path = $this->migrationPlatformPath . DIRECTORY_SEPARATOR . $file;
             if (preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/', $file, $matches) && is_file($path) && !isset($applied[$matches[2]])) {
                 $migrations[] = $matches[1];
             }
        }
        closedir($handle);
        sort($migrations);
        return $migrations;
    }
}