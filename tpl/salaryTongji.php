<?php
$errorMsg = $form_data ['error'];
$comList = $form_data ['comList'];
$salaryTimeList = $form_data ['salaryTimeList'];
$billState = $form_data ['billState'];
$billType = $form_data ['billType'];
$warn = $form_data ['warn'];
if (empty ( $warn )) {
	$warn = "";
}
// var_dump($salaryTimeList);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- -->
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript"
	src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="common/js/jquery.pagination.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript">
	  function a(){
	  $("#iform").attr("action","index.php?action=SalaryBill&mode=searchSalaryTongji"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/zhongqiOA/import.php");
	  $("#iform").submit();
	  }
	  function send(eid){
		  if(confirm('确定要发放工资吗?')){
		  $("#iform").attr("action","index.php?action=SalaryBill&mode=salarySend"); 
		  $("#timeid").val(eid)
		  $("#iform").submit();
		  //
		  }
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
	    function update(timeid,jine){
          $("#divs").show();
          $("#leijiyue").val(jine);
          $("#updateId").val(timeid)
		    }
	    function updateYue(){
		    var timeid=$("#updateId").val();
		    var str=$("#updateId").val()+"_text";
	    	$.ajax(
	                {
	                   type: "POST",
	                   url: "index.php?action=SalaryBill&mode=updateLeijiYueByTimeId",
	                   data: "leijie="+$("#leijiyue").val()+"&timeId="+$("#updateId").val(),
	                   success: function(msg)
	                            {
	                      var result=msg.split("$");
	                     // alert(result);
	                      if(result[1]=="ok"){
		                      alert('修改成功');
		                      $("#"+str).text($("#leijiyue").val());
		                      }else{
		                    	  alert('修改失败');
			                      }      
	                            }
	            }
	            );

		    }
        </script>
</head>
<body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width: 960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
			<!--导航栏-->
			<div class="navigate">工资统计</div>
			<!--功能项-->
			<div class="search">
				<form enctype="multipart/form-data" id="iform" action=""
					method="post">
					<div style="float: left;">
						单位名称：<input type="text" name="comName" id="comName" /><input
							type="button" value="查询公司列表" onclick="getCompanyByName()" />
					</div>
					<div style="float: left;">
						<select name="cid" id="cid" size="5">
							<option value="-1">请选择公司名称</option>
						</select> <input type="hidden" id="timeid" name="timeid" value="" />
						<input type="button" class="btn_submit" value="查询" onclick="a()" />
						<font color="red"><?=$warn?></font> <font color="green"></font>
					</div>
				</form>
			</div>
			<div style="display: none;" id="divs">
				<input type="hidden" id="updateId" name="updateId" value="" /><input
					type="text" id="leijiyue" name="leijiyue" value="" /><input
					type="button" class="btn_submit" value="累计余额修改"
					onclick="updateYue()" />
			</div>

			<div
				style="width: 100%; overflow-x: scroll; dispaly: inline; white-space: nowrap;">
				<table id="tab_list" class="table_list" cellpadding="2"
					cellspacing="1" style="table-layout: fixed;">
					<tr>
						<td align="left" width="150px" style="word-wrap: break-word;">序号</td>
						<td align="left" width="300px" style="word-wrap: break-word;">单位</td>
						<td align="left" width="150px" style="word-wrap: break-word;">单位工资表月份</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: red;">发票日期</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: red;">发票项目</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: red;">发票金额</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: red;">发票金额合计</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: green;">支票日期</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: green;">支票金额</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: green;">支票金额合计</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: blue;">支票到账日期</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: blue;">支票到账金额</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: blue;">到账金额合计</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">个人应发合计</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">个人失业</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">个人医疗</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">个人养老</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">个人公积金</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">代扣税</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">个人扣款合计</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">实发合计</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">单位失业</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">单位医疗</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">单位养老</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">单位工伤</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">单位生育</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">单位公积金</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">单位合计</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tan;">交中企基业合计</td>
						<td align="left" width="150px" style="word-wrap: break-word;">本月余额</td>
						<td align="left" width="150px" style="word-wrap: break-word;">累计余额</td>
						<td align="left" width="150px"
							style="word-wrap: break-word; background-color: Tomato;">状态</td>
						<td align="left" width="300px"
							style="word-wrap: break-word; background-color: Tomato;">操作</td>
					</tr>
         <?php
									if ($salaryTimeList) {
										for($i = 0; $i < count ( $salaryTimeList ); $i ++) {
											for($j = 0; $j < count ( $salaryTimeList [$i] ); $j ++) {
												echo $salaryTimeList [$i] [$j];
											}
											// echo '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" '. $count['count'].'"><a href="#" onclick="updateYu()" target="_self">修改</a></td>';
										}
									}
									?>
          </table>
			</div>
		</div>
	</div>
</body>
</html>