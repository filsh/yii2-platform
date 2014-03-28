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
        $location = P::$app->getGeoLocator()->getLocation();
        if($location !== null && !empty($location->country)) {
            $query = Timezones::find()->where('country = :country', [':country' => $location->country]);
            
            if(!empty($location->region)) {
                $query->andWhere('region = :region OR region = \'\'', [':region' => $location->region]);
                $query->orderBy('region DESC');
            }
            if(!empty($timezones)) {
                $query->andWhere(['timezone' => $timezones]);
            }
            
            $mTimezones = $query->one();
            if($mTimezones !== null) {
                return $mTimezones->timezone;
            }
        }
        
        return null;
    }
}