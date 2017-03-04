<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 22/01/17
 * Time: 18:00
 */


include '../../init.php';

echo '<img src="'.\lib\File::get_img_path(\lib\File::BUSINESS_LOGO,2).'">';
echo '<pre>';
var_dump(\lib\File::get_img_path(\lib\File::BUSINESS_LOGO,2));