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
<title>查看管理员工</title>
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
        store:employListstore,
        width:1150,
        height:500,
        enableLocking : true,
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
            {text: "编号", width: 80, dataIndex: 'id', sortable: true,locked:true},
            {text: "姓名", width: 80, dataIndex: 'e_name', sortable: true,locked:true},
            {text: "单位名称", width: 170, dataIndex: 'e_company', sortable: true,locked:true},
            {text: "身份证号", width: 150, dataIndex: 'e_num', sortable: true,locked:true},
            {text: "合同年份", width: 80, dataIndex: 'e_hetongnian', sortable: true},
            {text: "合同日期", width: 100, dataIndex: 'e_hetong_date', sortable: true,
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="修改合同" onclick=contract(' + record.data['id'] + ',"' + record.data['e_name'] + '","' + record.data['e_hetongnian'] + '","' + record.data['e_hetong_date'] + '")><span style="color: gray"> 合同已经到期 </span></a>';
                    }if (val == 1) {
                        return '<a href="#" title="修改合同" onclick=contract(' + record.data['id'] + ',"' + record.data['e_name'] + '","' + record.data['e_hetongnian'] + '","' + record.data['e_hetong_date'] + '")><span style="color: red"> 合同即将到期 </span></a>';
                    }
                    return '<a href="#" title="修改合同" onclick=contract(' + record.data['id'] + ',"' + record.data['e_name'] + '","' + record.data['e_hetongnian'] + '","' + record.data['e_hetong_date'] + '")><span >'+val+'</span></a>';
                }
            },
            {text: "开户行", width: 80, dataIndex: 'bank_name', sortable: true},
            {text: "银行账号", width: 150, dataIndex: 'bank_num', sortable: true},
            {text: "身份类别", width: 110, dataIndex: 'e_type', sortable: true},
            {text: "社保基数", width: 80, dataIndex: 'shebaojishu', sortable: true},
            {text: "公积金基数", width: 80, dataIndex: 'gongjijinjishu', sortable: true},
            {text: "劳务费", width: 80, dataIndex: 'laowufei', sortable: true},
            {text: "残保金", width: 80, dataIndex: 'canbaojin', sortable: true},
            {text: "档案费", width: 80, dataIndex: 'danganfei', sortable: true},
            {text: "备注", width: 200, dataIndex: 'memo', sortable: true}
        ],
        tbar : [
            {
                xtype:'textfield',
                id:'e_name',
                width:100,
                emptyText:"筛选姓名"
            },
            {
                xtype:'textfield',
                id:'e_num',
                width:150,
                emptyText:"筛选身份证号"
            },
            {
                xtype:'textfield',
                id:'e_company',
                width:150,
                emptyText:"筛选公司"
            },
            {
                xtype: 'combobox',
                editable:false,
                store: {
                    fields: ['abbr', 'name'],
                    data: [
                        {"abbr": "即将到期", "name": "即将到期"},
                        {"abbr": "2", "name": "已经到期"},
                        {"abbr": "3", "name": "未到期"},
                        {"abbr": "", "name": "全部"}
                    ]
                },
                valueField: 'abbr',
                displayField: 'name',
                id:'contractinfo',
                width:150,
                emptyText:"筛选合同"
            },
            {
                xtype: 'combobox',
                id:"e_type" ,
                width:150,
                emptyText: "筛选身份类别",
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
                displayField: 'name'
            },
            {
                xtype : 'button',
                id : 'searchqita',
                handler : function(src) {
                    if(Ext.getCmp("contractinfo").getValue()){
                        Ext.getCmp("xx").hide();
                    }else{
                        Ext.getCmp("xx").show();
                    }
                    employListstore.removeAll();
                    employListstore.load( {
                        params : {
                            e_name:Ext.getCmp("e_name").getValue(),
                            contractinfo:Ext.getCmp("contractinfo").getValue(),
                            e_type:Ext.getCmp("e_type").getValue(),
                            e_num:Ext.getCmp("e_num").getValue(),
                            e_company:Ext.getCmp("e_company").getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                },
                text : '筛选',
                iconCls : 'chakan'
            }
        ],
        bbar: Ext.create('Ext.PagingToolbar', {
            id:"xx",
            store: employListstore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        })
    });
    var myDate = new Date();
    if(myDate.getDate()==1){
        Ext.getCmp("contractinfo").setValue("即将到期");
        Ext.getCmp("xx").hide();
    }
    employListstore.on("beforeload", function () {
        Ext.apply(employListstore.proxy.extraParams, {e_name:Ext.getCmp("e_name").getValue(),contractinfo:Ext.getCmp("contractinfo").getValue(),e_type:Ext.getCmp("e_type").getValue(),e_num:Ext.getCmp("e_num").getValue(),e_company:Ext.getCmp("e_company").getValue(),});
    });
    employListstore.loadPage(1);
    var items=[contractWin];

    var indexWin = Ext.create('Ext.window.Window', {
        title: "欢迎使用员工功能", // 窗口标题
        width:1160, // 窗口宽度
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

function contract(id) {
    Ext.Ajax.request({
        url: "index.php?action=ExtEmploy&mode=searchContractInfo",  //从json文件中读取数据，也可以从其他地方获取数据
        method : 'POST',
        params: {
            id : id
        },
        success : function(response) {
            var json = Ext.JSON.decode(response.responseText); //获得后台传递json
            if(null==json){
                Ext.Msg.alert("错误！", "出现了问题，请重试！！");
                return false;
            }
            Ext.getCmp("up_id").setValue(id);
            Ext.getCmp("up_e_name").setValue(json['e_name']);
            Ext.getCmp("up_e_hetongnian").setValue(json['e_hetongnian']);
            Ext.getCmp("up_e_hetong_date").setValue(json['e_hetong_date']);
            var items = [salList];
            var winSal = Ext.create('Ext.window.Window', {
                title: "修改合同", // 窗口标题
                width: 410, // 窗口宽度
                height: 180, // 窗口高度
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
            winSal.show();
        }
    });
}

var salList = Ext.create("Ext.form.Panel", {
    width: 400,
    height: 180,
    bodyPadding: 10,
    labelWidth: 50,
    id: 'updateHetongDate',
    items: [
        {
            xtype: 'fieldcontainer',
            fieldLabel: '请输入数据',
            defaultType: 'checkboxfield',
            items: [
                {
                    id: 'up_id',
                    xtype: 'hiddenfield'
                },
                {
                    id: 'up_e_name',
                    xtype: 'displayfield',
                    width: 250,
                    fieldLabel: '姓名'
                },
                {
                    id: 'up_e_hetongnian',
                    xtype: 'numberfield',
                    readonly: false,
                    allowBlank: false,
                    width: 250,
                    fieldLabel: '合同期限'
                },
                {
                    id: 'up_e_hetong_date',
                    xtype: 'datefield',
                    format: "Y-m-d",
                    width: 250,
                    allowBlank: false,
                    emptyText: "请更新合同日期",
                    fieldLabel: '合同年份'
                }
            ]
        }
    ],
    buttons: [
        {
            text: '更新',
            handler: function () {
                var submitInfo = this.up('form').getForm().isValid();
                if (!submitInfo) {
                    Ext.Msg.alert("警告！", "请输入完整的信息！");
                    return false;
                }
                this.up('form').getForm().submit(
                    {
                        url: "index.php?action=ExtEmploy&mode=updateContractInfo",
                        method: 'POST',
                        waitTitle : '请等待' ,
                        waitMsg: '正在提交中',
                        success: function (form,action) {
                            var text = form.responseText;
                            Ext.Msg.alert("提示", action.result.info);
                            document.location = 'index.php?action=Ext&mode=contractInfo';
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
                var id = Ext.getCmp("id").getValue();
                this.up('form').getForm().reset();
                Ext.getCmp("id").setValue(id);
            }
        }
    ]
});
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