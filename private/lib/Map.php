<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 10/03/17
 * Time: 20:31
 */

namespace lib;

/**
 * Class GPSCoordinate
 * @package lib
 * @author Valentin Lacour
 */
class GPSCoordinate{

    /**
     * @var float Latitude
     */
    public $latitude;
    /**
     * @var float Longitude
     */
    public $longitude;

    function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

}

/**
 * Class Map
 * @package lib
 * @author Valentin Lacour
 */
class Map {

    /**
     * @param string $address Adresse
     * @param string $city Ville
     * @param string $zip_code Code Postal
     * @param string $country_code Code ISO Pays
     * @return \StdClass
     * @throws \Exception
     */
    static function get_info_from_address($address = '',$city = '', $zip_code = '', $country_code = ''){
        if($address == '' && $city == '' && $zip_code == '' && $country_code == '') throw new \Exception('Veuillez rentrer un minimum d\' informations',1);
        $config = new Config();
        $link = 'https://maps.googleapis.com/maps/api/geocode/json?address=:address&key=:APIKey';
        $address_url = urlencode($address.','.$city.' '.$zip_code.' '.$country_code);
        $link = str_replace([':address',':APIKey'],[$address_url,$config->api_key_gmaps],$link);
        $json_data = file_get_contents($link);
        $data = json_decode($json_data);
        if($data->status != 'OK') throw new \Exception('Erreur lors de la recupération des données, essayez avec d\'autres coordonnées',2);
        return $data;
    }

    /**
     * @param string $address Adresse
     * @param string $city Ville
     * @param string $zip_code Code Postal
     * @param string $country_code Code ISO Pays
     * @return GPSCoordinate
     * @throws \Exception
     */
    static function get_latitude_longitude($address = '',$city = '', $zip_code = '', $country_code = ''){
        try{
            $data = self::get_info_from_address($address,$city,$zip_code,$country_code);
        }catch (\Exception $e)
        {
            throw $e;
        }
        return new GPSCoordinate($data->results[0]->geometry->location->lat,$data->results[0]->geometry->location->lng);
    }

} 