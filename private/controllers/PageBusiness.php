<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 25/01/17
 * Time: 22:19
 */

namespace controllers;


use lib\BDD;
use lib\File;
use lib\PageTemplate;

class PageBusiness extends PageTemplate{

    public function __construct(){
        parent::__construct();
        //if(!$this->_isLoaded('login'))
            //$this->_redirect('login');

    }

    public function _index(){
        $this->var->data = BDD::search_query();
    }

    public function _article(){
        if(is_null($this->global->get->item))
            $this->_redirect('index');
        $this->var->data = BDD::get_proposition_info($this->global->get->item);
        $this->var->business = BDD::get_business_info($this->var->data->ID_ent);
    }

    public function _business(){
        if(is_null($this->global->get->id))
            $this->_redirect('index');
        $this->var->business = BDD::get_business_info($this->global->get->id);
        $this->var->proposition = BDD::get_business_prop_list($this->global->get->id);
        $this->var->img_src = File::get_img_path(File::BUSINESS_LOGO,$this->global->get->id);
    }

} 