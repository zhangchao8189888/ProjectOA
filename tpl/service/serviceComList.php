<?php
$errorMsg = $form_data ['errormsg'];
$succ = $form_data ['succ'];
$salaryTimeList = $form_data ['salaryTimeList'];
// var_dump($comList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />
<link href="common/css/validator.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript"
	src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="common/js/jquery.pagination.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script src="common/js/formValidator.js" type="text/javascript"
	charset="UTF-8"></script>
<script src="common/js/formValidatorRegex.js" type="text/javascript"
	charset="UTF-8"></script>
<script language="javascript" type="text/javascript">
	  function a(){
		  $("#iform").attr("action","index.php?action=Service&mode=searchSalaryByOther"); 
		  $("#iform").submit();
		  }
   function add(){
	   var aa="";
	   $("input[name='timeList']:checkbox:checked").each(function(){
	   aa+=$(this).val()+"*";
	   }) 
	   $("#timeid").val(aa);
	   $("#iform").attr("action","index.php?action=Service&mode=addServiceCompany");
	   
	   $("#iform").submit();
	   }
	function returnList(){
		$("#iform").attr("action","index.php?action=Service&mode=getAdminComList"); 
		 $("#iform").submit();
		}
   </script>
</head>
<body>
    <?php require_once("tpl/commom/top.html"); ?>
    <div id="main" style="min-width: 960px">
    <?php require_once("tpl/commom/left.php"); ?>
    <div id="right">
			<!--导航栏-->
			<div class="navigate">
				员工列表查询 <input type="button" value=" 返回客服首页 " id="btn_ok"
					class="btn_submit" onclick="returnList();" />
			</div>
			<div class="manage">
				<font color="red"></font>
			</div>
			<!--功能项-->
			<div class="manage">
				<form enctype="multipart/form-data" id="iform" action=""
					method="post">
					单位名称：<input type="text" id="comname" name="comname" value="" /> <input
						type="hidden" id="timeid" name="timeid" value="" /> <input
						type="hidden" id="modeType" name="modeType" value="service" /> <input
						type="button" value="查询" onclick="a()" /> <font color="red"><?=$warn?></font>
					<font color="green"></font>
				</form>
			</div>
			<div style="min-width: 830px">
				<table id="tab_list" class="table_list" width="100%">
					<tr>
						<th><div>操作</div></th>
						<th><div>单位名称</div></th>
					</tr>
           <?php
											
if ($salaryTimeList) {
												while ( $row = mysql_fetch_array ( $salaryTimeList ) ) {
													// var_dump($row);
													?>
            <tr>
						<td><div>
								<input type="checkbox" name="timeList"
									value="<?php echo $row['id'];?>">
							
							</div></td>
						<td><div>
								<a
									href="index.php?action=SaveSalary&mode=searchSalaryById&id=<?php  echo $row['id'];?>"
									target="_self"><?php echo $row['company_name'];?></a>
							</div></td>
					</tr>
<?php }?>
<?php }?>
 </table>
			</div>
			<div class="submit">
				<input type="button" value=" 添加客服操作公司  " id="btn_ok"
					class="btn_submit" onClick="add();" />
			</div>

		</div>
	</div>
</body>
</html>