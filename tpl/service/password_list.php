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

            $('.btn').click(function(){
                searchUserList();
            }
            )

        });
        function updatePassword (uid){
            var obj = {
                uid : uid
            };
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Service&mode=updatePassword",
                    async:false,
                    data: obj,
                    dataType: "json",
                    success: function(data){
                        if (data.code ==100000) {
                            alert(data.mess);
                            searchUserList();
                        } else {
                            alert(data.msg);
                        }
                    }
                }
            );
        }
        function searchUserList(){
                var obj = {
                    e_num : $("#e_num").val()
                };
                $.ajax(
                    {
                        type: "POST",
                        url: "index.php?action=Service&mode=getUserListJson",
                        async:false,
                        data: obj,
                        dataType: "json",
                        success: function(data){
                            $("#userList").html("");
                            for(var i=0;i<data.length;i++){
                                $("#userList").append('<td>'+data[i]['e_company']+'</td>' +
                                    '<td>'+data[i]['e_name']+'</td>' +
                                    '<td>'+data[i]['name']+'</td>' +
                                    '<td>'+data[i]['e_num']+'</td>' +
                                    '<td>'+data[i]['password']+'</td>' +
                                    '<td>'+data[i]['last_login_time']+'</td>'+
                                    '<td>' +
                                    '<input type="button" class="resetPass" onclick="updatePassword('+data[i]["id"]+')" value="重置密码"/>' +
                                    '</td>'
                                );

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
        <div class="navigate">员工帐号密码查询</div>
        <div>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <th width="2%"><table border="0" cellspacing="5" cellpadding="1">
                            <?php include(ROOT."/common/tpl/left.php"); ?></table></th>
                    <td width="89%">
                        <table cellpadding=4 cellspacing=0 width="100%">
                            <tr class="form-action-bar">
                                <td width="99%"><div align="center"><strong>员工帐号密码列表</strong></div></td>
                            </tr>
                            <tr class="form-action-bar">
                                <td width="99%"><div align="center">身份证号后六位：<input type="text" name="e_num" id="e_num"/><a class="btn btn-primary btn-large theme-login"  href="javascript:;">查询</a></div></td>
                            </tr>
                        </table>
                        <!-------------------------------------------------------------------------->
                        <form method="post" id="sform" name="main" action="">
                            <table cellspacing="0" id="tab_list" class="table_list" width="100%" cellpadding="0" class="rt">
                                <hr>
                                <tr>
                                    <td style="width:15%;"><div>单位</div></td>
                                    <td style="width:15%;"><div>员工姓名</div></td>
                                    <td style="width:15%;"><div>用户名</div></td>
                                    <td style="width:15%;"><div>身份证号</div></td>
                                    <td style="width:5%;"><div>密码</div></td>
                                    <td style="width:5%;"><div>上次登录时间</div></td>
                                    <td style="width:20%;"><div>操作</div></td>
                                </tr>
                                <tbody id="userList">

                                </tbody>
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


</body>
</html>