<?php 
$errorMsg=$form_data['error'];
$comList=$form_data['comList'];
$salaryTimeList=$form_data['salaryTimeList'];
$billState=$form_data['billState'];
$warn=$form_data['warn'];
if(empty($warn)){
	$warn="";
}
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
        	  $("#iform").attr("action","index.php?action=Finance&mode=getSalaryTimeListByComId"); 
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
        		 $("#iform").attr("action","index.php?action=Finance&mode=getSalaryTimeListByComId"); 
        		  $("#iform").submit(); 
        		 }
        	function send(id){
        		  $("#timeId").val(id);
        		  $("#iform").attr("action","index.php?action=Finance&mode=shenPiDuiBi"); 
        		  $("#iform").submit(); 
        		}
        	function opShenpi(id,type){
                alert(id);
                return false;
        		$("#billId").val(id);
        		$("#shenPiType").val(type);
        		var massge="";
        		if(type==1){
        			massge="同意";
            		}else{
            			massge="拒绝";
                		}
       		 if(confirm('确定要'+massge+'审批吗？')){
       			 $("#iform").attr("action","index.php?action=Finance&mode=opShenPi"); 
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
        <div class="navigate">申请发放列表</div>
        <div class="manage">
        <font color="red"></font>
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
        <!--功能项-->
        <div class="manage">
     <form enctype="multipart/form-data" id="iform" action="" method="post"> 
			<input type="hidden" name="yearDate" id="yearDate" value=""/>
        <input type="hidden" name="monDate" id="monDate"  value=""/>
         <input type="hidden" name="date" id="date"  value="<?php echo $comlist[0]['salDate']?>"/>
         <input type="hidden" name="timeId" id="timeId"  value=""/>
         <input type="hidden" name="billId" id="billId"  value=""/>
         <input type="hidden" name="shenPiType" id="shenPiType"  value=""/>
         <input type="hidden" name="comname" id="comname"  value=""/>
       	</form> 
         </div>
         <div style="min-width:830px">
 <table id="tab_list" class="table_list" width="100%">
  <tr>          
                <th><div>操作</div></th>
                <th><div>单位名称</div></th>
                <th><div>工资月份</div></th>
                <th><div>操作日期</div></th>
                <th><div>工资状态</div></th>
                <th><div>发放申请状态</div></th>
                <th><div>操作</div></th>
            </tr>
           <?php  if($salaryTimeList){
           	  foreach ($salaryTimeList as $row ){
           	  //var_dump($row);
           	  	?>
            <tr >
            <?php if($row['faValue']['bill_value']!=1){?>
           <td><div><a href="#" onclick="send('<?php  echo $row['id'];?>')" target="_self">处理审批</a></div></td>
                <?php }else{?>
                <td><div><font color="green">已审批</font></div></td>
            
                <?php }?>
               <td><div><a href="index.php?action=SaveSalary&mode=searchSalaryById&id=<?php  echo $row['id'];?>" target="_self"><?php echo $row['company_name'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['salaryTime'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['op_salaryTime'];?></a></div></td>
                <td><div><a target="_self"><?php echo $billState[$row['salary_state']];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['falsate'];?></a></div></td>
                 <td><div><a href="#" onclick="opShenpi('<?php  echo $row['faValue']['id'];?>',1)" target="_self">同意</a>|<a href="#" onclick="opShenpi('<?php  echo $row['faValue']['id'];?>',2)" target="_self">拒绝</a></div></td>
           
            </tr>
<?php }?>
<?php }?>
 </table>
        </div>
    </div>
    </div>
  </body>
</html>