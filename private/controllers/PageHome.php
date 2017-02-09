<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 25/01/17
 * Time: 14:12
 */

namespace controllers;


use lib\BDD;
use lib\File;
use lib\PageTemplate;

class PageHome extends PageTemplate{

    public function _index(){
        echo "ok whouu  ".$this->global->get->name;

        $this->var->val = 23;
        $this->var->filePath = File::get_img_path(File::BUSINESS_LOGO,'1');
    }

    public function _edit(){
        $this->var->message = 'Bienvenue sur ma page';
        $this->var->data = $this->maRequete(false);
    }

    public function _new(){
        $this->var->data = $this->maRequete();
        $partnerList = BDD::get_partner_list();
        $this->var->partnerList = $partnerList;

    }

    public function _test(){
        $this->var->testData = BDD::get_partner_list();
    }

    private function maRequete($bool = true){
        if($bool) return BDD::search_query('Web'); else return BDD::search_query();
    }

    protected function header(){
        echo "<style>.nav{  display: block; width: 100%; height: 2em; background: #FF0000; color: #FFFFFF;  }</style>";
    }
} 