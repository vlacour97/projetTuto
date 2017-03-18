<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 10/01/17
 * Time: 18:27
 */

namespace lib;


class Text {
    /**
     * Permet de réduire une chaine de caractéres
     * @param string $string
     * @param int $start
     * @param int $length
     * @param string $endStr
     * @return string
     */
    static function cutString($string, $start, $length, $endStr = '&hellip;'){
        // si la taille de la chaine est inférieure ou égale à celle attendue on la retourne telle qu'elle
        if( strlen( $string ) <= $length ) return $string;

        // permet de couper la phrase aux caractères définis tout en prenant en compte la taille de votre $endStr et en re-précisant l'encodage du contenu récupéré
        $str = mb_substr( $string, $start, $length - strlen( $endStr ) + 1, 'UTF-8');
        // retourne la chaîne coupée avant la dernière espace rencontrée à laquelle s'ajoute notre $endStr
        return substr( $str, 0, strrpos( $str,' ') ).$endStr;
    }

    /**
     * Génére automatique des émoticones
     * @param string $string
     * @return mixed
     */
    static function automatic_emo($string){
        $replace = array(' :)',' ;)',' :(',' :/',' :$',' :p',' ;p',' :P',' ;P',' :D',' <3',' :\')',' :*',' B)',' :o','(caca)',' (y)');
        $by = array(' &#128522;',' &#128521;',' &#128532;',' &#128533;',' &#128563;',' &#128539;',' &#128540;',' &#128539;',' &#128540;',' &#128513;',' &#10084;',' &#128514;',' &#128514;',' &#128526;',' &#128558;','&#128169;',' &#128077;');
        return str_replace($replace,$by,$string);
    }

    /**
     * Récupére les initials d'une chaine
     * @param string $string
     * @return string
     */
    static function getInitials($string){
        $flag = true;
        $response = "";
        for($i=0;$i<strlen($string);$i++){
            if($string[$i-1] == " " && $string[$i] != " ")
                $flag = true;
            if($flag)
            {
                $response .= $string[$i];
                $flag = false;
            }
        }
        return $response;
    }

    static function format_address($address,$zip_code,$city,$country){
        return $address.', '.$zip_code.'<br>'.$city.', '.$country;
    }
} 