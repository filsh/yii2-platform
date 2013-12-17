<?php

namespace yii\platform\geo;

use yii\platform\Platform;
use yii\base\Component;

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
    protected $remoteLatitude;
    
    /**
     * User remote longitude
     * @var float
     */
    protected $remoteLongitude;
    
    /**
     * User remote address
     * @var string
     */
    protected $remoteAddress;
    
    public function init()
    {
        parent::init();
        
        $this->setRemoteAddress();
        $this->setRemoteCoords();
    }
    
    /**
     * Resolve location data
     * 
     * @return object GeoLocations
     */
    protected function resolve()
    {
        if($this->_mGeoLocations == null) {
            if($this->getIsValidLocation($this->remoteLatitude, $this->remoteLongitude)) {
                $mGeoLocations = $this->findByCoordsLocation($this->remoteLatitude, $this->remoteLongitude);
                $this->log(null, $this->remoteLatitude, $this->remoteLongitude);
            } else {
                $mGeoLocations = $this->findByRemoteAddress($this->remoteAddress);
                $this->log($this->remoteAddress);
            }
            
            $this->_mGeoLocations = $mGeoLocations;
        }
        
        return $this->_mGeoLocations;
    }
    
    /**
     * Set user remote address
     * @return void
     */
    protected function setRemoteAddress()
    {
        $request = Platform::$app->getRequest();
        $this->remoteAddress = $request->getUserIP();
    }
    
    /**
     * Set user coords info
     * @return void
     */
    protected function setRemoteCoords()
    {
        $request = Platform::$app->getRequest();
        $this->remoteLatitude = (float) $request->get($this->latitudeParamName);
        $this->remoteLongitude = (float) $request->get($this->longitudeParamName);
    }
    
    /**
     * Return if the location coordinates is valid
     * 
     * @return boolean
     */
    public function getIsValidLocation($latitude, $longitude)
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
     * Return country code in format iso2
     * 
     * @return type
     */
    public function getCountry()
    {
        return $this->getSourceData()->geo_locations_country;
    }
    
    /**
     * Return city name
     * 
     * @return type
     */
    public function getCity()
    {
        return $this->getSourceData()->geo_locations_city;
    }
    
    /**
     * Return geografic latitude
     * 
     * @return type
     */
    public function getLatitude()
    {
        return $this->remoteLatitude
                ? $this->remoteLatitude
                : $this->getSourceData()->geo_locations_latitude;
    }
    
    /**
     * Return geografic longitude
     * 
     * @return type
     */
    public function getLongitude()
    {
        return $this->remoteLongitude
                ? $this->remoteLongitude
                : $this->getSourceData()->geo_locations_longitude;
    }
}