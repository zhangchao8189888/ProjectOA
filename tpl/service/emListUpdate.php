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
			  if($("#shenfenzheng_emp").val()==''){
				  alert('填入身份证位置');
				  return;
				  }
			  $("#iform").attr("action","/zhongqiOA/index.php?action=Service&mode=updateEmpList");
			  $("#iform").submit();
	  
	  }
        </script>
  </head>
  <body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate">员工字段批量修改</div>
         <form enctype="multipart/form-data" id="iform" action="/zhongqiOA/index.php?action=Service&mode=updateEmpList" method="post">
        <div><font color="red">如果员工较多建议100为单位修改,以身份证为查询条件</font></div>
        <div class="manage">
                  身份证：<input type="text" name="shenfenzheng" id="shenfenzheng_emp" value="" size="1"/> 
			姓名：<input type="text" name="name" value="" size="1"/> 
			银行卡号：<input type="text" name="eno" value="" size="1"/> 
			开户行：<input type="text" name="bank" value="" size="1"/> 
			身份类别：<input type="text" name="etype" value="" size="1"/> 
			社保基数：<input type="text" name="shebao" value="" size="1"/> 
        </div>
        <!--功能项-->
        <div class="manage">
      	
			公积金基数：<input type="text" name="gongjijin" value="" size="1"/> 
			残保费：<input type="text" name="canbaofei" value="" size="1"/> 
			劳务费：<input type="text" name="laowufei" value="" size="1"/> 
			档案费：<input type="text" name="danganfei" value="" size="1"/> 
			合同年限：<input type="text" name="nianxian" value="" size="1"/>
			起始日期：<input type="text" name="qishi" value="" size="1"/>
			<input type="button" value="批量修改字段" onclick="b('emp')"/>
       	
         </div>
         </form>
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