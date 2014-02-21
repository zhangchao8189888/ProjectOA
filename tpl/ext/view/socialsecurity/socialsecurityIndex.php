<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>业务变更</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<style type="text/css">
    <!--
    A { text-decoration: none}
    -->
</style>
<script type="text/javascript">
Ext.require([
    'Ext.grid.*',
    'Ext.toolbar.Paging',
    'Ext.data.*'
]);
Ext.onReady(function () {
    var businessLogWindow = Ext.create('Ext.grid.Panel',{
        store: businessLogstore,
        selType: 'checkboxmodel',
        id : 'comlist',
        columns: [
            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
            {text: "单位名称", width: 150, dataIndex: 'companyName', sortable: true},
            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
            {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
            {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
            {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false},
            {text: "备注", width: 100, dataIndex: 'remarks', sortable: false},
            {text: "申请客服", width: 100, dataIndex: 'serviceName', sortable: false},
            {text: "办理情况", width: 200, dataIndex: 'socialSecurityStateId', sortable: false,
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<span style="color: gray"> 已取消 </span>';
                    } else if (val == 1) {
                        return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: red"> 等待办理 </span></a>';
                    } else if (val ==2) {
                        return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: blue"> 正在办理 </span></a>';
                    } else if (val ==3) {
                        return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: green"> 办理成功 </span>';
                    }
                    return val;
                }
            },
        ],
        height:600,
        width:1000,
        x:0,
        y:0,
        title: '主页',
        renderTo: 'demo',
        viewConfig: {
            id: 'gv',
            trackOver: false,
            stripeRows: false
        },
        bbar: Ext.create('Ext.PagingToolbar', {
            store: geshuiListstore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        })
    });
    businessLogWindow.getSelectionModel().on('selectionchange', function (selModel, selections) {
    }, this);
    businessLogstore.loadPage(1);

});

function changeState(updateId) {
    Ext.MessageBox.show({
        title:'更改状态',
        msg: '请选择修改的状态',
        buttonText:{ok: '正在办理', yes: '办理成功'},
        animateTarget: 'mb4',
        fn: function (btn) {
            var updateType;
            if(updateId==null){
                return false;
            }
            if("ok"==btn){
                updateType=2;
            } else if("yes"==btn){
                updateType=3;
            }else{
                return false;
            }
            var itcIds = [];
            itcIds.push(updateId);
            Ext.Ajax.request({
                url: 'index.php?action=ExtSocialSecurity&mode=updateBusiness',
                method: 'post',
                params: {
                    ids: Ext.JSON.encode(itcIds),
                    updateType:updateType
                },
                success: function (response) {
                    var text = response.responseText;
                    Ext.Msg.alert("提示",text);
                    businessLogstore.load( {
                            params: {
                                start: 0,
                                limit: 50
                            }
                        }
                    );
                }
            });
        },
        icon: Ext.MessageBox.INFO
    })
}
function checkSalWin() {
    var items=[addBusinessWindow];
    winSal = Ext.create('Ext.window.Window', {
        title: "业务变更", // 窗口标题
        width:600, // 窗口宽度
        height:360, // 窗口高度
        layout:"border",// 布局
        minimizable:true, // 最大化
        maximizable:true, // 最小化
        frame:true,
        constrain:true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
        buttonAlign:"center", // 按钮显示的位置
        modal:true, // 模式窗口，弹出窗口后屏蔽掉其他组建
        resizable:true, // 是否可以调整窗口大小，默认TRUE。
        plain:true,// 将窗口变为半透明状态。
        items:items,
        listeners: {
            //最小化窗口事件
            minimize: function(window){
                this.hide();
                window.minimizable = true;
            }
        },
        closeAction:'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });
    winSal.show();

}

</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width: 960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <div id="demo"></div>
        <div id="demo2"></div>
    </div>

</div>
</body>
</html>
