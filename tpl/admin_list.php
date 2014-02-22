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
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
   <link href="common/css/validator.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
	    <script src="common/js/formValidator.js" type="text/javascript" charset="UTF-8"></script>
	    <script src="common/js/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script>
        <script language="javascript" type="text/javascript">
        function deal(byid, act){
        	if (act == "delete"){
        		if (confirm("是否确定删除该用户？") == false) return;
        	}
        	$("#aid").val(byid);
        	$("#mod").val(act);
        	$("#dform").submit();
        }
        function onSub(){
            if($("#byid").val()==""){
              alert("管理员id不能为空");
              return;
                }
        	$("#iform").submit();
        }
    </script>
  </head>
  <body>
     <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
            <!--导航栏-->
            <div class="navigate">{$navigate}</div>
            <div class="form">
               <div id="span_msg"><font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font></div>
               <div class="input">
<table  class="comments">
	<tr valign="top">
		<th width="11%"><table border="0" cellspacing="5" cellpadding="1">
		   <?php include(ROOT."/common/tpl/left.php"); ?></table></th>
	  <td width="89%">
		<table cellpadding=4 cellspacing=0 width="100%">
          <tr class="form-action-bar">
            <td width="99%"><div align="center"><strong>管理员管理</strong></div></td>
          </tr>
        </table>
<form  name="iform"  id="iform" action="index.php?action=Admin&mode=add" method="post">
	    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20%">&nbsp;</td>
            <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="95">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="25"><div align="right">管理员ID：</div></td>
                <td class="bz_gray"><input id="byid" name="byid" class="text" value="" size="20" maxlength="20"  onfocus=""/><span style="font-size: 10px;color: red"> *</span></td>
              </tr>
              <tr>
               <tr>
                <td height="25"><div align="right">管理员密码：</div></td>
                <td class="bz_gray"><input id="pass" name="pass" class="text" value="" size="20" maxlength="20"  onfocus=""/><span style="font-size: 10px;color: red"> *</span></td>
              </tr>
                <td><div align="right">管理员级别：</div></td>
                <td class="bz_gray"  style="font-size: 12px">
				<label for="productionYes">
                  <input type="radio" name="user_type" value="1" id="user_type" checked="checked"/>
                  系统管理员
                </label>
                <label for="productionNo">
                  <input type="radio" name="user_type" value="2" id="user_type"/>
				  管理员
				</label>
				<br>
				<label for="productionNo">
                <input type="radio" name="user_type" value="3" id="user_type"/>
				  客服&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</label>
				<label for="productionNo">
                  <input type="radio" name="user_type" value="4" id="user_type"/>
				  财务
				</label>
                <label for="productionNo">
                   <input type="radio" name="user_type" value="5" id="user_type"/>
                   社保
                </label>
				</td>
              </tr>
              <tr>
                <td><div align="right">备注说明：</div></td>
                 <td class="bz_disabled"><input id="memo" name="memo" class="text" value="" size="35" maxlength="50"  onfocus=""/>
                　<span style="font-size: 12px; color: blue;">请说明管理员姓名所在部门及分机</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input name="提交" type="button" class="ygbt" id="form_submit" onclick="onSub()" value=" 添 加 "/></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
            <td width="20%">&nbsp;</td>
          </tr>
        </table>
</form>
      <hr>
      <p align="center">管理员列表　　</p></hr>
<form id="dform" method="post" name="main" action="index.php?action=Admin&mode=delete">
	<input type="hidden" name="mod" id="mod"  value="" />
	<input type="hidden" name="aid" id="aid" value="" />
	</form>
      <table id="tab_list" class="table_list" width="100%">
        <tr>
          <th><div>管理员ID</div></th>
          <th><div> 管理员级别</div></th>
          <th><div>上次登录时间</div></th>
          <th><div>创建时间</div></th>
          <th><div>备注</div></th>
          <th><div>操作</div></th>
        </tr>
         <?php

         while($row=mysql_fetch_array($adminList)){
            ?>
        <tr>
          <td><div align="center"><?php echo $row['name'];?></div></td>
          <td><div align="center"><?php if($row['admin_type']==2)echo '普通管理员'; elseif($row['admin_type']==1)echo '系统管理员';elseif($row['admin_type']==3)echo '客服管理员';elseif($row['admin_type']==4)echo '财务管理员';?> </div></td>
          <td class="bz_disabled"><div align="center"><?php echo $row['last_login_time'];?></div></td>
           <td class="bz_disabled"><div align="center"><?php echo $row['create_time'];?></div></td>
          <td><div align="center"><?php echo $row['memo'];?></div></td>
          <td><div align="center">
          <?php if($admin['admin_type']==1){?>
            <input class="ygbt" type="button" onclick="return deal('<?php echo $row['id'];?>', 'delete');" value=" 删 除 "/>
          <?php }?>
</div></td>

        </tr>
         <?php
           }
            ?>
      </table>

</td></tr></table>
               </div>
            </div>
        </div>
    </div>
  </body>
</html>
