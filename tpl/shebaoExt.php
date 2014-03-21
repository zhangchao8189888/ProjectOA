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
<script language="javascript" type="text/javascript"src="tpl/ext/js/MonthPickerPlugin.js" charset="utf-8"></script>
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
        selType: 'checkboxmodel',
        id : 'comlist',
        columns: [
            {text: "编号", width: 100, dataIndex: 'id', sortable: true},
            {text: "提交时间", width: 100, dataIndex: 'submitTime', sortable: true},
            {text: "客服姓名", width: 100, dataIndex: 'CName', sortable: true},
            {text: "部门", width: 150, dataIndex: 'Dept', sortable: true},
            {text: "员工姓名", width: 100, dataIndex: 'EName', sortable: true},
            {text: "身份证号", width: 100, dataIndex: 'EmpNo', sortable: true},
            {text: "身份类别", width: 100, dataIndex: 'EmpType', sortable: true},
            {text: "联系方式", width: 100, dataIndex: 'tel', sortable: true},
            {text: "操作标志", width: 100, dataIndex: 'zengjianbiaozhi', sortable: true},
            {text: "社保基数", width: 100, dataIndex: 'shebaojishu', sortable: true},
            {text: "公积金基数", width: 100, dataIndex: 'gongjijinjishu', sortable: true},
            {text: "外区转入/新参保", width: 200, dataIndex: 'waiquzhuanru', sortable: true},
            {text: "社保金额合计", width: 100, dataIndex: 'sum', sortable: true},
            {text: "公积金金额合计", width: 100, dataIndex: 'gongjijinsum', sortable: true},
            {text: "用人单位基数", width: 150, dataIndex: 'danweijishu', sortable: true},
            {text: "操作人姓名", width: 150, dataIndex: 'caozuoren', sortable: true},
            {text: "申报状态", width: 100, dataIndex: 'shenbaozhuangtai', sortable: true},
            {text: "联系方式", width: 100, dataIndex: 'tel', sortable: true},
            {text: "备注", width: 100, dataIndex: 'beizhu', sortable: true}
        ],
        height:600,
        width:1000,
        x:0,
        y:0,
        title: '增减员添加',
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
            {
                xtype: 'button',
                id: 'bt_deleteDocument',
                handler: function (src) {
                    var model = salTimeListGrid.getSelectionModel();
                    var sel=model.getSelection();
                    if (sel) {
                        var ids = [];
                        for(var i = 0; i < sel.length ;i++){
                            ids.push(sel[i].data.id);
                        }
                        Ext.Ajax.request({
                            url: 'index.php?action=ExtSalary&mode=deleteZengjianyuan',
                            method: 'post',
                            params: {
                                ids: Ext.JSON.encode(ids)
                            },
                            success: function (response) {
                                var text = response.responseText;

                                zengjianListstore.load( {
                                        params: {

                                        }
                                    }
                                );
                            }
                        });

                    } else {
                        alert('请选择一条记录');
                    }

                },
                text : '删除',
                iconCls : 'shanchu'
            },

            {
                xtype : 'button',
                id : 'searchSalBu',
                handler : function(src) {
                    checkSalWin();
                },
                text : '增员按钮',
                iconCls : 'chakan'
            },
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
                            STime :  Ext.getCmp("STime").getValue(),
                            zengjian : Ext.getCmp("zengjian").getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                }
            },
            {
                xtype: 'combobox',
                id:"zengjian",
                emptyText: "筛选增减员类型",
                editable: false,
                store: {
                    fields: ['abbr', 'name'],
                    data: [
                        {"abbr": "增员", "name": "增员"},
                        {"abbr": "减员", "name": "减员"}
                    ]
                },
                valueField: 'abbr',
                displayField: 'name'
            },
            {
                id:'STime',
                name: 'STime',
                xtype : 'monthfield',
                editable: false,
                width: 150,
                labelAlign: 'right',
                format: 'Y-m'
            },
            {
                xtype : 'button',
                id : 'chaxun',
                handler : function(src) {
                    zengjianListstore.removeAll();
                    zengjianListstore.load( {
                        params : {
                            companyName :  Ext.getCmp("comname").getValue(),
                            STime :  Ext.getCmp("STime").getValue(),
                            zengjian : Ext.getCmp("zengjian").getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                },
                text : '查询',
                iconCls : 'chaxun'
            }

        ]
    });
    salTimeListGrid.getSelectionModel().on('selectionchange', function (selModel, selections) {

    }, this);
    var salList = Ext.create('Ext.form.Panel', {
        bodyPadding: 10,
        width: 700,
        height: 500,
        title: '',
        items: [
            {
                xtype: 'fieldcontainer',
                fieldLabel: '请输入数据',
                defaultType: 'checkboxfield',
                items: [

                    {
                        xtype: 'textfield',
                        id:"companyName" ,
                        emptyText: "请输入单位名称",
                        allowBlank: false,
                        fieldLabel: '单位'

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
                        id:"employNumber",
                        emptyText: "请输入身份证号",
                        allowBlank: false,
                        fieldLabel: '身份证号'
                    },
                    {
                        xtype: 'combobox',
                        id:"leibie" ,
                        emptyText: "请选择身份类别",
                        editable: false,
                        allowBlank: false,
                        store: {
                            fields: ['abbr', 'name'],
                            data: [
                                {"abbr": "本市城镇职工", "name": "本市城镇职工"},
                                {"abbr": "外埠城镇职工", "name": "外埠城镇职工"},
                                {"abbr": "本市农村劳动力", "name": "本市农村劳动力"},
                                {"abbr": "外地农村劳动力", "name": "外地农村劳动力"}
                            ]
                        },
                        valueField: 'abbr',
                        displayField: 'name',
                        fieldLabel: '身份类别'
                    },
                    {
                        xtype: 'combobox',
                        id:"caozuo" ,
                        editable: false,
                        emptyText: "请选择操作状态",
                        allowBlank: false,
                        store: {
                            fields: ['abbr', 'name'],
                            data: [
                                {"abbr": "增员", "name": "增员"},
                                {"abbr": "减员", "name": "减员"}
                            ]
                        },
                        valueField: 'abbr',
                        displayField: 'name',
                        fieldLabel: '操作状态'
                    },
                    {
                        xtype: 'textfield',
                        id:"shebao",
                        emptyText: "请输入社保基数",
                        fieldLabel: '社保基数'
                    },
                    {
                        xtype: 'textfield',
                        id:"gongjijin",
                        emptyText: "请输入公积金基数",
                        fieldLabel: '公积金基数'
                    },
                    {
                        xtype: 'combobox',
                        id:"waiqu" ,
                        editable: false,
                        emptyText: "请选择操作状态",
                        allowBlank: false,
                        store: {
                            fields: ['abbr', 'name'],
                            data: [
                                {"abbr": "外区转入", "name": "外区转入"},
                                {"abbr": "新参保", "name": "新参保"}
                            ]
                        },
                        valueField: 'abbr',
                        displayField: 'name',
                        fieldLabel: '外区转入/新参保'
                    },

                    {
                        xtype: 'combobox',
                        id:"yongren" ,
                        editable: false,
                        emptyText: "请选择用人单位基数是否有",
                        allowBlank: false,
                        store: {
                            fields: ['abbr', 'name'],
                            data: [
                                {"abbr": "是", "name": "是"},
                                {"abbr": "否", "name": "否"}
                            ]
                        },
                        valueField: 'abbr',
                        displayField: 'name',
                        fieldLabel: '用人单位基数'
                    },
                    {
                        xtype: 'textfield',
                        id:"tel",
                        emptyText: "请输入电话号码",
                        fieldLabel: '联系方式'
                    },
                    {
                        xtype: 'textareafield',
                        id:"beizhu",
                        width:400,
                        height:80,
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
                    addZengyuan();
                }
            },
            '-',
            {
                text: '清空',
                handler: function () {
                    var gongjijin =  Ext.getCmp("gongjijin").setValue("");
                    var yongren =  Ext.getCmp("yongren").setValue("");
                    var waiqu =  Ext.getCmp("waiqu").setValue("");
                    var shebao =  Ext.getCmp("shebao").setValue("");
                    var caozuo =  Ext.getCmp("caozuo").setValue("");
                    var leibie =  Ext.getCmp("leibie").setValue("");
                    var companyName =  Ext.getCmp("companyName").setValue("");
                    var employName  =  Ext.getCmp("employName").setValue("");
                    var employNumber  =  Ext.getCmp("employNumber").setValue("");
                    var tel =   Ext.getCmp("tel").setValue("");
                    var beizhu =   Ext.getCmp("beizhu").setValue("");
                }
            }
        ]

    });
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
                gongjijin: Ext.getCmp("gongjijin").getValue(),
                yongren: Ext.getCmp("yongren").getValue(),
                waiqu: Ext.getCmp("waiqu").getValue(),
                shebao: Ext.getCmp("shebao").getValue(),
                caozuo: Ext.getCmp("caozuo").getValue(),
                leibie: Ext.getCmp("leibie").getValue(),
                companyName: Ext.getCmp("companyName").getValue(),
                employName: Ext.getCmp("employName").getValue(),
                employNumber: Ext.getCmp("employNumber").getValue(),
                beizhu: Ext.getCmp("beizhu").getValue(),
                tel: Ext.getCmp("tel").getValue()
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
