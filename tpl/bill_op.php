<?php 
$errorMsg=$form_data['error'];
$comList=$form_data['comList'];
$salaryTimeList=$form_data['salaryTimeList'];
$billState=$form_data['billState'];
$comId=$form_data['cid'];
$warn=$form_data['warn'];
if(empty($warn)){
	$warn="";
}
//var_dump($salaryTimeList);
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
        <script language="javascript" type="text/javascript"><!--
	  function a(){
	  $("#iform").attr("action","index.php?action=SalaryBill&mode=searchBill"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/companyOA/import.php"); 
	  $("#iform").submit();
	  }
	  function del(eid){
		  if(confirm('确定删除发票吗?')){
		  $("#iform").attr("action","index.php?action=SalaryBill&mode=delBill"); 
		  $("#timeid").val(eid)
		  $("#iform").submit();
		  //
		  }
		  }
	  function update(eid){
		  $("#iform").attr("action","index.php?action=SalaryBill&mode=editBill"); 
		  $("#timeid").val(eid)
		  $("#iform").submit();
		  //
		  }
	  function getCompanyByName(){
	    	$("#cid").html("");
	        
	    	$.ajax(
	                {
	                   type: "POST",
	                   url: "index.php?action=SalaryBill&mode=getCompanyListByName",
	                   data: "comName="+$("#comName").val(),
	                   success: function(msg)
	                            {
	                      var result=msg.split("$");
	                     // alert(result);
	                             for(var i=1;i<result.length;i++){
	                                 var obj=result[i].split("|");
	                                 //alert(obj);
	                                 $("#cid").append(" <option value="+obj[0]+"  >"+obj[1]+"</option>");
	                                 }
	                             $("#cid").append("<option value='-1' id='selectCust' selected='selected' >选择客户信息列表</option>");
	                            	$("#selectCust").attr("selected","selected");
	                            }
	            }
	            );
	    	
	    }
        --></script>
  </head>
  <body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate">员工列表查询</div>
        <!--功能项-->
        <div class="search">
     <form enctype="multipart/form-data" id="iform" action="" method="post"> 
			<div style="float:left;" >单位名称：<input type="text" name="comName" id="comName"/><input type="button" value="查询公司列表" onclick="getCompanyByName()"/>
           			</div>
           			
           		<div style="float:left;" >	<select  name="cid" id="cid" size="5">
                                <option value="-1">请选择公司名称</option>
                </select>
			<input type="hidden" id="timeid" name="timeid" value=""/>
			<input type="hidden" id="comid" name="comid" value="<?php echo $comId ;?>"/>
			<input type="button" value="查询" onclick="a()" class="btn_submit"/>
			<font color="red"><?=$warn?></font>
			<font color="green"></font>
			</div>
       	</form> 
         </div>
           <div style="width:100%;overflow-x:scroll;dispaly:inline;white-space:nowrap;">
 <table id="tab_list" class="table_list" cellpadding="2" cellspacing="1" style="table-layout:fixed;">
  <tr>          
                 <td align="left" width="150px" style="word-wrap:break-word; background-color:red;">单位</td>
                <td align="left" width="150px" style="word-wrap:break-word; background-color:red;">单位工资表月份</td>
                <td align="left" width="150px" style="word-wrap:break-word; ">发票日期</td>
                <td align="left" width="150px" style="word-wrap:break-word; ">发票类型</td>
                <td align="left" width="150px" style="word-wrap:break-word; ">发票项目</td>
                <td align="left" width="150px" style="word-wrap:break-word; ">发票金额</td>
                <td align="left" width="150px" style="word-wrap:break-word; ">操作</td>
            </tr>
         <?php 
         if($salaryTimeList){
         	foreach ($salaryTimeList as $row){
         		foreach ($row as $key=>$value){
         			if($key!='time'||$key==0){
         				echo' <tr>    
         				   <td align="left" width="150px" style="word-wrap:break-word;">'.$value['company_name'].'</td>
                           <td align="left" width="150px" style="word-wrap:break-word;">'.$value['salaryTime'].'</td>
         				   <td align="left" width="150px" style="word-wrap:break-word;">'.$value['bill_date'].'</td> ';
         				echo'<td align="left" width="150px" style="word-wrap:break-word;">'.$value['type'].'</td>';
         				echo'<td align="left" width="150px" style="word-wrap:break-word;">'.$value['bill_item'].'</td>';
         				echo'<td align="left" width="150px" style="word-wrap:break-word;">'.$value['bill_value'].'</td>';
         				echo'<td align="left" width="150px" style="word-wrap:break-word;"><a href="#" onclick="del(' .$value['id'].')" target="_self">删除</a>|<a href="#" onclick="update(' .$value['id'].')" target="_self">修改</a></td>';
         				echo '</tr>';
         			}
         		
         		}
         	}
         }
         ?>
          </table>
        </div>
    </div>
    </div>
  </body>
</html>