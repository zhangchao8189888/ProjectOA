<?php 
session_start();
$errorMsg=$_SESSION['error'];
$excelList=$_SESSION['salaryTimeList'];
$salarySumTimeList=$_SESSION['salarySumTimeList'];
$salaryPO=$_SESSION['salaryPO'];
$excelList=$_SESSION['excelList'];
$_SESSION['salType']="excel";
//exit;
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
       <script src="common/js/jquery.js" type="text/javascript"></script>  
        <script language="javascript" type="text/javascript">
	  function a(){
	  $("#iform").attr("action","/zhongqiOA/import.php");
	  $("#iform").submit();
	  }
	  function b(){
	  $("#iform").attr("action","/zhongqiOA/import.php");
	  $("#iform").submit();
	  }
	  function save(ofname){
		  
		  $("#botton").attr("style","display:block"); 
		  $("#cbotton").attr("style","display:block"); 
		  //$("#ofname").val(ofname);
		  $("#salaryTime").attr("style","display:block"); 
		  $("#comname").attr("style","display:block"); 
		  }
	 function cancel(){
          $("#botton").attr("style","display:none"); 
          $("#cbotton").attr("style","display:none"); 
		  $("#salaryTime").attr("style","display:none"); 
		  $("#comname").attr("style","display:none"); 
		 }
	 function updateMark(){
		 $("#iformMark").attr("action","index.php?action=Salary&mode=updateSalMark"); 
		  $("#iformMark").submit(); 

		 }
        </script>
  </head>
  <body>
    <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
    <div class="postbody"><div id="cnblogs_post_body"><script type="text/javascript">// <![CDATA[
		$(document).ready(function () {
			FixTable("MyTable", 3, 1090, 500);
		});

		function FixTable(TableID, FixColumnNumber, width, height) {
			if ($("#" + TableID + "_tableLayout").length != 0) {
				$("#" + TableID + "_tableLayout").before($("#" + TableID));
				$("#" + TableID + "_tableLayout").empty();
			}
			else {
				$("#" + TableID).after("<div id='" + TableID + "_tableLayout' style='overflow:hidden;height:" + height + "px; width:" + width + "px;'></div>");
			}

			$('<div id="' + TableID + '_tableFix"></div>'
			+ '<div id="' + TableID + '_tableHead"></div>'
			+ '<div id="' + TableID + '_tableColumn"></div>'
			+ '<div id="' + TableID + '_tableData"></div>').appendTo("#" + TableID + "_tableLayout");


			var oldtable = $("#" + TableID);

			var tableFixClone = oldtable.clone(true);
			tableFixClone.attr("id", TableID + "_tableFixClone");
			$("#" + TableID + "_tableFix").append(tableFixClone);
			var tableHeadClone = oldtable.clone(true);
			tableHeadClone.attr("id", TableID + "_tableHeadClone");
			$("#" + TableID + "_tableHead").append(tableHeadClone);
			var tableColumnClone = oldtable.clone(true);
			tableColumnClone.attr("id", TableID + "_tableColumnClone");
			$("#" + TableID + "_tableColumn").append(tableColumnClone);
			$("#" + TableID + "_tableData").append(oldtable);

			$("#" + TableID + "_tableLayout table").each(function () {
				$(this).css("margin", "0");
			});


			var HeadHeight = $("#" + TableID + "_tableHead thead").height();
			HeadHeight += 2;
			$("#" + TableID + "_tableHead").css("height", HeadHeight);
			$("#" + TableID + "_tableFix").css("height", HeadHeight);


			var ColumnsWidth = 0;
			var ColumnsNumber = 0;
			$("#" + TableID + "_tableColumn tr:last td:lt(" + FixColumnNumber + ")").each(function () {
				ColumnsWidth += $(this).outerWidth(true);
				ColumnsNumber++;
			});
			ColumnsWidth += 2;
			if ($.browser.msie) {
				switch ($.browser.version) {
					case "7.0":
						if (ColumnsNumber >= 3) ColumnsWidth--;
						break;
					case "8.0":
						if (ColumnsNumber >= 2) ColumnsWidth--;
						break;
				}
			}
			$("#" + TableID + "_tableColumn").css("width", ColumnsWidth);
			$("#" + TableID + "_tableFix").css("width", ColumnsWidth);


			$("#" + TableID + "_tableData").scroll(function () {
				$("#" + TableID + "_tableHead").scrollLeft($("#" + TableID + "_tableData").scrollLeft());
				$("#" + TableID + "_tableColumn").scrollTop($("#" + TableID + "_tableData").scrollTop());
			});

			$("#" + TableID + "_tableFix").css({ "overflow": "hidden", "position": "relative", "z-index": "50", "background-color": "Silver" });
			$("#" + TableID + "_tableHead").css({ "overflow": "hidden", "width": width - 17, "position": "relative", "z-index": "45", "background-color": "Silver" });
			$("#" + TableID + "_tableColumn").css({ "overflow": "hidden", "height": height - 17, "position": "relative", "z-index": "40", "background-color": "Silver" });
			$("#" + TableID + "_tableData").css({ "overflow": "scroll", "width": width, "height": height, "position": "relative", "z-index": "35" });

			$("#" + TableID + "_tableFix").offset($("#" + TableID + "_tableLayout").offset());
			$("#" + TableID + "_tableHead").offset($("#" + TableID + "_tableLayout").offset());
			$("#" + TableID + "_tableColumn").offset($("#" + TableID + "_tableLayout").offset());
			$("#" + TableID + "_tableData").offset($("#" + TableID + "_tableLayout").offset());

			if ($("#" + TableID + "_tableHead").width() > $("#" + TableID + "_tableFix table").width()) {
				$("#" + TableID + "_tableHead").css("width", $("#" + TableID + "_tableFix table").width());
				$("#" + TableID + "_tableData").css("width", $("#" + TableID + "_tableFix table").width() + 17);
			}
			if ($("#" + TableID + "_tableColumn").height() > $("#" + TableID + "_tableColumn table").height()) {
				$("#" + TableID + "_tableColumn").css("height", $("#" + TableID + "_tableColumn table").height());
				$("#" + TableID + "_tableData").css("height", $("#" + TableID + "_tableFix table").height() + 17);
			}
		}
		
