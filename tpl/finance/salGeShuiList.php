<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$excelList=$form_data['salaryTimeList'];
$salarySumTimeList=$form_data['salarySumTimeList'];
$comList=$form_data['comlist'];
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
	 function save_geshui(){
		 $("#iform").attr("action","/companyOA/index.php?action=Finance&mode=saveGeShui"); 
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
	          工资年月：<font color="red">(2010-11-10)</font><input type="text"  value="" name="salaryTime" id="salaryTime" />
                      公司名称：<select  name="comname" id="comname" >
			<?php  if($comList){
           	  while ($row=mysql_fetch_array($comList) ){
           	  var_dump($row);
           	  	?>
           	  	<option value="<?php echo $row['company_name'];?>"><?php echo $row['company_name'];?></option>
           	  	<?php }?>
                <?php }?>
			</select>
             <input type="button" value="保存个税"   id="botton"  onclick="save_geshui()"/>         
       	</form>
         </div>
             <!--搜索栏-->
        <div class="search" style="min-width:830px;">
        
        </div>
         <div style="width:100%;overflow-x:scroll;dispaly:inline;white-space:nowrap;">

        <table id="tab_list" class="table_list" cellpadding="2" cellspacing="1" style="table-layout:fixed;">
       
        <tr>  
         <?php 
         $z=0;
        // var_dump($excelList);
        $hang=0;
        $salaryList=array();
        // foreach ($excelList as $salaryTimeList) {
         	//var_dump($salaryTimeList);
         //导出excel行数标记
         
         $salaryList[Sheet1][$hang][0]="姓名";
         $salaryList[Sheet1][$hang][1]="身份证号";
         $salaryList[Sheet1][$hang][2]="银行卡号";
         $salaryList[Sheet1][$hang][3]="开户行";
         $salaryList[Sheet1][$hang][4]="个人所得税";
         
         $hang++;
        ?>
        <td align="left" width="150px" style="word-wrap:break-word;">姓名</td>
        <td align="left" width="150px" style="word-wrap:break-word;">身份证号</td>
        <td align="left" width="150px" style="word-wrap:break-word;">银行卡号</td>
        <td align="left" width="150px" style="word-wrap:break-word;">开户行</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人所得税</td>
        </tr>
           <?php 
          // var_dump($excelList);
            foreach ($excelList as $salaryTimeList) {
           foreach ($salaryTimeList as $value) {
			echo '<tr onmouseover="" onmouseout="">';
				
					$salaryList[Sheet1][$hang][0]=$value[0];//工资字段
					$salaryList[Sheet1][$hang][1]=$value[1];
					$salaryList[Sheet1][$hang][2]=$value[2];
					$salaryList[Sheet1][$hang][3]=$value[3];
					$salaryList[Sheet1][$hang][4]=$value[4];
					echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value[0].'</td>';
				    echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value[1].'</td>';
				    echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value[2].'</td>';
				    echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value[3].'</td>';
				    echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value[4].'</td>';
			echo "</tr>";	
			$hang++;
           }
         }
         echo '<tr onmouseover="" onmouseout="">';
      $ziduan=0;
      $salaryList[Sheet1][$hang][$ziduan]="合计";//工资字段
      $ziduan++;
       $salaryList[Sheet1][$hang][$ziduan]=" ";
       $ziduan++;
       $salaryList[Sheet1][$hang][$ziduan]=" ";
       $ziduan++;
       $salaryList[Sheet1][$hang][$ziduan]=" ";
       $ziduan++;
       echo '<td align="left" width="150px" style="word-wrap:break-word;">合计</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;"> </td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;"> </td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;"> </td>';
        
       // var_dump($salarySumTimeList);
        $row=$salarySumTimeList ;
         $salaryList[Sheet1][$hang][($ziduan+0)]=$row['sum_per_daikoushui'];
         echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_daikoushui'].'</td>';
        echo "</tr>";
           ?>
           
        <?php 
         //var_dump($salaryList[Sheet1]);
         session_start();
         
$_SESSION['excelList']=$salaryList[Sheet1];
         ?>
        </table>
        </div>
        
    </div>
    </div>
  </body>
</html>