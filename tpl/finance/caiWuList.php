<?php 
$comlist=$form_data['comList'];
$errorlist=$form_data['error'];
$salaryTimeList=$form_data['salaryTimeList'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>财务管理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <!-- --> <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript">
	  function searchCom(){
	  $("#iform").attr("action","index.php?action=Salary&mode=rename"); 
	  $("#nfname").val($("#newfname").val());
	  $("#iform").submit();
	  }
	  function getEmploy(com){
	  $("#iform").attr("action","index.php?action=Service&mode=getEmList"); 
	  $("#comname").val(com);
	  $("#iform").submit();
      }
	  function getOther(){
	  $("#iform").attr("action","index.php?action=Finance&mode=input"); 
	  if($("#mon").val()==-1){
		  alert("请选择月份");
		  return;
		  }
	  $("#yearDate").val($("#year").val());
	  $("#monDate").val($("#mon").val());
	  $("#iform").submit();
	  }
	  function other(){
		  $("#select").attr("style","display:block"); 
		  }
	 function dangyue(){
		 $("#iform").attr("action","index.php?action=Finance&mode=input"); 
		  $("#iform").submit(); 
		 }
	function makeSal(id){
          $("#comId").val(id);
		  $("#iform").attr("action","index.php?action=Service&mode=makeSal"); 
		  $("#iform").submit(); 
				}
	function cancel(id){
		if(confirm('确定取消对该公司管理吗?')){
		  $("#comId").val(id);
		  $("#iform").attr("action","index.php?action=Service&mode=cancelService"); 
		  $("#iform").submit(); 
		}
		}
        </script>
  </head>
  <body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate"><h2>票务管理首页</h2></div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="index.php?action=Service&mode=toOpCompanyList" method="post"> 
       <div class="submit">
         <input type="hidden" name="yearDate" id="yearDate" value=""/>
         <input type="hidden" name="monDate" id="monDate"  value=""/>
         <input type="hidden" name="date" id="date"  value="<?php echo $comlist[0]['salDate']?>"/>
         <input type="hidden" name="comId" id="comId"  value=""/>
         <input type="hidden" name="comname" id="comname"  value=""/>
			<input type="button" value="查询" onclick="searchCom()"/>
               </div>  
       	</form>
         </div>
          <!--搜索栏-->
        <div class="search" style="min-width:830px;">
         <?php 
        foreach ($errorlist as $error){
        	echo '<font color=red>'.$error.'</font></br>';
        }
        ?>
         <div class="submit">
                  <a href="#" onclick="dangyue()" >当月</a>/<a href="#" onclick="other()" >往月</a> 
                  <div id="select" style="display:none">
                 年：<select name="year" id="year">
                  <option value="2011">2011</option>
                  <option value="2012" selected>2012</option>
                  <option value="2013">2013</option>
                  <option value="2014">2014</option>
                  </select>
                  月：<select name="mon" id="mon" onchange="getOther()">
                  <option value="-1">请选择</option>
                  <option value="01">01</option>
                  <option value="02">02</option>
                  <option value="03">03</option>
                  <option value="04">04</option>
                  <option value="05">05</option>
                  <option value="06">06</option>
                  <option value="07">07</option>
                  <option value="08">08</option>
                  <option value="09">09</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                  </select>
                  </div>
               </div>
			
          <div style="width:100%;overflow-x:scroll;dispaly:inline;white-space:nowrap;">

           <table id="tab_list" class="table_list" cellpadding="2" cellspacing="1" style="table-layout:fixed;">
  <tr>          
                <td align="left" width="30px" style="word-wrap:break-word;">序号</td>
                 <td align="left" width="150px" style="word-wrap:break-word;">单位</td>
                <td align="left" width="150px" style="word-wrap:break-word;">单位工资表月份</td>
                <td align="left" width="150px" style="word-wrap:break-word; background-color:red;">发票日期</td>
                <td align="left" width="150px" style="word-wrap:break-word;background-color:red;">发票号</td>
                <td align="left" width="40px" style="word-wrap:break-word;background-color:red;">发票数</td>
                <td align="left" width="50px" style="word-wrap:break-word;background-color:red;">发票金额合计</td>
                <td align="left" width="150px" style="word-wrap:break-word;background-color:green;">支票日期</td>
                <td align="left" width="40px" style="word-wrap:break-word;background-color:green;">支票数</td>
                <td align="left" width="50px" style="word-wrap:break-word;background-color:green;">支票金额合计</td>
                <td align="left" width="150px" style="word-wrap:break-word;background-color:blue;">支票到账日期</td>
                <td align="left" width="40px" style="word-wrap:break-word;background-color:blue;">到账数</td>
                <td align="left" width="50px" style="word-wrap:break-word;background-color:blue;">到账金额合计</td>
            </tr>
         <?php 
         if($salaryTimeList){
         	for($i=0;$i<count($salaryTimeList);$i++){
         				  echo '<tr >';
          echo'<td align="left" width="30px" style="word-wrap:break-word;" >'.($i+1).'</td>';
          echo  '<td align="left" width="150px" style="word-wrap:break-word;><a href="index.php?action=SaveSalary&mode=searchSalaryById&id='. $salaryTimeList[$i]['id'].'" target="_self">'.$salaryTimeList[$i]['company_name'].'</a></td>';
          echo'<td align="left" width="150px" style="word-wrap:break-word;"  >'.$salaryTimeList[$i]['salaryTime'].'</td>';
           echo'<td align="left" width="150px" style="word-wrap:break-word;" title="'.$salaryTimeList[$i]['bill_fa_date'].'"  >'.$salaryTimeList[$i]['bill_fa_date'].'</td>';
          echo'<td align="left" width="150px" style="word-wrap:break-word;"  >'.$salaryTimeList[$i]['bill_fa'].'</td>';
          if($salaryTimeList[$i]['bill_fa_num']>1){
          	 echo'<td align="left" width="10px" style="word-wrap:break-word;"  ><font color=red>'.$salaryTimeList[$i]['bill_fa_num'].'</font></td>';
          }else{
          	echo'<td align="left" width="10px" style="word-wrap:break-word;"  >'.$salaryTimeList[$i]['bill_fa_num'].'</td>';
          }
          echo'<td align="left" width="50px" style="word-wrap:break-word;"  >'.$salaryTimeList[$i]['bill_fa_value'].'</td>';
           echo'<td align="left" width="150px" style="word-wrap:break-word;" title="'.$salaryTimeList[$i]['bill_zhi_date'].'"  >'.$salaryTimeList[$i]['bill_zhi_date'].'</td>';
          echo'<td align="left" width="40px" style="word-wrap:break-word;"  >'.$salaryTimeList[$i]['bill_zhi_num'].'</td>';
          echo'<td align="left" width="50px" style="word-wrap:break-word;"  >'.$salaryTimeList[$i]['bill_zhi_value'].'</td>';
           echo'<td align="left" width="150px" style="word-wrap:break-word;" title="'.$salaryTimeList[$i]['bill_dao_date'].'"  >'.$salaryTimeList[$i]['bill_dao_date'].'</td>';
          echo'<td align="left" width="40px" style="word-wrap:break-word;"  >'.$salaryTimeList[$i]['bill_dao_num'].'</td>';
          echo'<td align="left" width="50px" style="word-wrap:break-word;"  >'.$salaryTimeList[$i]['bill_dao_value'].'</td>';
          
         	echo '</tr >';		
         		}
         }
         ?>
        </table>

        </div>
    </div>
    </div>
  </body>
</html>