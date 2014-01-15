<?php 
$comlist=$form_data['comList'];
$errorlist=$form_data['error'];
$financeList=$form_data['financeList'];
//var_dump($files);
//href="/zhongqiOA/index.php?action=Salary&mode=rename&fname=<?php echo $row;
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
	  $("#iform").attr("action","index.php?action=Finance&mode=finance_frist"); 
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
		 $("#iform").attr("action","index.php?action=Finance&mode=finance_frist"); 
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
        <div class="navigate"><h2>财务管理</h2></div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="index.php?action=Service&mode=toOpCompanyList" method="post"> 
       <div class="submit">
     <!--     单位名称：<input type="text" id="comname" name="comname" value=""/>
     <input type="button" value="查询" onclick="searchCom()"/>
      -->
         <input type="hidden" name="yearDate" id="yearDate" value=""/>
         <input type="hidden" name="monDate" id="monDate"  value=""/>
         <input type="hidden" name="date" id="date"  value="<?php echo $comlist[0]['salDate']?>"/>
         <input type="hidden" name="comId" id="comId"  value=""/>
         <input type="hidden" name="comname" id="comname"  value=""/>
			
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
                <td align="left"  style="width:30;word-wrap:break-word;">序号</td>
                <td align="left"  style="width:200;word-wrap:break-word;">单位</td>
                <td align="left"  style="word-wrap:break-word;">月份</td>
                <td align="left"  style="word-wrap:break-word; background-color:red;">工资状态</td>
                <td align="left"  style="word-wrap:break-word;background-color:red;">发票状态</td>
                <td align="left"  style="word-wrap:break-word;background-color:red;">支票状态</td>
                <td align="left" style="word-wrap:break-word;background-color:red;">支票到账</td>
                <td align="left" style="word-wrap:break-word;background-color:green;">工资发放</td>
            
            </tr>
         <?php 
         if($financeList){
         	for($i=0;$i<count($financeList);$i++){
         				  echo '<tr >';
          echo'<td align="left"  style="word-wrap:break-word;" >'.($i+1).'</td>';
          echo  '<td align="left" style="word-wrap:break-word;><a href="index.php?action=SaveSalary&mode=searchSalaryById&id='. $financeList[$i]['id'].'" target="_self">'.$financeList[$i]['company_name'].'</a></td>';
          echo'<td align="left"  style="word-wrap:break-word;"  >'.$financeList[$i]['date'].'</td>';
          echo'<td align="left"  style="word-wrap:break-word;"  >'.$financeList[$i]['salState'].'</td>';
          echo'<td align="left"  style="word-wrap:break-word;"  >'.$financeList[$i]['bill_fa'].'</td>';
          echo'<td align="left"  style="word-wrap:break-word;"  >'.$financeList[$i]['bill_zhi'].'</td>';
          echo'<td align="left"  style="word-wrap:break-word;"  >'.$financeList[$i]['bill_dao'].'</td>';
           echo'<td align="left"  style="word-wrap:break-word;"  >'.$financeList[$i]['bill_fafang'].'</td>';
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