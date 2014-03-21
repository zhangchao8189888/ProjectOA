<?php
//define token
define("TOKEN", "weixin");
define("MOREN", "Master!晓晓现在还不能理解您的命令含义！您可以发送“开发进度（version）”查看一下我现在拥有什么能力！");//丢包后默认回复
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();

class wechatCallbackapiTest {

    public function responseMsg() {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if(!empty($postStr)) {
            //extract post data
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE) {
                case "text":
                    $resultStr = $this->handleText($postObj);
                    break;
                case "event":
                    $resultStr = $this->handleEvent($postObj);
                    break;
                case "image":
                    $resultStr = $this->receiveImage($postObj);
                    break;
                case "location":
                    $resultStr = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $resultStr = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $resultStr = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $resultStr = $this->receiveLink($postObj);
                    break;
                default:
                    $resultStr = "Unknow msg type: " . $RX_TYPE;
                    break;
            }
            echo $resultStr;
        } else {
            echo "请您对我说点什么吧！";
            exit;
        }
    }

    //处理文字
    private function handleText($postObj) {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
        if(!empty($keyword)) {
            $msgType = "text";
            //截取天气关键字
            $str = mb_substr($keyword, -2, 2, "UTF-8");
            $str_key = mb_substr($keyword, 0, -2, "UTF-8");

            //截取翻译关键字
            $str_trans = mb_substr($keyword,0,2,"UTF-8");
            $str_valid = mb_substr($keyword,0,-2,"UTF-8");
            if($str_trans=="翻译"){
                if(!empty($str_valid)){
                    $word = mb_substr($keyword,2,220,"UTF-8");
                    $contentStr = $this->baiduDic($word);
                    if(empty($contentStr)){
                        $contentStr="翻译出了点问题，请您再试一下Master！";
                    }
                }else{
                    $contentStr ="Master!至少让我翻译点什么！\n标准的输入方式是这样的：“翻译我爱你”";
                }
            }else if($str == "天气" && !empty($str_key)) {
                $data = $this->weatherNow($str_key);
                $dataNow = $this->weatherInfo($str_key);
                if(empty($data->weatherinfo)) {
                    $contentStr = "抱歉，没有查到“$str_key”的天气信息！";
                } else {
                    $contentStr = "【" . $data->weatherinfo->city .
                        "实时天气】\n" .
                        "发布时间：" .
                        $data->weatherinfo->time .
                        "\n天气：".
                        $dataNow->weatherinfo->weather.
                        "\n气温：" .
                        $data->weatherinfo->temp .
                        "\n风向" .
                        $data->weatherinfo->WD .
                        "\n风力" .
                        $data->weatherinfo->WS .
                        "\n相对湿度" .
                        $data->weatherinfo->SD;
                }
            }else if($str == "天气" && empty($str_key)) {
                $contentStr = "Master!至少给我一个城市让我去找！\n标准的输入方式是这样的：“北京天气”";
            } else if($str == "你没有女朋友"|| $keyword == "你女朋友是谁") {
                $contentStr = "Master!不说这个我们还是好朋友！";
            } else if($keyword == "?" || $keyword == "？") {
                $contentStr = "Master!得到您的输入文字为 $keyword 现在报给您当前时间！\n" . date("Y-m-d H:i:s", time());
            } else if($keyword == "我就是你的主人，saber！" || $keyword == "我就是你的主人,saber!" ||$keyword == "我是你的主人，saber！" ||$keyword == "我是你的主人,saber!") {
                $contentStr = "Master！愿为您献出生命！";
            } else if($keyword == "开发进度" || $keyword == "version") {
                $contentStr = date("Y-m-d H:i:s", time()) . "\n目前开发进度：".
                    "\n1.获得当前时间：可以输入“？”使用。".
                    "\n2.实时天气：可以输入“xx天气”使用。".
                    "\n3.语言翻译：可以输入“翻译xx”使用（目前是自动翻译模式，可以进行中-英，英-中，日-中互译。）。".
                    "\n4.聊天女仆：开发中……".
                    "\n5.推荐音乐：开发中……".
                    "\n       ——Author:ilia.";
            } else if($keyword == "你好" || $keyword == "晓晓") {
                $contentStr = "Master！贵安！请每天开心！";
            } else if($keyword == "小黄图" || $keyword == "和谐万岁") {
                $contentStr =   $this->yellowInfo();
                if(is_array($contentStr)){
                    return $this->responseNews($postObj, $contentStr,0);
                }
            } else if($keyword == "我喜欢你" || $keyword == "我爱你") {
                $contentStr = "Master！最喜欢你了！";
            } else if($keyword == "你喜欢谁" || $keyword == "你爱谁") {
                $contentStr = "一定是Master啦！";
            } else if($keyword == "你是机器人吗" || $keyword == "你是真人吗") {
                $contentStr = "Master！我是人工智能万用助手的阿鲁！正在成长中！";
            } else if($keyword == "彩蛋" || $keyword == "喵") {
                $contentStr = "Master！尝试一下“和谐万岁”吧！";
            } else if($keyword == "卧槽" || $keyword == "尼玛") {
                $contentStr = "Master！文明万岁的万万岁！";
            } else {
                $content = $this->biaoqing($keyword); //表情处理
                $contentStr =  $this->xiaojo($content,$fromUsername,$toUsername);
            }
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        } else {
            echo "Input something...";
        }
    }

    //处理事件
    private function handleEvent($object) {
        $contentStr = "";
        switch ($object->Event) {
            case "subscribe":
                $contentStr = "Master！初次见面请多关照！\n这里是您的小助手晓晓！\n由于我还在开发中，可能会遇到很多奇怪的问题，但我一定会努力修正的！\n您可以发送“开发进度（version）”查看一下我现在拥有什么能力！\n那么，以后的日子就请多关照啦！（鞠躬）\nO(∩_∩)O";
                break;
            default :
                $contentStr = "Unknow Event: " . $object->Event;
                break;
        }
        $resultStr = $this->responseText($object, $contentStr);
        return $resultStr;
    }

    //处理图片
    private function receiveImage($object){
        $funcFlag=0;
        $contentStr = "你发送的是图片，地址为：".$object->PicUrl;
        $resultStr = $this->responseText($object, $contentStr,$funcFlag);
        return $resultStr;
    }

    //处理位置
    private function receiveLocation($object){
        $funcFlag=0;
        $contentStr = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $resultStr = $this->responseText($object, $contentStr,$funcFlag);
        return $resultStr;
    }

    //处理声音
    private function receiveVoice($object){
        $funcFlag = 0;
        $contentStr = "你发送的是语音，媒体ID为：".$object->MediaId;
        $resultStr = $this->responseText($object, $contentStr,$funcFlag);
        return $resultStr;
    }

    //处理视频
    private function receiveVideo($object){
        $funcFlag = 0;
        $contentStr = "你发送的是视频，媒体ID为：".$object->MediaId;
        $resultStr = $this->responseText($object, $contentStr,$funcFlag);
        return $resultStr;
    }

    //处理链接
    private function receiveLink($object){
        $funcFlag = 0;
        $contentStr = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $resultStr = $this->responseText($object, $contentStr,$funcFlag);
        return $resultStr;
    }

    //发送消息封装
    private function responseText($object, $content, $flag = 0) {
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>%d</FuncFlag>
					</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }
    //发送图文封装
    private function responseNews($object, $content,$flag) {
        $picTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[%s]]></Title>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                    </item>
                    </Articles>
                    </xml>";
        $resultStr = sprintf($picTpl, $object->FromUserName, $object->ToUserName, time(),$content['Title'],$content['PicUrl'],$content['Url'], $flag);
        return $resultStr;
    }
    //和谐方法
    private function yellowInfo(){
        $record=array(
            'Title' =>'美图赏析',
            'PicUrl' => 'http://monyria-monoria.stor.sinaapp.com/1.jpg',
            'Url' =>'http://monyria-monoria.stor.sinaapp.com/1.jpg'
        );
        $ran=rand(1,3);
        switch($ran){
            case 1:
                return "http://acgzone.us/（正确的打开方式是 复制-粘贴（捂脸飞奔";
                break;
            case 2:
                return "http://www.hacg.me/（正确的打开方式是 复制-粘贴（捂脸飞奔";
                break;
            default:
                return $record;
                break;
        }
    }
    //当前实时天气
    private function weatherNow($n) {
        include("weather_cityId.php");
        $c_name = $weather_cityId[$n];
        if(!empty($c_name)) {
            $json = file_get_contents("http://www.weather.com.cn/data/sk/" . $c_name . ".html");
            return json_decode($json);
        } else {
            return null;
        }

    }
    //实时天气情况
    private function weatherInfo($n) {
        include("weather_cityId.php");
        $c_name = $weather_cityId[$n];
        if(!empty($c_name)) {
            $json = file_get_contents("http://www.weather.com.cn/data/cityinfo/" . $c_name . ".html");
            return json_decode($json);
        } else {
            return null;
        }
    }
    //百度翻译
    public function baiduDic($word, $from = "auto", $to = "auto") {
        //首先对要翻译的文字进行 urlencode 处理
        $word_code = urlencode($word);
        //注册的API Key
        $appid = "sRFEUiEXrY3Mf5SgYzsfXI0Z";
        //生成翻译API的URL GET地址
        $baidu_url = "http://openapi.baidu.com/public/2.0/bmt/translate?client_id=" . $appid . "&q=" . $word_code . "&from=" . $from . "&to=" . $to;
        $text = json_decode($this->language_text($baidu_url));
        $text = $text->trans_result;
        return $text[0]->dst;
    }
    //百度翻译-获取目标URL所打印的内容
    public function language_text($url) {
        if(!function_exists('file_get_contents')) {
            $file_contents = file_get_contents($url);
        } else {
            //初始化一个cURL对象
            $ch = curl_init();
            $timeout = 5;
            //设置需要抓取的URL
            curl_setopt($ch, CURLOPT_URL, $url);
            //设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //在发起连接前等待的时间，如果设置为0，则无限等待
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            //运行cURL，请求网页
            $file_contents = curl_exec($ch);
            //关闭URL请求
            curl_close($ch);
        }
        return $file_contents;
    }

    //小九机器人
    private function xiaojo($key,$from,$to) //小九接口函数，该函数可通用于其他程序
    {
        $yourdb="monoria";
        $yourpw="521125";
        $key=urlencode($key);
        $yourdb=urlencode($yourdb);
        $from=urlencode($from);
        $to=urlencode($to);
        $post="chat=".$key."&db=".$yourdb."&pw=".$yourpw."&from=".$from."&to=".$to;
        $api = "http://www.xiaojo.com/api5.php";
        $replys =  $this->curlpost($post,$api);
        $reply =  $this->media(urldecode( $replys));//多媒体转换
        return $reply;
    }
    private function curlpost($curlPost,$url) //curl post 函数
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }
    private function media($content) //多媒体转换
    {
        if(strstr($content,'murl')){//音乐
            $a=array();
            foreach (explode('#',$content) as $content)
            {
                list($k,$v)=explode('|',$content);
                $a[$k]=$v;
            }
            $content = $a;
        }
        elseif(strstr($content,'pic'))//多图文回复
        {
            $a=array();
            $b=array();
            $c=array();
            $n=0;
            $contents = $content;
            foreach (explode('@t',$content) as $b[$n])
            {
                if(strstr($contents,'@t'))
                {
                    $b[$n] = str_replace("itle","title",$b[$n]);
                    $b[$n] = str_replace("ttitle","title",$b[$n]);
                }

                foreach (explode('#',$b[$n]) as $content)
                {
                    list($k,$v)=explode('|',$content);
                    $a[$k]=$v;
                    $d.= $k;
                }
                $c[$n] = $a;
                $n++;

            }
            $content = $c ;
        }
        return $content;
    }
    //处理表情
    private function biaoqing($content)
    {
        if(strstr($content,"/:")){
            if(preg_match("/\/::\)/",$content)){

                $content = "笑话";

            }
            elseif(preg_match("/\/::~/",$content)){

                $content = "撇嘴";

            }
            elseif(preg_match("/\/::B/",$content)){

                $content = "色";

            }
            elseif(preg_match("/\/:,@f/",$content)){

                $content = "奋斗";

            }
            elseif(preg_match("/\/:heart/",$content)){

                $content = "爱心";

            }
            elseif(preg_match("/\/:showlove/",$content)){

                $content = "嘴唇";

            }
            elseif(preg_match("/\/:cake/",$content)){

                $content = "蛋糕";

            }
            elseif(preg_match("/\/:gift/",$content)){

                $content = "礼物";

            }
            elseif(preg_match("/\/:handclap/",$content)){

                $content = "鼓掌";

            }
            elseif(preg_match("/\/::\*/",$content)){

                $content = "亲亲";

            }
            elseif(preg_match("/\/:rose/",$content)){

                $content = "玫瑰";

            }
            elseif(preg_match("/\/:kiss/",$content)){

                $content = "献吻";

            }
            elseif(preg_match("/\/:love/",$content)){

                $content = "爱情";

            }
            elseif(preg_match("/\/:ok/",$content)){

                $content = "OK";

            }
            elseif(preg_match("/\/:lvu/",$content)){

                $content = "爱你";

            }
            elseif(preg_match("/\/:jj/",$content)){

                $content = "勾引";

            }
            elseif(preg_match("/\/:@\)/",$content)){

                $content = "抱拳";

            }
            elseif(preg_match("/\/:share/",$content)){

                $content = "握手";

            }
            elseif(preg_match("/\/:hug/",$content)){

                $content = "拥抱";

            }
            elseif(preg_match("/\/::\-O/",$content)){

                $content = "哈欠";

            }
            elseif(preg_match("/\/:xx/",$content)){

                $content = "敲打";

            }
            elseif(preg_match("/\/:\-\-b/",$content)){

                $content = "冷汗";

            }
            elseif(preg_match("/\/::X/",$content)){

                $content = "闭嘴";

            }
            elseif(preg_match("/\/:no/",$content)){

                $content = "NO";

            }
            elseif(preg_match("/\/::@/",$content)){

                $content = "发怒";

            }
            elseif(preg_match("/\/::\(/",$content)){

                $content = "难过";

            }
            elseif(preg_match("/\/::Q/",$content)){

                $content = "抓狂";

            }
            elseif(preg_match("/\/::T/",$content)){

                $content = "吐";

            }
            elseif(preg_match("/\/::d/",$content)){

                $content = "白眼";

            }
            elseif(preg_match("/\/::!/",$content)){

                $content = "惊恐";

            }
            elseif(preg_match("/\/::L/",$content)){

                $content = "流汗";

            }
            elseif(preg_match("/\/::\-S/",$content)){

                $content = "咒骂";

            }
            elseif(preg_match("/\/:,@@/",$content)){

                $content = "晕";

            }
            elseif(preg_match("/\/::8/",$content)){

                $content = "疯了";

            }
            elseif(preg_match("/\/:,@!/",$content)){

                $content = "衰";

            }
            elseif(preg_match("/\/:!!!/",$content)){

                $content = "骷髅";

            }
            elseif(preg_match("/\/:dig/",$content)){

                $content = "抠鼻";

            }
            elseif(preg_match("/\/:pd/",$content)){

                $content = "菜刀";

            }
            elseif(preg_match("/\/:pig/",$content)){

                $content = "猪头";

            }
            elseif(preg_match("/\/:fade/",$content)){

                $content = "凋谢";

            }
            elseif(preg_match("/\/:break/",$content)){

                $content = "心碎";

            }
            elseif(preg_match("/\/:li/",$content)){

                $content = "闪电";

            }
            elseif(preg_match("/\/:bome/",$content)){

                $content = "炸弹";

            }
            elseif(preg_match("/\/:kn/",$content)){

                $content = "刀";

            }
            elseif(preg_match("/\/:shit/",$content)){

                $content = "便便";

            }
            elseif(preg_match("/\/::\+/",$content)){

                $content = "酷";

            }
            elseif(preg_match("/\/:,@o/",$content)){

                $content = "傲慢";

            }
            elseif(preg_match("/\/:X-\)/",$content)){

                $content = "阴险";

            }
            elseif(preg_match("/\/:v/",$content)){

                $content = "胜利";

            }
            elseif(preg_match("/\/:turn/",$content)){

                $content = "回头";

            }
            elseif(preg_match("/\/:ladybug/",$content)){

                $content = "瓢虫";

            }
            elseif(preg_match("/\/:,@x/",$content)){

                $content = "嘘";

            }
            elseif(preg_match("/\/::,@/",$content)){

                $content = "悠闲";

            }
            elseif(preg_match("/\/:8-\)/",$content)){

                $content = "得意";

            }
            elseif(preg_match("/\/:#-0/",$content)){

                $content = "激动";

            }
            elseif(preg_match("/\/:kotow/",$content)){

                $content = "磕头";

            }
            elseif(preg_match("/\/:@x/",$content)){

                $content = "吓";

            }
            elseif(preg_match("/\/:8\*/",$content)){

                $content = "可怜";

            }
            elseif(preg_match("/\/:P-\(/",$content)){

                $content = "委屈";

            }
            elseif(preg_match("/\/:B-\)/",$content)){

                $content = "坏笑";

            }
            elseif(preg_match("/\/:&-\(/",$content)){

                $content = "糗大了";

            }
            elseif(preg_match("/\/:\?/",$content)){

                $content = "疑问";

            }
            elseif(preg_match("/\/::$/",$content)){

                $content = "害羞";

            }
            elseif(preg_match("/\/::P/",$content)){

                $content = "调皮";

            }
            elseif(preg_match("/\/::D/",$content)){

                $content = "呲牙";

            }
            elseif(preg_match("/\/::O/",$content)){

                $content = "惊讶";

            }
            elseif(preg_match("/\/:,@-D/",$content)){

                $content = "愉快";

            }
            elseif(preg_match("/\/:,@P/",$content)){

                $content = "偷笑";

            }
            elseif(preg_match("/\/::</",$content)){

                $content = "流泪";

            }
            elseif(preg_match("/\/:weak/",$content)){

                $content = "弱";

            }
            elseif(preg_match("/\/:<@/",$content)){

                $content = "左哼哼";

            }
            elseif(preg_match("/\/:@>/",$content)){

                $content = "右哼哼";

            }
            elseif(preg_match("/\/:wipe/",$content)){

                $content = "擦汗";

            }
            elseif(preg_match("/\/:@@/",$content)){

                $content = "拳头";

            }
            elseif(preg_match("/\/:bad/",$content)){

                $content = "差劲";

            }
            elseif(preg_match("/\/:shake/",$content)){

                $content = "发抖";

            }
            elseif(preg_match("/\/:moon/",$content)){

                $content = "月亮";

            }
            elseif(preg_match("/\/::Z/",$content)){

                $content = "睡";

            }
            elseif(preg_match("/\/:bye/",$content)){

                $content = "再见";

            }
            elseif(preg_match("/\/:beer/",$content)){

                $content = "啤酒";

            }
            elseif(preg_match("/\/::g/",$content)){

                $content = "饥饿";

            }
            elseif(preg_match("/\/:eat/",$content)){

                $content = "吃饭";

            }
            elseif(preg_match("/\/:coffee/",$content)){

                $content = "咖啡";

            }
            elseif(preg_match("/\/:sun/",$content)){

                $content = "太阳";

            }
            elseif(preg_match("/\/:hiphot/",$content)){

                $content = "街舞";

            }
            elseif(preg_match("/\/:footb/",$content)){

                $content = "足球";

            }
            elseif(preg_match("/\/:oo/",$content)){

                $content = "乒乓";

            }
            elseif(preg_match("/\/:basketb/",$content)){

                $content = "篮球";

            }
            elseif(preg_match("/\/:jump/",$content)){

                $content = "跳跳";

            }
            elseif(preg_match("/\/:circle/",$content)){

                $content = "转圈";

            }
            elseif(preg_match("/\/:skip/",$content)){

                $content = "跳绳";

            }
            elseif(preg_match("/\/:<&/",$content)){

                $content = "左太极";

            }
            elseif(preg_match("/\/:&>/",$content)){

                $content = "右太极";

            }
            elseif(preg_match("/\/:strong/",$content)){

                $content = "强";

            }
            elseif(preg_match("/\/::>/",$content)){

                $content = "憨笑";

            }
            elseif(preg_match("/\/:<L>/",$content)){

                $content = "飞吻";

            }
            elseif(preg_match("/\/::-\|/",$content)){

                $content = "尴尬";

            }
            elseif(preg_match("/\/:oY/",$content)){

                $content = "投降";

            }
            elseif(preg_match("/\/:>-\|/",$content)){

                $content = "鄙视";

            }
            elseif(preg_match("/\/::\|/",$content)){

                $content = "发呆";

            }
            elseif(preg_match("/\/:\<W\>/",$content)){

                $content = "西瓜";

            }
            elseif(preg_match("/\/:\|\-\)/",$content)){

                $content = "困";

            }
            elseif(preg_match("/\/:/",$content)){

                $content = "怄火";

            }
        }


        return $content;
    }
}

?>