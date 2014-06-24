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
<script language="javascript" type="text/javascript"src="tpl/ext/js/monthPickerPlugin.js" charset="utf-8"></script>
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
            {text: "编号", width: 80, dataIndex: 'id', sortable: true,locked:true,hidden:true},
            {text: "姓名", width: 80, dataIndex: 'e_name', sortable: true,locked:true,
                renderer: function (val, cellmeta, record) {
                    return '<a href="#" title="修改员工信息" onclick=updateEmpInfo(' + record.data['id'] + ')><span >'+val+'</span></a>';
                }
            },
            {text: "单位名称", width: 170, dataIndex: 'e_company', sortable: true,locked:true},
            {text: "身份证号", width: 150, dataIndex: 'e_num', sortable: true,locked:true},
            {text: "合同年份", width: 80, dataIndex: 'e_hetongnian', sortable: true},
            {text: "合同日期", width: 100, dataIndex: 'e_hetong_date', sortable: true,
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="修改合同" onclick=contract(' + record.data['id'] + ')><span style="color: gray"> 合同已经到期 </span></a>';
                    }if (val == 1) {
                        return '<a href="#" title="修改合同" onclick=contract(' + record.data['id'] + ')><span style="color: red"> 合同即将到期 </span></a>';
                    }
                    return '<a href="#" title="修改合同" onclick=contract(' + record.data['id'] + ')><span >'+val+'</span></a>';
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
                listeners: {
                    select: function (tab) {
                        employListstore.removeAll();
                        employListstore.load({
                            params: {
                                e_name:Ext.getCmp("e_name").getValue(),
                                contractinfo:Ext.getCmp("contractinfo").getValue(),
                                e_type:Ext.getCmp("e_type").getValue(),
                                e_num:Ext.getCmp("e_num").getValue(),
                                e_company:Ext.getCmp("e_company").getValue(),
                                start: 0,
                                limit: 50
                            }
                        });
                    }
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
                listeners: {
                    select: function (tab) {
                        employListstore.removeAll();
                        employListstore.load({
                            params: {
                                e_name:Ext.getCmp("e_name").getValue(),
                                contractinfo:Ext.getCmp("contractinfo").getValue(),
                                e_type:Ext.getCmp("e_type").getValue(),
                                e_num:Ext.getCmp("e_num").getValue(),
                                e_company:Ext.getCmp("e_company").getValue(),
                                start: 0,
                                limit: 50
                            }
                        });
                    }
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
        closeAction:'destroy'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
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
                    emptyText: "请输入合同年限",
                    width: 250,
                    fieldLabel: '合同期限'
                },
                {
                    id: 'up_e_hetong_date',
                    xtype: 'datefield',
                    format: "Y-m-d",
                    width: 250,
                    allowBlank: false,
                    emptyText: "请输入合同日期",
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
                            Ext.Msg.alert("提示", action.result.info);
                            document.location = 'index.php?action=Ext&mode=contractInfo';
                        },
                        failure:function(form,action){
                            Ext.Msg.alert('提示',action.result.info);
                        }
                    }
                );


            }
        },
        {
            text: '清空',
            handler: function () {
                Ext.getCmp("up_e_hetongnian").setValue("");
                Ext.getCmp("up_e_hetong_date").setValue("");
            }
        }
    ]
});

