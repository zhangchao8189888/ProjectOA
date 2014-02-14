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
            height: 300,
            title: '业务变更',
            items: [
                {
                    xtype: 'fieldcontainer',
                    fieldLabel: '请输入数据',
                    defaultType: 'checkboxfield',
                    items: [
                        {
                            xtype: 'combobox',
                            editable:false,
                            emptyText:"选择公司",
                            allowBlank:false,
                            store:{
                                fields: ['abbr', 'name'],
                                data : [
                                    {"abbr":"AL", "name":"Alabama"},
                                    {"abbr":"AK", "name":"Alaska"},
                                    {"abbr":"AZ", "name":"Arizona"}
                                    //...
                                ]
                            },
                            valueField: 'abbr',
                            displayField: 'name',
                            fieldLabel: '单位'

                        },
                        {
                            xtype: 'textfield',
                            emptyText:"请输入姓名",
                            fieldLabel: '姓名'
                        },
                        {
                            xtype: 'textfield',
                            emptyText:"请输入身份证号",
                            fieldLabel: '身份证号'
                        },
                        {
                            xtype: 'textfield',
                            emptyText:"请输入办理的业务",
                            fieldLabel: '办理的业务'
                        },
                        {
                            xtype: 'textfield',
                            emptyText:"请输入身份证号",
                            fieldLabel: '社保状态'
                        },
                        {
                            xtype: 'combobox',
                            editable:false,
                            emptyText:"请选择状态",
                            allowBlank:false,
                            store:{
                                fields: ['abbr', 'name'],
                                data : [
                                    {"abbr":"yes", "name":"完成"},
                                    {"abbr":"no", "name":"未完成"},
                                ]
                            },
                            valueField: 'abbr',
                            displayField: 'name',
                            fieldLabel: '社保经办状态'
                        }
                    ]
                }
            ],
            bbar: [
                {
                     align:right
                }   ,
                {
                    text: '提交',
                    handler: function() {

                    }
                },
                '-',
                {
                    text: '取消',
                    handler: function() {
                        Ext.getCmp('checkbox1').setValue(true);
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
