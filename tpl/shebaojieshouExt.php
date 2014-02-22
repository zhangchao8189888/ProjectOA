<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>工资统计</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script type="text/javascript">
Ext.require([
    'Ext.grid.*',
    'Ext.toolbar.Paging',
    'Ext.data.*'
]);
Ext.onReady(function(){
    //创建Grid
    var salTimeListGrid = Ext.create('Ext.grid.Panel',{
        store: zengjianListstore,
        id : 'comlist',
        columns: [
            {text: "编号", width: 100, dataIndex: 'id', sortable: true},
            {text: "客服姓名", width: 100, dataIndex: 'CName', sortable: true},
            {text: "部门", width: 150, dataIndex: 'Dept', sortable: true},
            {text: "员工姓名", width: 100, dataIndex: 'EName', sortable: true},
            {text: "身份证号", width: 100, dataIndex: 'EmpNo', sortable: true},
            {text: "身份类别", width: 100, dataIndex: 'EmpType', sortable: true},
            {text: "操作标志", width: 100, dataIndex: 'zengjianbiaozhi', sortable: true},
            {text: "社保基数", width: 100, dataIndex: 'shebaojishu', sortable: true},
            {text: "外区转入/新参保", width: 200, dataIndex: 'waiquzhuanru', sortable: true},
            {text: "金额合计", width: 100, dataIndex: 'sum', sortable: true},
            {text: "用人单位基数", width: 150, dataIndex: 'danweijishu', sortable: true},
            {text: "操作人姓名", width: 150, dataIndex: 'caozuoren', sortable: true},
            {text: "申报状态", width: 100, dataIndex: 'shenbaozhuangtai', sortable: true,
                renderer: function (val, cellmeta, record) {
                    if (val == "") {
                        return '<span style="color: gray"> 已取消 </span>';
                    } else if (val == "等待受理") {
                        return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: red"> 等待办理 </span></a>';
                    } else if (val =="正在办理") {
                        return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: blue"> 正在办理 </span></a>';
                    } else if (val =="办理成功") {
                        return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: green"> 办理成功 </span>';
                    }
                    return val;
                }
            },
            {text: "备注", width: 100, dataIndex: 'beizhu', sortable: true}
        ],
        height:600,
        width:1000,
        x:0,
        y:0,
        title: '增减员查看',
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
        }),

        tbar : [
            '公司名称查询', {
                id:'comname',
                xtype : 'trigger',
                triggerClass : 'x-form-search-trigger',
                name: 'comname',
                onTriggerClick : function(src) {
                    zengjianListstore.removeAll();
                    zengjianListstore.load( {
                        params : {
                            companyName : this.getValue(),
                            zengjian : Ext.getCmp("zengjian").getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                }
            },
            '增减类型查询', {
                id:'zengjian',
                xtype : 'trigger',
                triggerClass : 'x-form-search-trigger',
                name: 'zengjian',
                onTriggerClick : function(src) {
                    zengjianListstore.removeAll();
                    zengjianListstore.load( {
                        params : {
                            companyName : Ext.getCmp("comname").getValue(),
                            zengjian : this.getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                }
            }

        ]
    });
    salTimeListGrid.getSelectionModel().on('selectionchange', function (selModel, selections) {

    }, this);

    var winSal=null;
    function checkSalWin() {
        var items=[salList];
         winSal = Ext.create('Ext.window.Window', {
            title: "增员信息", // 窗口标题
            width:600, // 窗口宽度
            height:560, // 窗口高度
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
    //通过ajax添加信息
    function addZengyuan() {
        var url = "index.php?action=SaveSalary&mode=addZengyuan";
        Ext.Ajax.request({
            url: url,  //从json文件中读取数据，也可以从其他地方获取数据
            method : 'POST',
            params: {
                yongren: Ext.getCmp("yongren").getValue(),
                waiqu: Ext.getCmp("waiqu").getValue(),
                shebao: Ext.getCmp("shebao").getValue(),
                caozuo: Ext.getCmp("caozuo").getValue(),
                leibie: Ext.getCmp("leibie").getValue(),
                kefuName: Ext.getCmp("kefuName").getValue(),
                companyName: Ext.getCmp("companyName").getValue(),
                employName: Ext.getCmp("employName").getValue(),
                employNumber: Ext.getCmp("employNumber").getValue(),
                shenbao: Ext.getCmp("shenbao").getValue(),
                beizhu: Ext.getCmp("beizhu").getValue()
            },
            success : function(response) {
                winSal.hide();
                zengjianListstore.load( {
                    params: {

                    }
                });
            }
        });
    }
    zengjianListstore.loadPage(1);
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
                updateType="正在办理";
            } else if("yes"==btn){
                updateType="办理成功";
            }else{
                return false;
            }

            Ext.Ajax.request({
                url: 'index.php?action=ExtSocialSecurity&mode=updateZengjianyuan',
                method: 'post',
                params: {
                    updateId:updateId,
                    updateType:updateType
                },
                success: function (response) {
                    var text = response.responseText;
                    Ext.Msg.alert("提示",text);
                    zengjianListstore.load( {
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
</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <div id="demo"></div>
    </div>
</div>
<form id="iform" action="" method="post">
</form>
</body>
</html>
