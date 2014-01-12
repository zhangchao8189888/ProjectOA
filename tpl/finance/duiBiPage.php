<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
$salarylist=$form_data['salarylist'];
$salTime=$form_data['salTime'];
session_start();
$_SESSION['salarylist']=$salarylist;

//var_dump($salarylist);
//exit;
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
        <script language="javascript" type="text/javascript">
	  function a(){
	  $("#iform").attr("action","__ROOT__/Black/blacklistexport"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/zhongqiOA/index.php?action=Salary&mode=sumSalary");
	  $("#iform").submit();
	  }
	  function nian_b(){
	  $("#iform_nian").attr("action","/zhongqiOA/index.php?action=Finance&mode=shenPiSalHeji");
	  $("#iform_nian").submit();
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
        <font color="red">公司：<?php echo $salTime['company_name']?></font>
        </div>
        <!--功能项-->
         <div class="manage" style="word-wrap:break-word;">
       <form enctype="multipart/form-data" id="iform_nian" action="/zhongqiOA/index.php?action=Salary&mode=sumSalary" method="post">
			选择身份证：<input type="text" name="shenfenzheng_nian" value=""/> 
			对比月份（2012-01-01）：<input type="text" name="salTime" value="<?php echo $salTime['salaryTime']?>" readonly="readonly"/> 
			实发合计项：<input type="text" name="nian" value=""/> 
			<input type="hidden"  id="timeId"name="timeId" value="<?php echo $salTime['id']?>"/>
			<input type="button" value="实发合计项审核" onclick="nian_b()"/>
			<font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font>
       	</form>
         </div>
         <div style="min-width:830px">

        <table id="tab_list" class="table_list" width="100%">
           
           <?php 
           echo '<tr onmouseover="" onmouseout="">';
			for ($j=0;$j<count($salarylist['Sheet1'][0]);$j++)
			{
				//if($salarylist[Sheet1][$i][$j]!=""){
				echo '<td><div><font color="green">'.($j+1).'</font></div></td>';
				//}
			}
			echo "</tr>";
           for ($i=0;$i<count($salarylist['Sheet1']);$i++)
		{
			echo '<tr onmouseover="" onmouseout="">';
			for ($j=0;$j<count($salarylist['Sheet1'][$i]);$j++)
			{
				//if($salarylist[Sheet1][$i][$j]!=""){
				echo '<td><div>'.$salarylist['Sheet1'][$i][$j].'</div></td>';
				//}
			}
			echo "</tr>";
		}
           ?>


        </table>

        </div>
    </div>
    </div>
  </body>
</html>