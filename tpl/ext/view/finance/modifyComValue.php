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
                $.ajax({
                    type: "post",
                    url : "index.php?action=SalaryBill&mode=getSalaryTimeById&chequeType="+$("#chequeType").val(),
                    dataType:'json',
                    data: 'username='+username+'&password='+password,
                    success: function(json){
                        $('#result').html("姓名:" + json.username + "<br/>密码:" + json.password); //把php中的返回值显示在预定义的result定位符位置
                    }
                });
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
        <div class="theme-popover">
            <div class="theme-poptit">
                <a href="javascript:;" title="关闭" class="close">×</a>
                <h3>系统对账</h3>
            </div>
            <div class="theme-popbod dform">
                <form class="theme-signin" name="loginform" action="" method="post">
                    <ol>
                        <li><h4>你必须先登录！</h4></li>
                        <li><strong>用户名：</strong><input class="ipt" type="text" name="log" value="lanrenzhijia" size="20" /></li>
                        <li><strong>密码：</strong><input class="ipt" type="password" name="pwd" value="***" size="20" /></li>
                        <li><input class="btn btn-primary" type="submit" name="submit" value=" 登 录 " /></li>
                    </ol>
                </form>
            </div>
        </div>
        <div class="theme-popover-mask"></div>
    </div>
</div>

</body>
</html>