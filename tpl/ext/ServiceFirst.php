<?php 
$comlist=$form_data['comList'];
$errorlist=$form_data['error'];
$searchType=$form_data['searchType'];
$comlist=json_encode($comlist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>客服首页</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
        <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
        <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
   		<link href="common/css/validator.css" rel="stylesheet" type="text/css" />
   		<script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="tpl/ext/js/service_first.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript">
	  function a(){
	  $("#iform").attr("action","index.php?action=Salary&mode=rename"); 
	  $("#nfname").val($("#newfname").val());
	  $("#iform").submit();
	  }
	  function getEmploy(com){
	  $("#iform").attr("action","index.php?action=Service&mode=getEmList"); 
	  $("#comname").val(com);
	  $("#iform").submit();
      }
	  function getOther(){
    	  $("#iform").attr("action","index.php?action=Service&mode=getOtherAdminComList"); 
    	  if($("#mon").val()==-1){
    		  alert("请选择月份");
    		  return;
    		  }
    	  $("#yearDate").val($("#year").val());
    	  $("#monDate").val($("#mon").val());
    	  $("#sType").val($("#searchType").val());
    	  store.load( {  
    		    url : 'index.php?action=Service&mode=getOtherAdminComList',
              params : {   
               yearDate : $("#year").val(),  
               monDate : $("#mon").val(),
               sType:$("#searchType").val()
              }  
             });
    	  }
	  function other(){
		  $("#select").attr("style","display:block"); 
		  }
	 function dangyue(){
		 $("#iform").attr("action","index.php?action=Service&mode=getAdminComList"); 
		 $("#sType").val($("#searchType").val());
		  $("#iform").submit(); 
		 }
	function makeSal(id,sDate,salType){
		//alert(sDate);
		if(sDate==""){
			alert("未找到做工资月份，请按工资月份查询后再做工资！");
			return;
			}
          $("#comId").val(id);
          $("#sDate").val(sDate);
          $("#salType").val(salType);
		  $("#iform").attr("action","index.php?action=Service&mode=makeSal"); 
		  $("#iform").submit(); 
				}
	function cancel(id){
		if(confirm('确定取消对该公司管理吗?')){
		  $("#comId").val(id);
		  $("#iform").attr("action","index.php?action=Service&mode=cancelService"); 
		  $("#iform").submit(); 
		}
		}
	function send(eid){
		  if(confirm('确定要申请发放工资吗?')){
		  $("#iform").attr("action","index.php?action=Service&mode=salarySend"); 
		  $("#timeid").val(eid)
		  $("#iform").submit();
		  //
		  }
		  }
	function addFa(){
			  $("#iform").attr("action","index.php?action=SalaryBill&mode=toAddInvoice"); 
			  $("#iform").submit();
		}
        </script>
  </head>
  <body>
  <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
    <form enctype="multipart/form-data" id="iform" action=""  target="_blank" method="post"> 
         <input type="hidden" name="comId" id="comId"  value=""/>
         <input type="hidden" name="sDate" id="sDate"  value=""/>
         <input type="hidden" name="salType" id="salType"  value=""/>
       	</form>
    <div class="submit">
         <div align="left">
         <select name="searchType" id="searchType"  >
                  <option value="1" <?php if($searchType==1) echo "selected"?>>按工资月份查询</option>
                  <option value="2" <?php if($searchType==2) echo "selected"?>>按操作时间查询</option>
                  </select>
                  </div>
       <div align="center">   
         <div id="bDate"> </div>       
         <div id="select" style="display:none"></div>
       </div>
       
               </div>
    <div id="tab" class="TipDiv"></div>
    <div id="demo"></div>  
    <div id="center"></div>  
   <div id="div1" class="content">
    <ul>
        <li id="li1"></li>
         <li id="li3"></li>
    </ul>
</div>
         <div  id="tableList">
         
         </div>
    </div>
    </div>
  </body>
</html>