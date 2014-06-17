<?php

namespace yii\platform\locale\detectors;

use yii\platform\P;
use yii\platform\geo\models\Timezones;

class GeoLocatorDetector extends Detector
{
    public function detectLocale($locales = [])
    {
        // TODO: логика по определению языка с GeoLocator
        return null;
    }

    /**
     * @param type $timezones - not used
     * @return null
     */
    public function detectTimezone($timezones = [])
    {
        return P::$app->getGeoLocator()->getTimezone($timezones);
    }
}