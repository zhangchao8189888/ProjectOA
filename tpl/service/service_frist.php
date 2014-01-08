<?php 
$comlist=$form_data['comList'];
$errorlist=$form_data['error'];
$searchType=$form_data['searchType'];
//var_dump($files);
//href="/companyOA/index.php?action=Salary&mode=rename&fname=<?php echo $row;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>工资管理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <!-- --> <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript">
	  function a(){
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
	  $("#iform").attr("action","index.php?action=Service&mode=getOtherAdminComList"); 
	  if($("#mon").val()==-1){
		  alert("请选择月份");
		  return;
		  }
	  $("#yearDate").val($("#year").val());
	  $("#monDate").val($("#mon").val());
	  $("#sType").val($("#searchType").val());
	  $("#iform").submit();
	  }
	  function other(){
		  $("#select").attr("style","display:block"); 
		  }
	 function dangyue(){
		 $("#iform").attr("action","index.php?action=Service&mode=getAdminComList"); 
		 $("#sType").val($("#searchType").val());
		  $("#iform").submit(); 
		 }
	function makeSal(id,sDate,salType){
		//alert(sDate);
		if(sDate==""){
			alert("未找到做工资月份，请按工资月份查询后再做工资！");
			return;
			}
          $("#comId").val(id);
          $("#sDate").val(sDate);
          $("#salType").val(salType);
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
	function send(eid){
		  if(confirm('确定要申请发放工资吗?')){
		  $("#iform").attr("action","index.php?action=Service&mode=salarySend"); 
		  $("#timeid").val(eid)
		  $("#iform").submit();
		  //
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
        <div class="navigate">工资管理</div>
        <div class="manage">
        </div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="index.php?action=Service&mode=toOpCompanyList" method="post"> 
       <div class="submit">
       <input type="hidden" name="yearDate" id="yearDate" value=""/>
        <input type="hidden" name="timeid" id="timeid" value=""/>
        <input type="hidden" name="monDate" id="monDate"  value=""/>
        <input type="hidden" name="sType" id="sType"  value=""/>
         <input type="hidden" name="date" id="date"  value="<?php echo $comlist[0]['salDate']?>"/>
         <input type="hidden" name="comId" id="comId"  value=""/>
         <input type="hidden" name="sDate" id="sDate"  value=""/>
         <input type="hidden" name="salType" id="salType"  value=""/>
         <input type="hidden" name="comname" id="comname"  value=""/>
			 <input type="submit" value=" 添加管理公司 " id="btn_ok" align="left" class="btn_submit" />
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
         <div align="left">
         <select name="searchType" id="searchType"  >
                  <option value="1" <?php if($searchType==1) echo "selected"?>>按工资月份查询</option>
                  <option value="2" <?php if($searchType==2) echo "selected"?>>按操作时间查询</option>
                  </select>
                  </div>
        <div align="center">          
                  <a href="#" onclick="dangyue()" >当月</a>/<a href="#" onclick="other()" >往月</a> 
                  <div id="select" style="display:none">
                 年：<select name="year" id="year">
                  <option value="2011">2011</option>
                  <option value="2012" >2012</option>
                  <option value="2013" selected>2013</option>
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
               </div>
			
         <div style="min-width:830px">

            <table id="tab_list" class="table_list" width="100%">
          
             <tr>
          <th><div align="center">工资日期</div></th>
          <th><div align="center">工资操作日期</div></th>
          <th><div >单位名称</div></th>
          <th><div align="center">一次工资</div></th>
          <th><div align="center">二次工资</div></th>
          <th><div align="center">年终奖</div></th>
          <th><div  align="center">发票情况</div></th>
          <th><div  align="center">审批状态</div></th>
          <th><div  align="center">添加管理时间</div></th>
          <th><div  align="center"> 备注</div></th>
          <th><div  align="center">操作</div></th>
        </tr>
         <?php 
         //var_dump($comlist);
         //exit;
         foreach ($comlist as $row){
            ?>
        <tr>
        <td class="bz_disabled"><div align="center"><?php echo $row['salDate'];?></div></td>
        <td class="bz_disabled"><div align="center"><?php echo $row['op_salaryTime'];?></div></td>
          <td><div><a href="#" onclick="getEmploy('<?php  echo $row['company_name'];?>')" target="_self"><?php echo $row['company_name'];?></a></div></td>
          <td class="bz_disabled"><div align="center"><?php echo $row['salStat'];?></div></td>
          <td class="bz_disabled"><div align="center"><?php echo $row['salOrStat'];?></div></td>
          <td class="bz_disabled"><div align="center"><?php echo $row['salNianStat'];?></div></td>
          <td class="bz_disabled"><div align="center"><?php echo $row['fastat'];?></div></td>
          <td class="bz_disabled"><div align="center"><?php echo $row['fa_state'];?></div></td>
           <td class="bz_disabled"><div align="center"><?php echo $row['opTime'];?></div></td>
          <td><div align="center"><?php echo $row['mark'];?> </div></td>
          <td><div align="center">
          <a href="#" onclick="makeSal(<?php echo $row['companyId'];?>)" target="_self">做工资</a>|
          <?php if($row['salTimeid']!=-1){?>
          <a href="index.php?action=SaveSalary&mode=searchSalaryById&id=<?php  echo $row['salTimeid'];?>" target="_self">查看工资</a>|
          <a href="#" onclick="send(<?php echo $row['salTimeid'];?>)" target="_self">申请发放审批</a>|
          <?php }?>
          <a href="#" onclick="getEmploy('<?php  echo $row['company_name'];?>')" target="_self">员工操作|</a>
          <a href="#" onclick="cancel('<?php  echo $row['companyId'];?>')" target="_self">取消对该公司管理</a>
          </div></td>

        </tr>
         <?php 
           }
            ?>

        </table>

        </div>
    </div>
    </div>
  </body>
</html>