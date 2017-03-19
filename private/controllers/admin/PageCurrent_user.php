<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 19/03/17
 * Time: 18:21
 */

namespace controllers\admin;


use lib\BDD;
use lib\Crypt;
use lib\File;
use lib\PageTemplate;

class PageCurrent_user extends PageTemplate{

    function __construct(){
        $this->var->titlePage = "Mon Compte";
        parent::__construct();
    }

    function _index(){
        $id = Crypt::decrypt($this->global->session->{parent::connexion_tag});
        $this->var->data = BDD::get_user_info($id);
        $this->var->avatarPath = File::get_img_path(File::USER_AVATAR,$id);
        //Vérification de la récupération des données
        if(!empty(get_object_vars($this->global->post))){
            try{
                if($this->edit_user($id,$this->global->post)){
                    if(!empty(get_object_vars($this->global->files))){
                        try{
                            File::upload('.'.File::USER_AVATAR,get_object_vars($this->global->files->avatar),null,$id.'.jpg',true);
                            clearstatcache();
                            $this->_redirect(null,'index',false,true);
                        }catch (\Exception $e){
                            $this->var->updateError = $e->getMessage();
                        }
                    }
                    $this->_redirect(null,'index',false,true);
                }
                else
                    $this->var->updateError = "Erreur lors de la modification de vos informations";
            }catch (\Exception $e){
                $this->var->updateError = $e->getMessage();
            }
        }
    }

    /**
     * Private Tasks
     */

    private function edit_user($id,$post){
        if(!isset($post->email) || $post->email == "") throw new \Exception('Veuillez indiquer une adresse mail');
        if($post->password1 != "" && $post->password2 != ""){
            if($post->password1 != $post->password2) throw new \Exception('La confirmation de votre mot de passe est différente de votre mot de passe');
            if(!BDD::change_user_password($id,$post->password1)) throw new \Exception('Erreur lors du changement du mot de passe');
        }
        return BDD::edit_user($id,$post->fname,$post->name,$post->email,$post->phone);
    }

} 