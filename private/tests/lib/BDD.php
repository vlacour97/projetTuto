<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 22/01/17
 * Time: 17:57
 */

include '../../init.php';

echo '<pre>';
var_dump(\lib\BDD::search_query());
var_dump(\lib\BDD::get_business_continuities(1));
var_dump(\lib\BDD::get_business_fields(1));
var_dump(\lib\BDD::get_business_info(1));
var_dump(\lib\BDD::get_business_list(1));
var_dump(\lib\BDD::get_business_prop_list(1));
var_dump(\lib\BDD::get_partner_list(1));
var_dump(\lib\BDD::get_prop_continuities(1));
var_dump(\lib\BDD::get_prop_fields(1));
var_dump(\lib\BDD::get_proposition_info(1));