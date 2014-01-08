<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$emList=$form_data['emList'];
$cname=$form_data['cname'];
$e_stat=$form_data['e_stat'];
//$errorList=$form_data['errorlist'];
//session_start();
//$_SESSION['excelList']=$excelList;

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
	  function getEmpByType(){
	  $("#iform").attr("action","index.php?action=Service&mode=getEmList"); 
	  $("#iform").submit();
	  }
	  function improtEmp(){
	  $("#iform").attr("action","index.php?action=Employ&mode=toimport"); 
	  $("#iform").submit();
	  }
	  function del(eid){
		  $("#iform").attr("action","index.php?action=Employ&mode=delEm"); 
		  $("#eid").val(eid)
		  $("#mode").val('service')
		  $("#iform").submit();
		  //
		  }
	  function lizhi(){
		  $("#iform").attr("action","index.php?action=Service&mode=lizhiEm"); 
		  $("#eid").val(eid)
		  $("#mode").val('service')
		  $("#iform").submit();
		  }
	 function toHetongdaoqi(){
		 $("#iform").attr("action","index.php?action=Service&mode=toHedaoqi"); 
		  $("#iform").submit()
		 }
		function returnList(){
			
			$("#iform").attr("action","index.php?action=Service&mode=getAdminComList"); 
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
        <div class="navigate">员工详细表</div>
        <div class="manage">
        <font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font>
        </div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="index.php?action=Service&mode=toEmployAdd" method="post"> 
       <input type="hidden" value="<?php echo $cname;?>" name="comname"/>
       <input type="hidden" id="eid" name="eid" value=""/> 
       <input type="hidden" value="" name="modeType" id="mode"/>
			 <input type="submit" value=" 添加员工 " id="btn_ok" align="left" class="btn_submit" />
			  <input type="button" value=" 批量导入员工 " id="btn_ok" align="left" onclick="improtEmp()" class="btn_submit" />
			  <a href="#" onclick="toHetongdaoqi()"><font color="green">2.合同到期提示</font></a></br>
			<div align="right"> <select name="emState" onchange="getEmpByType()">
			 <option  value="0" <?php if($e_stat==0){echo 'selected';}?>>正常</option>
			 <option value="1" <?php if($e_stat==1){echo 'selected';}?>>离职</option>
			 <option value="2" <?php if($e_stat==2){echo 'selected';}?>>合同到期</option>
			 <option value="-1"<?php if($e_stat==-1){echo 'selected';}?>>新增</option>
			 </select>
			 </div>
       	</form>
         </div>
         <div style="width:100%;overflow-x:scroll;dispaly:inline;white-space:nowrap;">
 <table id="tab_list" class="table_list" cellpadding="2" >
  <tr>          
                <th  width="150px" style="word-wrap:break-word;"><div>序号</div></th>
                <th  width="150px" style="word-wrap:break-word;"><div>姓名</div></th>
                <th  width="150px" style="word-wrap:break-word;"><div>单位名称</div></th>
                <th  width="150px" style="word-wrap:break-word;"><div>身份证号</div></th>
                <th  width="150px" style="word-wrap:break-word;"><div>身份类别</div></th>
                <th  width="150px" style="word-wrap:break-word;"><div>合同期限</div></th>
                <th  width="150px" style="word-wrap:break-word;"><div>合同日期</div></th>
                <th  width="150px" style="word-wrap:break-word;"><div>备注</div></th>
                <?php if($e_stat==2){?><th  width="150px" style="word-wrap:break-word;"><div>合同到期日</div></th>
                <?php }?>
                <th  width="150px" style="word-wrap:break-word;"><div >操作</div></th>
                
            </tr>
           <?php 
           if($e_stat!=2){ if($emList){
           	$i=1;
           	  while ($row=mysql_fetch_array($emList) ){
           	  //var_dump($row);
           	  	?>
            <tr >
                
                <td><div><?php  echo $i;?></div></td>
                <td><div><a href="index.php?action=Service&mode=getEmp&eid=<?php  echo $row['id'];?>" target="_self"><?php echo $row['e_name'];?></a></div></td>
                <td><div><?php echo $row['e_company'];?></div></td>
                <td><div><?php echo $row['e_num'];?></div></td>
                 <td style="word-wrap:break-word;"><div><?php echo $row['e_type'];?></div></td>
                <td style="word-wrap:break-word;"><div ><?php echo $row['e_hetongnian']."年";?></div></td>
                <td><div><?php echo $row['e_hetong_date'];?></div></td>
                <td><div><?php echo $row['memo'];?></div></td>
                <td><div><a href="#" onclick="del(<?php  echo $row['id'];?>)" target="_self">删除</a>|<a href="#" onclick="lizhi(<?php  echo $row['id'];?>)" target="_self">离职</a></div></td>
                
            </tr>
<?php $i++;
           	  }?>
<?php }}else{
	$i=1;
           	  foreach ($emList as $row) {
	?>
	
	 <tr >
                
                <td><div><?php  echo $i;?></div></td>
                <td><div><a href="index.php?action=Service&mode=getEmp&eid=<?php  echo $row['id'];?>" target="_self"><?php echo $row['e_name'];?></a></div></td>
                <td><div><?php echo $row['e_company'];?></div></td>
                <td><div><?php echo $row['e_num'];?></div></td>
                 <td><div><?php echo $row['e_type'];?></div></td>
                <td><div><?php echo $row['e_hetongnian']."年";?></div></td>
                <td><div><?php echo $row['e_hetong_date'];?></div></td>
                <td><div><?php echo $row['memo'];?></div></td>
                <td style="background-color:red;"><div><?php echo $row['daoqiri'];?></div></td>
                <td><div><a href="#" onclick="del(<?php  echo $row['id'];?>)" target="_self">删除</a></div></td>
                
            </tr>
	
<?php $i++;}}?>
 </table>

        </div>
         <div class="submit">
                   <input type="button" value=" 返回 " id="btn_ok" class="btn_submit" onclick="returnList();"/>
               </div>
    </div>
    </div>
      
  </body>
</html>