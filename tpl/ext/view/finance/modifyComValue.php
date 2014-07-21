<?php
//var_dump($form_data);
$salaryList=$form_data['salaryList'];
$startIndex=$form_data['startIndex'];
$total=$form_data['total'];
$pageindex=$form_data['pageindex'];
$pagesize=$form_data['pagesize'];
global $loginusername;
global $rootPath;
?>
<html>
<head>
    <title>公司对账查询</title>
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
                var jine = $(this).parent().parent().children("td").eq(2).text();
                var jiaoyiDate = $(this).parent().parent().children("td").eq(1).text();
                var companyName = $(this).parent().parent().children("td").eq(0).text();
                $('.theme-popover-mask').fadeIn(100);
                $('.theme-popover').slideDown(200);
                $('#jine').text(jine);
                $('#comName').text(companyName);
                $('#date').text(jiaoyiDate);
                /*$.ajax({
                    type: "post",
                    url : "index.php?action=SalaryBill&mode=getSalaryTimeById&chequeType="+$("#chequeType").val(),
                    dataType:'json',
                    data: 'username='+username+'&password='+password,
                    success: function(json){
                        $('#result').html("姓名:" + json.username + "<br/>密码:" + json.password); //把php中的返回值显示在预定义的result定位符位置
                    }
                });*/
            })
            $('.theme-poptit .close').click(function(){
                $('.theme-popover-mask').fadeOut(100);
                $('.theme-popover').slideUp(200);
            })
        });
        function changeTagPage(page_id, jq) {
            var jumpurl = "index.php?action=Admin&mode=toOpLog&p="+(page_id+1);
            location = jumpurl;
            return false;
        }
        function sumYue () {

        }
    </script>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <!--导航栏-->
        <div class="navigate">日志查询</div>
        <!--       <div class="manage">
               </div>
               <!--功能项
               <div class="manage">
                </div>
                    <!--搜索栏
               <div class="search" >

               </div>
               -->
        <div>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <th width="2%"><table border="0" cellspacing="5" cellpadding="1">
                            <?php include(ROOT."/common/tpl/left.php"); ?></table></th>
                    <td width="89%">
                        <table cellpadding=4 cellspacing=0 width="100%">
                            <tr class="form-action-bar">
                                <td width="99%"><div align="center"><strong>操作日志查询列表</strong></div></td>
                            </tr>
                        </table>
                        <!-------------------------------------------------------------------------->
                        <form method="post" id="sform" name="main" action="">
                            <table cellspacing="0" id="tab_list" class="table_list" width="100%" cellpadding="0" class="rt">
                                <hr>
                                <tr>
                                    <td style="width:15%;"><div>客户名称</div></td>
                                    <td style="width:15%;"><div>交易日期</div></td>
                                    <td style="width:5%;"><div>收入金额</div></td>
                                    <td style="width:10%;"><div>备注</div></td>
                                    <td style="width:10%;"><div>操作</div></td>
                                </tr>
                                <?php
                                foreach($salaryList as $row){
                                    ?>
                                    <tr>
                                        <td><?php  echo $row['companyName'];?></td>
                                        <td><?php echo $row['transactionDate'];?></td>
                                        <td><?php echo $row['accountsValue'];?></td>
                                        <td><?php echo $row['remark'];?></td>
                                        <td><a class="btn btn-primary btn-large theme-login" href="javascript:;">点击查看效果</a></td>

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
                    <ol>
                        <li><h4>请选选择单位和工资月份！</h4></li>
                        <li><strong>进账客户：</strong><span class="ipt" size="20" id="comName"></span></li>
                        <li><strong>进账金额：</strong><span class="ipt" size="20" id="jine"></span></li>
                        <li><strong>进账日期：</strong><span class="ipt" size="20" id="date"></span></li>
                        <li><strong>进账总额：</strong><div class="gover_search">
                                <div class="gover_search_form">
                                    <input type="text" class="input_search_key" id="gover_search_key" placeholder="输入公司名称">
                                </div>
                                <div class="search_suggest" id="gov_search_suggest">
                                    <ul>
                                    </ul>
                                </div>

                            </div></li>
                        <li><input class="btn btn-primary" type="submit" name="submit" value=" 查 询 " /></li>
                        <li><div style="float:left;" >	<select  name="cid" id="cid" size="5">
                                    <option value="-1">请选择工资月份</option>
                                </select></li>
                    </ol>

                </form>

            </div>
            <table border="2" style="border-style: solid;width: 500px;">
                <tr>
                    <td style="width:5%;"><div>客户</div></td>
                    <td style="width:5%;"><div>交易</div></td>
                    <td style="width:5%;"><div>收入</div></td>
                    <td style="width:5%;"><div>备注</div></td>
                    <td style="width:5%;"><div>操作</div></td>
                    <td style="width:5%;"><div>余额</div></td>
                </tr>
                <tr><td><div><span>1122</span><input type="button" onclick="sumYue()" value="减" /></div></td>
                    <td><div><span>1231</span><input type="button" onclick="sumYue()" value="减" /></div></td>
                    <td><div><span>32343</span><input type="button" onclick="sumYue()" value="减" /></div></td>
                    <td><div><span>12313</span><input type="button" onclick="sumYue()" value="减" /></div></td>
                    <td><div><span>1555</span><input type="button" onclick="sumYue()" value="减" /></div></td>
                    <td><div><span>1555</span></div></td>
                </tr>
            </table>
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
        /*$.ajax({
            type: "POST",
            url: "/a/api/GetHouseName",
            async:false,
            data: obj,
            dataType: "json",
            success: function(data){
                var aData = [];
                for(var i=0;i<data.data.length;i++){
                    //以下为根据输入返回搜索结果的模拟效果代码,实际数据由后台返回
                    if(data.data[i]){
                        aData.push(data.data[i].buildname);
                    }
                }
                //将返回的数据传递给实现搜索输入框的输入提示js类
                searchSuggest.dataDisplay(aData);
            }
        });*/
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