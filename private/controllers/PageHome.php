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
use lib\Text;

class PageHome extends PageTemplate{

    public function _index(){
        $this->var->search_results_partner = BDD::search_query(
            true,
            $this->global->get->q_business_name,
            $this->global->get->q_country,
            $this->global->get->q_fields,
            $this->global->get->q_continuities,
            $this->global->get->q_remuneration);
        $this->var->search_results_other = BDD::search_query(
            false,
            $this->global->get->q_business_name,
            $this->global->get->q_country,
            $this->global->get->q_fields,
            $this->global->get->q_continuities,
            $this->global->get->q_remuneration);
        $this->var->img = File::get_img_path(File::BUSINESS_LOGO,2);
        $this->var->continuities = BDD::get_continuities_list();
        $this->var->fields = BDD::get_fields_list();
        $this->var->countries = link_parameters('lib/countries');
        $this->var->partnerList = BDD::get_partner_list();

        $total_search_result = '[';
        foreach(BDD::search_query(false, $this->global->get->q_business_name, $this->global->get->q_country, $this->global->get->q_fields, $this->global->get->q_continuities, $this->global->get->q_remuneration) as $content){
            unset($content->description);
            $content->label = Text::cutString($content->label,0,30);
            $content->img = File::get_img_path(File::BUSINESS_LOGO,$content->ID);
            json_encode($content) != '' && $total_search_result .= str_replace('\r\n','<br>',json_encode($content)).',';
        }
        $this->var->total_search_result = substr($total_search_result,0,-1).']';

    }

}