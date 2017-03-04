<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 25/01/17
 * Time: 22:19
 */

namespace controllers;


use lib\BDD;
use lib\Date;
use lib\File;
use lib\PageTemplate;
use lib\Text;

class PageBusiness extends PageTemplate{

    public function _proposition(){
        $this->var->proposition = BDD::get_proposition_info($this->global->get->id);
        if(is_null($this->var->proposition)) $this->_redirect();
        $this->var->countryList = link_parameters('lib/countries');

        // Récupération des domaines d'activités de la proposition
        $this->var->fields = "  ";
        foreach(BDD::get_prop_fields($this->global->get->id) as $field) {
            $this->var->fields .= $field->label.', ';
        }
        $this->var->fields = substr($this->var->fields,0,-2);

        //Récupération des possibilités de poursuites pour la proposition
        $this->var->continuities = "  ";
        foreach(BDD::get_prop_continuities($this->global->get->id) as $continuity) {
            $this->var->continuities .= $continuity->label.', ';
        }
        $this->var->continuities = substr($this->var->continuities,0,-2);

        //Conversion JSON pour la carte
        $proposition = $this->var->proposition;
        unset($proposition->description);
        $proposition->label = Text::cutString($proposition->label,0,30);
        $proposition->img = File::get_img_path(File::BUSINESS_LOGO,$proposition->ID);
        $this->var->json_proposition = str_replace('\r\n','<br>',json_encode($proposition));
    }

} 