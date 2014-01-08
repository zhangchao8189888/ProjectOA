<?php 
$errorMsg=$form_data['error'];
$succ=$form_data['succ'];
$comname=$form_data['comname'];
//var_dump($files);
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
		$('input[name=protocol]').click(function(){
			if($(this).val() == 2){
				var mySelect = document.getElementById('strategy') ; 
				mySelect.options[3] = new Option ('重定向',3); 
				
			}else{
				var mySelect = document.getElementById('strategy') ;  
				mySelect.remove(3);
			}
			$('#redirecturl').attr('disabled',true);
		});
		$('#strategy').change(function(event){
			if($('#strategy[value=3]').size() > 0){
				$('#redirecturl').attr('disabled',false);
			}else{
				$('#redirecturl').attr('disabled',true);
			}
		});
	});
    function blackadd(){
    	if ($.formValidator.pageIsValid("1")){
			$("#form1").submit();
		}
    }
    function onChangeProtocol(){
        $("#trrefer").show();
        $("#index").html("<label>httpurl:</label>");
    }
    function onChangeNone(){
        $("#trrefer").hide();
        $("#index").html("<label>索引:</label>");
    }
	function func(){
		$("#transfer").countdown({
			until: "+3s",
			expiryUrl: "__ROOT__/Black/blacklist",
			onTick:
				function(periods){
					$(this).text(periods[6]);
				}
		});
	}
	function setprotocol(){
		var s_protocol = $('#protocol').val();
		
		if(s_protocol == 2){
			$('#strategy')[0].options.add(new Option('重定向',3));
		}
		return;
	}
	function bao232(event){
		e = event ? event : window.event;
		thisobj = e.target;
		alert(thisobj.options[thisobj.options.selectedIndex].value);
		e.stopPropagation();
		e.preventDefault();
		e.returnValue = false;
		e.cancelBubble = true;
		alert(slt_value);
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
            <div class="navigate">添加员工页面<input type="button" value=" 返回客服首页 " id="btn_ok" class="btn_submit" onclick="returnList();"/>
              </div>
            <div class="form">
            <form id="iform" method="post" action=""> </form>
            <form id="form1" method="post" action="index.php?action=Service&mode=addEmp">
            <input type="hidden" value="<?php echo $comname;?>" name="comname"/>
               <div id="span_msg"><font color="red"><?php if($errorMsg)echo $errorMsg?></font>
			<font color="green"><?php if($succ)echo $succ?></font></div>
               <div class="input">
               		<table class="form_list">
                       <tr>
                           <td class="td_left"><label>姓名:</label></td>
                           <td class="td_right" nowrap style="line-height:20px;">
                               <input type="text" id="name" name="name"  style="width:100px;float:left" value=""/>
                           </td>
                           <td class="td_right">
                               <div id="nameTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>所属公司:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="company" name="company" maxlength="255" style="width:500px;float:left" value="<?php echo $comname;?>" readonly="readonly"/>
                           </td>
                           <td class="td_right">
                               <div id="companyTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>身份证号:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="e_no" name="e_no" maxlength="255" style="width:300px;float:left"  value="" />
                           </td>
                           <td class="td_right">
                               <div id="e_noTip"></div>
                           </td>
                       </tr>
                        <tr>
                           <td class="td_left" id="index"><label>银行卡号:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="bank_no" name="bank_no" maxlength="255" style="width:300px;float:left" value=""/>
                           </td>
                           <td class="td_right">
                               <div id="bank_noTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>开户行:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="bank" name="bank" maxlength="255" style="width:150px;float:left" value=""/>
                           </td>
                           <td class="td_right">
                               <div id="bankTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>身份类别:</label></td>
                           <td class="td_right" nowrap>
                           <select name="e_type" >
                           <option value="实习生">实习生</option>
                           <option value="未缴纳保险">未缴纳保险</option>
                           <option value="本市城镇职工">本市城镇职工</option>
                           <option value="外埠城镇职工">外埠城镇职工</option>
                           <option value="本市农村劳动力">本市农村劳动力</option>
                           <option value="外地农村劳动力">外地农村劳动力</option>
                           <option value="本市农民工">本市农民工</option>
                           <option value="外地农民工">外地农民工</option>
                           </select>
                                </td>
                           <td class="td_right">
                               <div id="e_typeTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>社保基数:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="shebaojishu" name="shebaojishu" maxlength="255" style="width:100px;float:left" value=""/>
                           </td>
                           <td class="td_right">
                               <div id="shebaojishuTip"></div>
                           </td>
                       </tr>
                        <tr>
                           <td class="td_left" id="index"><label>公积金基数:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="gongjijinjishu" name="gongjijinjishu" maxlength="255" style="width:100px;float:left" value=""/>
                           </td>
                           <td class="td_right">
                               <div id="gongjijinjishuTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>残保费:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="canbaofei" name="canbaofei" maxlength="255" style="width:100px;float:left" value="0"/>
                           </td>
                           <td class="td_right">
                               <div id="canbaofeiTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>劳务费:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="laowufei" name="laowufei" maxlength="255" style="width:100px;float:left" value="0"/>
                           </td>
                           <td class="td_right">
                               <div id="laowufeiTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>档案费:</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="danganfei" name="danganfei" maxlength="255" style="width:100px;float:left" value="0"/>
                           </td>
                           <td class="td_right">
                               <div id="danganfeiTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>合同年限:</label></td>
                           <td class="td_right" nowrap>
                              <select name="hetongNian">
                              <option value="1">一年</option>
                              <option value="2">二年</option>
                              <option value="3">三年</option>
                              <option value="4">四年</option>
                              <option value="5">五年</option>
                              </select>
                           </td>
                           <td class="td_right">
                               <div id="danganfeiTip"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="td_left" id="index"><label>起始合同日期(2012-01-01):</label></td>
                           <td class="td_right" nowrap>
                               <input type="text" id="qishinian" name="qishinian" maxlength="255" style="width:100px;float:left" value=""/>
                           </td>
                           <td class="td_right">
                               <div id="danganfeiTip"></div>
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
                   <input type="button" value=" 添加  " id="btn_ok" class="btn_submit" onClick="blackadd();"/>
               </div>
            </form>
            </div>
            
        </div>
    </div>
  </body>
</html>