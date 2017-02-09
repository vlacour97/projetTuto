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
        $this->var->search_results = BDD::search_query();
    }
}