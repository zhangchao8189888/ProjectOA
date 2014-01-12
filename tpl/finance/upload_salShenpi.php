<?php 
$errorMsg=$form_data['error'];
$succ=$form_data['succ'];
$files=$form_data['files'];
$timeid=$form_data['timeId'];
//var_dump($files);
//href="/zhongqiOA/index.php?action=Salary&mode=rename&fname=<?php echo $row;
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
	  function duibi(fname){
	  $("#iform").attr("action","index.php?action=Finance&mode=salDuiBi"); 
	  $("#nfname").val(fname);
	  $("#iform").submit();
      }
	  function b(){
	  $("#iform").attr("action","index.php?action=Finance&mode=upload"); 
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
        </script>
  </head>
  <body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate">工资管理</div>
        <div class="manage">
        <font color="red">导入文件类型必须是.xls类型</font>
        </div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="" method="post"> 
			<input type="hidden" name="max_file_size" value="10000000"/> 
			<input name="fname" id="nfname" type="hidden" value=""/>　　
			<input name="timeId" id="timeId" type="hidden" value="<?php echo $timeid?>"/>　
			<input name="file"  type="file"/>　　 
			
			<input type="button" value="导入" onclick="b()"/>
			
			<input type="button" value="工资导出" onclick="salImport()"/> 
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

           <?php foreach ($files as $row){?>
            <tr >

                <td><div><?php echo $row;?></div></td>

                <td><a href="/zhongqiOA/index.php?action=Finance&mode=delFile&fname=<?php echo $row;?>">删除</a>|<a href="#" onclick="duibi('<?php echo $row;?>')">对比</a></td>


            </tr>
<?php }?>

        </table>

        </div>
    </div>
    </div>
  </body>
</html>