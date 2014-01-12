<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$emList=$form_data['emList'];
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
	  function a(){
	  $("#iform").attr("action","index.php?action=Employ&mode=getEmList"); 
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/zhongqiOA/import.php");
	  $("#iform").submit();
	  }
	  function del(eid){
		  $("#iform").attr("action","index.php?action=Employ&mode=delEm"); 
		  $("#eid").val(eid)
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
			单位名称：<input type="text" id="comname" name="comname" value=""/> 
			姓名：<input type="text" id="empname" name="empname" value=""/> 
			身份证号：<input type="text" id="empno" name="empno" value=""/> 
			<input type="hidden" id="eid" name="eid" value=""/> 
			<input type="button" value="查询" onclick="a()"/>
			<font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font>
       	</form>
         </div>
         <div style="min-width:830px">

        <table id="tab_list" class="table_list" width="100%">

           <?php 
        /*   for ($i=0;$i<count($excelList);$i++)
		{
			echo '<tr onmouseover="" onmouseout="">';
			for ($j=0;$j<count($excelList[$i]);$j++)
			{
				echo '<td>'.$excelList[$i][$j].'</td>';
			}
			echo "</tr>";
		}*/
           ?>


        </table>
 <table id="tab_list" class="table_list" width="100%">
  <tr>          
                <th><div>操作</div></th>
                <th><div>姓名</div></th>
                <th><div>单位名称</div></th>
                <th><div>身份证号</div></th>
                <th><div>开户行</div></th>
                <th><div>银行卡号</div></th>
                <th><div>身份类别</div></th>
                <th><div>社保基数</div></th>
                <th><div>公积金基数</div></th>
                <th><div>劳务费</div></th>
                <th><div>残保金</div></th>
                <th><div>档案费</div></th>
                <th><div>备注</div></th>
            </tr>
           <?php  if($emList){
           	/*
           	 * CREATE TABLE `OA_employ` (
  `id` int(11) NOT NULL auto_increment,
  `e_name` varchar(20) NOT NULL default '',
  `e_company` varchar(40) character set utf8 collate utf8_unicode_ci NOT NULL,
  `e_num` varchar(40) character set utf8 collate utf8_unicode_ci NOT NULL,
  `bank_name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `bank_num` varchar(40) character set utf8 collate utf8_unicode_ci NOT NULL,
  `e_type` varchar(40) character set utf8 collate utf8_unicode_ci NOT NULL,
  `shebaojishu` int(10) NOT NULL default '0',
  `gongjijinjishu` int(10) NOT NULL default '0',
  `laowufei` int(10) NOT NULL default '0',
  `canbaojin` int(10) NOT NULL default '0',
  `danganfei` int(10) NOT NULL default '0',
  `memo` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `em_company_index` (`e_company`)
           	 */
           	  while ($row=mysql_fetch_array($emList) ){
           	  //var_dump($row);
           	  	?>
            <tr >
                <td><div><a href="#" onclick="del(<?php  echo $row['id'];?>)" target="_self">删除</a></div></td>
                <td><div><a href="index.php?action=Service&mode=getEmp&eid=<?php  echo $row['id'];?>" target="_self"><?php echo $row['e_name'];?></a></div></td>
                <td><div><?php echo $row['e_company'];?></div></td>
                <td><div><?php echo $row['e_num'];?></div></td>
                <td><div><?php echo $row['bank_name'];?></div></td>
                <td><div><?php echo $row['bank_num'];?></div></td>
                 <td><div><?php echo $row['e_type'];?></div></td>
                <td><div><?php echo $row['shebaojishu'];?></div></td>
                <td><div><?php echo $row['gongjijinjishu'];?></div></td>
                <td><div><?php echo $row['laowufei'];?></div></td>
                <td><div><?php echo $row['canbaojin'];?></div></td>
                <td><div><?php echo $row['danganfei'];?></div></td>
                <td><div><?php echo $row['memo'];?></div></td>
            </tr>
<?php }?>
<?php }?>
 </table>
        </div>
    </div>
    </div>
  </body>
</html>