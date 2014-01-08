<?php 
$errorMsg=$form_data['error'];
$succ=$form_data['succ'];
$salList=$form_data['salList'];
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
            <div class="navigate">{$navigate}</div>
            <div style="width:100%;overflow-x:scroll;dispaly:inline;white-space:nowrap;">
            <form id="form1" method="post" action="index.php?action=Salary&mode=salUpdate">
            <table id="tab_list" class="table_list" cellpadding="2" cellspacing="1" style="table-layout:fixed;">
        <tr>  
        <td align="left" width="150px" style="word-wrap:break-word;">姓名</td>
        <td align="left" width="150px" style="word-wrap:break-word;">身份证号</td>
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
        </tr>
           <?php 
        echo '<tr>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['e_name'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['employid'].'</td>';
		echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['per_yingfaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['per_shiye'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['per_yiliao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['per_yanglao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['per_gongjijin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['per_daikoushui'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['per_koukuangheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['per_shifaheji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['com_shiye'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['com_yiliao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['com_yanglao'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['com_gongshang'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['com_shengyu'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['com_gongjijin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['com_heji'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['laowufei'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['canbaojin'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['danganfei'].'</td>';
        echo '<td align="left" width="150px" style="word-wrap:break-word;">'.$salList['paysum_zhongqi'].'</td>';
		echo "</tr>";
           ?>
           <tr>
           <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="name" name="name" style="width:100px;float:left" readonly="readonly"  value="<?php echo $salList['e_name']?>"/>
                               <input  type="hidden"  name="eid" value="<?php echo $salList["employid"]?>"/>
                               <input  type="hidden"  name="stId" value="<?php echo $salList["stId"]?>"/>
                               <input  type="hidden"  name="sId" value="<?php echo$salList['sId']?>"/></td>
             <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="e_no" name="e_no" maxlength="255" style="width:300px;float:left" readonly="readonly"  value="<?php echo $salList['employid']?>" /></td>
             <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="per_yingfaheji" name="per_yingfaheji" maxlength="255" style="width:300px;float:left" value="<?php echo $salList['per_yingfaheji']?>"/>
                               </td>
             <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="per_shiye" name="per_shiye" maxlength="255" style="width:150px;float:left" value="<?php echo $salList['per_shiye']?>"/></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="per_yiliao" name="per_yiliao" maxlength="255" style="width:150px;float:left" value="<?php echo $salList['per_yiliao']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="per_yanglao" name="per_yanglao" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['per_yanglao']?>"/>
                          </td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="per_gongjijin" name="per_gongjijin" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['per_gongjijin']?>"/>
                           </td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="per_daikoushui" name="per_daikoushui" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['per_daikoushui']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="per_shifaheji" name="per_koukuangheji" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['per_koukuangheji']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="per_shifaheji" name="per_shifaheji" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['per_shifaheji']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="per_shifaheji" name="com_shiye" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['com_shiye']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="com_yiliao" name="com_yiliao" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['com_yiliao']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="com_yanglao" name="com_yanglao" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['com_yanglao']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="com_gongshang" name="com_gongshang" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['com_gongshang']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="com_shengyu" name="com_shengyu" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['com_shengyu']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="com_gongjijin" name="com_gongjijin" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['com_gongjijin']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="com_heji" name="com_heji" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['com_heji']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="laowufei" name="laowufei" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['laowufei']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="canbaojin" name="canbaojin" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['canbaojin']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="danganfei" name="danganfei" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['danganfei']?>" /></td>
                              <td align="left" width="150px" style="word-wrap:break-word;"><input type="text" id="paysum_zhongqi" name="paysum_zhongqi" maxlength="255" style="width:100px;float:left" value="<?php echo $salList['paysum_zhongqi']?>" /></td>
                            </tr>
        </table>
        <div class="submit">
         <textarea name="memo" cols="100" rows="10" class="text" id="memo" onFocus="" value="<?php echo $salList['memo']?>"></textarea>
                   <input type="button" value=" 修改  " id="btn_ok" class="btn_submit" onClick="update();"/>
               </div>
          </form>
            </div>
        </div>
    </div>
  </body>
</html>