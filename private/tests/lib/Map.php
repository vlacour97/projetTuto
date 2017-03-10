<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 10/03/17
 * Time: 20:36
 */

include '../../init.php';


try{
    var_dump(\lib\Map::get_latitude_longitude('','Amiens','',''));
}catch (Exception $e){
    echo $e->getMessage();
}