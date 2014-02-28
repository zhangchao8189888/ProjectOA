<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>修改密码</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script type="text/javascript">
Ext.require([
    'Ext.grid.*',
    'Ext.toolbar.Paging',
    'Ext.data.*'
]);
Ext.onReady(function(){
    var addBusinessWindow = Ext.create('Ext.form.Panel', {
        title:"修改密码",
        bodyPadding: 10,
        width: 580,
        height: 320,
        items: [
            {
                xtype: 'fieldcontainer',
                fieldLabel: '请输入数据',
                defaultType: 'checkboxfield',
                items: [
                    {
                        xtype: 'textfield',
                        id:"nowpass",
                        allowBlank: false,
                        emptyText: "请输入当前密码",
                        fieldLabel: '当前密码<span style="color: red;font-size: 12px">(必填)</span>'
                    },
                    {
                        xtype: 'textfield',
                        id:"newpass",
                        inputType: 'password',
                        emptyText: "新密码",
                        regex: /^([a-zA-Z0-9]{6,})$/i,
                        regexText: '密码必须包含字母或数字,且最少有6位',
                        allowBlank: false,
                        fieldLabel: '请输入新密码'
                    },
                    {
                        xtype: 'textfield',
                        id:"repass",
                        inputType: 'password',
                        emptyText: "确认密码",
                        regex: /^([a-zA-Z0-9]{6,})$/i,
                        regexText: '密码必须包含字母或数字,且最少有6位',
                        allowBlank: false,
                        fieldLabel: '请再输入一次',
                        validator: function(value){
                            var pw = this.previousSibling().value;
                            if(value != pw){
                                return '两次输入的密码不一致';
                            }else{
                                return true;
                            }

                        }
                    },
                    {
                        id:'hint',
                        xtype : 'displayfield',
                        readonly:true,
                        width:200,
                        height:50,
                        value:"<span style='color: red'><br>注：修改密码成功需要重新登录。</span>",
                        name: 'hint'
                    }
                ]
            }
        ],
        buttons: [
            {
                text: '提交',
                handler: function () {
                    var nowpass = Ext.getCmp("nowpass").getValue();
                    var newpass = Ext.getCmp("newpass").getValue();
                    var repass = Ext.getCmp("repass").getValue();
                    var submitInfo = this.up('form').getForm().isValid();
                    if (!submitInfo) {
                        Ext.Msg.alert("警告！", "请输入完整的信息！");
                        return false;
                    }
                    Ext.Ajax.request({
                        url: "index.php?action=Admin&mode=modifyPass",
                        method: 'POST',
                        params: {
                            nowpass: nowpass,
                            newpass: newpass,
                            repass: repass
                        },
                        success: function (response) {
                            var text = response.responseText;
                            Ext.Msg.alert("提示！", text);
                            window.location.reload();
                        }
                    });
                }
            },
            {
                text: '清空',
                handler: function () {
                    this.up('form').getForm().reset();
                }
            }
        ] ,
        renderTo:"demo"
    });
});

function  updatePassword(btn){
    Ext.example.msg('Button Click', 'You clicked the {0} button', "v");;
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
</body>
</html>
