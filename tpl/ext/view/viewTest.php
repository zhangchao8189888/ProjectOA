<?php
$comlist=$form_data['comList'];
$errorlist=$form_data['error'];
$searchType=$form_data['searchType'];
$comlist=json_encode($comlist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>{$title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
    <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
    <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
     <script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
      <script language="javascript" type="text/javascript" src="tpl/ext/js/widget.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="tpl/ext/js/test.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>

  </head>
  <body>
  <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">

        <div  id="tableList">

        </div>
        <div id="div1" class="content">
            <ul>
                <li id="li1"></li>
                <li id="li3"></li>
            </ul>
        </div>

    </div>
  </body>
</html>