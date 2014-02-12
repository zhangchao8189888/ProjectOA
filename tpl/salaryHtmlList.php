<?php
$errorMsg = $form_data ['error'];
// $succ=$form_data['succ'];
$salarylist = $form_data ['salarylist'];
session_start ();
$_SESSION ['salarylist'] = $salarylist;
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
// var_dump($salarylist);
// exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>文件内容查看</title>
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
        $(document).ready(function(){
    		  hiddenDiv();
        });
	  function a(){
	  $("#iform").attr("action","__ROOT__/Black/blacklistexport"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","index.php?action=Salary&mode=sumSalary"); 
	  $("#iform").submit();
	  }
	  function nian_b(){
	  $("#iform_nian").attr("action","index.php?action=Salary&mode=sumNianSalary"); 
	  $("#iform_nian").submit();
	  }
	  function nian_er(){
		  $("#iform_er").attr("action","index.php?action=Salary&mode=sumErSalary"); 
		  $("#iform_er").submit();
		  }
	  <?php if($checkType){?>
	   
	  function hiddenDiv(){
		  var salType="<?php echo $checkType;?>";
		  if(salType=="first"){
			  $("#nian").hide();
			  $("#second").hide();
		  }else if(salType=="nian"){
			  $("#first").hide();
			  $("#second").hide();
		  }else if(salType=="second"){
			  $("#nian").hide();
			  $("#first").hide();
		  }

	  }
	  
	  <?php }?>
	  function goBack(){
		  $("#iform").attr("action","index.php?action=Service&mode=makeSal"); 
		  $("#iform").submit();
	  }
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
				<span style="font-size: 12px; color: red">如果选择多项请用"+"号隔开</span>
				<p>注：</p>
			</div>
			<!--功能项-->
			<div id="first" class="manage"
				style="word-wrap: break-word; background-color: Tan;">
				<form enctype="multipart/form-data" id="iform"
					action="index.php?action=Salary&mode=sumSalary" method="post">
					选择身份证：<input type="text" name="shenfenzheng" value="3" style="width: 30"/> 选择相加项：<input
						type="text" name="add" value="4" style="width: 30"/> 选择相减项：<input type="text"
						name="del" value="" style="width: 30"/> 免税项：<input type="text" name="freeTex"
						value="" size="3" /> 实发扣减项：<input type="text" name="shifajian"
						value="" size="3" /> <input type="hidden" value="" id="datas"
						name="datas[]" /> <input type="hidden" name="comId" id="comId"
						value="<?php if($companyId)echo $companyId;?>" /> <input
						type="hidden" name="sDate" id="sDate"
						value="<?php if($salDate)echo $salDate;?>" /> <input type="hidden"
						name="salType" id="salType"
						value="<?php if($checkType)echo $checkType;?>" /> <input
						type="hidden" name="companyName" id="companyName"
						value="<?php if($companyName)echo $companyName;?>" /> <input
						type="button" value="普通工资计算" onclick="b()" /> <font color="red"><?php if($errorMsg)echo $errorMsg?></font>
					<font color="green"><?php if($succ)echo $succ?></font>
				</form>
			</div>
			<div id="nian" class="manage"
				style="word-wrap: break-word; background-color: red;">
				<form enctype="multipart/form-data" id="iform_nian"
					action="index.php?action=Salary&mode=sumSalary" method="post">
					选择身份证：<input type="text" name="shenfenzheng_nian" value="3" style="width: 30"/>
					发年终奖月份（2012-01-01）：<input type="text" name="salaryTime_nian"
						value="<?php if($salDate)echo $salDate;?>" /> 年终奖项：<input
						type="text" name="nian" value="4" style="width: 30"/> 是否做过本月一次工资：<select
						name="isFirst"><option value="1">是</option>
						<option value="0">否</option></select> <input type="hidden"
						value="" id="datas" name="datas[]" /> <input type="hidden"
						name="comId" id="comId"
						value="<?php if($companyId)echo $companyId;?>" /> <input
						type="hidden" name="sDate" id="sDate"
						value="<?php if($salDate)echo $salDate;?>" /> <input type="hidden"
						name="salType" id="salType"
						value="<?php if($checkType)echo $checkType;?>" /> <input
						type="hidden" name="companyName" id="companyName"
						value="<?php if($companyName)echo $companyName;?>" /> <input
						type="button" value="年终奖计算" onclick="nian_b()" /> <font
						color="red"><?php if($errorMsg)echo $errorMsg?></font> <font
						color="green"><?php if($succ)echo $succ?></font>
				</form>
			</div>
			<div id="second" class="manage"
				style="word-wrap: break-word; background-color: yellow;">
				<form enctype="multipart/form-data" id="iform_er"
					action="index.php?action=Salary&mode=sumSalary" method="post">
					选择身份证：<input type="text" name="shenfenzheng_er" value="3" style="width: 30"/>
					二次工资月份（2012-01-01）：<input type="text" name="salaryTime_er"
						value="<?php if($salDate)echo $salDate;?>" /> 相加项：<input
						type="text" name="add" value="4" style="width: 30"/> <input type="hidden" value=""
						id="datas" name="datas[]" /> <input type="hidden" name="comId"
						id="comId" value="<?php if($companyId)echo $companyId;?>" /> <input
						type="hidden" name="sDate" id="sDate"
						value="<?php if($salDate)echo $salDate;?>" /> <input type="hidden"
						name="salType" id="salType"
						value="<?php if($checkType)echo $checkType;?>" /> <input
						type="hidden" name="companyName" id="companyName"
						value="<?php if($companyName)echo $companyName;?>" /> <input
						type="button" value="二次工资计算" onclick="nian_er()" /> <font
						color="red"><?php if($errorMsg)echo $errorMsg?></font> <font
						color="green"><?php if($succ)echo $succ?></font>
				</form>
			</div>
			<div style="min-width: 830px">

				<table id="tab_list" class="table_list" width="100%">

                    <?php
                    echo '<tr onmouseover="" onmouseout="">';
                    for ($j = 0; $j < count($salarylist ['Sheet1'] [0]); $j++) {
                        // if($salarylist[Sheet1][$i][$j]!=""){
                        echo '<td><div><font color="green">' . ($j + 1) . '</font></div></td>';
                        // }
                    }
                    echo "</tr>";
                    for ($i = 0; $i < count($salarylist ['Sheet1']); $i++) {
                        echo '<tr onmouseover="" onmouseout="">';
                        for ($j = 0; $j < count($salarylist ['Sheet1'] [$i]); $j++) {
                            // if($salarylist[Sheet1][$i][$j]!=""){
                            echo '<td><div>' . $salarylist ['Sheet1'] [$i] [$j] . '</div></td>';
                            // }
                        }
                        echo "</tr>";
                    }
                    ?>

                </table>

			</div>
		</div>
	</div>
</body>
</html>