<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 08/10/16
 * Time: 14:43
 */

/**
 * Récupére et convertis des paramétres
 * @param string $filename
 * @return array|mixed
 */
function link_parameters($filename){
    $json_url = ABS_PATH."/private/parameters/{$filename}.json";
    if(!file_exists($json_url))
        return array();
    // Si le fichier n’existe pas on revois un tableau vide
    $json = file_get_contents($json_url);
    return json_decode($json, TRUE);
    // Sinon on retourne le tableau des paramètres
}

/**
 * Sauvegarde des données sous forme de JSON
 * @param string  $filename
 * @param array|object|int|string|bool $data
 * @return bool
 */
function save_parameters($filename,$data){
    $json_url = ABS_PATH."/private/parameters/{$filename}.json";
    return boolval(file_put_contents($json_url,json_encode($data)));
}