<?php

namespace yii\platform\geo;

use yii\platform\P;
use yii\platform\geo\models\Locations;
use yii\platform\geo\models\Timezones;
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
    
    private $_timezone;
    
    public function init()
    {
        parent::init();
        
        $request =  P::$app->getRequest();
        $this->address = ($address = $request->getUserIP()) === null ? '127.0.0.1' : $address;
        $this->latitude = (float) $request->getQueryParam($this->latitudeParamName);
        $this->longitude = (float) $request->getQueryParam($this->longitudeParamName);
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
        
        return Locations::find()
                ->fromPoint($lat, $lng)
                ->limit(1)
                ->one();
    }
    
    /**
     * Find location by default coords
     * @return type
     */
    protected function resolveByDefaultCoords()
    {
        $latitude = (float) ini_get('date.default_latitude');
        $longitude = (float) ini_get('date.default_longitude');
        
        return $this->resolveByCoords($latitude, $longitude);
    }
    
    /**
     * Find location by remote address
     * @param type $address
     * @return type
     */
    protected function resolveByAddress($addr)
    {
        return Locations::find()
                ->fromBlock($addr)
                ->limit(1)
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
     * @return object Locations
     */
    public function getLocation()
    {
        if($this->_location == null) {
            if($this->getIsValidLocation($this->latitude, $this->longitude)) {
                $this->_location = $this->resolveByCoords($this->latitude, $this->longitude);
            } else {
                $this->_location = $this->resolveByAddress($this->address);
            }
            
            if($this->_location === null) {
                $this->_location = $this->resolveByDefaultCoords();
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
    
    public function getTimezone(array $timezones = [])
    {
        if($this->_timezone === null) {
            $location = $this->getLocation();
            if($location !== null) {
                $timezone = $location->getTimezone($timezones)->one();
                if($timezone !== null) {
                    $this->_timezone = $timezone->timezone;
                }
            }
        }
        return $this->_timezone;
    }
}