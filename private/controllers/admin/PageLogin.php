<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 11/03/17
 * Time: 15:26
 */

namespace controllers\admin;


use lib\BDD;
use lib\Crypt;
use lib\File;
use lib\PageTemplate;

class PageLogin extends PageTemplate{

    public function __construct(){
        if($this->_isConnected() && !$this->_isLoaded('ajax'))
            $this->_redirect('home','index',false,true);
        parent::__construct();
    }

    public function include_content($view_path,$controller_object,$var){
        $controller_object->header();
        include($view_path);
        $controller_object->footer();
    }

    public function _index(){
        $this->var->hasCookies = false;
        if(!is_null($this->global->cookie->{self::connexion_tag})){
            $this->var->hasCookies = true;
            $this->var->userData = BDD::get_user_info(Crypt::decrypt($this->global->cookie->{self::connexion_tag}));
            if(is_null($this->var->userData->ID)) $this->_logout() && $this->_redirect(null,'index',false,true);
            $this->var->avatarPath = File::get_img_path(File::USER_AVATAR,$this->var->userData->ID);
        }
        //Vérification de la récupération des données
        if(!empty(get_object_vars($this->global->post))){
            $this->var->hasCookies && $this->global->post->email = $this->var->userData->email;
            try{
                if($this->login($this->global->post,$this->var->hasCookies))
                    $this->_redirect('home','index',false,true);
                else
                    $this->var->updateError = "Erreur lors de la connexion";
            }catch (\Exception $e){
                $this->var->updateError = $e->getMessage();
            }
        }
    }

    public function _logout(){
        unset($_SESSION[self::connexion_tag]);
        unset($_COOKIE[self::connexion_tag]);
        setcookie(self::connexion_tag,null,time());
    }

    private function login($post){
        $data = BDD::get_user_info_by_email($post->email);
        if(!isset($data->ID)) throw new \Exception('L\'adresse email que vous avez rentré est incorrect !');
        if($data->password != md5($post->password)) throw new \Exception('Votre mot de passe est incorrect !');
        $_SESSION[self::connexion_tag] = Crypt::encrypt($data->ID);
        setcookie(self::connexion_tag,Crypt::encrypt($data->ID),time() + 3600*24*365);
        return true;
    }

} 