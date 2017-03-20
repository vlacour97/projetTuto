<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 20/03/17
 * Time: 11:01
 */

namespace controllers\admin;


use lib\BDD;
use lib\Crypt;
use lib\PageTemplate;
use lib\Text;

class PagePDF extends PageTemplate{

    public function __construct(){
        if(!$this->_isConnected())
            $this->_redirect('login','index',false,true);
        parent::__construct();
        $this->var->titlePage = $this->global->part_name;
    }

    public function include_content($view_path,$controller_object,$var){
        include ABS_PATH.'/private/lib/html2pdf.php';
        $html2pdf = new \HTML2PDF('P', 'A4', 'fr');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        ob_start();
        include($view_path);
        $content = ob_get_clean();
        $html2pdf->writeHTML($content);
        $datas = $html2pdf->Output($var->titlePage.'.pdf');
    }

    public function _student_without_internship(){
        $this->var->titlePage = "Liste des étudiants sans stage";
        $this->var->tabStudents = BDD::get_student_list_without_internship();
    }

    public function _student_list(){
        $this->var->titlePage = "Liste des étudiants";
        $this->var->tabStudents = BDD::get_student_list();
    }

    public function _business_list(){
        $this->var->titlePage = "Liste des entreprises";
        $this->var->tabBusiness = BDD::get_business_list();
    }

    public function _business_prop_list(){
        if(!isset($this->global->get->id)) $this->_redirect('home','index',false,true);
        $id = Crypt::decrypt($this->global->get->id);
        $business_data = BDD::get_business_info($id);
        if($business_data->ID == 0) $this->_redirect('home','index',false,true);
        $this->var->tabPropositions = BDD::get_business_prop_list($id);
        $this->var->titlePage = "Liste des propositions pour l'entreprise ".$business_data->name;
    }

    public function _propositions_list(){
        $this->var->titlePage = "Liste des propositions";
        $this->var->tabPropositions = BDD::get_propositions_list();
    }



} 