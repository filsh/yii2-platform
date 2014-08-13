<?php

namespace yii\platform\console\controllers;

use yii\platform\P;
use yii\console\Controller;

/**
 * This command manages application runners.
 */
class RunnerController extends Controller
{
    const LOCATIONS_SOURCE_URL = 'http://geolite.maxmind.com/download/geoip/database/GeoLiteCity_CSV/GeoLiteCity-latest.zip';
    
    const REGIONS_SOURCE_URL = 'http://dev.maxmind.com/static/csv/codes/maxmind/region.csv';
    
    const TIMEZONES_SOURCE_URL = 'http://dev.maxmind.com/static/csv/codes/time_zone.csv';
    
    const COUNTRIES_SOURCE_URL = 'http://download.geonames.org/export/dump/countryInfo.txt';
    
    /**
     * Run locations runner.
     *
     * This command load and parse Maxmind csv data file.
     *
     * @param string $sourceUrl the path of the destination source file. This should only contain
     * path to zip archive.
     * @throws Exception if the path argument is invalid.
     */
    public function actionLocations($sourceUrl = self::LOCATIONS_SOURCE_URL)
    {
        P::$app->runner->run('locations', [
            'sourceUrl' => $sourceUrl
        ]);
    }
    
    /**
     * Run regions runner.
     *
     * This command load and parse Maxmind regions csv data file.
     *
     * @param string $sourceUrl the path of the destination source file.
     * @throws Exception if the path argument is invalid.
     */
    public function actionRegions($sourceUrl = self::REGIONS_SOURCE_URL)
    {
        P::$app->runner->run('regions', [
            'sourceUrl' => $sourceUrl
        ]);
    }
    
    /**
     * Run timezones runner.
     *
     * This command load and parse Maxmind timezones csv data file.
     *
     * @param string $sourceUrl the path of the destination source file.
     * @throws Exception if the path argument is invalid.
     */
    public function actionTimezones($sourceUrl = self::TIMEZONES_SOURCE_URL)
    {
        P::$app->runner->run('timezones', [
            'sourceUrl' => $sourceUrl
        ]);
    }
    
    /**
     * Run countries runner.
     *
     * This command load and parse countryinfo csv data file from web site http://www.geonames.org/.
     *
     * @param string $sourceUrl the path of the destination source file.
     * @throws Exception if the path argument is invalid.
     */
    public function actionCountries($sourceUrl = self::COUNTRIES_SOURCE_URL)
    {
        P::$app->runner->run('countries', [
            'sourceUrl' => $sourceUrl
        ]);
    }
}