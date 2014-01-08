<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>欢迎使用工资管理系统</title>
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />
<link href="common/css/login.css" rel="stylesheet" type="text/css" />
<link href="common/css/png.css" rel="stylesheet" type="text/css" />
<link href="common/css/validator.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/formValidator.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/formValidatorRegex.js" charset="utf-8"></script>

<style>
.content .loginbtn{
	background:url(common/image/loginbt.png) no-repeat 0 0px!important;background:none;
	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='common/image/loginbt.png' ,sizingMethod='crop');
}
.content .loginbtnfocus{
	background:url(common/image/loingbt2.png) no-repeat 0 0px!important;background:none;
	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='common/image/loingbt2.png' ,sizingMethod='crop');
}
</style>
</head>

<body>
<div class="content">
  <h1 >欢迎使用工资管理系统</h1>
  <div class="body">
    <form id="form1" name="form1" method="post" action="index.php?action=Admin&mode=checklogin">
    <ul>
      <li>
        <input id="usrname" type="text" name="usrname" class="inputstyle" maxlength="50" autocomplete="off"
		        value="请输入用户名"  
                onfocus="if(this.value=='请输入用户名') this.value='';this.className='inputstylefocus';return true;"
                onblur="if(this.value=='') this.value='请输入用户名';this.className='inputstyle';return true;" />
      </li>
      <li>
        <input id="password" type="password" name="password" class="inputstyle" maxlength="20"
				value="请输入密码"  
                onfocus="if(this.value=='请输入密码') this.value='';this.className='inputstylefocus';return true;"
                onblur="if(this.value=='') this.value='请输入密码';this.className='inputstyle';return true;" />
      </li>
      <li class="btn png">
        <input name="login" type="submit" class="loginbtn" value=" "
		onmouseover="this.blur();return true;"
	    onmousedown="this.className='loginbtnfocus';return true;"/>
      </li>
	  </ul>
    </form>
    </div>
  <div class="foot"></div>
</div>
</body>
</html>
