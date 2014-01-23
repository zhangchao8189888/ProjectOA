<?php
//var_dump($form_data);
   $opLogList=$form_data['opLogList'];
   $startIndex=$form_data['startIndex'];
   $total=$form_data['total'];
   $pageindex=$form_data['pageindex'];
   $pagesize=$form_data['pagesize'];
  global $loginusername;
  global $rootPath;
?> 
<html>
   <head>
    <title>日志查询</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
   <link href="common/css/validator.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
	    <script src="common/js/formValidator.js" type="text/javascript" charset="UTF-8"></script>
	    <script src="common/js/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script>
        <script language="javascript" type="text/javascript">
	$(function() {
		var amount = <?php echo $total ;?>;
		var rowsize = <?php echo $pagesize ;?>;
		var current_page = <?php echo $pageindex-1 ;?>;
		$(".common_page").pagination(amount, {
			items_per_page:rowsize,
			current_page:current_page,
			num_display_entries:10,
			num_edge_entries:1,
			prev_text:"<<上一页",
			next_text:"下一页>>",
			callback:changeTagPage
        });
    });
	function changeTagPage(page_id, jq) {
        var jumpurl = "index.php?action=Admin&mode=toOpLog&p="+(page_id+1);
		location = jumpurl;
        return false;
	}
</script>
<body>
 <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate">日志查询</div>
 <!--       <div class="manage">
        </div>
        <!--功能项
        <div class="manage">
         </div>
             <!--搜索栏
        <div class="search" >

        </div>
        -->
<div>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<th width="2%"><table border="0" cellspacing="5" cellpadding="1">
		    <?php include(ROOT."/common/tpl/left.php"); ?></table></th>
	  <td width="89%">
		<table cellpadding=4 cellspacing=0 width="100%">
          <tr class="form-action-bar">
            <td width="99%"><div align="center"><strong>操作日志查询列表</strong></div></td>            
          </tr>
        </table>
              <!-------------------------------------------------------------------------->
<form method="post" id="sform" name="main" action="">
<table cellspacing="0" id="tab_list" class="table_list" width="100%" cellpadding="0" class="rt">
                  <hr>
              <tr>
                <td style="width:15%;"><div>操作管理员名称</div></td>
                <td style="width:15%;"><div>操作对象</div></td>
                <td style="width:5%;"><div>操作名称</div></td>
                <td style="width:10%;"><div>操作时间</div></td>
                <td style="width:10%;"><div>明细</div></td>
              </tr>
                   <?php 
            foreach($opLogList as $row){
//$sql=	"select DRT.id task_run_id,DRT.task_id task_id,DT.task_name task_name,DT.creator_id creator_id,DA.name creator_name,DT.task_type task_type,DT.create_time reate_time,DT.auth_time auth_time from DM_Admin DA,DM_TASK DT,DM_Run_Task DRT where DT.id=DRT.task_id and DA.id=DT.creator_id and $where";
            ?>
            <tr>
                <td><?php  echo $row['name'];?></td>
                <td><?php echo $row['whatname'];?></td>
                <td><?php echo $row['subject'];?></td>
                <td><?php echo $row['time'];?></td>
                <?php 
                if($row['Subject']=='ADD_TEMPLATE'||$row['Subject']=='UPDATE_TEMPLATE'){
                	?>
                	<td><input type="button" onClick="return previewHtml_be(<?php echo $row['memo'];?>);" value="查看模版"></td>
                <?php 
                }else{
                ?>
                <td><?php echo $row['memo'];?></td>
                <?php 
                }
                ?>
            </tr>
  <?php 
           }
            ?>
                </table><br />
<!--分页-->
        <div class="bottom">
	        <div class="common_page"></div>
	        <div class="total_page">共 <span class="redtitle"><?php echo $total ;?></span> 条记录</div>
        </div>
</form></td>
                </tr>
              <!-------------------------------------------------------------------------->
            </table>
        </div>
        
    </div>
    </div>
  </body>
</html>