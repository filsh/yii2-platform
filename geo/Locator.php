<?php

namespace yii\platform\geo;

use yii\platform\Platform;
use yii\platform\geo\models\GeoLocations;
use yii\base\Component;
use yii\base\Exception;

class Locator extends Component
{
    /**
     * Latitude request param name
     * @var string
     */
    public $latitudeParamName = 'lat';
    
    /**
     * Longitude request param name
     * @var string
     */
    public $longitudeParamName = 'lng';
    
    /*
     * User remote latitude
     * @var float
     */
    protected $latitude;
    
    /**
     * User remote longitude
     * @var float
     */
    protected $longitude;
    
    /**
     * User remote address
     * @var string
     */
    protected $address;
    
    private $_location;
    
    public function init()
    {
        parent::init();
        
        $request =  Platform::$app->getRequest();
        $this->address = $request->getUserIP();
        $this->latitude = (float) $request->get($this->latitudeParamName);
        $this->longitude = (float) $request->get($this->longitudeParamName);
        
        // hack for localhost
        if($this->address === '127.0.0.1') {
            $this->address = '217.146.245.0'; // id = 220
        }
    }
    
    /**
     * Find Location by geografic coordinates
     * @param type $lat
     * @param type $lng
     * @return type
     * @throws AppException
     */
    protected function resolveByCoords($lat, $lng)
    {
        if (!$this->getIsValidLocation($lat, $lng)) {
            throw new Exception('Latitude or longitude is not valid.');
        }
        
        return GeoLocations::find()
                ->fromPoint($lat, $lng)
                ->one();
    }
    
    /**
     * Find location by remote address
     * @param type $address
     * @return type
     */
    protected function resolveByAddress($addr)
    {
        return GeoLocations::find()
                ->fromBlock($addr)
                ->one();
    }
    
    /**
     * Return if the location coordinates is valid
     * @return boolean
     */
    protected function getIsValidLocation($latitude, $longitude)
    {
        if (empty($latitude)
                || empty($longitude)
                || !is_float($latitude)
                || !is_float($longitude)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get location data
     * @return object GeoLocations
     */
    public function getLocation()
    {
        if($this->_location == null) {
            if($this->getIsValidLocation($this->latitude, $this->longitude)) {
                $this->_location = $this->resolveByCoords($this->latitude, $this->longitude);
            } else {
                $this->_location = $this->resolveByAddress($this->address);
            }
        }
        
        return $this->_location;
    }
    
    /**
     * Return country code in format iso2
     * @return type
     */
    public function getCountry()
    {
        $location = $this->getLocation();
        return $location !== null ? $location->country : null;
    }
    
    /**
     * Return region
     * @return type
     */
    public function getRegion()
    {
        $location = $this->getLocation();
        return $location !== null ? $location->region : null;
    }
    
    /**
     * Return city name
     * @return type
     */
    public function getCity()
    {
        $location = $this->getLocation();
        return $location !== null ? $location->city : null;
    }
    
    /**
     * Return postal code
     * @return type
     */
    public function getPostal()
    {
        $location = $this->getLocation();
        return $location !== null ? $location->postal : null;
    }
    
    /**
     * Return geografic latitude
     * @return type
     */
    public function getLatitude()
    {
        $location = $this->getLocation();
        if($location !== null && $location->latitude) {
            $latitude = $location->latitude;
        } else {
            $latitude = $this->latitude;
        }
        
        return (float) $latitude;
    }
    
    /**
     * Return geografic longitude
     * @return type
     */
    public function getLongitude()
    {
        $location = $this->getLocation();
        if($location !== null && $location->longitude) {
            $longitude = $location->longitude;
        } else {
            $longitude = $this->longitude;
        }
        
        return (float) $longitude;
    }
}