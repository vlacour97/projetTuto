<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 08/10/16
 * Time: 14:43
 */

//Fonction de récupération et de conversion des paramètres
function link_parameters($filename){
    $json_url = ABS_PATH."/private/parameters/{$filename}.json";
    if(!file_exists($json_url))
        return array();
    // Si le fichier n’existe pas on revois un tableau vide
    $json = file_get_contents($json_url);
    return json_decode($json, TRUE);
    // Sinon on retourne le tableau des paramètres
}