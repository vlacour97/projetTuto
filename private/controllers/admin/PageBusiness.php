<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 13/03/17
 * Time: 19:28
 */

namespace controllers\admin;


use lib\BDD;
use lib\Crypt;
use lib\File;
use lib\PageTemplate;

class PageBusiness extends PageTemplate{

    function __construct(){
        $this->var->titlePage = "Gestion des entreprises";
        parent::__construct();
    }

    function _index(){
        $this->var->tabEntreprises = BDD::get_business_list();
    }

    function _view(){
        if(!isset($this->global->get->id)) $this->_redirect(null,'index',false,true);
        $id= Crypt::decrypt($this->global->get->id);
        $this->var->data = BDD::get_business_info($id);
        $this->var->propositions = BDD::get_business_prop_list($id);
        $this->var->titlePage .= ' - '.$this->var->data->name;
    }

    function _edit(){
        if(!isset($this->global->get->id)) $this->_redirect(null,'index',false,true);
        $id= Crypt::decrypt($this->global->get->id);
        $this->var->data = BDD::get_business_info($id);
        $this->var->titlePage .= ' - '.$this->var->data->name;

        //Vérification de la récupération des données
        if(!empty(get_object_vars($this->global->post))){
            if($this->edit_businnes($id,$this->global->post)){
                if(!empty(get_object_vars($this->global->files))){
                    try{
                        File::upload('.'.File::BUSINESS_LOGO,get_object_vars($this->global->files->logo),null,$id.'.jpg',true);
                        clearstatcache();
                        $this->_redirect(null,'index',false,true);
                    }catch (\Exception $e){
                        $this->var->updateError = $e->getMessage();
                    }
                }
                $this->_redirect(null,'index',false,true);
            }
            else
                $this->var->updateError = "Erreur lors de la modification de l'entreprise";
        }

    }

    function _add(){
        //Vérification de la récupération des données
        if(!empty(get_object_vars($this->global->post))){
            if($this->add_business($this->global->post)){
                if(!empty(get_object_vars($this->global->files))){
                    try{
                        $id = BDD::get_max_business_id();
                        File::upload('.'.File::BUSINESS_LOGO,get_object_vars($this->global->files->logo),null,$id.'.jpg',true);
                        clearstatcache();
                        $this->_redirect(null,'index',false,true);
                    }catch (\Exception $e){
                        $this->var->updateError = $e->getMessage();
                    }
                }
                $this->_redirect(null,'index',false,true);
            }
            else
                $this->var->updateError = "Erreur lors de l'ajout de l'entreprise";
        }
    }


    /**
     * Private tasks
     */

    private function edit_businnes($id,$post){
        if(!isset($post->partner)) $post->partner = false;
        return BDD::edit_business($id,$post->name,$post->address,$post->zip_code,$post->city,$post->country,$post->description,$post->director_name,$post->phone,$post->mail,$post->siren,$post->partner);
    }

    private function add_business($post){
        if(!isset($post->partner)) $post->partner = false;
        return BDD::add_business($post->name,$post->address,$post->zip_code,$post->city,$post->country,$post->description,$post->director_name,$post->phone,$post->mail,$post->siren,$post->partner);
    }


    /**
     * AJAX QUERIES
     */


    function _ajax_add_partner(){
        $id = Crypt::decrypt($this->global->get->id);
        $data = BDD::get_business_info($id);
        echo json_encode(BDD::edit_business($data->ID,$data->name,$data->address,$data->zip_code,$data->city,$data->country,$data->description,$data->director_name,$data->phone,$data->mail,$data->siren,true));
    }

    function _ajax_remove_partner(){
        $id = Crypt::decrypt($this->global->get->id);
        $data = BDD::get_business_info($id);
        echo json_encode(BDD::edit_business($data->ID,$data->name,$data->address,$data->zip_code,$data->city,$data->country,$data->description,$data->director_name,$data->phone,$data->mail,$data->siren,false));
    }

    function _ajax_remove_business(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::delete_business($id));
    }
} 