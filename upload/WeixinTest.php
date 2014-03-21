<?php
/*
    File: index.php
    Author: 管理员
    Date: 2013.04.15
    Usage: 小九机器人微信接口
    论坛微信QQ群: 39161950(已满),39161950
	小九官网： www.xiaojo.com
	微信论坛： www.weixen.com
 */
header("content-Type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . "/wechat.php");
define("DEBUG", true);

//这里为你的小九后台账号,不填不能正常回复！！！！
$yourdb="";
$yourpw="";

//下面为可选配置的选项
define("TOKEN", "xiaojo");
define("MOREN", "抱歉，我真的不知道肿么回答了，您可以用问*答*来教我");//丢包后默认回复
define("FLAG", "@");//星标标识，默认为 @,用户对话里包含此标识则设置为星标，用于留言
//配置结束

$w = new Wechat(TOKEN, DEBUG);
if (isset($_GET['echostr']))
{
    $w->valid();
    exit();
}

//回复用户
$w->reply("reply_cb");
exit();

function reply_cb($request, $w)//消息回复主函数
{
    $to = $request['ToUserName'];
    $from = $request['FromUserName'];
    $time = $w->get_creattime();
    if ($w->get_msg_type() == "location") //发送位置接口
    {
        $lacation = "x@".(string)$request['Location_X']."@".(string)$request['Location_Y'];
        $lacation = urlencode(str_replace('\.','\\\.',$lacation));
        $lacation = urldecode(xiaojo($lacation,$from,$to));
        return  $lacation;
    }
    else if ($w->get_msg_type() == "image")//返回图片地址
    {
        $PicUrl = $request['PicUrl'];
        $pic = urldecode(xiaojo("&".$PicUrl,$from,$to));
        //$w->set_funcflag();
        return $pic;
    }
    else if ($w->get_msg_type() == "voice") //用户发语音时回复语音或音乐,请在此配置默认语音回复
    {

        return array(
            "title" =>  "你好",
            "description" =>  "亲爱的主人",
            "murl" =>  "http://weixen-file.stor.sinaapp.com/b/xiaojo.mp3",//语音地址，建议自定义一个语音
            "hqurl" =>  "http://weixen-file.stor.sinaapp.com/b/xiaojo.mp3",
        );
    }
    else if ($w->get_msg_type() == "event")//事件检测
    {
        if ($w->get_event_type() == "subscribe")//首次关注回复请在后台设置关键词为 "subscribe" 的图文、文本或语音规则
        {
            return xiaojo("subscribe",$from,$to);
        }
        elseif($w->get_event_type() == "unsubscribe")
        {
            $unsub = xiaojo("unsubscribe",$from,$to);
            return $unsub;
        }
        elseif($w->get_event_type() == "click")
        {
            $menukey = $w->get_event_key();
            $menu = xiaojo($menukey,$from,$to);
            return $menu;
        }
        else
        {
            $menukey = $w->get_event_key();
            return $menukey;
        }
    }
    $content = trim($request['Content']);
    $firsttime = $content;
    if ($content !== "") //发纯文本
    {
        //$w->set_funcflag(); //如果有必要的话，加星标，方便在web处理
        $content = $w->biaoqing($content); //表情处理
        if(strstr($content,FLAG))//如果有星标的标记则设为星标(用于留言)
        {
            $w->set_funcflag();
        }
        $content = $content."^".$time;
        $reply = xiaojo($content,$from,$to);
        if($reply=="")
        {
            $reply = MOREN ;
        }
        return  $reply;

    }
    else
    {
        return  MOREN;
    }

}
?>
