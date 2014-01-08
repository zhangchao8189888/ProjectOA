<?php 
$errorMsg=$form_data['error'];
$succ=$form_data['succ'];
$errorList=$form_data['errorlist'];
$emList=$form_data['emList'];

//var_dump($errorList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>员工批量导入</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <!-- --> <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript">
        function downLoadTmp(){
        	  $("#iform").attr("action","index.php?action=Employ&mode=getEmployTemlate"); 
        	  $("#iform").submit();
        	  }
	  function b(){
		  
		  if($("#file").val()==""){
              alert("选择导入的文件");
              return;  
          }
	  $("#iform").attr("action","index.php?action=Employ&mode=emImport"); 
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
        <div class="navigate">员工管理</div>
        <div class="manage">
        <font color="red">导入文件类型必须是.xls类型</font>
        </div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="" method="post"> 
			<input type="hidden" name="max_file_size" value="10000000"/> 
			<input name="file" id="file"  type="file"/>　　 
			
			<input type="button" value="导入" onclick="b()"/>
			<input type="button" value="下载导入员工模板" id="btn_ok" onclick="downLoadTmp()" class="btn_submit" />
			<!-- <input type="button" value="导出" onclick="a()"/> <input type="text" name="fnames" value=""/>-->
			<font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font>
       	</form>
         </div>
         <div style="min-width:830px">
         <font color="red">员工导入错误信息列表</font>
          <table id="tab_list" class="table_list" width="50%">

            <tr>
                <th><div>员工名称</div></th>
                <th><div>身份证号</div></th>
                <th><div>错误信息</div></th>
            </tr>

           <?php foreach ($errorList as $row){?>
            <tr >

                <td><div><?php echo $row['e_name'];?></div></td>
                <td><div><?php echo $row['e_num'];?></div></td>
                <td><div><?php echo $row['errmg'];?></div></td>

            </tr>
<?php }?>
        
        </table>
        <font color="red">员工导入成功列表(<?php echo count($emList);?>)</font>
        <table id="tab_list" class="table_list" width="50%">

            <tr>
                <th><div>员工名称</div></th>
                <th><div>身份证号</div></th>
            </tr>

           <?php foreach ($emList as $row){?>
            <tr >

                <td><div><?php echo $row['e_name'];?></div></td>
                <td><div><?php echo $row['e_num'];?></div></td>

            </tr>
<?php }?>
        
        </table>
        </div>
    </div>
    </div>
  </body>
</html>