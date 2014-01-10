<?php
$errorMsg = $form_data ['error'];
$warn = $form_data ['warn'];
$admin = $_SESSION ['admin'];
// var_dump($files);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>网页正在测试</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css"
	rel="stylesheet" />
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet" />
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript"
	src="common/ext/ext-all-debug.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="common/js/jquery_last.js" charset="utf-8"></script>
</head>
<body>
 <?php include("tpl/commom/top.html"); ?>
    <?php include("tpl/commom/left.php"); ?>
<div style="color: blue;"><h3>抱歉，该功能正在内测，请等待开放。</h3></div>

</body>
</html>