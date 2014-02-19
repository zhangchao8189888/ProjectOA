<?php 
$errorMsg=$form_data['errormsg'];
$succ=$form_data['succ'];
$comList=$form_data['comList'];
$comId=$form_data['comId'];
$date=$form_data['date'];
echo($comId["company_name"]) ;
echo($date) ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <title></title>
    
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
   <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
   <link href="common/css/validator.css" rel="stylesheet" type="text/css" />
   <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
   <script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
   <script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
   <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
   <script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
   <script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
   <script src="common/js/formValidator.js" type="text/javascript" charset="UTF-8"></script>
   <script src="common/js/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script>
   <script language="javascript" type="text/javascript">
       var valueID;
        $(document).ready(function(){
		});
        Ext.onReady(function(){  
       	 new Ext.form.ComboBox({
       		 id:'ByComname', 
       	     name:'level',  
       	     lazyRender : true,  
       	     triggerAction: 'all', 
       	     transform : 'comname',
       	     listeners:{
                 beforequery : function(e){
                     var combo = e.combo;
                 if(!e.forceAll){
                 var value = e.query;
                 combo.store.filterBy(function(record,id){
                 var text = record.get(combo.displayField);
                 return (text.indexOf(value)!=-1);
                 });
                 combo.expand();
                 return false;
                 }
                 }
                 }
       	 }) 
       	 Ext.getCmp('ByComname').addListener('change', function(box, newv, oldv) {
        		valueID = Ext.getCmp('ByComname').getValue();
        		getSalaryTimeByCompany();
       	  }); 
       	 }); 
        function getSalaryTimeByCompany(){
    		$("#salaryTime").html("<option value='-1'>请选择</option>");
    		$.ajax(
                {
                   type: "POST",
                   url: "index.php?action=SalaryBill&mode=getSalaryTimeById",
                   data: "comid="+valueID,
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
	   if(valueID=='-1'||$("#salaryTime").val()=='-1'||$("#billname").val()==""||$("#billval").val()==""){
		   alert("请填入完整信息！");
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
            <!--导航栏-->
            <div class="navigate">{$navigate}</div>
            <div class="form">
            <form id="form1" method="post" action="index.php?action=SalaryBill&mode=addInvoice">
               <div id="span_msg"><font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font></div>
               <div class="input">
               		<table >
               		 <tr>
                           <td class="td_left" id="index"><label>公司名称：</label></td>
                           <td >
                                <select  name="comname" id="comname"  >
                                <option value="<?php echo($comId["company_name"])?>">请选择公司名称</option>
			<?php  if($comList){
           	  while ($row=mysql_fetch_array($comList) ){
           	  	?>
           	  	<option value="<?php echo $row['id'];?>"><?php echo $row['company_name'];?></option>
           	  	<?php }?>
                <?php }?>
			</select></td>
                           <td class="td_right">
                               <div id="e_noTip"></div>
                           </td>
                       </tr>
                        <tr>
                            <td class="td_left"><label>发票编号:</label></td>
                            <td class="td_right" nowrap style="line-height:20px;">
                                <input type="text" id="billno" name="billno"  style="width:150px;float:left" value=""/>
                            </td>
                            <td class="td_right">
                                <div id="noTip"></div>
                            </td>
                        </tr>
               		<tr>
                           <td class="td_left" id="index"><label>工资日期：</label></td>
                           <td class="td_right" nowrap>
                                <select  name="salaryTime" id="salaryTime"  >
			                    <option value="<?php echo($date)?>">请选择工资日期</option>
			                    </select></td>
                           <td class="td_right">
                               <div id="e_noTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left"><label>发票项目:</label></td>
                           <td class="td_right" nowrap style="line-height:20px;">
                               <input type="text" id="billname" name="billname"  style="width:100px;float:left" value=""/>
                           </td>
                           <td class="td_right">
                               <div id="nameTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>发票金额:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="billval" name="billval" maxlength="255" style="width:150px;float:left" value=""/>
                           </td>
                           <td class="td_right">
                               <div id="companyTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>备注:</label></td>
                           <td class="td_right" nowrap>
                               <textarea name="memo" cols="100" rows="10" class="text" id="memo" onFocus=""></textarea>
                           </td>
                           <td class="td_right">
                               <div id="infohashTip"></div>
                           </td>
                       </tr>
					</table>
               </div>
               <div class="submit">
                   <input type="button" value=" 添加  " id="btn_ok" class="btn_submit" onClick="add();"/>
               </div>
            </form>
            </div>
        </div>
    </div>
  </body>
</html>