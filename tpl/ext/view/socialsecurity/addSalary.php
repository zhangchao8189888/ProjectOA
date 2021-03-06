<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>个人工资</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
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
Ext.onReady(function () {
    var insurancewindow = Ext.create('Ext.grid.Panel',{
        store: insurancestore,
        id : 'comlist',
        columns: [
            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
            {text: "单位名称", width: 150, dataIndex: 'companyName', sortable: true},
            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
            {text: "员工身份类别", width: 100, dataIndex: 'idClass', sortable: false},
            {text: "负责客服", width: 100, dataIndex: 'serviceName', sortable: false,hidden:true},
            {text: "未上保险原因", width: 100, dataIndex: 'unInsuranceReason', sortable: false},
            {text: "说明", width: 100, dataIndex: 'explainInfo', sortable: false},
            {text: "入职时间", width: 100, dataIndex: 'entryTime', sortable: false},
            {text: "备注", width: 100, dataIndex: 'remark', sortable: false}
        ],
        height:600,
        width:1000,
        x:0,
        y:0,
        title: '个人工资功能',
        renderTo: 'demo',
        viewConfig: {
            id: 'gv',
            trackOver: false,
            enableTextSelection:true,
            stripeRows: false
        },
        bbar: Ext.create('Ext.PagingToolbar', {
            store: insurancestore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),

        tbar : [
            {
                xtype : 'button',
                id : 'searchSalBu',
                handler : function(src) {
                    checkSalWin();
                },
                text : '添加业务',
                iconCls : 'chakan'
            }  ,
            {
                xtype : 'button',
                id : 'disType',
                hidden:true,
                value:"1" ,
                text : '显示个人工资',
                iconCls : 'chakan'
            }
        ]
    });
    insurancestore.on("beforeload", function () {
        Ext.apply(insurancestore.proxy.extraParams, {disType:"1"});
    });
    insurancewindow.getSelectionModel().on('selectionchange', function (selModel, selections) {
        Ext.apply(insurancestore.proxy.extraParams, {disType: "1"});
    }, this);
    insurancestore.loadPage(1);

});
function checkSalWin() {
    var items=[addInsurancWindow];
    winSal = Ext.create('Ext.window.Window', {
        title: "添加个人工资", // 窗口标题
        width:600, // 窗口宽度
        height:400, // 窗口高度
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
var addInsurancWindow = Ext.create('Ext.form.Panel', {
    bodyPadding: 15,
    width: 580,
    height: 460,
    items: [
        {
            xtype: 'fieldcontainer',
            fieldLabel: '请输入数据',
            defaultType: 'checkboxfield',
            items: [
                {
                    xtype: 'textfield',
                    id:"employId",
                    allowBlank: false,
                    emptyText: "请输入身份证号",
                    fieldLabel: '身份证号'
                },
                {
                    xtype: 'textfield',
                    id:"employName",
                    emptyText: "请输入姓名",
                    allowBlank: false,
                    fieldLabel: '姓名'
                },
                {
                    xtype: 'textfield',
                    id:"companyName" ,
                    emptyText: "请输入所在公司",
                    allowBlank: false,
                    fieldLabel: '单位'
                },
                {
                    xtype: 'textfield',
                    id:"unInsuranceReason" ,
                    emptyText: "请输入未上保险原因",
                    allowBlank: false,
                    fieldLabel: '未上保险原因'
                },
                {
                    xtype: 'textfield',
                    id:"explainInfo" ,
                    emptyText: "请输入说明",
                    allowBlank: false,
                    fieldLabel: '说明'
                },
                {
                    xtype: 'textfield',
                    id:"tel" ,
                    emptyText: "请输入电话号码",
                    fieldLabel: '联系方式'
                },
                {
                    id: 'entryTime',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    emptyText: "请选择入职时间",
                    allowBlank: false,
                    readOnly: false,
                    fieldLabel: '入职时间'
                },
                {
                    xtype: 'textareafield',
                    id:"remarks",
                    width:400,
                    height:80,
                    emptyText: "请输入备注信息",
                    fieldLabel: '备注'
                }
            ]
        }
    ],
    buttons: [
        {
            text: '提交',
            handler: function () {
                var submitInfo = this.up('form').getForm().isValid();
                if (!submitInfo) {
                    Ext.Msg.alert("警告！", "请输入完整的信息！");
                    return false;
                }
                this.up('form').getForm().submit(
                    {
                        url: "index.php?action=ExtSocialSecurity&mode=addInsurance",
                        method: 'POST',
                        waitTitle : '请等待' ,
                        waitMsg: '正在提交中',
                        success: function (form,action) {
                            Ext.Msg.alert("提示", action.result.info);
                            document.location = 'index.php?action=Ext&mode=toPersonSalary';
                        },
                        failure:function(form,action){
                            Ext.Msg.alert('提示',action.result.info);
                        }
                    }
                );


            }
        }
        ,
        {
            text: '清空',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ]
});
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
