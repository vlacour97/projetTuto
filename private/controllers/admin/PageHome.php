<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 10/03/17
 * Time: 22:04
 */

namespace controllers\admin;


use lib\BDD;
use lib\Date;
use lib\PageTemplate;

class PageHome extends PageTemplate{

    public function __construct(){
        if(!$this->_isConnected())
            $this->_redirect('login','index',false,true);
        parent::__construct();
        $this->var->titlePage = "Tableau de bord";
    }

    function _index(){
        $this->var->tabEntreprises = BDD::get_business_ranking();
        $this->var->cardsData = BDD::get_general_info();
        $evolutionData = BDD::get_internship_evolution();
        $this->var->dataChart[-1] = 0;
        $evolIte = 0;
        for($i=0;$i<12;$i++){
            $current_month = (date('n')+$i)%12;
            $this->var->monthList[] = "'".Date::$cutMonth[(date('n')+$i)%12]."'";
            $this->var->dataChart[$i] = $this->var->dataChart[$i-1];
            if(intval($evolutionData->{$evolIte}->date) == $current_month+1){
                $this->var->dataChart[$i] += $evolutionData->{$evolIte}->nbStudent;
                $evolIte++;
            }
        }
        unset($this->var->dataChart[-1]);
        $this->var->tabStudents = BDD::get_student_list_without_internship();
    }

} 