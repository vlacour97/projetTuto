<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 18/03/17
 * Time: 23:17
 */

namespace controllers\admin;


use lib\BDD;
use lib\Crypt;
use lib\Date;
use lib\PageTemplate;

class PageStudents extends PageTemplate{

    function __construct(){
        if(!$this->_isConnected())
            $this->_redirect('login','index',false,true);
        $this->var->titlePage = "Gestion des étudiants";
        parent::__construct();
    }

    function _index(){
        $this->var->tabStudents = BDD::get_student_list();
    }

    function _view(){
        if(!isset($this->global->get->id)) $this->_redirect(null,'index',false,true);
        $id= Crypt::decrypt($this->global->get->id);
        $this->var->data = BDD::get_student_info($id);
        $this->var->data->birth_date = new Date($this->var->data->birth_date);
        $this->var->titlePage .= ' - '.$this->var->data->fname.' '.$this->var->data->name;
    }

    function _edit(){
        if(!isset($this->global->get->id)) $this->_redirect(null,'index',false,true);
        $id = Crypt::decrypt($this->global->get->id);
        $this->var->data = BDD::get_student_info($id);
        $this->var->tabGroup = BDD::get_group_list();
        $this->var->tabBusiness = BDD::get_business_list();
        if($this->var->data->ID_ent == "") $this->var->tabPropositions=BDD::get_propositions_list(); else $this->var->tabPropositions = BDD::get_business_prop_list($this->var->data->ID_ent);
        $this->var->titlePage .= ' - '.$this->var->data->fname.' '.$this->var->data->name;
        //Vérification de la récupération des données
        if(!empty(get_object_vars($this->global->post))){
            try{
                if($this->edit_student($id,$this->global->post))
                    $this->_redirect(null,'index',false,true);
                else
                    $this->var->updateError = "Erreur lors de la modification des informations de l'étudiant";
            }catch (\Exception $e){
                $this->var->updateError = $e->getMessage();
            }
        }
    }

    function _add(){
        if(isset($this->global->get->ID_prop)) $this->var->ID_ent = BDD::get_proposition_info(Crypt::decrypt($this->global->get->ID_prop))->ID_ent;
        $this->var->tabGroup = BDD::get_group_list();
        $this->var->tabBusiness = BDD::get_business_list();
        $this->var->tabPropositions=BDD::get_propositions_list();
        //Vérification de la récupération des données
        if(!empty(get_object_vars($this->global->post))){
            try{
                if($this->add_student($this->global->post))
                    $this->_redirect(null,'index',false,true);
                else
                    $this->var->updateError = "Erreur lors de la modification des informations de l'étudiant";
            }catch (\Exception $e){
                $this->var->updateError = $e->getMessage();
            }
        }
    }

    /**
     * Private Tasks
     */

    private function link_student($ID_student,$ID_prop){
        return BDD::unlink_student_to_internship($ID_student) && BDD::link_student_to_proposition($ID_student,$ID_prop);
    }

    private function edit_student($id,$post){
        if(!is_int(intval($post->ID))) throw new \Exception('Votre identifiant doit être un entier');
        if($post->ID != $id && isset(BDD::get_student_info($post->ID)->ID)) throw new \Exception('Cet identifiant est déjà utilisé :/');
        try{
            if(!BDD::edit_student($id,$post->ID,$post->ID_group,$post->name,$post->fname,$post->email,$post->phone,$post->INE,$post->address,$post->zip_code,$post->city,$post->country,$post->informations,$post->birth_date)) return false;
            if($post->ID_prop != "false" && !$this->link_student($post->ID,Crypt::decrypt($post->ID_prop))) return false;
        }catch(\Exception $e){
            throw $e;
        }
        return true;
    }

    private function add_student($post){
        if(!is_int(intval($post->ID))) throw new \Exception('Votre identifiant doit être un entier');
        if(isset(BDD::get_student_info($post->ID)->ID)) throw new \Exception('Cet identifiant est déjà utilisé :/');
        try{
            if(!BDD::add_student($post->ID,$post->ID_group,$post->name,$post->fname,$post->email,$post->phone,$post->INE,$post->address,$post->zip_code,$post->city,$post->country,$post->informations,$post->birth_date)) return false;
            if($post->ID_prop != "false" && !$this->link_student($post->ID,Crypt::decrypt($post->ID_prop))) return false;
        }catch(\Exception $e){
            throw $e;
        }
        return true;
    }


    /**
     * AJAX QUERIES
     */

    function _ajax_get_proposition_list(){
        $id = Crypt::decrypt($this->global->get->id);
        $response = BDD::get_business_prop_list($id);
        foreach($response as $key=>$content){
            $response->{$key}->ID = Crypt::encrypt($content->ID);
            $response->{$key}->ID_ent = Crypt::encrypt($content->ID_ent);
        }
        echo json_encode($response);
    }

    function _ajax_get_business_ID(){
        $id = Crypt::decrypt($this->global->get->id);
        echo Crypt::encrypt(BDD::get_proposition_info($id)->ID_ent);
    }

    function _ajax_remove_student(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::delete_student($id));
    }

    function _ajax_get_out_student(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::unlink_student_to_internship($id));
    }

} 