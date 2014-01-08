<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$salaryTimeList=$form_data['salaryTimeList'];
$salarySumTimeList=$form_data['salarySumTimeList'];
/*session_start();
$_SESSION['excelList']=$excelList;*/
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
        <font color="red">显示的工资列表有些字段有可能会被省略掉，以导出的EXCEL文件为正确核对</font>
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
        <td align="left" width="150px" style="word-wrap:break-word;">姓名</td>
        <td align="left" width="150px" style="word-wrap:break-word;">身份证号</td>
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
        
        </tr>
         <?php 
         $z=0;
         $hang=0;//导出excel行数标记
         $salaryList=array();
          	$ziduan=2;
        $salaryList[Sheet1][$hang][($ziduan-2)]="姓名";
		$salaryList[Sheet1][$hang][($ziduan-1)]="身份证号";
        $salaryList[Sheet1][$hang][($ziduan+0)]="个人应发合计";
		$salaryList[Sheet1][$hang][($ziduan+1)]="个人失业";
		$salaryList[Sheet1][$hang][($ziduan+2)]="个人医疗";
		$salaryList[Sheet1][$hang][($ziduan+3)]="个人养老";
		$salaryList[Sheet1][$hang][($ziduan+4)]="个人公积金";
		$salaryList[Sheet1][$hang][($ziduan+5)]="代扣税";
		$salaryList[Sheet1][$hang][($ziduan+6)]="个人扣款合计";
		$salaryList[Sheet1][$hang][($ziduan+7)]="实发合计";
		$salaryList[Sheet1][$hang][($ziduan+8)]="单位失业";
		$salaryList[Sheet1][$hang][($ziduan+9)]="单位医疗";
		$salaryList[Sheet1][$hang][($ziduan+10)]="单位养老"; 
		$salaryList[Sheet1][$hang][($ziduan+11)]="单位工伤";
		$salaryList[Sheet1][$hang][($ziduan+12)]="单位生育";
		$salaryList[Sheet1][$hang][($ziduan+13)]="单位公积金";
		$salaryList[Sheet1][$hang][($ziduan+14)]="单位合计";
		$salaryList[Sheet1][$hang][($ziduan+15)]="劳务费";
		$salaryList[Sheet1][$hang][($ziduan+16)]="残保金";
		$salaryList[Sheet1][$hang][($ziduan+17)]="档案费";
		$salaryList[Sheet1][$hang][($ziduan+18)]="交中企基业合计";
         $hang++;
         
         foreach ($salaryTimeList as $excelList) {
        
        ?>
           <?php 
          // var_dump($excelList);
         $count=count($excelList[0]);
           for ($i=0;$i<count($excelList);$i++)
		{
			$ziduan=0;
			echo '<tr onmouseover="" onmouseout="">';
			 foreach ($excelList[$i] as $key=>$value) {
			{
				
				if($key=="guding_salary"){
					echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['e_name'].'</td>';
					echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['employid'].'</td>';
				$salaryList[Sheet1][$hang][$ziduan]=$value['e_name'];
				$ziduan++;
				$salaryList[Sheet1][$hang][$ziduan]=$value['employid'];
				$ziduan++;
					for($j=3;$j<count($value);$j++){
					if(!empty($value[$j])){
						$salaryList[Sheet1][$hang][$ziduan]=$value[$j];
						$ziduan++;
						echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value[$j].'</td>';
					}
				}
				}
			}
			}
			echo "</tr>";
			$hang++;
		}
           ?>
        <?php 
        $hang++;
         $z++;
         }
         
         //var_dump($salaryList[Sheet1]);
         session_start();
         ?>
          <tr onmouseover="" onmouseout="">
      <?php 
      $ziduan=0;
      $salaryList[Sheet1][$hang][$ziduan]="合计";//工资字段
      $ziduan++;
       $salaryList[Sheet1][$hang][$ziduan]=" ";
       $ziduan++;
       echo '<td align="left" width="150px" style="word-wrap:break-word;">合计</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;"> </td>';
       // var_dump($salarySumTimeList);
        $row=$salarySumTimeList ;
        $salaryList[Sheet1][$hang][($ziduan+0)]=$row['sum_per_yingfaheji'];
		$salaryList[Sheet1][$hang][($ziduan+1)]=$row['sum_per_shiye'];
		$salaryList[Sheet1][$hang][($ziduan+2)]=$row['sum_per_yiliao'];
		$salaryList[Sheet1][$hang][($ziduan+3)]=$row['sum_per_yanglao'];
		$salaryList[Sheet1][$hang][($ziduan+4)]=$row['sum_per_gongjijin'];
		$salaryList[Sheet1][$hang][($ziduan+5)]=$row['sum_per_daikoushui'];
		$salaryList[Sheet1][$hang][($ziduan+6)]=$row['sum_per_koukuangheji'];
		$salaryList[Sheet1][$hang][($ziduan+7)]=$row['sum_per_shifaheji'];
		$salaryList[Sheet1][$hang][($ziduan+8)]=$row['sum_com_shiye'];
		$salaryList[Sheet1][$hang][($ziduan+9)]=$row['sum_com_yiliao'];
		$salaryList[Sheet1][$hang][($ziduan+10)]=$row['sum_com_yanglao'];
		$salaryList[Sheet1][$hang][($ziduan+11)]=$row['sum_com_gongshang'];
		$salaryList[Sheet1][$hang][($ziduan+12)]=$row['sum_com_shengyu'];
		$salaryList[Sheet1][$hang][($ziduan+13)]=$row['sum_com_gongjijin'];
		$salaryList[Sheet1][$hang][($ziduan+14)]=$row['sum_com_heji'];
		$salaryList[Sheet1][$hang][($ziduan+15)]=$row['sum_laowufei'];
		$salaryList[Sheet1][$hang][($ziduan+16)]=$row['sum_canbaojin'];
		$salaryList[Sheet1][$hang][($ziduan+17)]=$row['sum_danganfei'];
		$salaryList[Sheet1][$hang][($ziduan+18)]=$row['sum_paysum_zhongqi'];
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
        $_SESSION['excelList']=$salaryList[Sheet1];
        ?>
        </tr>
        </table>
        </div>
        
    </div>
    </div>
  </body>
</html>