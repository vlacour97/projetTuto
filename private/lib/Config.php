<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 06/01/17
 * Time: 09:52
 */

namespace lib;


class Config extends DataFormatter{

    public $name;
    public $description;
    public $keywords;
    public $author;
    public $copyright;
    public $version;
    public $last_update;
    public $admin_mail;
    public $crypt_key;
    public $debug_mod;
    public $api_key_gmaps;

    public $dates = ['last_update'];
    public $bool = ['debug_mod'];

    public function __construct(){
        DataFormatter::format_data(link_parameters(CONFIG_FILE_NAME),$this);
    }

    /**
     * @return mixed
     */
    public function getAdminMail()
    {
        return $this->admin_mail;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->last_update;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getCryptKey()
    {
        return $this->crypt_key;
    }

    /**
     * @return mixed
     */
    public function getDebugMod()
    {
        return $this->debug_mod;
    }

    /**
     * @return mixed
     */
    public function getApiKeyGmaps()
    {
        return $this->api_key_gmaps;
    }
} 