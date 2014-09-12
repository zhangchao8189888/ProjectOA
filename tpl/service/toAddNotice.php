<?php
//var_dump($salaryList);
$noticeList=$form_data['noticeList'];
$startIndex=$form_data['startIndex'];
$total=$form_data['total'];
$pageindex=$form_data['pageindex'];
$pagesize=$form_data['pagesize'];
?>
<html>
<head>
<title>添加公告</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />
<link href="common/css/validator.css" rel="stylesheet" type="text/css" />
<link href="common/css/lanrenzhijia.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    #gov_search_suggest{margin-left: 185px;}
    /* mailBox */
    #gover_search_key{ height: 20px;margin-left: 20px;width: 300px;}
    #gov_search_suggest{background:#fff;border:1px solid #ddd;padding:3px 5px 5px;position:absolute;z-index:9999;display:none;-webkit-box-shadow:0px 2px 7px rgba(0, 0, 0, 0.35);-moz-box-shadow:0px 2px 7px rgba(0, 0, 0, 0.35);}
    #gov_search_suggest p{width:100%;margin:0;padding:0;height:20px;line-height:20px;clear:both;font-size:12px;color:#ccc;cursor:default;}
    #gov_search_suggest ul{padding:0;margin:0;}
    #gov_search_suggest li{font-size:12px;height:22px;line-height:22px;color:#939393;font-family:'Tahoma';list-style:none;cursor:pointer;overflow:hidden;}
    #gov_search_suggest .cmail{color:#000;background:#e8f4fc;}
</style>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script src="common/js/formValidator.js" type="text/javascript" charset="UTF-8"></script>
<script src="common/js/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script>
<script language="javascript" type="text/javascript">
    $(function() {
        var amount = <?php echo $total ;?>;
        var rowsize = <?php echo $pagesize ;?>;
        var current_page = <?php echo $pageindex-1 ;?>;
        $(".common_page").pagination(amount, {
            items_per_page:rowsize,
            current_page:current_page,
            num_display_entries:10,
            num_edge_entries:1,
            prev_text:"<<上一页",
            next_text:"下一页>>",
            callback:changeTagPage
        });
        $('.theme-login').click(function(){

            $('.theme-popover-mask').fadeIn(100);
            $('.theme-popover').slideDown(200);
        })
        $('.theme-delete').click(function(){
            var rowId = $(this).attr("row-data");
            var obj = {
                notice_id : rowId
            };
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Service&mode=delNotice",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        if (data.code ==10000) {
                            alert(data.msg);
                            window.location.href='index.php?action=Service&mode=toAddNotice';
                        } else {
                            alert(data.msg);
                        }
                    }
                }
            );
        })
        $('.theme-modify').click(function(){
            var rowId = $(this).attr("row-data");
            var content = $(this).parent().parent().children("td").eq(2).text();
            var title = $(this).parent().parent().children("td").eq(1).text();
            var companyName = $(this).parent().parent().children("td").eq(0).text();
            $('.theme-popover-mask').fadeIn(100);
            $('.theme-popover').slideDown(200);
            $("#noticeId").val(rowId);
            $("#content").val(content);
            $("#title").val(title);
            $("#gover_search_key").val(companyName);
        })
        $('.theme-poptit .close').click(function(){
            $('.theme-popover-mask').fadeOut(100);
            $('.theme-popover').slideUp(200);
        })
        $('#searchBtn').click(function(){
            var obj = {
                "keyword" : $("#gover_search_key").val()
            };
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=AjaxJson&mode=getSalTimeListByComNameJson",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        var aData = [];
                        for(var i=0;i<data.length;i++){
                            //以下为根据输入返回搜索结果的模拟效果代码,实际数据由后台返回
                            if(data[i]){
                                if (i == 0){
                                    $("#salTime").append('<option value ="'+data[i].id+'"   selected="selected" >'+data[i].salaryTime+'</option>');
                                } else {
                                    $("#salTime").append('<option value ="'+data[i].id+'" >'+data[i].salaryTime+'</option>');
                                }

                            }
                        }
                    }
                }
            );
        })

    });
    function changeTagPage(page_id, jq) {
        var jumpurl = "index.php?action=Admin&mode=toOpLog&p="+(page_id+1);
        location = jumpurl;
        return false;
    }
    function sumYue (vals) {
        var jine = parseFloat($("#"+vals).text());
        jine = parseFloat($("#yue").text()) - jine;
        jine = Math.round(jine*100)/100;
        $("#yue").text(jine);
        $("#"+vals+"_btn").hide();
        $("#"+vals+"_btn").attr("data-value",1);
    }
    function saveNotice (type) {
        var obj = {
            company_name : $("#gover_search_key").val(),
            title : $("#title").val(),
            content : $("#content").val()
        };
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Service&mode=addNotice",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    if (data.code ==10000) {
                        alert(data.msg);
                        window.location.href='index.php?action=Service&mode=toAddNotice';
                    } else {
                        alert(data.msg);
                    }
                }
            }
        );
    }
    function updateNotice (){
        var rowId = $(this).attr("row-data");
        var obj = {
            notice_id : rowId,
            title : $("#title").val(),
            content : $("#content").val()
        };
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Service&mode=updateNotice",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    if (data.code ==10000) {
                        alert(data.msg);
                        window.location.href='index.php?action=Service&mode=toAddNotice';
                    } else {
                        alert(data.msg);
                    }
                }
            }
        );
    }
