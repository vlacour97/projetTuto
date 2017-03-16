<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 11/03/17
 * Time: 15:26
 */

namespace controllers\admin;


use lib\Crypt;
use lib\PageTemplate;

class PageLogin extends PageTemplate{

    public function __construct(){
        if($this->_isConnected() && !$this->_isLoaded('ajax'))
            $this->_redirect('home','index',false,true);
    }

    public function include_content($view_path,$controller_object,$var){
        $controller_object->header();
        $controller_object->nav();
        include($view_path);
        $controller_object->footer();
    }

    private function login(){
        $_SESSION[self::connexion_tag] = Crypt::encrypt(1);
    }

    public function _index(){
        var_dump($this->_isConnected());
        $this->login();
        $this->var->ok = "ok";

    }

    public function _ajax(){
        echo 'AJAX';
    }

} 