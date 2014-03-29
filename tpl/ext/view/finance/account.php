<?php
$comlist = $form_data['comList'];
$errorlist = $form_data['error'];
$searchType = $form_data['searchType'];
$comlist = json_encode($comlist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>导入收益</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
<link href="common/css/validator.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/MonthPickerPlugin.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<style type="text/css">
    <!--
    A { text-decoration: none}
    -->
</style>
<script language="javascript" type="text/javascript">
Ext.require([
    'Ext.grid.*',
    'Ext.toolbar.Paging',
    'Ext.data.*'
]);
Ext.onReady(function () {

    var contractWin=Ext.create("Ext.grid.Panel",{
        store:accountstore,
        width:800,
        height:500,
        selType: 'checkboxmodel',
        id : 'configGrid',
        name : 'configGrid',
        features: [{
            ftype: 'summary'
        }],
        viewConfig: {
            trackOver: false,
            enableTextSelection:true,
            stripeRows: false
        },
        columns : [
            {text: "编号", width: 80, dataIndex: 'id', sortable: true,hidden:true},
            {text: "单位编号", width: 80, dataIndex: 'companyId', sortable: true,hidden:true},
            {text: "单位名称", width: 170, dataIndex: 'companyName', sortable: true},
            {text: "交易日期", width: 100, dataIndex: 'transactionDate', sortable: true},
            {text: "交易类型", width: 170, dataIndex: 'accountsType', sortable: true,
                renderer:function(val,cellmeta,record){
                    if(val ==1){
                        return  '<span style="color:green ">收入</span>';
                    } else if(val ==2){
                        return '<span style="color:gray ">支出</span>';
                    }
                    return val;
                }
            },
            {text: "金额", width: 150, dataIndex: 'value', sortable: true},
            {text: "状态", flex: 120, dataIndex: 'salType', sortable: false,align:'center',
                renderer:function(val,cellmeta,record){
                    if(val == 0){
                        return '<a href="#" title="审核工资" onclick=updateSal(' + record.data['sal_approve_id'] + ')><span style="color: blue">等待审批</span></a>';
                    } else if(val ==1){
                        return  '<a href="#" title="审核工资" onclick=updateSal(' + record.data['sal_approve_id'] + ')><span style="color:green ">审批通过</span></a>';
                    } else if(val ==2){
                        return '<a href="#" title="审核工资" onclick=updateSal(' + record.data['sal_approve_id'] + ')><span style="color:gray ">审批未通过</span></a>';
                    }else if(val ==-1){
                        return '<span style="color:gray ">未找到审核</span>';
                    }else if(val ==-2){
                        return '<span style="color:gray ">支出业务</span>';
                    }
                    return val;
                }
            },
            {text: "备注", width: 80, dataIndex: 'remark', sortable: true}
        ],
        tbar : [
            {
                id: 'transactionDate',
                xtype: 'datefield',
                width:120,
                format: "Y-m-d",
                emptyText:"请筛选交易日",
                readOnly: false,
                anchor: '95%'
            } ,
            {
                xtype:'textfield',
                id:'companyName',
                width:150,
                emptyText:"筛选公司"
            },
            {
                xtype : 'button',
                id : 'searchw',
                handler : function(src) {
                    accountstore.removeAll();
                    accountstore.load({
                        params: {
                            companyName: Ext.getCmp("companyName").getValue(),
                            transactionDate: Ext.getCmp("transactionDate").getValue(),
                            start: 0,
                            limit: 50
                        }
                    });
                },
                text : '筛选',
                iconCls : 'chakan'
            },
            {
                xtype : 'button',
                id : 'import',
                handler : function(src) {
                    uploadFile();
                },
                text : '导入文件',
                iconCls : 'chakan'
            }
        ],
        bbar: Ext.create('Ext.PagingToolbar', {
            id:"xx",
            store: accountstore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        })
    });
    accountstore.on("beforeload", function () {
        Ext.apply(accountstore.proxy.extraParams, {});
    });
    accountstore.loadPage(1);

    var items=[contractWin];
    var indexWin = Ext.create('Ext.window.Window', {
        title: "欢迎使用收益导入功能", // 窗口标题
        width:810, // 窗口宽度
        height:540, // 窗口高度
        layout:"border",// 布局
        frame:true,
        renderTo:"tableList",
        constrain:true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
        buttonAlign:"left", // 按钮显示的位置
        modal:true, // 模式窗口，弹出窗口后屏蔽掉其他组建
        resizable:true, // 是否可以调整窗口大小，默认TRUE。
        plain:true,// 将窗口变为半透明状态。
        items:items,
        listeners: {
            //最小化窗口事件
            minimize: function(window){
                this.hide();
                mk.hide();
                window.minimizable = true;
            },
            close:function(){
                indexWin.show();
            }
        },
        closeAction:'hide'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });
    indexWin.show();
});
function uploadFile() {
    var filewin = Ext.create('Ext.form.Panel', {
        width: 400,
        frame: true,
        bodyPadding: '10 10 0',
        defaults: {
            anchor: '100%',
            allowBlank: false,
            msgTarget: 'side',
            labelWidth: 80
        },
        items: [
            {
                xtype: 'displayfield',
                value:"<span style='color: blue'>请创建后缀名为“.xls”的文件进行上传(重名将会覆盖)<br>系统会读取前五列数据，分别为：</span>" +
                    "<br>“单位名称”，“交易日期”，“支出”，“收入”，“备注”" +
                    "<br><span style='color: red'>注：一条数据只允许一次支出或收入，同时填写只读取支出。</span>"
            },
            {
                xtype: 'filefield',
                id: 'form-file',
                name: 'photo-path',
                emptyText: '选择上传的文件',
                buttonText: '选择文件',
                buttonConfig: {
                    iconCls: 'upload-icon'
                }
            }
        ],

        buttons: [
            {
                text: '上传',
                handler: function () {
                    var form = this.up('form').getForm();
                    if (form.isValid()) {
                        form.submit({
                            url: 'index.php?action=ExtSalary&mode=upload',
                            isUpload: true,
                            waitMsg: '上传中.',
                            success: function (form, action) {
                                Ext.MessageBox.show({
                                    title: '提示',
                                    msg: '上传成功！是否导入！',
                                    width: 250,
                                    buttonText: {ok: '导入', yes: '取消'},
                                    animateTarget: 'mb4',
                                    fn: function (btn) {
                                        if ("ok" == btn) {
                                            Ext.Ajax.request({
                                                url: "index.php?action=ExtSalary&mode=importAccounts",  //从json文件中读取数据，也可以从其他地方获取数据
                                                method : 'POST',
                                                params: {
                                                    filename : action.result.message
                                                },
                                                success : function(response) {
                                                    var json = Ext.JSON.decode(response.responseText);
                                                    Ext.Msg.alert("提示", json['message']);
                                                    document.location = 'index.php?action=Ext&mode=toAccount';
                                                }
                                            });
                                        }
                                        else if ("yes" == btn) {
                                            document.location = 'index.php?action=Ext&mode=toAccount';
                                        }
                                        return false;
                                    },
                                    icon: Ext.MessageBox.INFO
                                })
                            },
                            failure: function (form, action) {
                                Ext.Msg.alert("警告", action.result.message);
                            }
                        });
                    }
                }
            },
            {
                text: '清空',
                handler: function () {
                    this.up('form').getForm().reset();
                }
            }
        ]
    });
    var items = [filewin];
    uploadfilewindow = Ext.create('Ext.window.Window', {
        title: "上传", // 窗口标题
        width: 410, // 窗口宽度
        height: 200, // 窗口高度
        layout: "border",// 布局
        minimizable: false, // 最大化
        maximizable: false, // 最小化
        frame: true,
        constrain: true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
        buttonAlign: "center", // 按钮显示的位置
        modal: true, // 模式窗口，弹出窗口后屏蔽掉其他组建
        resizable: true, // 是否可以调整窗口大小，默认TRUE。
        plain: true,// 将窗口变为半透明状态。
        items: items,
        listeners: {
            close:function(){

            }
        },
        closeAction: 'destroy'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });
    uploadfilewindow.show();
}
</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <span id="tableList"></span>
    </div>
</div>
</body>
</html>