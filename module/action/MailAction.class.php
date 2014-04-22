<?php
require_once("tools/PHPMailer-master/PHPMailerAutoload.php");
$mail = new PHPMailer(); //建立邮件发送类
$address ="459666001@qq.com";
$mail->IsSMTP(); // 使用SMTP方式发送
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
$mail->Host = "smtp.qq.com"; // 您的企业邮局域名
$mail->CharSet = "UTF-8";
$mail->SetLanguage("zh_cn", "tools/PHPMailer-master/language/");
$mail->SMTPAuth = true; // 启用SMTP验证功能
$mail->Username = "459666001@qq.com"; // 邮局用户名(请填写完整的email地址)
$mail->Password = "520@gmail."; // 邮局密码
$mail->Port=465;
$mail->From = "459666001@qq.com"; //邮件发送者email地址
$mail->FromName = "ilia";
$mail->AddAddress("$address", "a");//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
//$mail->AddReplyTo("", "");

//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
//$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式

$mail->Subject = "PHPMailer测试邮件"; //邮件标题
$mail->Body = "Hello,这是测试邮件"; //邮件内容
$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略

if(!$mail->Send())
{
    echo "邮件发送失败. <p>";
    echo "错误原因: " . $mail->ErrorInfo;
    exit;
}

echo "邮件发送成功";


/*************************************************

附件：
phpmailer 中文使用说明（简易版）
A开头：
$AltBody--属性
出自：PHPMailer::$AltBody
文件：class.phpmailer.php
说明：该属性的设置是在邮件正文不支持HTML的备用显示
AddAddress--方法
出自：PHPMailer::AddAddress()，文件：class.phpmailer.php
说明：增加收件人。参数1为收件人邮箱，参数2为收件人称呼。例 AddAddress("eb163@eb163.com","eb163")，但参数2可选，AddAddress(eb163@eb163.com)也是可以的。
函数原型：public function AddAddress($address, $name = '') {}
AddAttachment--方法
出自：PHPMailer::AddAttachment()
文件：class.phpmailer.php。
说明：增加附件。
参数：路径，名称，编码，类型。其中，路径为必选，其他为可选
函数原型：
AddAttachment($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream'){}
AddBCC--方法
出自：PHPMailer::AddBCC()
文件：class.phpmailer.php
说明：增加一个密送。抄送和密送的区别请看[SMTP发件中的密送和抄送的区别] 。
参数1为地址，参数2为名称。注意此方法只支持在win32下使用SMTP，不支持mail函数
函数原型：public function AddBCC($address, $name = ''){}
AddCC --方法
出自：PHPMailer::AddCC()
文件：class.phpmailer.php
说明：增加一个抄送。抄送和密送的区别请看[SMTP发件中的密送和抄送的区别] 。
参数1为地址，参数2为名称注意此方法只支持在win32下使用SMTP，不支持mail函数
函数原型：public function AddCC($address, $name = '') {}
AddCustomHeader--方法
出自：PHPMailer::AddCustomHeader()
文件：class.phpmailer.php
说明：增加一个自定义的E-mail头部。
参数为头部信息
函数原型：public function AddCustomHeader($custom_header){}
AddEmbeddedImage --方法
出自：PHPMailer::AddEmbeddedImage()
文件：class.phpmailer.php
说明：增加一个嵌入式图片
参数：路径,返回句柄[,名称,编码,类型]
函数原型：public function AddEmbeddedImage($path, $cid, $name = '', $encoding = 'base64', $type = 'application/octet-stream') {}
提示：AddEmbeddedImage(PICTURE_PATH. "index_01.jpg ", "img_01 ", "index_01.jpg ");
在html中引用
AddReplyTo--方法
出自：PHPMailer:: AddRepl
 *************************************************/
?>