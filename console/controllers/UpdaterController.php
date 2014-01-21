<?php

namespace yii\platform\console\controllers;

use yii\platform\P;
use yii\console\Controller;

/**
 * This command manages application updaters.
 */
class UpdaterController extends Controller
{
    const LOCATIONS_SOURCE_URL = 'http://geolite.maxmind.com/download/geoip/database/GeoLiteCity_CSV/GeoLiteCity-latest.zip';
    
    const REGIONS_SOURCE_URL = 'http://dev.maxmind.com/static/csv/codes/maxmind/region.csv';
    
    const TIMEZONES_SOURCE_URL = 'http://dev.maxmind.com/static/csv/codes/time_zone.csv';
    
    /**
     * Run locations updater.
     *
     * This command load and parse Maxmind csv data file.
     *
     * @param string $sourceUrl the path of the destination source file. This should only contain
     * path to zip archive.
     * @throws Exception if the path argument is invalid.
     */
    public function actionLocations($sourceUrl = self::LOCATIONS_SOURCE_URL)
    {
        P::$app->updater->run('locations', [
            'sourceUrl' => $sourceUrl
        ]);
    }
    
    /**
     * Run regions updater.
     *
     * This command load and parse Maxmind regions csv data file.
     *
     * @param string $sourceUrl the path of the destination source file.
     * @throws Exception if the path argument is invalid.
     */
    public function actionRegions($sourceUrl = self::REGIONS_SOURCE_URL)
    {
        P::$app->updater->run('regions', [
            'sourceUrl' => $sourceUrl
        ]);
    }
    
    /**
     * Run timezones updater.
     *
     * This command load and parse Maxmind timezones csv data file.
     *
     * @param string $sourceUrl the path of the destination source file.
     * @throws Exception if the path argument is invalid.
     */
    public function actionTimezones($sourceUrl = self::TIMEZONES_SOURCE_URL)
    {
        P::$app->updater->run('timezones', [
            'sourceUrl' => $sourceUrl
        ]);
    }
}