// ]]></script>

		
        <!--导航栏-->
        <div class="navigate">{$navigate}</div>
        <div class="manage">
        <font color="red">导入文件类型必须是.xls类型</font>
        </div>
        <!--功能项-->
        <div class="manage">
       <form enctype="multipart/form-data" id="iform" action="/zhongqiOA/index.php?action=Salary&mode=sumSalary" method="post">
			导出文件名称：<input type="text" name="name" value=""/> 
			<input type="button" value="导出" onclick="a()"/>
			<font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font>
			<a href="index.php?action=Salary&mode=toSalListExcel&modes=html"><font>切换模式</font></a>
       	</form>
         </div>
        <div>
        <form enctype="multipart/form-data" id="iformMark" action="/zhongqiOA/index.php?action=Salary&mode=sumSalary" method="post">
		备 &nbsp  注：&nbsp&nbsp&nbsp&nbsp  
         <textarea name="mark" cols="100" rows="3" class="text" id="mark"><?php if($salaryPO['mark']) echo $salaryPO['mark'];?></textarea>
         <input type="hidden" name="salTimeId" id="salTimeId"  value="<?php if($salaryPO['id']) echo $salaryPO['id'];?>"/>
         <input type="button" value="修改备注" onclick="updateMark()"/>
        </form>
        </div>
        	<table style="width: 960px; font-family: 微软雅黑; color: #000000; font-size: 16; border-color: black;" id="MyTable" border="1" cellspacing="0" cellpadding="0">
        	<thead>

		<tr><th style="text-align: center; width: 80px;" rowspan="3">部门</th>
		<th style="text-align: center; width: 80px;" rowspan="3">姓名</th>
		<th style="text-align: center; width: 80px;" rowspan="3">身份证</th>
		<th style="text-align: center;" colspan="<?php echo (count($excelList[0])+20);?>">中企基业</th></tr>
		</tr>
		<tr>  
         <?php 
         $x=0;
        foreach ($excelList[0] as $key=>$value) {
        	if($x>=3){
        	if($key!="guding_salary"&&$key!="log"){
        		
             echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$key.'</td>';
        	
        	}
        		
        	}
        	$x++;
        	
        	
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
        <td align="left" width="300px" style="word-wrap:break-word;">备注</td>
        </tr>
		</thead>
		<tbody>
		 
           <?php 
          // var_dump($excelList);
         $count=count($excelList[0]);
           for ($i=0;$i<count($excelList);$i++)
		{
			echo '<tr onmouseover="" onmouseout="">';
			 foreach ($excelList[$i] as $key=>$value) {
			{
				if($key!="guding_salary"&&$key!="log"){
					echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value.'</td>';
				}
				if($key=="guding_salary"){
				for($j=3;$j<count($value);$j++){
					if(!empty($value[$j])){
						echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$value[$j].'</td>';
					}
				}
				}
			if($key=="log"){
						echo '<td align="left" width="300px" style="word-wrap:break-word;">'.$value.'</td>';
			}
			}
			}
			echo "</tr>";
		}
           ?>
       <tr onmouseover="" onmouseout="">
      <?php 
       echo '<td align="left" width="150px" style="word-wrap:break-word;">合计</td>';
        for ($i=3;$i<count($excelList[0]);$i++) {
             echo '<td align="left" width="150px" style="word-wrap:break-word;"> </td>';
        }
        $row=mysql_fetch_array($salarySumTimeList) ;
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_yingfaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_shiye'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_yiliao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_yanglao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_gongjijin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_daikoushui'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_koukuangheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_per_shifaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_shiye'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_yiliao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_yanglao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_gongshang'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_shengyu'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_gongjijin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_com_heji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_laowufei'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_canbaojin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_danganfei'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$row['sum_paysum_zhongqi'].'</td>';
        ?>
        </tr>
		</tbody>
		</table>

		</table>
        
    </div>
    </div>
  </body>
</html>