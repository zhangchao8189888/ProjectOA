<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
$salarylist=$form_data['salarylist'];
session_start();
$_SESSION['salarylist']=$salarylist;

//var_dump($salarylist);
//exit;
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
	  $("#iform").attr("action","__ROOT__/Black/blacklistexport"); 
	  $("#iform").submit();
	  }
	  function b(type){
		  if(type=='emp'){
			  if($("#shenfenzheng_emp").val()==''){
				  alert('填入身份证位置');
				  return;
				  }
			  $("#iform").attr("action","/zhongqiOA/index.php?action=Salary&mode=perZiliaoDuibi");
			  $("#iform").submit();
			  }else if(type=='com'){
				  if($("#shenfenzheng_com").val()==''){
					  alert('填入身份证位置');
					  return;
					  }
			   if($("#salTime").val()==''){
					  alert('填入工资时间');
					  return;
				 }
			  $("#iform_com").attr("action","/zhongqiOA/index.php?action=Salary&mode=salPerDuibi");
			  $("#iform_com").submit();
			  }else if(type=='jishu'){
				  if($("#shenfenzheng_jishu").val()==''){
					  alert('填入身份证位置');
					  return;
					  }
			  $("#iform_jishu").attr("action","/zhongqiOA/index.php?action=Salary&mode=jishuDuibi");
			  $("#iform_jishu").submit();
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
        <div class="navigate">{$navigate}</div>
        <div class="manage">
        <font color="red">如果选择多项请用"+"号隔开</font>
        </div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="/zhongqiOA/index.php?action=Salary&mode=perZiliaoDuibi" method="post">
			<font  style="word-wrap:break-word; background-color:red;">员工个人资料对比</font>
			身份证：<input type="text" name="shenfenzheng" id="shenfenzheng_emp" value="" size="1"/> 
			姓名：<input type="text" name="name" value="" size="1"/> 
			公司：<input type="text" name="com" value="" size="1"/> 
			银行卡号：<input type="text" name="eno" value="" size="1"/> 
			开户行：<input type="text" name="bank" value="" size="1"/> 
			身份类别：<input type="text" name="etype" value="" size="1"/> 
			社保基数：<input type="text" name="shebao" value="" size="1"/> 
			公积金基数：<input type="text" name="gongjijin" value="" size="1"/> 
			残保费：<input type="text" name="canbaofei" value="" size="1"/> 
			劳务费：<input type="text" name="laowufei" value="" size="1"/> 
			档案费：<input type="text" name="danganfei" value="" size="1"/> 
			<input type="button" value="对比" onclick="b('emp')"/>
       	</form>
         </div>
         <form enctype="multipart/form-data" id="iform_com" action="/zhongqiOA/index.php?action=Salary&mode=sumSalary" method="post">
         <div class="manage">
       	<font  style="word-wrap:break-word; background-color:red;">工资五险一金对比</font>
			身份证：<input type="text" name="shenfenzheng_com" id="shenfenzheng_com" value="" size="1"/>
			工资月份（<font color='red'>2011-01-01</font>）：<input type="text" name="salTime_com"  id="salTime" value="" />  
			个人失业：<input type="text" name="pershiye_com" value="" size="1"/> 
			个人医疗：<input type="text" name="peryiliao_com" value="" size="1"/> 
			个人养老：<input type="text" name="peryanglao_com" value="" size="1"/> 
			个人公积金：<input type="text" name="pergongjijin_com" value="" size="1"/>
         </div>
          <div class="manage">
			单位失业：<input type="text" name="comshiye_com" value="" size="1"/> 
			单位医疗：<input type="text" name="comyiliao_com" value="" size="1"/> 
			单位养老：<input type="text" name="comyanglao_com" value="" size="1"/> 
			单位工伤：<input type="text" name="comgongshang_com" value="" size="1"/> 
			单位生育：<input type="text" name="comshengyu_com" value="" size="1"/> 
			<input type="button" value="对比" onclick="b('com')"/>
			</div>
				</form>
            <div class="manage">
       <form enctype="multipart/form-data" id="iform_jishu" action="/zhongqiOA/index.php?action=Salary&mode=sumSalary" method="post">
			<font  style="word-wrap:break-word; background-color:red;">员工个人基数对比</font>
			身份证：<input type="text" name="shenfenzheng_jishu" id="shenfenzheng_jishu" value="" size="1"/>  
			失业基数：<input type="text" name="shiye_jishu" value="" size="1"/> 
			医疗基数：<input type="text" name="yiliao_jishu" value="" size="1"/> 
			养老基数：<input type="text" name="yanglao_jishu" value="" size="1"/> 
			生育基数：<input type="text" name="shengyu_jishu" value="" size="1"/> 
			公积金基数：<input type="text" name="gongjijin_jishu" value="" size="1"/>
			<input type="hidden" value="" id="datas"name="datas[]"/>
			<input type="button" value="对比" onclick="b('jishu')"/>
       	</form>
         </div> 
         <div style="min-width:830px">

        <table id="tab_list" class="table_list" width="100%">
           
           <?php 
           echo '<tr onmouseover="" onmouseout="">';
			for ($j=0;$j<count($salarylist['Sheet1'][0]);$j++)
			{
				//if($salarylist[Sheet1][$i][$j]!=""){
				echo '<td><div><font color="green">'.($j+1).'</font></div></td>';
				//}
			}
			echo "</tr>";
           for ($i=0;$i<count($salarylist['Sheet1']);$i++)
		{
			echo '<tr onmouseover="" onmouseout="">';
			for ($j=0;$j<count($salarylist['Sheet1'][$i]);$j++)
			{
				//if($salarylist[Sheet1][$i][$j]!=""){
				echo '<td><div>'.$salarylist['Sheet1'][$i][$j].'</div></td>';
				//}
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