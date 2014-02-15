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
        var window = Ext.create('Ext.form.Panel', {
            bodyPadding: 10,
            width: 700,
            height: 400,
            title: '业务变更',
            items: [
                {
                    xtype: 'fieldcontainer',
                    fieldLabel: '请输入数据',
                    defaultType: 'checkboxfield',
                    items: [
                        {
                            xtype: 'combobox',
                            id:"companyName" ,
                            emptyText: "选择公司",
                            triggerAction:"all",
                            allowBlank: false,
                            store: managerCom,
                            valueField: 'abbr',
                            displayField: 'name',
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
                                        Ext.getCmp("employName").setValue(response["e_name"]);
                                    }
                                });

                            } ,
                            fieldLabel: '身份证号'
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
                                    {"abbr": "yes", "name": "在职"},
                                    {"abbr": "no", "name": "离职"},
                                ]
                            },
                            valueField: 'abbr',
                            displayField: 'name',
                            fieldLabel: '社保状态'
                        },
                        {
                            xtype: 'combobox',
                            id:"socialSecurityState" ,
                            editable: false,
                            emptyText: "请选择状态",
                            allowBlank: false,
                            store: {
                                fields: ['abbr', 'name'],
                                data: [
                                    {"abbr": "yes", "name": "完成"},
                                    {"abbr": "no", "name": "未完成"},
                                ]
                            },
                            valueField: 'abbr',
                            displayField: 'name',
                            fieldLabel: '社保经办状态'
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
                        var socialSecurityState =   Ext.getCmp("socialSecurityState").getValue();
                        var remarks =   Ext.getCmp("remarks").getValue();
                        alert(companyName)   ;
                    }
                },
                '-',
                {
                    text: '清空',
                    handler: function () {
                        var companyName =  Ext.getCmp("companyName").setValue(" ");
                        var employName  =  Ext.getCmp("employName").setValue(" ");
                        var employNumber  =  Ext.getCmp("employNumber").setValue(" ");
                        var business    =   Ext.getCmp("business").setValue(" ");
                        var employState =   Ext.getCmp("employState").setValue(" ");
                        var socialSecurityState =   Ext.getCmp("socialSecurityState").setValue(" ");
                        var remarks =   Ext.getCmp("remarks").setValue(" ");
                    }
                }
            ],
            renderTo: demo
        });

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
