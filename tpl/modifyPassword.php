<?php 
$errorMsg=$form_data['error'];
$succ=$form_data['succ'];
$adminList=$form_data['adminlist'];
$admin=$_SESSION['admin'];
//var_dump($files);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>修改密码</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
   <link href="common/css/validator.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
	    <script src="common/js/formValidator.js" type="text/javascript" charset="UTF-8"></script>
	    <script src="common/js/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script>

        <script language="javascript" type="text/javascript">

        function onUpdate(){
			$("#iform").submit();
        }
        function repw(){
        	var pass	=	document.getElementById("newpass").value;
        	var repass	=	document.getElementById("repass").value;
        	if (pass!=repass) {
        		document.getElementById("info").innerHTML="密码不一致";
			}
			else{
				document.getElementById("info").innerHTML=" ";
			}
        }
    </script>
  </head>
  <body>
     <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
            <!--导航栏-->
            <div class="navigate">修改密码</div>
            <div class="form">
               <div id="span_msg"><font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font></div>
              <!--  input样式   <div class="input"> --> 
<table  class="comments" width="70%" align="left">
	<tr valign="top">
		<th width="11%"><table border="0" cellspacing="5" cellpadding="1">
		   <?php include(ROOT."/common/tpl/left.php"); ?></table></th>
	  <td width="89%">
		<table cellpadding=4 cellspacing=0 width="100%">
          <tr class="form-action-bar">
            <td width="99%" ><div align="center"><strong>修改密码</strong></div></td>            
          </tr>
        </table>
<form  name="iform"  id="iform" action="index.php?action=Admin&mode=modifyPass" method="post">
	    <table width="100%"  border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
            <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30%">&nbsp;</td>
                <td width="70%">&nbsp;</td>
              </tr>
              <tr>
                <td height="20"><div align="right">请输入当前密码：</div></td>
                <td class="bz_gray"><input id="nowpass" name="nowpass" class="text" value="" size="20" maxlength="20"  onfocus=""/>
                <span style="font-size: 10px;color: red">*</span></td>
              </tr>
              
               <tr>
                <td height="25"><div align="right">请输入新密码：</div></td>
                <td class="bz_gray"><input type="password" id="newpass" name="newpass" class="password" value="" size="20" maxlength="20"  onfocus="" />
                <span style="font-size: 10px;color: red">*</span></td>
              </tr>
              <tr>
                <td height="25"><div align="right">请再确认一次：</div></td>
                <td class="bz_gray"><input type="password" id="repass" name="repass" class="password" value="" size="20" maxlength="20"  onfocus="" onblur="repw();"/>
                <span style="font-size: 10px;color: red">*</span>
                <span id="info"  style="font-size: 12px;color: red"></span>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input name="提交" type="button" class="ygbt" id="form_submit" onclick="onUpdate()" value=" 更新密码"/></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
				<td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
				<td align="left"><p style="font-size: 12px">请注意，修改密码之后需要重新登陆。</p></td>
              </tr>
                          
            </table></td>

          </tr>
        </table>
</form>
</td></tr></table>
               </div>
            </div>
        </div>
    </div>
  </body>
</html>