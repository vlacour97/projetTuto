<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 19/03/17
 * Time: 20:48
 */

namespace controllers\admin;


use lib\BDD;
use lib\Crypt;
use lib\PageTemplate;

class PageGroup extends PageTemplate{

    function __construct(){
        $this->var->titlePage = "Gestion des groupes d'étudiants";
        parent::__construct();
    }

    function _index(){
        $this->var->tabGroup = BDD::get_group_list();
    }

    /**
     * AJAX Queries
     */

    public function _ajax_add_group(){
        echo json_encode(BDD::add_group($this->global->get->name));
    }

    public function _ajax_edit_group(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::edit_group($id,$this->global->get->name));
    }

    public function _ajax_remove_group(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::delete_group($id));
    }
} 