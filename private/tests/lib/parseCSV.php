<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 05/11/16
 * Time: 22:15
 */

include '../../init.php';

$csv = new lib\parseCSV('test.csv');
print_r($csv->data);