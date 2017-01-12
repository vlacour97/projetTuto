<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 05/01/17
 * Time: 16:46
 */

namespace lib;


class Crypt {

    static private $init = false;
    static private $key;

    private static function _init(){
        $config = new Config();
        self::$key = $config->getCryptKey();
        self::$init = true;
    }

    /**
     * Permet de crypter des données
     * @param mixed $data
     * @return string
     */
    static function encrypt($data) {
        if(!self::$init) self::_init();
        $data = serialize($data);
        $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td,self::$key,$iv);
        $data = base64_encode(mcrypt_generic($td, '!'.$data));
        mcrypt_generic_deinit($td);
        return $data;
    }

    /**
     * Permet de décrypter des données
     * @param string $data
     * @return bool|mixed
     */
    static function decrypt($data) {
        if(!self::$init) self::_init();
        $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td,self::$key,$iv);
        $data = mdecrypt_generic($td, base64_decode($data));
        mcrypt_generic_deinit($td);

        if (substr($data,0,1) != '!')
            return false;

        $data = substr($data,1,strlen($data)-1);
        return unserialize($data);
    }

} 