<?php 
$errorMsg=$form_data['errormsg'];
$succ=$form_data['succ'];
$salaryTimeList=$form_data['salaryTimeList'];
$salErList=$form_data['salErList'];
//var_dump($comList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
   <link href="common/css/validator.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
	    <script src="common/js/formValidator.js" type="text/javascript" charset="UTF-8"></script>
	    <script src="common/js/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script>
        <script language="javascript" type="text/javascript">
	  function a(){
		  $("#iform").attr("action","index.php?action=SaveSalary&mode=searchSalaryByOther"); 
		  $("#iform").submit();
		  }
   function add(type){
	   var aa="";
	   $("input[name='timeList']:checkbox:checked").each(function(){
	   aa+=$(this).val()+"*";
	   }) 
	   $("#timeid").val(aa);
	   if(type=='guding'){
		   $("#iform").attr("action","index.php?action=Finance&mode=salImportByCom");
		   }else if(type=='geshui'){
			   $("#iform").attr("action","index.php?action=Finance&mode=salGeShuiByCom");
			   }
	   
	   $("#iform").submit();
	   }
   function SelectAll() {
	   var checkboxs=document.getElementsByName("timeList");
	   for (var i=0;i<checkboxs.length;i++) {
	    var e=checkboxs[i];
	    e.checked=!e.checked;
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
			单位名称：<input type="text" id="comname" name="comname" value=""/> 
			工资月份：<input type="text" id="salaryTime" name="salaryTime" value=""/> 
			操作时间：<input type="text" id="opTime" name="opTime" value=""/> 
			<input type="hidden" id="timeid" name="timeid" value=""/>
			<input type="hidden" id="modeType" name="modeType" value="caiWuImport"/>
			<input type="button" value="查询" onclick="a()"/>
			<font color="red"><?=$warn?></font>
			<font color="green"></font>
       	</form> 
         </div>
         <div style="min-width:830px">
 <table id="tab_list" class="table_list" width="100%">
  <tr>          
                <th><div>操作<a href="#" onclick="SelectAll()">全选/反选</a></div></th>
                <th><div>单位名称</div></th>
                <th><div>工资月份</div></th>
                <th><div>保存工资日期</div></th>
                <th><div>工资类型</div></th>
            </tr>
           <?php  if($salaryTimeList){
           	  while ($row=mysql_fetch_array($salaryTimeList) ){
           	  //var_dump($row);
           	  	?>
            <tr >
                <td><div>
                	<input type="checkbox" name="timeList" value="<?php echo $row['id'];?>" >
                </div></td>
                <td><div><a href="index.php?action=SaveSalary&mode=searchSalaryById&id=<?php  echo $row['id'];?>" target="_blank"><?php echo $row['company_name'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['salaryTime'];?></a></div></td>
                <td><div><a target="_self"><?php echo $row['op_salaryTime'];?></a></div></td>
                <td><div><a target="_self">一次工资</a></div></td>
            </tr>
            
<?php 
           	  if(!empty($salErList[$row['companyId']])){
           	  	/**
           	  	 * CREATE TABLE `OA_salarytime_other` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salaryTime` date NOT NULL,
  `op_salaryTime` date DEFAULT NULL,
  `companyId` int(11) DEFAULT NULL,
  `salaryType` int(2) DEFAULT NULL,
  `op_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
           	  	 * @var unknown_type
           	  	 */
           	  	
           	  	$arrayList=$salErList[$row['companyId']][$row['salaryTime']];
           	  	foreach ($arrayList as $salOr){
           	  $sqlType=$salOr['salaryType'];
           	  if($sqlType==ER_SALARY_TIME_TYPE){
           	  	$arr="Er";
           	  	$type="<font  color=green>二次工资</font>";
           	  }elseif($sqlType==SALARY_TIME_TYPE){
           	  	$arr="Nian";
           	  	$type="<font  color=red>年终奖</font>";
           	  }
           	  	?>
           	  	<tr >
                <td><div>
                	<!--<input type="checkbox" name="timeList" value="<?php echo $row['id'];?>" >
                --></div></td>
                <td><div><a href="index.php?action=SaveSalary&mode=search<?php echo $arr;?>SalaryById&id=<?php  echo $salOr['id'];?>" target="_blank"><?php echo $row['company_name'];?></a></div></td>
                <td><div><a target="_self"><?php echo $salOr['salaryTime'];?></a></div></td>
                <td><div><a target="_self"><?php echo $salOr['op_salaryTime'];?></a></div></td>
                <td><div><a target="_self"><?php echo $type;?></a></div></td>
            </tr>
           	<?php  } }
           	  }}
           	?>
     
 </table>
        </div>
        <div class="submit">
                   <input type="button" value=" 多单位固定字段导出  " id="btn_ok" class="btn_submit" onClick="add('guding');"/>
              <input type="button" value="个税导出" id="btn_ok" class="btn_submit" onClick="add('geshui');"/>
               </div>
         <div class="submit">
                    </div>      
    </div>
    </div>
  </body>
</html>