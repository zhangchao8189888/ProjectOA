<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$comList=$form_data['comList'];
//$errorList=$form_data['errorlist'];
//session_start();
//$_SESSION['excelList']=$excelList;

//var_dump($files);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>{$title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <!-- --> <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript"><!--
	  function a(){
	  $("#iform").attr("action","index.php?action=Employ&mode=getEmList"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/zhongqiOA/import.php");
	  $("#iform").submit();
	  }
	  function del(eid){
		  if(confirm('确定要删除整个公司吗?')){
		  $("#iform").attr("action","index.php?action=Employ&mode=delEmlistByCom"); 
		  $("#cname").val(eid)
		  $("#iform").submit();
		  //
		  }
		  }
        --></script>
  </head>
  <body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate">员工列表查询</div>
        <div class="manage">
        <font color="red"><?php if($errorMsg) echo $errorMsg;?></font>
        </div>
        <!--功能项-->
        <div class="manage">
     <form enctype="multipart/form-data" id="iform" action="" method="post"> 
			<input type="hidden" id="cname" name="cname" value=""/> 
			<font color="red"></font>
			<font color="green"></font>
       	</form> 
         </div>
         <div style="min-width:830px">

        <table id="tab_list" class="table_list" width="100%">

           <?php 
        /*   for ($i=0;$i<count($excelList);$i++)
		{
			echo '<tr onmouseover="" onmouseout="">';
			for ($j=0;$j<count($excelList[$i]);$j++)
			{
				echo '<td>'.$excelList[$i][$j].'</td>';
			}
			echo "</tr>";
		}*/
           ?>


        </table>
 <table id="tab_list" class="table_list" width="100%">
  <tr>          
                <th><div>操作</div></th>
                <th><div>单位名称</div></th>
            </tr>
           <?php  if($comList){
           	  while ($row=mysql_fetch_array($comList) ){
           	  //var_dump($row);
           	  	?>
            <tr >
                <td><div><a href="#" onclick="del('<?php  echo $row['e_company'];?>')" target="_self">删除</a></div></td>
                <td><div><a href="index.php?action=Employ&mode=getEmList&comname=<?php  echo $row['e_company'];?>" target="_self"><?php echo $row['e_company'];?></a></div></td>
            </tr>
<?php }?>
<?php }?>
 </table>
        </div>
    </div>
    </div>
  </body>
</html>