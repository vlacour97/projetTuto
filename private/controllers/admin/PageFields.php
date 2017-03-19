<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 19/03/17
 * Time: 22:09
 */

namespace controllers\admin;


use lib\BDD;
use lib\Crypt;
use lib\PageTemplate;

class PageFields extends PageTemplate{

    function __construct(){
        $this->var->titlePage = "Gestion des domaines d'activités";
        parent::__construct();
    }

    function _index(){
        $this->var->tabFields = BDD::get_fields_list();
    }

    /**
     * AJAX Queries
     */

    public function _ajax_add_fields(){
        echo json_encode(BDD::add_field($this->global->get->name));
    }

    public function _ajax_edit_fields(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::edit_field($id,$this->global->get->name));
    }

    public function _ajax_remove_fields(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::delete_field($id));
    }

} 