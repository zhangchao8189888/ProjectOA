<?php 
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$excelList=$form_data['excelList'];
$back=$form_data['back'];
//$this->objForm->setFormData("jisanlist",$jisuan_var);
//$this->objForm->setFormData("excelList",$salaryList[Sheet1]);
$errorList=$form_data['errorlist'];
session_start();
$_SESSION['excelList']=$excelList;
//var_dump($errorList);
//exit;
//var_dump($files);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>{$title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript">
	  function a(){
	  $("#iform").attr("action","/companyOA/import.php"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/companyOA/import.php"); 
	  $("#iform").submit();
	  }
	  function back(ofname){
		  //$("#iform").attr("action",''); 
		  $("#iform").submit();
		  }
	 function cancel(){
          $("#botton").attr("style","display:none"); 
          $("#cbotton").attr("style","display:none"); 
		  $("#salaryTime").attr("style","display:none"); 
		  $("#comname").attr("style","display:none"); 
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
        <div class="manage">
        <font color="red">导入文件类型必须是.xls类型</font>
        </div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="<?php echo $back;?>" method="post"> 
			<input type="button" value="返回" onclick="back()"/>
			<font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font>
       	</form>
         </div>
             <!--搜索栏-->
        <div class="search" style="min-width:830px;">
        </div>
         <div style="width:100%;overflow-x:scroll;dispaly:inline;white-space:nowrap;">

        <table id="tab_list" class="table_list" cellpadding="2" cellspacing="1" style="table-layout:fixed;">

           <?php 
         $count=count($excelList[0]);
           for ($i=0;$i<count($excelList);$i++)
		{
			echo '<tr onmouseover="" onmouseout="">';
			for ($j=0;$j<$count;$j++)
			{
				/*if(!isset($excelList[$i][$j])){
					echo '<td></td>';
				}else{*/
				echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i][$j].'</td>';
				//}
			}
			echo "</tr>";
		}
           ?>


        </table>
        </div>
 <table id="tab_list" class="table_list" width="30%">
  <tr>
                <th><div>错误信息</div></th>
            </tr>
            
           <?php   foreach ($errorList as $rows){
           	foreach ($rows as $key=>$value){
           	  if($key!='error_shenfen'){
           		?>
           
            <tr >

                <?php echo $value;?>

            </tr>
<?php }}}
foreach ($errorList as $rows){
           foreach ($rows as $key=>$value){
           	  if($key=='error_shenfen'){
           		?>
           
            <tr >

               <?php echo $value;?>

            </tr>
<?php }} }?>
 </table>
        
    </div>
    </div>
  </body>
</html>