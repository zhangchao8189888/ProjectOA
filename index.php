<?php
ob_start ();
session_start ();
require_once ("./common/common.inc");
$actionPath = $_REQUEST ['action'];
date_default_timezone_set ( "PRC" );
$user = $_SESSION ['admin'];
$loginusername = $usr ['name'];
if (empty ( $user ) && $_REQUEST ['mode'] != "checklogin") {
	$actionrealpath = "tpl/login.php";
} else {
	global $loginusername;
	if (empty ( $actionPath )) {
		$actionrealpath = "module/action/SalaryAction.class.php";
		$actionPath = "Salary";
		$firstLoginType = 1;
	} else {
		$actionrealpath = "module/action/{$actionPath}Action.class.php";
	}
}
require_once ($actionrealpath);
?>
