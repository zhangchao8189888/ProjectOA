<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>发票到账查看</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>

<script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/monthPickerPlugin.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script type="text/javascript">
Ext.require([
    'Ext.grid.*',
    'Ext.toolbar.Paging',
    'Ext.form.field.File',
    'Ext.form.field.Number',
    'Ext.form.Panel',
    'Ext.window.MessageBox',
    'Ext.data.*'
]);

Ext.onReady(function () {

    //创建Grid
    var salTimeListGrid1 = Ext.create('Ext.grid.Panel', {
        store: fapiaoStore,
        id: 'comlist1',
        columns: [
            {text: "发票编号", width: 120, dataIndex: 'bill_no', sortable: true},
            {text: "工资日期", width: 120, dataIndex: 'salaryTime', sortable: true},
            {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
            {text: "发票金额", flex: 200, dataIndex: 'bill_value', sortable: true}
        ],
        height: 500,
        width: 550,
        x: 0,
        y: 0,
        title: '发票信息',
        disableSelection: false,
        loadMask: true,
        renderTo: 'demo',
        viewConfig: {
            id: 'gv1',
            trackOver: false,
            stripeRows: false
        },
        bbar: Ext.create('Ext.PagingToolbar', {
            store: fapiaoStore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),

        tbar: [

            '公司名称', {
                id: 'comname',
                xtype: 'textfield',
                name: 'comname'
            },
            '查看月份',
            {
                id: 'salTime',
                name: 'salTime',
                xtype: 'monthfield',
                editable: true,
                width: 130,
                labelAlign: 'right',
                format: 'Y-m'
            }
        ]

    });
    fapiaoStore.on("beforeload", function () {
        Ext.apply(fapiaoStore.proxy.extraParams, {Key: Ext.getCmp("comname").getValue(), comname: Ext.getCmp("comname").getValue(), salTime: Ext.getCmp("salTime").getValue()});

    });

    var salTimeListGrid2 = Ext.create('Ext.grid.Panel', {
        store: daozhangListstore,
        id: 'comlist2',
        columns: [
            {text: "工资日期", width: 120, dataIndex: 'daozhangTime', sortable: true},
            {text: "单位名称", flex: 200, dataIndex: 'cname', sortable: true},
            {text: "到账金额", flex: 200, dataIndex: 'daozhangValue', sortable: true}
        ],
        height: 500,
        width: 550,
        x: 550,
        y: -500,
        title: '银行进账',
        disableSelection: false,
        loadMask: true,
        renderTo: 'demo',
        viewConfig: {
            id: 'gv2',
            trackOver: false,
            stripeRows: false
        },
        bbar: Ext.create('Ext.PagingToolbar', {
            store: daozhangListstore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),

        tbar: [

            {
                xtype: 'button',
                id: 'searchSalBu1',
                handler: function (src) {
                    fapiaoStore.removeAll();
                    fapiaoStore.load({
                        params: {
                            comname: Ext.getCmp("comname").getValue(),
                            salTime: Ext.getCmp("salTime").getValue(),
                            start: 0,
                            limit: 50
                        }
                    });
                    daozhangListstore.removeAll();
                    daozhangListstore.load({
                        params: {
                            comname: Ext.getCmp("comname").getValue(),
                            salTime: Ext.getCmp("salTime").getValue(),
                            start: 0,
                            limit: 50
                        }
                    });
                },
                text: '查询',
                iconCls: 'chakan'
            },
            {
                xtype: 'button',
                id: 'tianjia',
                handler: function (src) {
                    checkSalWin();
                },
                text: '添加发票/到账',
                iconCls: 'tianjia'
            },
            {
                xtype: 'button',
                id: 'openfile',
                handler: function (src) {
                    uploadFile();
                },
                text: '上传文件',
                iconCls: 'upload'
            }
        ]
    });

    daozhangListstore.on("beforeload", function () {
        Ext.apply(daozhangListstore.proxy.extraParams, {Key: Ext.getCmp("comname").getValue(), comname: Ext.getCmp("comname").getValue(), salTime: Ext.getCmp("salTime").getValue()});
    });
    var salList = Ext.create('Ext.form.Panel', {
        bodyPadding: 10,
        width: 700,
        height: 400,
        title: '',
        items: [
            {
                xtype: 'fieldcontainer',
                fieldLabel: '请输入数据',
                defaultType: 'checkboxfield',
                items: [

                    {
                        xtype: 'textfield',
                        id: "fapiaobianhao",
                        emptyText: "请输入发票编号",
                        allowBlank: false,
                        fieldLabel: '发票编号'

                    },
                    {
                        xtype: 'textfield',
                        id: "fapiaoxiangmu",
                        emptyText: "请输入发票项目",
                        allowBlank: false,
                        fieldLabel: '发票项目'

                    },
                    {
                        xtype: 'combobox',
                        id: "gongsiming",
                        emptyText: "请输入公司名称",
                        editable: true,
                        allowBlank: false,
                        store: gongsiming,
                        listeners: {
                            select: function () {
                                gongziriqi.removeAll();
                                gongziriqi.load({
                                    params: {
                                        comid: Ext.getCmp("gongsiming").getValue()
                                    }
                                });
                            }
                        },
                        valueField: 'companyid',
                        displayField: 'companyname',
                        fieldLabel: '公司名称'
                    },
                    {
                        xtype: 'combobox',
                        id: "gongziriqi",
                        editable: false,
                        emptyText: "请选择工资日期",
                        allowBlank: false,
                        store: gongziriqi,
                        valueField: 'id',
                        displayField: 'salaryTime',
                        fieldLabel: '工资日期'
                    },
                    {
                        xtype: 'textfield',
                        id: "jine",
                        emptyText: "请输入金额",
                        allowBlank: false,
                        fieldLabel: '金额'
                    },
                    {
                        xtype: 'combobox',
                        id: "leixing",
                        editable: false,
                        emptyText: "请选择类型",
                        allowBlank: false,
                        store: {
                            fields: ['abbr', 'name'],
                            data: [
                                {"abbr": "到账", "name": "到账"},
                                {"abbr": "发票", "name": "发票"}
                            ]
                        },
                        listeners: {
                            select: function () {
                                if (this.getValue() == '发票') {
                                    Ext.getCmp("fapiaobianhao").show();
                                    Ext.getCmp("fapiaoxiangmu").show();
                                } else {
                                    Ext.getCmp("fapiaobianhao").hide();
                                    Ext.getCmp("fapiaoxiangmu").hide();
                                }
                            }
                        },
                        valueField: 'abbr',
                        displayField: 'name',
                        fieldLabel: '类型'
                    },
                    {
                        xtype: 'textareafield',
                        id: "beizhu",
                        width: 400,
                        height: 80,
                        emptyText: "请输入备注信息",
                        fieldLabel: '备注'
                    }
                ]
            }
        ],
        bbar: [
            {
                text: '提交',
                handler: function () {
                    if (Ext.getCmp("fapiaobianhao").getValue() == '' && Ext.getCmp("leixing").getValue() == '发票') {
                        Ext.Msg.alert("警告！", "请输入完整的信息！");
                    } else {
                        addXinxi();
                    }
                }
            },
            '-',
            {
                text: '清空',
                handler: function () {
                    var fapiaobianhao = Ext.getCmp("fapiaobianhao").setValue("");
                    var fapiaoxiangmu = Ext.getCmp("fapiaoxiangmu").setValue("");
                    var gongsiming = Ext.getCmp("gongsiming").setValue("");
                    var gongziriqi = Ext.getCmp("gongziriqi").setValue("");
                    var jine = Ext.getCmp("jine").setValue("");
                    var leixing = Ext.getCmp("leixing").setValue("");
                    var beizhu = Ext.getCmp("beizhu").setValue("");
                }
            }
        ]

    });
    var winSal = null;

    function checkSalWin() {
        var items = [salList];
        winSal = Ext.create('Ext.window.Window', {
            title: "增员信息", // 窗口标题
            width: 600, // 窗口宽度
            height: 500, // 窗口高度
            layout: "border",// 布局
            minimizable: true, // 最大化
            maximizable: true, // 最小化
            frame: true,
            constrain: true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
            buttonAlign: "center", // 按钮显示的位置
            modal: true, // 模式窗口，弹出窗口后屏蔽掉其他组建
            resizable: true, // 是否可以调整窗口大小，默认TRUE。
            plain: true,// 将窗口变为半透明状态。
            items: items,
            listeners: {
                //最小化窗口事件
                minimize: function (window) {
                    this.hide();
                    window.minimizable = true;
                }
            },
            closeAction: 'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
        });
        winSal.show();

    }

    //通过ajax添加信息
    function addXinxi() {
        if (Ext.getCmp("leixing").getValue() == '发票') {
            var url = "index.php?action=SalaryBill&mode=addInvoice";
        } else {
            var url = "index.php?action=SalaryBill&mode=addCheque";
        }
        Ext.Ajax.request({
            url: url,  //从json文件中读取数据，也可以从其他地方获取数据
            method: 'POST',
            params: {
                billno: Ext.getCmp("fapiaobianhao").getValue(),
                billname: Ext.getCmp("fapiaoxiangmu").getValue(),
                comId: Ext.getCmp("gongsiming").getValue(),
                salaryTime: Ext.getCmp("gongziriqi").getValue(),
                billval: Ext.getCmp("jine").getValue(),
                leixing: Ext.getCmp("leixing").getValue(),
                memo: Ext.getCmp("beizhu").getValue(),
                chequeType: 3
            },
            success: function (response) {
                winSal.hide();

            }
        });
    }

    Ext.getCmp("fapiaobianhao").hide();
    Ext.getCmp("fapiaoxiangmu").hide();
});

function uploadFile() {
    var filewin = Ext.create('Ext.form.Panel', {
        width: 500,
        frame: true,
        bodyPadding: '10 10 0',
        defaults: {
            anchor: '100%',
            allowBlank: false,
            msgTarget: 'side',
            labelWidth: 100
        },
        items: [
            {
                xtype: 'textfield',
                valueText: 'xxxxx',
                fieldLabel: '文件命名'
            },
            {
                xtype: 'filefield',
                id: 'form-file',
                name: 'photo-path',
                emptyText: '选择上传的文件',
                fieldLabel: '文件地址',
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
                                Ext.Msg.alert("提示", action.result.message);
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
        width: 510, // 窗口宽度
        height: 160, // 窗口高度
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
            //最小化窗口事件
            minimize: function (window) {
                this.hide();
                window.minimizable = true;
            }
        },
        closeAction: 'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
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
        <div id="demo"></div>
    </div>
</div>
<form id="iform" action="" method="post">
</form>
</body>
</html>
