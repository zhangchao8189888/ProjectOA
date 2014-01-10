<?php
$errorMsg = $form_data ['error'];
// $succ=$form_data['succ'];
// $jisanlist=$form_data['jisanlist'];
$excelList = $form_data ['excelList'];
// $this->objForm->setFormData("jisanlist",$jisuan_var);
// $this->objForm->setFormData("excelList",$salaryList[Sheet1]);
$errorList = $form_data ['errorlist'];
$comList = $form_data ['comlist'];
$shifajian = $form_data ['shifajian'];
$freeTex = $form_data ['freeTex'];
session_start ();
$_SESSION ['excelList'] = $excelList;
$checkType = $form_data ['checkType'];
if ($checkType) {
	$companyName = $form_data ['companyName'];
	$companyId = $form_data ['companyId'];
	$salDate = $form_data ['salDate'];
	if ($checkType == "first") {
		$salType = "一次工资";
	} elseif ($checkType == "second") {
		$salType = "二次工资";
	} elseif ($checkType == "nian") {
		$salType = "年终奖";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript"
	src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="common/js/jquery.pagination.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"
	src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript">
        $(document).ready(function(){
    		  hiddenDiv();
        });
	  function a(){
	  	$("#iform").attr("action","import.php"); 
	  	$("#iform").submit();
	  }
	  function b(){
		$("#iform").attr("action","import.php"); 
	  	$("#iform").submit();
	  }
	  function pu_bu(){
		  $("#iform_sal").attr("action","index.php?action=SaveSalary&mode=saveSalary"); 
		  $("#iform_sal").submit();
	  }

	  function nian_bu(){
		  $("#iform_sal").attr("action","index.php?action=SaveSalary&mode=saveNianSalary"); 
		  $("#iform_sal").submit();
	  }
	  function secon_bu(){
		  $("#iform_sal").attr("action","index.php?action=SaveSalary&mode=saveErSalary"); 
		  $("#iform_sal").submit();
      }
	  function save(ofname){
		  $("#botton").attr("style","display:block"); 
		  $("#botton_1").attr("style","display:block"); 
		  $("#botton_2").attr("style","display:block"); 
		  $("#cbotton").attr("style","display:block"); 
		  //$("#ofname").val(ofname);
		  $("#salaryTime").attr("style","display:block"); 
		  $("#comname").attr("style","display:block"); 
		  }
	 function cancel(){
          $("#botton").attr("style","display:none"); 
          $("#botton_1").attr("style","display:none"); 
		  $("#botton_2").attr("style","display:none");
          $("#cbotton").attr("style","display:none"); 
		  $("#salaryTime").attr("style","display:none"); 
		  $("#comname").attr("style","display:none"); 
		 }
	 function goBack(){
		  $("#iform").attr("action","index.php?action=Service&mode=makeSal"); 
		  $("#iform").submit();
	  }
	 <?php if($checkType){?>
	   
	  function hiddenDiv(){
		  var salType="<?php echo $checkType;?>";
		  if(salType=="first"){
			  $("#botton_er").hide();
			  $("#botton_nian").hide();
		  }else if(salType=="nian"){
			  $("#botton_pu").hide();
			  $("#botton_er").hide();
		  }else if(salType=="second"){
			  $("#botton_nian").hide();
			  $("#botton_pu").hide();
		  }

	  }
	  
	  <?php }?>
        </script>
</head>
<body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width: 960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
			<!--导航栏-->
			<div class="navigate"><?php if($checkType) echo "<font color=red size=3>客服操作当前步骤  $companyName ：$salDate 计算$salType</font>"?><input
					type="button" value="返回上一步骤" onclick="goBack()" />
			</div>
			<div class="manage">
				<p style="font-size: 12px; color: red">导出的文件是.xls（2003excel文件）</p>
			</div>
			<!--功能项-->
			<form enctype="multipart/form-data" id="iform"
				action="index.php?action=Salary&mode=sumSalary" method="post">
				<div class="manage">
					导出文件名称：<input type="text" name="name" value="" /> <input
						type="button" value="导出" onclick="a()" /> <font color="red"><?php if($errorMsg)echo $errorMsg?></font>
					<font color="green"><?php if($succ)echo $succ?></font> <input
						type="hidden" name="comId" id="comId"
						value="<?php if($companyId)echo $companyId;?>" /> <input
						type="hidden" name="sDate" id="sDate"
						value="<?php if($salDate)echo $salDate;?>" /> <input type="hidden"
						name="salType" id="salType"
						value="<?php if($checkType)echo $checkType;?>" />

				</div>
			</form>
			<!--功能项-->
			<form enctype="multipart/form-data" id="iform_sal"
				action="index.php?action=SaveSalary&mode=saveSalary" method="post">
				<div class="manage">
					工资年月：<font color="red">(2010-11-10)</font><input type="text"
						value="<?php if($salDate){echo $salDate;}?>" name="salaryTime"
						id="salaryTime" /> 公司名称：<select name="comname" id="comname"
						value="测试公司">
			<?php
			if ($comList) {
				while ( $row = mysql_fetch_array ( $comList ) ) {
					// var_dump($row);
					?>
           	  	<option value="<?php echo $row['company_name'];?>"
							<?php if($row['company_name']==$companyName){echo "selected";}?>><?php echo $row['company_name'];?></option>
           	  	<?php }?>
                <?php }?>
			</select> <input type="button" value="保存普通工资" id="botton_pu"
						onclick="pu_bu()" /> <input type="button" value="保存年终奖"
						id="botton_nian" onclick="nian_bu()" /> <input type="button"
						value="保存二次工资" id="botton_er" onclick="secon_bu()" /> <input
						type="hidden" id="shifajian" name="shifajian"
						value="<?php if(!empty($shifajian)){echo $shifajian;}?>" /> <input
						type="hidden" value="" id="freeTex" name="freeTex"
						value="<?php if(!empty($freeTex)){echo $freeTex;}?>" />
				</div>

				<div>
					备 &nbsp 注：&nbsp&nbsp&nbsp&nbsp
					<textarea name="mark" cols="100" rows="3" class="text" id="mark"></textarea>
				</div>
			</form>
			<div
				style="width: 100%; overflow-x: scroll; dispaly: inline; white-space: nowrap;">

				<table id="tab_list" class="table_list" cellpadding="2"
					cellspacing="1" style="table-layout: fixed;">

           <?php
											$count = count ( $excelList [0] );
											for($i = 0; $i < count ( $excelList ); $i ++) {
												echo '<tr onmouseover="" onmouseout="">';
												for($j = 0; $j < $count; $j ++) {
													/*
													 * if(!isset($excelList[$i][$j])){ echo '<td></td>'; }else{
													 */
													echo '<td align="left" width="150px" style="word-wrap:break-word;">' . $excelList [$i] [$j] . '</td>';
													// }
												}
												echo "</tr>";
											}
											?>


        </table>
			</div>
        <?php if($errorList[0]['error']!=""||$errorList[0]['error']!="第"){?>
 <table id="tab_list" class="table_list" width="30%">
				<tr>
					<th><div>错误信息</div></th>
				</tr>
           <?php foreach ($errorList as $row){?>
            <tr>

					<td><div><?php echo $row['error'];?></div></td>

				</tr>
<?php }?>
<?php }?>
 </table>

		</div>
	</div>
</body>
</html>