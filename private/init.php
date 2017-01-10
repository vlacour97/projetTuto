<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 05/01/17
 * Time: 21:28
 */

define('ABS_PATH',$_SERVER['DOCUMENT_ROOT']);
define('URL', $_SERVER['HTTP_HOST']);
define('PARAM_PATH', ABS_PATH.'/private/parameters');
define('CONFIG_FILE_NAME','config');

require_once ABS_PATH.'/private/functions/autoloader.php';
require_once ABS_PATH.'/private/functions/linker.php';