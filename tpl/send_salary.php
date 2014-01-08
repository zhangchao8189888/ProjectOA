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
	  $("#iform").attr("action","index.php?action=SalaryBill&mode=getSalaryTimeListByComId"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/companyOA/import.php"); 
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
			单位名称：<select  name="cid" id="cid" onchange="getSalaryTimeByCompany()">
                                <option value="-1">请选择公司名称</option>
			<?php  if($comList){
           	  while ($row=mysql_fetch_array($comList) ){
           	  	?>
           	  	<option value="<?php echo $row['id'];?>"><?php echo $row['company_name'];?></option>
           	  	<?php }?>
                <?php }?>
                </select>
			<input type="hidden" id="timeid" name="timeid" value=""/>
			<input type="button" value="查询" onclick="a()"/>
			<font color="red"><?=$warn?></font>
			<font color="green"></font>
       	</form> 
         </div>
         <div style="min-width:830px">
 <table id="tab_list" class="table_list" width="100%">
  <tr>          
                <th><div>操作</div></th>
                <th><div>单位名称</div></th>
                <th><div>工资月份</div></th>
                <th><div>保存工资日期</div></th>
                <th><div>工资状态</div></th>
            </tr>
           <?php  if($salaryTimeList){
           	  while ($row=mysql_fetch_array($salaryTimeList) ){
           	  //var_dump($row);
           	  	?>
            <tr >
                <td><div><a href="#" onclick="send('<?php  echo $row['id'];?>')" target="_self">发放</a></div></td>
                <td><div><a href="index.php?action=SaveSalary&mode=searchSalaryById&id=<?php  echo $row['id'];?>" target="_self"><?php echo $row['company_name'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['salaryTime'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['op_salaryTime'];?></a></div></td>
                <td><div><a target="_self"><?php echo $billState[$row['salary_state']];?></a></div></td>
            </tr>
<?php }?>
<?php }?>
 </table>
        </div>
    </div>
    </div>
  </body>
</html>