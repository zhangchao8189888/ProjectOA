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
	  $("#iform").attr("action","/companyOA/import.php"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/companyOA/import.php"); 
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
       <form enctype="multipart/form-data" id="iform" action="/companyOA/index.php?action=Salary&mode=sumSalary" method="post"> 
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
         <?php 
        foreach ($excelList[0] as $key=>$value) {
        	if($key!="guding_salary"&&$key!="log"){
        		
             echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$key.'</td>';
        	
        	}
        }
        /**
         * $salaryList[Sheet1][0][($count+0)]="二次工资合计";
		$salaryList[Sheet1][0][($count+1)]="当月发放工资";
		$salaryList[Sheet1][0][($count+2)]="实际应发合计";
		$salaryList[Sheet1][0][($count+3)]="失业";
		$salaryList[Sheet1][0][($count+4)]="医疗";
		$salaryList[Sheet1][0][($count+5)]="养老";
		$salaryList[Sheet1][0][($count+6)]="公积金";
		$salaryList[Sheet1][0][($count+7)]="应扣税";
		$salaryList[Sheet1][0][($count+8)]="已扣税";
		$salaryList[Sheet1][0][($count+9)]="补扣税";
		$salaryList[Sheet1][0][($count+10)]="双薪进卡";
		$salaryList[Sheet1][0][($count+11)]="缴中企基业合计";
         */
        ?>
        <td align="left" width="150px" style="word-wrap:break-word;">当月发放工资</td>
        <td align="left" width="150px" style="word-wrap:break-word;">二次工资合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">实际应发合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">失业</td>
        <td align="left" width="150px" style="word-wrap:break-word;">医疗</td>
        <td align="left" width="150px" style="word-wrap:break-word;">养老</td>
        <td align="left" width="150px" style="word-wrap:break-word;">公积金</td>
        <td align="left" width="150px" style="word-wrap:break-word;">应扣税</td>
        <td align="left" width="150px" style="word-wrap:break-word;">已扣税</td>
        <td align="left" width="150px" style="word-wrap:break-word;">补扣税</td>
        <td align="left" width="150px" style="word-wrap:break-word;">双薪进卡</td>
        <td align="left" width="150px" style="word-wrap:break-word;">交中企基业合计</td>
        </tr>
           <?php 
          // var_dump($excelList);
         $count=count($excelList[0]);
           for ($i=0;$i<count($excelList);$i++)
		{
			echo '<tr onmouseover="" onmouseout="">';
			 foreach ($excelList[$i] as $key=>$value) {
			{
				if($key!="guding_salary"&&$key!="log"){
					echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value.'</td>';
				}
				if($key=="guding_salary"){
				for($j=3;$j<count($value);$j++){
					if(!empty($value[$j])){
						echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value[$j].'</td>';
					}
				}
				}
			if($key=="log"){
						echo '<td align="left" width="300px" style="word-wrap:break-word;">'.$value.'</td>';
			}
			}
			}
			echo "</tr>";
		}
           ?>
       <tr onmouseover="" onmouseout="">
      <?php 
       echo '<td align="left" width="150px" style="word-wrap:break-word;">合计</td>';
        for ($i=2;$i<count($excelList[0]);$i++) {
             echo '<td align="left" width="150px" style="word-wrap:break-word;"> </td>';
        }
        /**
         * sum_dangyueyingfa` double(10,2) DEFAULT NULL,
  `sum_ercigongziheji` double(10,2) DEFAULT NULL,
  `sum_yingfaheji` double(10,2) DEFAULT NULL,
  `sum_shiye` double(10,2) DEFAULT NULL,
  `sum_yiliao` double(10,2) DEFAULT NULL,
  `sum_yanglao` double(10,2) DEFAULT NULL,
  `sum_gongjijin` double(10,2) DEFAULT NULL,
  `sum_yingkoushui` double(10,2) DEFAULT NULL,
  `sum_yikoushui` double(10,2) DEFAULT NULL,
  `sum_bukoushui` double(10,2) DEFAULT NULL,
  `sum_jinka` double(10,2) DEFAULT NULL,
  `sum_jiaozhongqi` double(10,2) DEFAULT NULL,
         * @var unknown_type
         */
        $row=mysql_fetch_array($salarySumTimeList) ;
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_dangyueyingfa'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_ercigongziheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_yingfaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_shiye'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_yiliao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_yanglao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_gongjijin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_yingkoushui'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_yikoushui'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_bukoushui'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_jinka'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_jiaozhongqi'].'</td>';
        ?>
        </tr>
        </table>
        </div>
        
    </div>
    </div>
  </body>
</html>