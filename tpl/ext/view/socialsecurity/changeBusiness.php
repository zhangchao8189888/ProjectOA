<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>业务变更</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
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
                {text: "办理情况", width: 200, dataIndex: 'socialSecurityStateId', sortable: false,
                    renderer: function (val, cellmeta, record) {
                        if (val == 0) {
                            return '<span style="color: gray"> 已取消 </span>';
                        }
                        if (val == 1) {
                            return '<span style="color: blue"> 等待办理 </span>';
                        } else if (val =2) {
                            return '<span style="color: green"> 办理成功 </span>';
                        }
                        return val;
                    }
                },
            ],
            height:600,
            width:1000,
            x:0,
            y:0,
            title: '变更业务',
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
                        var model = businessLogWindow.getSelectionModel();
                        var sel=model.getSelection();
                        if (sel) {
                            var ids = [];
                            for(var i = 0; i < sel.length ;i++){
                                ids.push(sel[i].data.id);
                            }
                            Ext.Ajax.request({
                                url: 'index.php?action=ExtSocialSecurity&mode=updateBusiness',
                                method: 'post',
                                params: {
                                    ids: Ext.JSON.encode(ids),
                                    updateType:0
                                },
                                success: function (response) {
                                    var text = response.responseText;
                                    alert(text);
                                    businessLogstore.load( {
                                            params: {
                                                start: 0,
                                                limit: 50
                                            }
                                        }
                                    );
                                }
                            });

                        } else {
                            alert('请选择一条记录');
                        }

                    },
                    text : '取消提交',
                    iconCls : 'shanchu'
                },

                {
                    xtype : 'button',
                    id : 'searchSalBu',
                    handler : function(src) {
                        checkSalWin();
                    },
                    text : '申请变更业务',
                    iconCls : 'chakan'
                }
            ]
        });
        businessLogWindow.getSelectionModel().on('selectionchange', function (selModel, selections) {
        }, this);
        businessLogstore.loadPage(1);

    });
    function checkSalWin() {
        var items=[window];
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
    var window = Ext.create('Ext.form.Panel', {
        bodyPadding: 15,
        width: 700,
        height: 320,
        items: [
            {
                xtype: 'fieldcontainer',
                fieldLabel: '请输入数据',
                defaultType: 'checkboxfield',
                items: [
                    {
                        xtype: 'textfield',
                        id:"employNumber",
                        emptyText: "请输入身份证号",
                        onBlur:function(){
                            var title="修改余额";
                            var url = "index.php?action=ExtSalary&mode=searchEmploy";
                            Ext.Ajax.request({
                                url: url,  //从json文件中读取数据，也可以从其他地方获取数据
                                method : 'POST',
                                params: {
                                    employNumber: Ext.getCmp("employNumber").getValue()
                                },
                                success: function(response){
                                    var json = Ext.JSON.decode(response.responseText);
                                    Ext.getCmp("employName").setValue(json.e_name);
                                    Ext.getCmp("companyName").setValue(json.e_company);

                                }
                            });

                        } ,
                        fieldLabel: '身份证号<span style="color: red;font-size: 12px">(必填)</span>'
                    },
                    {
                        xtype: 'textfield',
                        id:"companyName" ,
                        emptyText: "选择公司",
                        allowBlank: false,
                        fieldLabel: '单位'
                    },
                    {
                        xtype: 'textfield',
                        id:"employName",
                        emptyText: "请输入姓名",
                        fieldLabel: '姓名'
                    },
                    {
                        xtype: 'textfield',
                        id:"business",
                        emptyText: "请输入办理的业务",
                        fieldLabel: '办理的业务'
                    },
                    {
                        xtype: 'combobox',
                        id:"employState" ,
                        emptyText: "请选择社保状态",
                        editable: false,
                        allowBlank: false,
                        store: {
                            fields: ['abbr', 'name'],
                            data: [
                                {"abbr": "1", "name": "在职"},
                                {"abbr": "2", "name": "离职"},
                                {"abbr": "3", "name": "合同到期"},
                            ]
                        },
                        valueField: 'abbr',
                        displayField: 'name',
                        fieldLabel: '员工状态'
                    },
                    {
                        xtype: 'textareafield',
                        id:"remarks",
                        width:400,
                        height:80,
                        emptyText: "请输入备注信息",
                        fieldLabel: '备注'
                    },
                ]
            }
        ],
        bbar: [
            {
                text: '提交',
                handler: function () {
                    var companyName =  Ext.getCmp("companyName").getValue();
                    var employName  =  Ext.getCmp("employName").getValue();
                    var employNumber  =  Ext.getCmp("employNumber").getValue();
                    var business    =   Ext.getCmp("business").getValue();
                    var employState =   Ext.getCmp("employState").getValue();
                    var remarks =   Ext.getCmp("remarks").getValue();
                    Ext.Ajax.request({
                        url: "index.php?action=ExtSocialSecurity&mode=changeBusiness",
                        method : 'POST',
                        params: {
                            companyName:companyName,
                            employName:employName,
                            employNumber:employNumber,
                            business:business,
                            employState:employState,
                            remarks:remarks
                        },
                        success : function(response) {
                            var text=   response.responseText;
                            alert(text);
                            document.location='index.php?action=Ext&mode=toBusiness';
                        }
                    });

                }
            },
            '-',
            {
                text: '清空',
                handler: function () {
                    var companyName =  Ext.getCmp("companyName").setValue("");
                    var employName  =  Ext.getCmp("employName").setValue("");
                    var employNumber  =  Ext.getCmp("employNumber").setValue("");
                    var business    =   Ext.getCmp("business").setValue("");
                    var employState =   Ext.getCmp("employState").setValue("");
                    var socialSecurityState =   Ext.getCmp("socialSecurityState").setValue("");
                    var remarks =   Ext.getCmp("remarks").setValue("");
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
