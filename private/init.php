<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 05/01/17
 * Time: 21:28
 */

define('ABS_PATH',$_SERVER['DOCUMENT_ROOT']);
define('URL', $_SERVER['HTTP_HOST']);

require_once ABS_PATH.'/private/functions/autoloader.php';
require_once ABS_PATH.'/private/functions/linker.php';

$html = new \lib\HTML();