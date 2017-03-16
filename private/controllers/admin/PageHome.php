<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 10/03/17
 * Time: 22:04
 */

namespace controllers\admin;


use lib\BDD;
use lib\PageTemplate;

class PageHome extends PageTemplate{

    public function __construct(){
        if(!$this->_isConnected())
            $this->_redirect('login','index',false,true);
        parent::__construct();
    }

    function _index(){
        $this->var->home = "Coucou";
        $this->var->name = "John Doe";
        $this->var->titlePage = "Tableau de bord";
        var_dump($this->_isConnected());
    }

    function _remi(){
        $this->var->test = BDD::get_student_list();
    }

    function _create_user(){
        $name = $this->global->get->name;
        $fname = $this->global->get->fname;
        if(!is_null($name,$fname)) {
            try{
                if(BDD::add_student(13,1,$name,$fname,'','','','','','','','','0000-00-00'))
                    echo json_encode(['success' => 'TRUE']);
                else
                    echo json_encode(['success' => 'FALSE']);
            }catch(\Exception $e){
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }

} 