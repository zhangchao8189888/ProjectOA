<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$excelList=$form_data['salList'];
//$errorList=$form_data['errorlist'];
//session_start();
//$_SESSION['excelList']=$excelList;

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
		  if($('#empno').val()==''){
          alert("请输入身份证号");
          return;
			  }
	  $("#iform").attr("action","index.php?action=Salary&mode=toSalaryUpdate"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/zhongqiOA/import.php");
	  $("#iform").submit();
	  }
	  function update(eid,stId){
		  $("#iform").attr("action","index.php?action=Salary&mode=updateSalary"); 
		  $("#eid").val(eid)
		  $("#stId").val(stId)
		  $("#iform").submit();
		  //
		  }
        </script>
  </head>
  <body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate">员工列表查询</div>
        <div class="manage">
        <font color="red"></font>
        </div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="" method="post"> 
			身份证号：<input type="text" id="empno" name="empno" value=""/> 
			工资月份：<input type="text" id="salTime" name="salTime" value=""/> 
			<input type="hidden" id="eid" name="eid" value=""/> 
			<input type="hidden" id="stId" name="stId" value=""/> 
			<input type="button" value="查询" onclick="a()"/>
			<font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font>
       	</form>
         </div>

            <div style="width:100%;overflow-x:scroll;dispaly:inline;white-space:nowrap;">
  <table id="tab_list" class="table_list" cellpadding="2" cellspacing="1" style="table-layout:fixed;">
       
        <tr>  
        <td align="left" width="150px" style="word-wrap:break-word; background-color:red;">工资月份</td>
         <?php 
        foreach ($excelList[0] as $key=>$value) {
        	if($key!="guding_salary"){
        		
             echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$key.'</td>';
        	
        	}
        }
        ?>
        <td align="left" width="150px" style="word-wrap:break-word;">个人应发合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人失业</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人医疗</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人养老</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人公积金</td>
        <td align="left" width="150px" style="word-wrap:break-word;">代扣税</td>
        <td align="left" width="150px" style="word-wrap:break-word;">个人扣款合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">实发合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位失业</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位医疗</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位养老</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位工伤</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位生育</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位公积金</td>
        <td align="left" width="150px" style="word-wrap:break-word;">单位合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">劳务费</td>
        <td align="left" width="150px" style="word-wrap:break-word;">残保金</td>
        <td align="left" width="150px" style="word-wrap:break-word;">档案费</td>
        <td align="left" width="150px" style="word-wrap:break-word;">交中企基业合计</td>
        <td align="left" width="150px" style="word-wrap:break-word;">操作</td>
        </tr>
           <?php 
           $admin=$_SESSION['admin'];
           if($excelList){
         $count=count($excelList[0]);
           for ($i=0;$i<count($excelList);$i++)
		{
			echo '<tr onmouseover="" onmouseout="">';
			echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$excelList[$i]['guding_salary']['salaryTime'].'</td>';
			 foreach ($excelList[$i] as $key=>$value) {
			{
				if($key!="guding_salary"){
					echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value.'</td>';
				}
				if($key=="guding_salary"){
					 echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['per_yingfaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['per_shiye'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['per_yiliao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['per_yanglao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['per_gongjijin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['per_daikoushui'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['per_koukuangheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['per_shifaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['com_shiye'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['com_yiliao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['com_yanglao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['com_gongshang'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['com_shengyu'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['com_gongjijin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['com_heji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['laowufei'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['canbaojin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['danganfei'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value['paysum_zhongqi'].'</td>';
				}
			}
			}
			if($admin['admin_type']==1){
			echo ' <td><div><a href="#" onclick=update("'.$value['employid'].'","'.$value['stId'].'")  target="_self">修改</a></div></td>';
			}else{
			echo ' <td><div></td>';
			}
			echo "</tr>";
		}
           }
           ?>
        </table>
        </div>
    </div>
    </div>
  </body>
</html>