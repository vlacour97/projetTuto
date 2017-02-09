<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 10/01/17
 * Time: 17:32
 */

namespace lib;

/**
 * Class DataFormatter
 * @package lib
 * @author Valentin Lacour
 */
Class DataFormatter{
    /**
     * @var array
     */
    protected $int = [];
    /**
     * @var array
     */
    protected $dates = [];
    /**
     * @var array
     */
    protected $bool = [];
    /**
     * @var array
     */
    protected $useless_attr = [];
    /**
     * @var array
     */
    protected  $useless_add_attr = [];

    /**
     * Formattage de données
     * @param array $datas
     * @param DataFormatter $object
     */
    static public function format_data($datas,&$object){
        foreach ($datas as $key=>$content) {
            if(is_string($key) && !in_array($key,$object->useless_attr)){
                if(in_array($key,$object->int))
                    $object->$key=intval($content);
                //elseif(in_array($key,$object->dates) && $content != '0000-00-00 00:00:00' && $content != '0000-00-00' && $content != null)
                    //$object->$key=new \lib\Date($content); TODO Integrer date
                elseif(in_array($key,$object->bool))
                    $object->$key=boolval($content);
                else
                    $object->$key=$content;
            }
        }
    }

    static public function convert_array_to_object($array){
        $return = new \stdClass();
        foreach ($array as $key=>$value)
            if(is_array($value))
                $return->$key = DataFormatter::convert_array_to_object($value);
            else
                $return->$key = $value;
        return $return;
    }
}