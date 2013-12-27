<?php

namespace yii\platform\helpers;

use yii\platform\P;

class GeoHelper
{
    /**
     * Radius of earth, in km
     */
    const EARTH_RADIUS_KM = 6371;
    
    /**
     * The distance between two points, given longitude and latitude coordinates
     * @param float $lat1 latitude first point
     * @param float $lon1 longitude first point
     * @param float $lat2 latitude second point
     * @param float $lon2 longitude second point
     * @return float distance between two points
     */
    public static function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        return acos(sin(deg2rad($lat1)) * SIN(deg2rad($lat2)) + COS(deg2rad($lat1)) * COS(deg2rad($lat2)) * COS(deg2rad($lon2 - $lon1))) * self::EARTH_RADIUS_KM;
    }
    
    /**
     * The calculation center of the polygon in this given point (latitude, longitude)
     * and distance of side length
     * @param float $lat latitude
     * @param float $lng longitude
     * @param float $dist distance
     */
    public static function createPoligonArea($lat, $lng, $dist)
    {
        // degrees in one kilometer
        $degInKm = 1 / self::getDistance($lat, $lng, $lat, $lng + 1);
        
        $p = [$lat, $lng];
        $sq = [];
        $rdeg = $dist * $degInKm;
        $sq[0] = [$p[0] - $rdeg, $p[1] + $rdeg]; // top left
        $sq[1] = [$p[0] + $rdeg, $p[1] + $rdeg]; // top right
        $sq[2] = [$p[0] + $rdeg, $p[1] - $rdeg]; // bottom right
        $sq[3] = [$p[0] - $rdeg, $p[1] - $rdeg]; // bottom left
        $sq[4] = $sq[0]; // close poligon
        
        return $sq;
    }
    
    /**
     * Create a fragment of SQL query to calculate the distance in this given point (latitude, longitude) and distance of side length
     * @param type $lat
     * @param type $lng
     * @param type $dist
     * @return type
     */
    public static function createPoligonCriteria($lat, $lng, $dist, $pointColumn = 'point')
    {
        $db = P::$app->getDb();
        $pointColumn = $db->quoteColumnName($pointColumn);
        
        $sq = self::createPoligonArea($lat, $lng, $dist);
        $poligon = [
            $sq[0][0] . ' ' . $sq[0][1],
            $sq[1][0] . ' ' . $sq[1][1],
            $sq[2][0] . ' ' . $sq[2][1],
            $sq[3][0] . ' ' . $sq[3][1],
            $sq[4][0] . ' ' . $sq[4][1]
        ];
        
        return 'MBRWithin(
                    ' . $pointColumn . ',
                    GeomFromText(
                        "Polygon((' . implode(',', $poligon) . '))"
                    )
                )';
    }
    
    /**
     * Create a fragment of SQL query to calculate the distance between points
     * @param type $lat
     * @param type $lng
     * @param type $latColumn
     * @param type $lngAttr
     * @return string
     */
    public static function createDistanceCondition($lat, $lng, $latColumn = 'latitude', $lngColumn = 'longitude')
    {
        $db = P::$app->getDb();
        $latColumn = $db->quoteColumnName($latColumn);
        $lngColumn = $db->quoteColumnName($lngColumn);
        
        $condition = 'ACOS(SIN(RADIANS(' . $lat . '))
            * SIN(RADIANS(' . $latColumn . '))
            + COS(RADIANS(' . $lat . '))
            * COS(RADIANS(' . $latColumn . '))
            * COS(RADIANS(' . $lngColumn . ' - ' . $lng . ')))
            * ' . self::EARTH_RADIUS_KM;
        
        return $condition;
    }
}