</script>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate">公告查询</div>
        <div>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <th width="2%"><table border="0" cellspacing="5" cellpadding="1">
                            <?php include(ROOT."/common/tpl/left.php"); ?></table></th>
                    <td width="89%">
                        <table cellpadding=4 cellspacing=0 width="100%">
                            <tr class="form-action-bar">
                                <td width="99%"><div align="center"><strong>公司公告列表</strong></div></td>
                            </tr>
                            <tr class="form-action-bar">
                                <td width="99%"><div align="center"><a class="btn btn-primary btn-large theme-login"  href="javascript:;">添加公告</a></div></td>
                            </tr>
                        </table>
                        <!-------------------------------------------------------------------------->
                        <form method="post" id="sform" name="main" action="">
                            <table cellspacing="0" id="tab_list" class="table_list" width="100%" cellpadding="0" class="rt">
                                <hr>
                                <tr>
                                    <td style="width:15%;"><div>客户名称</div></td>
                                    <td style="width:15%;"><div>公告标题</div></td>
                                    <td style="width:5%;"><div>公告内容</div></td>
                                    <td style="width:5%;"><div>更新时间</div></td>
                                    <td style="width:20%;"><div>操作</div></td>
                                </tr>
                                <?php
                                foreach($noticeList as $row){
                                    ?>
                                    <tr>
                                        <td><?php  echo $row['company_name'];?></td>
                                        <td><?php echo $row['title'];?></td>
                                        <td><?php echo $row['content'];?></td>
                                        <td><span style="color: green"><?php echo $row['update_time'];?></span></td>
                                        <td><a class="btn btn-primary btn-large theme-add" row-data="<?php  echo $row['id'];?>" href="javascript:;">删除</a><a class="btn btn-primary btn-large theme-modify" row-data="<?php  echo $row['id'];?>" href="javascript:;">修改</a></td>

                                    </tr>
                                <?php
                                }
                                ?>
                            </table><br />
                            <!--分页-->
                            <div class="bottom">
                                <div class="common_page"></div>
                                <div class="total_page">共 <span class="redtitle"><?php echo $total ;?></span> 条记录</div>
                            </div>
                        </form></td>
                </tr>
                <!-------------------------------------------------------------------------->
            </table>
        </div>
        <div class="theme-popover" style="height: 500px">
            <div class="theme-poptit">
                <a href="javascript:;" title="关闭" class="close">×</a>
                <h3>系统对账</h3>
            </div>
            <div class="theme-popbod dform">
                <form class="theme-signin" name="loginform" action="" method="post">
                    <input type="hidden" id="noticeId" value=""/>
                    <ol>
                        <li><strong>客户名称：</strong><div class="gover_search">
                                <div class="gover_search_form">
                                    <input type="text" class="input_search_key" id="gover_search_key" placeholder="输入公司名称">
                                </div>
                                <div class="search_suggest" id="gov_search_suggest">
                                    <ul>
                                    </ul>
                                </div>

                            </div></li>
                        <li><div style="float:left;" > 标题：<input type="text" size="50px" id ="title" /></div></li>
                        <li><div style="float:left;" > 内容：<textarea id="content" value="" style="width: 400px;height: 200px">

                                </textarea></div></li>
                        <li><div style="float:left;" ><div align="center"><a class="btn btn-primary btn-large theme-login"  href="javascript:saveNotice();">添加</a></div></li>
                        <li><div style="float:left;" ><div align="center"><a class="btn btn-primary btn-large theme-login"  href="javascript:updateNotice();">修改</a></div></li>
                    </ol>

                </form>

            </div>

        </div>
        <div class="theme-popover-mask"></div>

    </div>
