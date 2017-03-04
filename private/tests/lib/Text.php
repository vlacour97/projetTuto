<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 10/01/17
 * Time: 18:27
 */

include '../../init.php';

var_dump(\lib\Text::cutString('Lorem ipsum dolor sit amet',0,25)); echo '<br>';
var_dump(\lib\Text::automatic_emo(' :) <3 :/ ')); echo '<br>';
var_dump(\lib\Text::getInitials('Business Hunter')); echo '<br>';