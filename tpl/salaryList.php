<?php 
session_start();
$salType=$form_data['modes'];
if(!$salType){
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$excelList=$form_data['salaryTimeList'];
$salarySumTimeList=$form_data['salarySumTimeList'];
$salaryPO=$form_data['salaryPO'];
$_SESSION['error']=$errorMsg;
$_SESSION['salaryTimeList']=$excelList;
$_SESSION['salarySumTimeList']=$salarySumTimeList;
$_SESSION['salaryPO']=$salaryPO;
}else{
$errorMsg=$_SESSION['error'];
$excelList=$_SESSION['salaryTimeList'];
$salarySumTimeList=$_SESSION['salarySumTimeList'];
$salaryPO=$_SESSION['salaryPO'];	
}
$_SESSION['excelList']=$excelList;
$_SESSION['salType']="html";
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
	 function updateMark(){
		 $("#iformMark").attr("action","index.php?action=Salary&mode=updateSalMark"); 
		  $("#iformMark").submit(); 

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
			<a href="index.php?action=Salary&mode=toSalListExcel"><font>切换模式</font></a>
       	</form>
       	
         </div>
             <!--搜索栏-->
        <div class="search" style="min-width:830px;">
        
        </div>
        <div>
        <form enctype="multipart/form-data" id="iformMark" action="/zhongqiOA/index.php?action=Salary&mode=sumSalary" method="post">
		备 &nbsp  注：&nbsp&nbsp&nbsp&nbsp  
         <textarea name="mark" cols="100" rows="3" class="text" id="mark"><?php if($salaryPO['mark']) echo $salaryPO['mark'];?></textarea>
         <input type="hidden" name="salTimeId" id="salTimeId"  value="<?php if($salaryPO['id']) echo $salaryPO['id'];?>"/>
         <input type="button" value="修改备注" onclick="updateMark()"/>
        </form>
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
        ?>
        <td align="left" width="150px" style="word-wrap:break-word;">个人应发合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人失业</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人医疗</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人养老</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人公积金</td>
        <td align="left" width="150px" style="word-wrap:break-word;">代扣税</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人扣款合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">实发合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位失业</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位医疗</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位养老</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位工伤</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位生育</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位公积金</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">劳务费</td>
        <td align="left" width="150px" style="word-wrap:break-word;">残保金</td>
        <td align="left" width="150px" style="word-wrap:break-word;">档案费</td>
        <td align="left" width="150px" style="word-wrap:break-word;">交中企基业合计</td>
        <td align="left" width="300px" style="word-wrap:break-word;">备注</td>
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
        for ($i=3;$i<count($excelList[0]);$i++) {
             echo '<td align="left" width="150px" style="word-wrap:break-word;"> </td>';
        }
        $row=mysql_fetch_array($salarySumTimeList) ;
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_yingfaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_shiye'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_yiliao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_yanglao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_gongjijin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_daikoushui'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_koukuangheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_shifaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_shiye'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_yiliao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_yanglao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_gongshang'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_shengyu'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_gongjijin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_heji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_laowufei'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_canbaojin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_danganfei'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_paysum_zhongqi'].'</td>';
        ?>
        </tr>
        </table>
        </div>
        
    </div>
    </div>
  </body>
</html>