<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 19/03/17
 * Time: 22:16
 */

namespace controllers\admin;


use lib\BDD;
use lib\Crypt;
use lib\PageTemplate;

class PageContinuities extends PageTemplate{

    function __construct(){
        if(!$this->_isConnected())
            $this->_redirect('login','index',false,true);
        $this->var->titlePage = "Gestion des poursuites possibles en entreprise";
        parent::__construct();
    }

    function _index(){
        $this->var->tabContinuities = BDD::get_continuities_list();
    }

    /**
     * AJAX Queries
     */

    public function _ajax_add_continuities(){
        echo json_encode(BDD::add_continuity($this->global->get->name));
    }

    public function _ajax_edit_continuities(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::edit_continuities($id,$this->global->get->name));
    }

    public function _ajax_remove_continuities(){
        $id = Crypt::decrypt($this->global->get->id);
        echo json_encode(BDD::delete_continuity($id));
    }

} 