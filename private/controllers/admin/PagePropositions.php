<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 18/03/17
 * Time: 16:40
 */

namespace controllers\admin;


use lib\BDD;
use lib\Crypt;
use lib\Map;
use lib\PageTemplate;

class PagePropositions extends PageTemplate{

    function __construct(){
        if(!$this->_isConnected())
            $this->_redirect('login','index',false,true);
        $this->var->titlePage = "Gestion des propositions de stage";
        parent::__construct();
    }

    function _index(){
        $this->var->tabPropositions = BDD::get_propositions_list();
    }

    function _view(){
        if(!isset($this->global->get->id)) $this->_redirect(null,'index',false,true);
        $id = Crypt::decrypt($this->global->get->id);
        $this->var->data = BDD::get_proposition_info($id);
        $this->var->business = BDD::get_business_info($this->var->data->ID_ent);
        $this->var->titlePage .= ' - '.$this->var->data->label;
        $this->var->tabStudents = BDD::get_proposition_student_list($id);
        $arrayFields = $arrayContinuities =['N/C'];
        foreach(BDD::get_prop_fields($id) as $key=>$content){
            $arrayFields[$key] = $content->label;
        }
        $this->var->fields = implode(',',$arrayFields);
        foreach(BDD::get_prop_continuities($id) as $key=>$content){
            $arrayContinuities[$key] = $content->label;
        }
        $this->var->continuities = implode(",",$arrayContinuities);
    }

    function _edit(){
        if(!isset($this->global->get->id)) $this->_redirect(null,'index',false,true);
        $id = Crypt::decrypt($this->global->get->id);
        $this->var->data = BDD::get_proposition_info($id);
        $this->var->businessList = BDD::get_business_list();
        $this->var->fieldList = BDD::get_fields_list();
        $this->var->continuitiesList = BDD::get_continuities_list();
        foreach(BDD::get_prop_fields($id) as $content){
            $this->var->prop_fields[] = $content->ID;
        }
        foreach(BDD::get_prop_continuities($id) as $content){
            $this->var->prop_continuities[] = $content->id;
        }
        $this->var->titlePage .= ' - '.$this->var->data->label;

        //Vérification de la récupération des données
        if(!empty(get_object_vars($this->global->post))){
            if($this->edit_proposition($id,$this->global->post))
                $this->_redirect(null,'index',false,true);
            else
                $this->var->updateError = "Erreur lors de la modification de la proposition";
        }
    }

    function _add(){
        $this->var->businessList = BDD::get_business_list();
        $this->var->fieldList = BDD::get_fields_list();
        $this->var->continuitiesList = BDD::get_continuities_list();
        //Vérification de la récupération des données
        if(!empty(get_object_vars($this->global->post))){
            if($this->add_proposition($this->global->post))
                $this->_redirect(null,'index',false,true);
            else
                $this->var->updateError = "Erreur lors de l'ajout de la proposition";
        }
    }

    /**
     * Private tasks
     */

    private function linkFields($id,$fields){
        $response = true;
        !BDD::unlink_proposition_to_field($id) && $response = false;
        foreach($fields as $content) !BDD::link_proposition_to_field($id,$content) &&  $response = false;
        return $response;
    }

    private function linkContinuities($id,$continuities){
        $response = true;
        !BDD::unlink_proposition_to_continuity($id) && $response = false;
        foreach($continuities as $content) !BDD::link_proposition_to_continuity($id,$content) &&  $response = false;
        return $response;
    }

    private function edit_proposition($id,$post){
        $ID_ent = Crypt::decrypt($post->ID_ent);
        return BDD::edit_proposition($id,$ID_ent,$post->label,$post->address,$post->zip_code,$post->city,$post->country,$post->description,$post->duration,$post->skills,$post->remuneration) && $this->linkFields($id,$post->fields) && $this->linkContinuities($id,$post->continuities);
    }

    private function add_proposition($post){
        $ID_ent = Crypt::decrypt($post->ID_ent);
        $addResponse = BDD::add_proposition($ID_ent,$post->label,$post->address,$post->zip_code,$post->city,$post->country,$post->description,$post->duration,$post->skills,$post->remuneration);
        $id = BDD::get_max_proposition_id();
        return $addResponse && $this->linkFields($id,$post->fields) && $this->linkContinuities($id,$post->continuities);
    }

    /**
     * AJAX QUERIES
     */

    function _ajax_remove_propositions(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::delete_proposition($id));
    }

    function _ajax_get_address(){
        $id = Crypt::decrypt($this->global->get->id);
        $data = BDD::get_business_info($id);
        $values = ['address','zip_code','city','country'];
        $response = [];
        foreach($values as $content) $response[$content] = $data->$content;
        echo json_encode($response);
    }
} 