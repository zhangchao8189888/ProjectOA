<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$excelList=$form_data['salaryTimeList'];
$salarySumTimeList=$form_data['salarySumTimeList'];
session_start();
$_SESSION['excelList']=$excelList;
//exit;
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
        <script language="javascript" type="text/javascript">
	  function a(){
	  $("#iform").attr("action","/zhongqiOA/import.php");
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/zhongqiOA/import.php");
	  $("#iform").submit();
	  }
	  function save(ofname){
		  
		  $("#botton").attr("style","display:block"); 
		  $("#cbotton").attr("style","display:block"); 
		  //$("#ofname").val(ofname);
		  $("#salaryTime").attr("style","display:block"); 
		  $("#comname").attr("style","display:block"); 
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
       <form enctype="multipart/form-data" id="iform" action="/zhongqiOA/index.php?action=Salary&mode=sumSalary" method="post">
			导出文件名称：<input type="text" name="name" value=""/> 
			<input type="button" value="导出" onclick="a()"/>
			<font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font>
       	</form>
         </div>
             <!--搜索栏-->
        <div class="search" style="min-width:830px;">
        
        </div>
         <div style="width:100%;overflow-x:scroll;dispaly:inline;white-space:nowrap;">

        <table id="tab_list" class="table_list" cellpadding="2" cellspacing="1" style="table-layout:fixed;">
       
        <tr>  
        <td align="left" width="150px" style="word-wrap:break-word;">单位</td>
        <td align="left" width="150px" style="word-wrap:break-word;">姓名</td>
        <td align="left" width="150px" style="word-wrap:break-word;">身份证号</td>
        <td align="left" width="150px" style="word-wrap:break-word;">年终奖</td>
        <td align="left" width="150px" style="word-wrap:break-word;">年终奖代扣税</td>
        <td align="left" width="150px" style="word-wrap:break-word;">当月应发合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">实发进卡</td>
        <td align="left" width="150px" style="word-wrap:break-word;">缴纳中企基业合计</td>
        </tr>
           <?php 
          // var_dump($excelList);
         $count=count($excelList[0]);
           for ($i=0;$i<count($excelList);$i++)
		{
			echo '<tr onmouseover="" onmouseout="">';
			echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i]['comName'].'</td>';
			echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i]['e_name'].'</td>';
			echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i]['employid'].'</td>';
					echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i]['nianzhongjiang'].'</td>';
					echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i]['nian_daikoushui'].'</td>';
					echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i]['yingfaheji'].'</td>';
                    echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i]['shifajinka'].'</td>';
			        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i]['jiaozhongqi'].'</td>';
					echo "</tr>";
		}
           ?>
       <tr onmouseover="" onmouseout="">
      <?php 
       echo '<td align="left" width="150px" style="word-wrap:break-word;">合计</td>';
        for ($i=0;$i<2;$i++) {
             echo '<td align="left" width="150px" style="word-wrap:break-word;"> </td>';
        }
        $row=mysql_fetch_array($salarySumTimeList) ;
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_nianzhongjiang'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_daikoushui'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_yingfaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_shifajika'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_jiaozhongqi'].'</td>';
        ?>
        </tr>
        </table>
        </div>
        
    </div>
    </div>
  </body>
</html>