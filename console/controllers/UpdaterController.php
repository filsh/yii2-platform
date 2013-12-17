<?php

namespace yii\platform\console\controllers;

use yii\console\Controller;
use yii\platform\updaters\Updater;

/**
 * This command manages application updaters.
 */
class UpdaterController extends Controller
{
    const MAXMIND_SOURCE_FILE = 'http://geolite.maxmind.com/download/geoip/database/GeoLiteCity_CSV/GeoLiteCity-latest.zip';
    
    /**
     * Run Maxmind updater.
     *
     * This command load and parse Maxmind csv data file.
     *
     * @param string $sourceFile the path of the destination source file. This should only contain
     * path to zip archive.
     * @throws Exception if the path argument is invalid.
     */
    public function actionMaxmind($sourceFile = self::MAXMIND_SOURCE_FILE)
    {
        $updater = new Updater();
        $updater->run('maxmind', [
            'sourceFile' => $sourceFile
        ]);
    }
}