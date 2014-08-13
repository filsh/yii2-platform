<?php

namespace yii\platform\console\controllers;

use yii\platform\P;

/**
 * This command manages application messages.
 */
class MessageController extends \yii\console\controllers\MessageController
{
    const COUNTRIES_SOURCE_FILE_PATTERN = 'https://raw.githubusercontent.com/umpirsky/country-list/master/country/cldr/{language}/country.json';
    
    /**
     * Run generate translation messages for country names.
     *
     * This command load and parse translations csv data file.
     *
     * @param string $sourceUrl the path of the destination source file.
     * @throws Exception if the path argument is invalid.
     */
    public function actionCountries($language, $category = 'db/location', $sourceFilePattern = self::COUNTRIES_SOURCE_FILE_PATTERN)
    {
        P::$app->runner->run('message-countries', [
            'sourceUrl' => strtr($sourceFilePattern, ['{language}' => strtolower($language)]),
            'targetAttribute' => 'name',
            'language' => $language,
            'category' => $category
        ]);
    }
}