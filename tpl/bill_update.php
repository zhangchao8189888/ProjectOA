<?php 
$errorMsg=$form_data['error'];
$succ=$form_data['succ'];
$salaryTime=$form_data['salaryTime'];
$salaryBill=$form_data['salaryBill'];
//var_dump($salList);
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
    $(document).ready(function(){
		});
    function update(){
        if($("#billValue").val()==''){
            alert('请输入金额');
            return;
            }
			$("#form1").submit();
    }
    </script>
  </head>
  <body>
     <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
            <!--导航栏            -->
            <div class="navigate"><?php echo $errorMsg;?></div>
            <div style="width:100%;overflow-x:scroll;dispaly:inline;white-space:nowrap;">
            <form id="form1" method="post" action="index.php?action=SalaryBill&mode=billUpdate">
            <table id="tab_list" class="table_list" cellpadding="2" cellspacing="1" style="table-layout:fixed;">
        <tr>  
         <td align="left" width="150px" style="word-wrap:break-word; background-color:red;">单位</td>
                <td align="left" width="150px" style="word-wrap:break-word; background-color:red;">单位工资表月份</td>
                <td align="left" width="150px" style="word-wrap:break-word; ">发票日期</td>
                <td align="left" width="150px" style="word-wrap:break-word; ">发票类型</td>
                <td align="left" width="150px" style="word-wrap:break-word; ">发票项目</td>
                <td align="left" width="150px" style="word-wrap:break-word; ">发票金额</td>
        </tr>
           <?php 
          // $salaryTime=$form_data['salaryTime'];
//$salaryBill=$form_data['salaryBill'];
        echo' <tr>    
         				   <td align="left" width="150px" style="word-wrap:break-word;">'.$salaryTime['company_name'].'</td>
                           <td align="left" width="150px" style="word-wrap:break-word;">'.$salaryTime['salaryTime'].'</td>
         				   <td align="left" width="150px" style="word-wrap:break-word;">'.$salaryBill['bill_date'].'</td> ';
         				echo'<td align="left" width="150px" style="word-wrap:break-word;">'.$salaryBill['type'].'</td>';
         				echo'<td align="left" width="150px" style="word-wrap:break-word;">'.$salaryBill['bill_item'].'</td>';
         				echo'<td align="left" width="150px" style="word-wrap:break-word;">'.$salaryBill['bill_value'].'</td>';
         				echo '</tr>';
           ?>
           <tr>
                              <td align="left" width="150px" style="word-wrap:break-word;"><?php echo $salaryTime['company_name']?>
                              <input  type="hidden"  name="eid" value="<?php echo $salaryBill["id"]?>"/>
                              </td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><?php echo $salaryTime['salaryTime']?></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><?php echo $salaryBill['bill_date']?></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><?php echo $salaryBill['type']?></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="billItem" name="billItem" maxlength="255" style="width:100px;float:left" value="<?php echo $salaryBill['bill_item']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="billValue" name="billValue" maxlength="255" style="width:100px;float:left" value="<?php echo $salaryBill['bill_value']?>" /></td>
                               </tr>
        </table>
        <div class="submit">
                   <input type="button" value=" 修改  " id="btn_ok" class="btn_submit" onClick="update();"/>
               </div>
          </form>
            </div>
        </div>
    </div>
  </body>
</html>