function updateEmpInfo(id){
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
            Ext.getCmp("up_emp_id").setValue(id);
            Ext.getCmp("up_emp_name").setValue(json['e_name']);
            Ext.getCmp("up_emp_e_hetongnian").setValue(json['e_hetongnian']);
            Ext.getCmp("up_emp_e_hetong_date").setValue(json['e_hetong_date']);
            Ext.getCmp("up_emp_e_company").setValue(json['e_company']);
            Ext.getCmp("up_emp_e_num").setValue(json['e_num']);
            Ext.getCmp("up_emp_bank_name").setValue(json['bank_name']);
            Ext.getCmp("up_emp_bank_num").setValue(json['bank_num']);
            Ext.getCmp("up_emp_e_type").setValue(json['e_type']);
            Ext.getCmp("up_emp_shebaojishu").setValue(json['shebaojishu']);
            Ext.getCmp("up_emp_gongjijinjishu").setValue(json['gongjijinjishu']);
            Ext.getCmp("up_emp_laowufei").setValue(json['laowufei']);
            Ext.getCmp("up_emp_canbaojin").setValue(json['canbaojin']);
            Ext.getCmp("up_emp_danganfei").setValue(json['danganfei']);
            Ext.getCmp("up_emp_memo").setValue(json['memo']);
            Ext.getCmp("up_emp_e_teshustate").setValue(json['e_teshustate']);

            var items = [upEmpInfoPanel];
            var updateEmpWin = Ext.create('Ext.window.Window', {
                title: "修改员工信息", // 窗口标题
                width: 550, // 窗口宽度
                height: 600, // 窗口高度
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
                closeAction: 'hide'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
            });
            updateEmpWin.show();
        }
    });
}
var upEmpInfoPanel = Ext.create("Ext.form.Panel", {
    width: 540,
    height: 650,
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
                    id: 'up_emp_id',
                    xtype: 'hiddenfield'
                },
                {
                    id: 'up_emp_name',
                    xtype: 'textfield',
                    width: 250,
                    allowBlank: false,
                    fieldLabel: '姓名'
                },
                {
                    id: 'up_emp_e_company',
                    xtype: 'textfield',
                    width: 250,
                    allowBlank: false,
                    fieldLabel: '所属公司'
                },
                {
                    id: 'up_emp_e_num',
                    xtype: 'textfield',
                    width: 250,
                    allowBlank: false,
                    fieldLabel:  '身份证号<span style="color: red;font-size: 12px">*</span>'
                },
                {
                    id: 'up_emp_bank_name',
                    xtype: 'textfield',
                    width: 250,
                    fieldLabel: '开户银行'
                },
                {
                    id: 'up_emp_bank_num',
                    xtype: 'textfield',
                    width: 250,
                    fieldLabel: '银行账户'
                },
                {
                    xtype: 'combobox',
                    id:"up_emp_e_type" ,
                    emptyText: "请选择身份类别",
                    editable: false,
                    width: 250,
                    allowBlank: false,
                    store: {
                        fields: ['abbr', 'name'],
                        data: [
                            {"abbr": "实习生", "name": "实习生"},
                            {"abbr": "未缴纳保险", "name": "未缴纳保险"},
                            {"abbr": "本市农民工", "name": "本市农民工"},
                            {"abbr": "外地农民工", "name": "外地农民工"},
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
                    id: 'up_emp_shebaojishu',
                    xtype: 'numberfield',
                    width: 250,
                    fieldLabel: '社保基数'
                },
                {
                    id: 'up_emp_gongjijinjishu',
                    xtype: 'numberfield',
                    width: 250,
                    allowBlank: false,
                    fieldLabel: '公积金基数'
                },
                {
                    id: 'up_emp_laowufei',
                    xtype: 'numberfield',
                    width: 250,
                    allowBlank: false,
                    fieldLabel: '劳务费'
                },
                {
                    id: 'up_emp_canbaojin',
                    xtype: 'numberfield',
                    width: 250,
                    allowBlank: false,
                    fieldLabel: '参保金'
                },
                {
                    id: 'up_emp_danganfei',
                    xtype: 'numberfield',
                    width: 250,
                    allowBlank: false,
                    fieldLabel: '档案费'
                },
                {
                    xtype: 'combobox',
                    id:"up_emp_e_teshustate" ,
                    emptyText: "请选择身份类别",
                    editable: false,
                    allowBlank: false,
                    width:250,
                    store: {
                        fields: ['abbr', 'name'],
                        data: [
                            {"abbr": "1", "name": "残疾人"},
                            {"abbr": "0", "name": "非残疾人"}
                        ]
                    },
                    valueField: 'abbr',
                    displayField: 'name',
                    fieldLabel: '特殊标示'
                },
                {
                    id: 'up_emp_e_hetongnian',
                    xtype: 'numberfield',
                    readonly: false,
                    allowBlank: false,
                    width: 250,
                    fieldLabel: '合同期限'
                },
                {
                    id: 'up_emp_e_hetong_date',
                    xtype: 'datefield',
                    format: "Y-m-d",
                    width: 250,
                    allowBlank: false,
                    emptyText: "请更新合同日期",
                    fieldLabel: '合同年份'
                },
                {
                    xtype: 'textareafield',
                    id:"up_emp_memo",
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
            text: '更新信息',
            handler: function () {
                var submitInfo = this.up('form').getForm().isValid();
                if (!submitInfo) {
                    Ext.Msg.alert("警告！", "请输入完整的信息！");
                    return false;
                }
                this.up('form').getForm().submit(
                    {
                        url: "index.php?action=ExtEmploy&mode=emUpdate",
                        method: 'POST',
                        waitTitle : '请等待' ,
                        waitMsg: '正在提交中',
                        success: function (form,action) {
                            Ext.Msg.alert("提示", action.result.info);
                            document.location = 'index.php?action=Ext&mode=contractInfo';
                        },
                        failure:function(form,action){
                            Ext.Msg.alert('提示',action.result.info);
                        }
                    }
                );
            }
        },
        {
            text: '更新身份证号',
            handler: function () {
                var submitInfo = this.up('form').getForm().isValid();
                if (!submitInfo) {
                    Ext.Msg.alert("警告！", "请输入完整的信息！");
                    return false;
                }
                this.up('form').getForm().submit(
                    {
                        url: "index.php?action=ExtEmploy&mode=emNoUpdate",
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
        },
        {
            text: '清空',
            handler: function () {
                var id = Ext.getCmp("up_emp_id").getValue();
                Ext.getCmp("up_emp_id").setValue(id);
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