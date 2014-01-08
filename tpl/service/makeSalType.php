<?php 
$typeList=$form_data['typeList'];
$succ=$form_data['succ'];
$company=$form_data['company'];
$date=$form_data['salDate'];
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
    $(document).ready(function(){
		});
    function getSalaryTimeByCompany(){
    	$("#salaryTime").html("<option value='-1'>请选择</option>");
    	$.ajax(
                {
                   type: "POST",
                   url: "index.php?action=SalaryBill&mode=getSalaryTimeById",
                   data: "comid="+$("#comname").val(),
                   success: function(msg)
                            {
                      var result=msg.split(",");
                             for(var i=1;i<result.length;i++){
                                 var obj=result[i].split("|");
                                 $("#salaryTime").append(" <option value="+obj[0]+">"+obj[1]+"</option>");
                                 }
                            }
            }
            );
    }
   function add(){
	   if($("#comname").val()=='-1'||$("#salaryTime").val()=='-1'||$("#billname").val()==""||$("#billval").val()==""){
		   alert("请填入完整信息！");
		   return;
		   }
	   $("#form1").submit();
	   }
	function salMake(id){
		  $("#form1").attr("action","index.php?action=Salary&mode=input"); 
		  $("#form1").submit(); 
				}
	function  addZhi(){
		 $("#form1").attr("action","index.php?action=SalaryBill&mode=toAddInvoice"); 
		  $("#form1").submit(); 
		}
	function tiaoguo(){
		if($("#salTimeId").val()==-1){
			alert("未做工资不能添加或跳过发票");
			return;
			}
		if(confirm('确定要跳过添加发票吗?')){
			 $("#form1").attr("action","index.php?action=Service&mode=tiaoguoFaPiao"); 
			 $("#form1").submit();
		}
		}
	function returnList(){
		$("#form1").attr("action","index.php?action=Service&mode=getAdminComList"); 
		 $("#form1").submit();
		}
    function addZhiDao(){
       //index.php?action=SalaryBill&mode=toAddCheque
    	$("#form1").attr("action","index.php?action=SalaryBill&mode=toAddCheque"); 
		 $("#form1").submit();
        }
    function improtEmp(){
    	  $("#form1").attr("action","index.php?action=Employ&mode=toimport"); 
    	  $("#form1").submit();
    	  }
   </script>
  </head>
  <body>
     <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
            <!--导航栏-->
            <div class="navigate">{$navigate}</div>
            <div class="form">
            <form id="form1" method="post" action="index.php?action=SalaryBill&mode=addInvoice">
               <div id="span_msg"><font color="red"><?php if($errorMsg)echo $errorMsg?></font>
               <input  type="hidden" name="comId" value="<?php echo $company['id']?>"/>
                <input  type="hidden" name="date" value="<?php echo $date?>"/>
                <input  type="hidden" name="salTimeId" id="salTimeId" value="<?php echo $typeList['sal']['salTimeId']?>"/>
			<font color="green"><?php if($succ)echo $succ?></font></div>
               <div class="input">
               		<table class="form_list" >
               		 <tr>
                           <td class="td_left" id="index"><label>公司名称：</label></td>
                           <td class="td_right" nowrap>
                              <font color='red'> <label style="word-wrap:break-word;background-color:Tan;"><?php echo $company['company_name'];?> </label></font> 
                            </td>
                           <td class="td_right">
                               <div id="e_noTip"></div>
                           </td>
                       </tr>
               		<tr>
                           <td class="td_left" id="index"><label>工资日期：</label></td>
                           <td class="td_right" nowrap>
                                <label style="word-wrap:break-word;background-color:Tan;"><?php echo $date;?>  </label> 
                          </td>
                           <td class="td_right">
                               <div id="e_noTip">
                                  </div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left"><label>一、导入员工：</label></td>
                           <td class="td_right" nowrap style="line-height:20px;">
                               <label><?php echo $typeList['importEmp']['html']?></label>
                               
                                 </td>
                           <td class="td_right">
                               <div id="nameTip"><?php echo $typeList['importEmp']['button']?></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>二、做工资：</label></td>
                           <td class="td_right" nowrap>
                                <label><?php echo $typeList['sal']['html']?></label>
                              
                                </td>
                           <td class="td_right">
                               <div id="companyTip"> <input type="button" value="做工资" id="btn_ok"  onclick="salMake()" class="btn_submit" />
                             </div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>三、添加发票（<font color=red>可跳过</font>）：</label></td>
                           <td class="td_right" nowrap>
                                </td>
                           <td class="td_right">
                               <div id="infohashTip">
                               <input type="button" value="添加发票" id="btn_ok"  onclick="addZhi()" class="btn_submit" />
                               <input type="button" value="跳过" id="btn_ok"  onclick="tiaoguo()" class="btn_submit" />
                              
                               </div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>四、添加支票：</label></td>
                           <td class="td_right" nowrap>
                                </td>
                           <td class="td_right">
                               <div id="infohashTip">
                               <input type="button" value="添加支票" id="btn_ok"  onclick="addZhiDao()" class="btn_submit" />
                               </div>
                           </td>
                       </tr>
					</table>
               </div>
               <div class="submit">
                   <input type="button" value=" 返回 " id="btn_ok" class="btn_submit" onClick="returnList();"/>
               </div>
            </form>
            </div>
        </div>
    </div>
  </body>
</html>