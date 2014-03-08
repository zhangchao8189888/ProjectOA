<?php

// load library
require 'tools/php-excel.class.php';
 session_start();
 $salaryList=$_SESSION['excelListGeshui'];
// create a simple 2-dimensional array
/*$data = array(
        1 => array ('Name', 'Surname'),
        array('Schwarz', 'Oliver'),
        array('Test', 'Peter')
        );*/

//var_dump($salaryList);
// generate file (constructor parameters are optional)
$time = date('Y-m-d');

$xls = new Excel_XML('UTF-8', false, 'My Test Sheet');

$xls->addArray($salaryList);
$xls->generateXML('ByXiangxi'.$time);

?>