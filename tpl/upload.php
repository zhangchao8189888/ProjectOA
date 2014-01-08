<?php 
$errorMsg=$form_data['error'];
$succ=$form_data['succ'];
$files=$form_data['files'];
$admin=$_SESSION['admin'];
//var_dump($files);
//href="/companyOA/index.php?action=Salary&mode=rename&fname=<?php echo $row;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>工资管理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <!-- --> <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript">
	  function a(){
	  $("#iform").attr("action","index.php?action=Salary&mode=rename"); 
	  $("#nfname").val($("#newfname").val());
	  $("#iform").submit();
	  }
	  function salImport(){
	  $("#iform").attr("action","index.php?action=Salary&mode=toImportSalPage"); 
	  //$("#nfname").val($("#newfname").val());
	  $("#iform").submit();
      }
	  function b(){
	  $("#iform").attr("action","index.php?action=Salary&mode=upload"); 
	  $("#iform").submit();
	  }
	  function rename(ofname){
		  $("#botton").attr("style","display:block"); 
		  $("#cbotton").attr("style","display:block"); 
		  $("#ofname").val(ofname);
		  $("#newfname").attr("style","display:block"); 
		  }
	 function cancel(){
          $("#botton").attr("style","display:none"); 
          $("#cbotton").attr("style","display:none"); 
		  $("#newfname").attr("style","display:none"); 
		 }
	 function downLoadTmp(){
	   	  $("#iform").attr("action","index.php?action=Salary&mode=getSalTemlate"); 
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
        <div class="navigate">工资管理</div>
        <p style="font-size: 12px;color: red">&nbsp;&nbsp;导入文件类型必须是.xls或.xlsx类型（注：重复名称会覆盖原来文件。）</p>
        
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="" method="post"> 
			<input type="hidden" name="max_file_size" value="10000000"/> 
			<input name="nfname" id="nfname" type="hidden" value=""/>　　
			<input name="ofname" id="ofname" type="hidden" value=""/>　
			<input name="file"  type="file"/>　　 
			<input type="button" value="导入" onclick="b()"/>
			<input type="button" value="工资导出" onclick="salImport()"/> 
			<input type="button" value="下载导入工资模板" id="btn_ok" onclick="downLoadTmp()" class="btn_submit" />
			<font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font>
       	</form>
         </div>
          <!--搜索栏-->
        <div class="search" style="min-width:830px;">
         <input type="text"  value="" name="newfname" id="newfname" style="display:none"/>
         <div><input type="button" value="提交" onclick="a()"  id="botton" style="display:none"/><input type="button" value="取消" onclick="cancel()"  id="cbotton" style="display:none"/>
      </div>
        </div>
         <div style="min-width:830px">

            <table id="tab_list" class="table_list" width="50%">
          
            <tr>
                <th><div>文件名</div></th>
                <th><div>操作</div></th>
            </tr>

           <?php foreach ($files as $row){  if($row!=='duiBi'){?>
            <tr >

                <td><div><?php echo $row;?></div></td>

                <td><div><a href="index.php?action=Salary&mode=newExcelToHtml&fname=<?php echo $row;?>">查看</a>|<a href="index.php?action=Salary&mode=del&fname=<?php echo $row;?>">删除</a>|<a href="index.php?action=Salary&mode=salDuiBi&fname=<?php echo $row;?>">对比</a>|<a  onclick="rename('<?php echo $row;?>');">重命名</a>
               <?php if($admin['admin_type']==1){?>
                |<a  href="index.php?action=Service&mode=toUpdateEmpList&fname=<?php echo $row;?>">批量修改员工字段</a></div></td>
<?php }?>

            </tr>
<?php }}?>

        </table>

        </div>
    </div>
    </div>
  </body>
</html>