</div>
<script type="text/javascript">

    //实现搜索输入框的输入提示js类
    function oSearchSuggest(searchFuc){
        var input = $('#gover_search_key');
        var suggestWrap = $('#gov_search_suggest');
        var key = "";
        var init = function(){
            input.bind('keyup',sendKeyWord);
            input.bind('blur',function(){setTimeout(hideSuggest,1000);})
        }
        var hideSuggest = function(){
            suggestWrap.hide();
        }


        //发送请求，根据关键字到后台查询
        var sendKeyWord = function(event){
            if(suggestWrap.css('display')=='block' && event.keyCode == 38 || event.keyCode == 40 || event.keyCode == 13){
                var current = suggestWrap.find('li.cmail');
                if(event.keyCode == 38){
                    if(current.length>0){
                        var prevLi = current.removeClass('cmail').prev();
                        if(prevLi.length>0){
                            prevLi.addClass('cmail');
                            input.val(prevLi.html());
                        }
                    }else{
                        var last = suggestWrap.find('li:last');
                        last.addClass('cmail');
                        input.val(last.html());
                    }

                }else if(event.keyCode == 40){
                    if(current.length>0){
                        var nextLi = current.removeClass('cmail').next();
                        if(nextLi.length>0){
                            nextLi.addClass('cmail');
                            input.val(nextLi.html());
                        }
                    }else{
                        var first = suggestWrap.find('li:first');
                        first.addClass('cmail');
                        input.val(first.html());
                    }
                }else if(event.keyCode == 13){
                    input.val(current.html());
                    hideSuggest();
                }else{
                    suggestWrap.hide();
                }

                //输入字符
            }else{
                var valText = $.trim(input.val());
                if(valText ==''||valText==key){
                    suggestWrap.hide();
                    return;
                }
                searchFuc(valText);
                key = valText;
            }

        }
        //请求返回后，执行数据展示
        this.dataDisplay = function(data){
            if(data.length<=0){
                suggestWrap.hide();
                return;
            }

            //往搜索框下拉建议显示栏中添加条目并显示
            var li;
            var tmpFrag = document.createDocumentFragment();
            suggestWrap.find('ul').html('');
            for(var i=0; i<data.length; i++){
                li = document.createElement('LI');
                li.innerHTML = data[i];
                tmpFrag.appendChild(li);
            }
            suggestWrap.find('ul').append(tmpFrag);
            suggestWrap.show();

            //为下拉选项绑定鼠标事件
            suggestWrap.find('li').hover(function(){
                suggestWrap.find('li').removeClass('cmail');
                $(this).addClass('cmail');

            },function(){
                $(this).removeClass('cmail');
            }).click(function(){
                    input.val(this.innerHTML);
                    suggestWrap.hide();
                });
        }
        init();
    };

    //实例化输入提示的JS,参数为进行查询操作时要调用的函数名
    var searchSuggest =  new oSearchSuggest(sendKeyWordToBack);

    //这是一个模似函数，实现向后台发送ajax查询请求，并返回一个查询结果数据，传递给前台的JS,再由前台JS来展示数据。本函数由程序员进行修改实现查询的请求
    //参数为一个字符串，是搜索输入框中当前的内容
    function sendKeyWordToBack(keyword){
        if (!keyword) {
            searchSuggest.dataDisplay([]);
            return;
        }
        var obj = {
            "keyword" : keyword
        };
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=AjaxJson&mode=getCompanyListByName",
                async:false,
                data: obj,
                dataType: "json",
                success: function(data){
                    var aData = [];
                    for(var i=0;i<data.length;i++){
                        //以下为根据输入返回搜索结果的模拟效果代码,实际数据由后台返回
                        if(data[i]){
                            aData.push(data[i].company_name);
                        }
                    }
                    //将返回的数据传递给实现搜索输入框的输入提示js类
                    searchSuggest.dataDisplay(aData);
                }
            }
        );
        /*var aData = [];
         aData.push('返回数据1');
         aData.push('返回数据2');
         aData.push('不是有的人天生吃素的');
         aData.push('不是有的人天生吃素的');
         aData.push('2012是真的');
         aData.push('2012是假的');
         //将返回的数据传递给实现搜索输入框的输入提示js类
         searchSuggest.dataDisplay(aData);*/

    }

</script>
</body>
</html>