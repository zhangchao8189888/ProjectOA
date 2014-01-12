<?php 
$errorMsg=$form_data['error'];
$salaryTimeList=$form_data['comList'];
$year=$form_data['year'];
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
	  $("#iform").attr("action","index.php?action=SalaryBill&mode=salaryComList"); 
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
        </script>
  </head>
  <body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate">报个税列表查询</div>
        <div class="manage">
        <font color="red"></font>
        </div>
        <!--功能项-->
        <div class="manage">
     <form enctype="multipart/form-data" id="iform" action="" method="post"> 
			年份查询：<select  name="year" id="year" onchange="getSalaryTimeByCompany()">
                                <option value="-1">请选择年份</option>
			<?php  
           	  for($i=2010;$i<2021;$i++){
           	  	if($year==$i){
           	  		echo '<option value="'.$i.'" selected>'.$i.'</option>';
           	  	}else{
           	  	echo '<option value="'.$i.'">'.$i.'</option>';
           	  	}
           	   }?>
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
                <th><div>单位名称</div></th>
                <th><div>一月</div></th>
                <th><div>二月</div></th>
                <th><div>三月</div></th>
                <th><div>四月</div></th>
                <th><div>五月</div></th>
                <th><div>六月</div></th>
                <th><div>七月</div></th>
                <th><div>八月</div></th>
                <th><div>九月</div></th>
                <th><div>十月</div></th>
                <th><div>十一月</div></th>
                <th><div>十二月</div></th>
                <th><div>年终奖</div></th>
            </tr>
           <?php  if($salaryTimeList){
           	  foreach ($salaryTimeList as $row) {
           	  //var_dump($row);
           	  	?>
            <tr >
                <td><div><a target="_self"><?php echo $row['name'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date1'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date2'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date3'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date4'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date5'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date6'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date7'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date8'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date9'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date10'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date11'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['date12'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['nian'];?></a></div></td>
            </tr>
<?php }?>
<?php }?>
 </table>
        </div>
    </div>
    </div>
  </body>
</html>