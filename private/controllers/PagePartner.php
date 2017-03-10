<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 26/01/17
 * Time: 15:40
 */

namespace controllers;


use lib\BDD;
use lib\PageTemplate;

class PagePartner extends PageTemplate{

    public function _index(){
        $this->var->partnerList = BDD::get_partner_list();
    }

    public function _item(){
        $this->var->business = BDD::get_business_info($this->global->get->id);
    }

} 