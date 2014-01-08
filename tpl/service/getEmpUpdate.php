<?php 
$errorMsg=$form_data['error'];
$succ=$form_data['succ'];
$employ=$form_data['employ'];
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
		$.formValidator.initConfig({formid:"form1",onerror:function(msg){alert(msg)}});
		$("#name").formValidator({onshow:"请输入员工姓名！",onfocus:"员工姓名不能为空！",oncorrect:"员工姓名输入正确！"}).inputValidator({min:1,max:20,onerror:"请输入正确的员工姓名！"});
		$("#e_no").formValidator({onshow:"请输入员工身份证号！",onfocus:"员工身份证号不能为空！",oncorrect:"员工身份证号输入正确！"}).inputValidator({min:15,max:20,onerror:"请输入正确身份证号！"});
		$("#e_type").formValidator({onshow:"请输入员工身份类别！",onfocus:"身份类别不能为空！",oncorrect:"身份类别输入正确！"}).inputValidator({min:1,onerror:"请输入正确身份类别！"});
		$("#shebaojishu").formValidator({onshow:"请输社保基数！",onfocus:"社保基数不能为空！",oncorrect:"社保基数输入正确！"}).inputValidator({min:1,onerror:"请输入正确社保基数！"});
		$("#gongjijinjishu").formValidator({onshow:"请输入公积金基数！",onfocus:"公积金基数不能为空！",oncorrect:"公积金基数输入正确！"}).inputValidator({min:1,onerror:"请输入正确公积金基数！"});
		$("#company").formValidator({onshow:"请输入公司名称！",onfocus:"公司名称不能为空！",oncorrect:"公司名称输入正确！"}).inputValidator({min:1,onerror:"请输入正确公司名称！"});
	});
    function update(){
    	if ($.formValidator.pageIsValid("1")){
			$("#form1").submit();
		}
    }
	function returnList(){
		$("#iform").attr("action","index.php?action=Service&mode=getAdminComList"); 
		 $("#iform").submit();
		}
	function updateEmNo(){
    	if ($.formValidator.pageIsValid("1")){
    		if (confirm("是否确定修改身份证号？") == false) return;
    		$("#form1").attr("action","index.php?action=Employ&mode=emNoUpdate"); 
			$("#form1").submit();
		}

    }
    </script>
  </head>
  <body>
     <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
            <!--导航栏            -->
            <div class="navigate">员工修改页面<input type="button" value=" 返回客服首页 " id="btn_ok" class="btn_submit" onclick="returnList();"/>
              </div>
            <div class="form">
            <form id="iform"  method="post"  action=""></form>
            <form id="form1" method="post" action="index.php?action=Service&mode=emUpdate">
            <input type="hidden" value="<?php echo $employ['e_company'];?>" name="comname"/>
               <div id="span_msg"><font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font></div>
               <div class="input">
               		<table class="form_list">
                       <tr>
                           <td class="td_left"><label>姓名:</label></td>
                           <td class="td_right" nowrap style="line-height:20px;">
                               <input type="text" id="name" name="name" style="width:100px;float:left" value="<?php echo $employ['e_name']?>"/>
                               <input  type="hidden"  name="eid" value="<?php echo $employ['id']?>"/>
                           </td>
                           <td class="td_right">
                               <div id="nameTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>所属公司:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="company" name="company" maxlength="255" style="width:150px;float:left" value="<?php echo $employ['e_company']?>" readonly="readonly"/>
                           </td>
                           <td class="td_right">
                               <div id="companyTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>身份证号:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="e_no" name="e_no" maxlength="255" style="width:300px;float:left"  value="<?php echo $employ['e_num']?>" />
                           </td>
                           <td class="td_right">
                               <div id="e_noTip"></div>
                           </td>
                       </tr>
                        <tr>
                           <td class="td_left" id="index"><label>银行卡号:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="bank_no" name="bank_no" maxlength="255" style="width:300px;float:left" value="<?php echo $employ['bank_num']?>"/>
                           </td>
                           <td class="td_right">
                               <div id="bank_noTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>开户行:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="bank" name="bank" maxlength="255" style="width:150px;float:left" value="<?php echo $employ['bank_name']?>"/>
                           </td>
                           <td class="td_right">
                               <div id="bankTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>身份类别:</label></td>
                           <td class="td_right" nowrap>
                               <select name="e_type" >
                           <option value="实习生" <?php if($employ['e_type']=="实习生"){ echo 'selected';}?>>实习生</option>
                           <option value="未缴纳保险" <?php if($employ['e_type']=="未缴纳保险" ){ echo 'selected';}?>>未缴纳保险</option>
                           <option value="本市城镇职工" <?php if($employ['e_type']=="本市城镇职工"){ echo 'selected';}?>>本市城镇职工</option>
                           <option value="外埠城镇职工" <?php if($employ['e_type']=="外埠城镇职工"){ echo 'selected';}?>>外埠城镇职工</option>
                           <option value="本市农村劳动力" <?php if($employ['e_type']=="本市农村劳动力"){ echo 'selected';}?>>本市农村劳动力</option>
                           <option value="外地农村劳动力" <?php if($employ['e_type']=="外地农村劳动力"){ echo 'selected';}?>>外地农村劳动力</option>
                           <option value="本市农民工" <?php if($employ['e_type']=="本市农民工"){ echo 'selected';}?>>本市农民工</option>
                           <option value="外地农民工" <?php if($employ['e_type']=="外地农民工"){ echo 'selected';}?>>外地农民工</option>
                           </select> 
                          </td>
                           <td class="td_right">
                               <div id="e_typeTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>社保基数:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="shebaojishu" name="shebaojishu" maxlength="255" style="width:100px;float:left" value="<?php echo $employ['shebaojishu']?>"/>
                           </td>
                           <td class="td_right">
                               <div id="shebaojishuTip"></div>
                           </td>
                       </tr>
                        <tr>
                           <td class="td_left" id="index"><label>公积金基数:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="gongjijinjishu" name="gongjijinjishu" maxlength="255" style="width:100px;float:left" value="<?php echo $employ['gongjijinjishu']?>"/>
                           </td>
                           <td class="td_right">
                               <div id="gongjijinjishuTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>残保费:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="canbaofei" name="canbaofei" maxlength="255" style="width:100px;float:left" value="<?php echo $employ['canbaojin']?>"/>
                           </td>
                           <td class="td_right">
                               <div id="canbaofeiTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>劳务费:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="laowufei" name="laowufei" maxlength="255" style="width:100px;float:left" value="<?php echo $employ['laowufei']?>"/>
                           </td>
                           <td class="td_right">
                               <div id="laowufeiTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>档案费:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="danganfei" name="danganfei" maxlength="255" style="width:100px;float:left" value="<?php echo $employ['danganfei']?>"/>
                           </td>
                           <td class="td_right">
                               <div id="danganfeiTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>合同年限:</label></td>
                           <td class="td_right" nowrap>
                              <select name="hetongNian">
                              <option value="1"<?php if($employ['e_hetongnian']==1){ echo 'selected';}?>>一年</option>
                              <option value="2" <?php if($employ['e_hetongnian']==2){ echo 'selected';}?>>二年</option>
                              <option value="3"<?php if($employ['e_hetongnian']==3){ echo 'selected';}?>>三年</option>
                              <option value="4"<?php if($employ['e_hetongnian']==4){ echo 'selected';}?>>四年</option>
                              <option value="5"<?php if($employ['e_hetongnian']==5){ echo 'selected';}?>>五年</option>
                              </select>
                           </td>
                           <td class="td_right">
                               <div id="danganfeiTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>起始合同日期(2012-01-01):</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="qishinian" name="qishinian" maxlength="255" style="width:100px;float:left" value="<?php echo $employ['e_hetong_date'];?>"/>
                           </td>
                           <td class="td_right">
                               <div id="danganfeiTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>备注:</label></td>
                           <td class="td_right" nowrap>
                               <textarea name="memo" cols="100" rows="10" class="text" id="memo" onFocus="" value="<?php echo $employ['memo']?>"></textarea>
                           </td>
                           <td class="td_right">
                               <div id="infohashTip"></div>
                           </td>
                       </tr>
					</table>
               </div>
               <div class="submit">
                   <input type="button" value=" 修改  " id="btn_ok" class="btn_submit" onClick="update();"/>
               <input type="button" value=" 修改身份证号" id="btn_ok" class="btn_submit" onClick="updateEmNo();"/>
              
               </div>
                
            </form>
            </div>
        </div>
    </div>
  </body>
</html>