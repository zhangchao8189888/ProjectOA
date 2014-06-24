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
        store:accountstore,
        width:1000,
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

            {text: "操作序号", width: 80, dataIndex: 'id', sortable: true,hidden:true},
            {text: "单位编号", width: 80, dataIndex: 'companyId', sortable: true,hidden:true},
            {text: "单位名称", width: 170, dataIndex: 'companyName', sortable: true,
                renderer:function(val,cellmeta,record){
                    if(record.data['companyId'] > 0){
                        return  '<span style="color:green ">'+record.data['companyName']+'</span>';
                    } else {
                        return '<span style="color:red ">'+record.data['companyName']+'</span>';
                    }
                    return val;
                }},
            {text: "交易日期", width: 100, dataIndex: 'transactionDate', sortable: true},
            {text: "交易类型", width: 100, dataIndex: 'accountsType', sortable: true,
                renderer:function(val,cellmeta,record){
                    if(val ==1){
                        return  '<span style="color:gray ">收入</span>';
                    } else if(val ==2){
                        return '<span style="color:green ">支出</span>';
                    }
                    return val;
                }
            },
            {text: "类型说明", width: 100, dataIndex: 'accountsRemark', sortable: true},
            {text: "交易账号", width: 100, dataIndex: 'companyBank', sortable: true},

            {text: "金额", width: 100, dataIndex: 'value',sortable:false},
            {text: "操作", width: 120, dataIndex: 'salType', sortable: false,align:'center',
                renderer:function(val,cellmeta,record){
                    if (val == 1) {
                        return  '<a href="#" title="搜索工资" onclick=selectExpenses(' + record.data['companyId'] + ',"' + record.data['transactionDate'] + '","' + record.data['companyName'] + '","' + record.data['value'] + '")><span style="color:green ">查询工资</span></a>';
                    } else if (val == 0) {
                        return '<span style="color:gray ">收入业务</span>';
                    }
                    return val;
                }
            },
            {text: "备注", width: 200, dataIndex: 'remark', sortable: false}
        ],
        tbar : [
            {
                xtype: 'button',
                id: 'bt_deleteAcc',
                handler: function (src) {
                    var record = Ext.getCmp('configGrid').getSelectionModel().getSelection();
                    // getSelection()
                    //var records = grid.getSelectionModel().getSelection();
                    if (record.length>0) {
                        Ext.MessageBox.show({
                            title:'删除本项导入',
                            msg: '是否要删除选中的导入项？（警告，本过程不可逆！同时更新数据库中对应账目！）',
                            buttonText:{ok: '确认',no:'取消'},
                            animateTarget: 'mb4',
                            fn: function (btn) {
                                if("no"==btn){
                                    return false;
                                }
                                var itcIds = [];
                                var itcComs = [];
                                for (var i = 0; i < record.length; i++) {
                                    itcIds.push(record[i].data.id);
                                    itcComs.push(record[i].data.companyId);
                                }
                                Ext.Ajax.request({
                                    url: 'index.php?action=ExtSalary&mode=delAccountsById',
                                    method: 'post',
                                    waitTitle : '请等待' ,
                                    waitMsg: '正在提交中',
                                    params: {
                                        ids: Ext.JSON.encode(itcIds),
                                        coms: Ext.JSON.encode(itcComs)
                                    },
                                    success: function (response) {
                                        var text = response.responseText;
                                        Ext.Msg.alert('信息',text);
                                        accountstore.removeAll();
                                        accountstore.load({
                                            params: {
                                                companyName: Ext.getCmp("companyName").getValue(),
                                                transactionDateb: Ext.getCmp("transactionDateb").getValue(),
                                                transactionDatea: Ext.getCmp("transactionDatea").getValue(),
                                                accountsType:Ext.getCmp("accountType").getValue(),
                                                accountsRemark:Ext.getCmp("accountsRemark").getValue(),
                                                start: 0,
                                                limit: 50
                                            }
                                        });
                                    }
                                });
                            },
                            icon: Ext.MessageBox.WARNING
                        })

                    } else {
                        Ext.Msg.alert('警告','请选择一条记录！');
                    }

                },
                text : '删除',
                iconCls : 'shanchu'
            },
            {
                xtype : 'button',
                id : 'update',
                handler : function(src) {
                    var model = contractWin.getSelectionModel();
                    var sel=model.getSelection();
                    if(sel[0]==null){
                        Ext.Msg.alert("提示", "请先选中单条数据！");
                        return false;
                    }else{
                        updateCom(sel[0].data.id,sel[0].data.companyName);
                    }
                },
                text : '单位更正',
                iconCls : 'chakan'
            },
            {
                xtype:'textfield',
                id:'companyName',
                width:120,
                emptyText:"筛选公司"
            },
            {
                xtype:'textfield',
                id:'accountsRemark',
                width:120,
                emptyText:"筛选类型说明"
            },
            {
                xtype: 'combobox',
                id:"accountType" ,
                width:100,
                emptyText: "筛选交易类型",
                editable: false,
                store: {
                    fields: ['abbr', 'name'],
                    data: [
                        {"abbr": "1", "name": "收入"},
                        {"abbr": "2", "name": "支出"}
                    ]
                },
                valueField: 'abbr',
                displayField: 'name'
            },
            {
                id: 'transactionDateb',
                xtype: 'datefield',
                width:120,
                format: "Y-m-d",
                emptyText:"请筛选日期间隔",
                readOnly: false,
                anchor: '95%'
            },
            {
                id: 'transactionDatea',
                xtype: 'datefield',
                width:120,
                format: "Y-m-d",
                emptyText:"请筛选日期间隔",
                readOnly: false,
                anchor: '95%'
            } ,
            {
                xtype : 'button',
                id : 'searchw',
                handler : function(src) {
                    accountstore.removeAll();
                    accountstore.load({
                        params: {
                            companyName: Ext.getCmp("companyName").getValue(),
                            transactionDateb: Ext.getCmp("transactionDateb").getValue(),
                            transactionDatea: Ext.getCmp("transactionDatea").getValue(),
                            accountsType:Ext.getCmp("accountType").getValue(),
                            accountsRemark:Ext.getCmp("accountsRemark").getValue(),
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
                text : '添加支出',
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
        width:1010, // 窗口宽度
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
        height:200,
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
                value:"<span style='color: blue'>请创建后缀名为“.xls”的文件进行上传(重名将会覆盖)<br>请保证表格中包含以下数据，分别为：</span>" +
                    "<br>交易日	借	贷	摘要	收(付)方名称	收(付)方帐号	交易类型" +
                    "<br><span style='color: red'>注：一条数据只允许一次支出或收入，同时填写只读取支出。</span>"+
                    "<br><span style='color: red'>示例：</span>"
            },
            {
                xtype: 'image',
                src:"tpl/ext/resources/images/upload-examples/account.jpg"
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
                text: '下载示例文件',
                handler: function () {
                    Ext.Ajax.request({
                        url : "index.php?action=ExtSalary&mode=getImportAccountsTemplate",
                        params : {
                        },
                        method : "POST",
                        timeout : 4000,
                        success : function(response) {
                            var obj =Ext.decode(response.responseText);
                            window.location.href =obj.path;
                        }
                    });
                }
            },
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
        height: 250, // 窗口高度
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

var infolist=Ext.create("Ext.grid.Panel",{
    width:780,
    height:400,
    enableLocking : true,
    id : 'infogrid',
    name : 'infogrid',
    features: [{
        ftype: 'summary'
    }],
    columns : [], //注意此行代码，至关重要
    //displayInfo : true,
    emptyMsg : "没有数据显示"
});
function selectExpenses(comId,salTime,companyName,money){
    var title   =   companyName+"   金额： "+money;
    //加载数据遮罩
    var mk=new Ext.LoadMask(Ext.getBody(),{
        msg:'正在查询数据，请等待',removeMask:true
    });
    mk.show();
    var items=[infolist];

    var wininfo = Ext.create('Ext.window.Window', {
        title: title, // 窗口标题
        width:790, // 窗口宽度
        height:410, // 窗口高度
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
                mk.hide();
                window.minimizable = true;
            },
            close:function(){
                mk.hide();
            }
        },
        closeAction:'hide'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });

    Ext.Ajax.request({
        url: "index.php?action=ExtSalary&mode=selectExpenses",  //从json文件中读取数据，也可以从其他地方获取数据
        method : 'POST',
        params: {
            comId:comId,
            salTime:salTime,
            money:money
        },
        success : function(response) {
            mk.hide();
            var json = Ext.JSON.decode(response.responseText); //获得后台传递json
            if(json['info']!=null){
                Ext.Msg.alert("警告",json['info']);
                return false;
            }
            //创建store
            var store = Ext.create('Ext.data.Store', {
                fields : json.fields,//把json的fields赋给fields
                data : json.data     //把json的data赋给data
            });
            //根据store和column构造表格
            Ext.getCmp("infogrid").reconfigure(store, json.columns);
        }
    });
    wininfo.show();
}
var companyListGrid = Ext.create('Ext.grid.Panel',{
    store: comListStore,
    id : 'companyList',
    columns: [
        {text: "id", width: 50, dataIndex: 'id', sortable: true},
        {text: "公司名称", width: 350, dataIndex: 'company_name', sortable: true}
    ],
    height:400,
    width:420,
    disableSelection: false,
    loadMask: true,

    bbar: Ext.create('Ext.PagingToolbar', {
        store: comListStore,
        displayInfo: true,
        displayMsg: '共计{2}家单位',
        emptyMsg: "没有数据"
    }),
    tbar: [
        {
            xtype: 'button',
            id: 'bt_deleteDocument',
            handler: function () {
                var record = Ext.getCmp('companyList').getSelectionModel().getSelection();
                if(record[0]==null){
                    Ext.Msg.alert("提示", "请先选中单条数据！");
                    return false;
                }else{
                    var oldCom=Ext.getCmp("update_com").getValue();
                    var newCom=record[0].data.company_name;
                    Ext.MessageBox.show({
                        title:'更新单位',
                        msg: '确认修改<br><span style="color: blue">"'+oldCom+'"</span><br>为<br><span style="color: blue">"'+newCom+'"</span>？',
                        width:240,
                        buttonText:{ok: '修改', yes: '取消'},
                        animateTarget: 'mb4',
                        fn: function (btn) {
                            if("ok"==btn){
                                Ext.Ajax.request({
                                    url: 'index.php?action=ExtSalary&mode=updateAccount',
                                    method: 'post',
                                    params: {
                                        id:Ext.getCmp("update_id").getValue(),
                                        newCom:newCom
                                    },
                                    success: function (response) {
                                        var json = Ext.JSON.decode(response.responseText);
                                        Ext.Msg.alert("提示",json['message']);
                                        document.location = 'index.php?action=Ext&mode=toAccount';
                                    }
                                });
                            }else{
                                return false;
                            }
                        },
                        icon: Ext.MessageBox.INFO
                    })

                }
            },
            text: '更新单位',
            iconCls: 'shanchu'
        },
        {
            id: 'update_id',
            xtype: 'hiddenfield'
        },
        {
            id: 'update_com',
            xtype: 'hiddenfield'
        },
        {
            id: 'comnameid',
            xtype: 'textfield',
            width:240,
            emptyText:"请输入筛选公司"
        },
        {
            xtype: 'button',
            id: 'search_company',
            handler: function () {
                comListStore.removeAll();
                comListStore.load({
                    params: {
                        Key:Ext.getCmp("comnameid").getValue(),
                        start: 0,
                        limit: 50
                    }
                });
            },
            text: '搜索',
            iconCls: 'shanchu'
        }
    ]
});

comListStore.on("beforeload",function(){
    Ext.apply(comListStore.proxy.extraParams, {Key:Ext.getCmp("comnameid").getValue()});
});
function updateCom(id,comName){
    Ext.getCmp("update_com").setValue(comName);
    Ext.getCmp("update_id").setValue(id);
    var window = new Ext.Window({
        title:"管理", // 窗口标题
        width:430, // 窗口宽度
        height:440, // 窗口高度
        layout:"border",// 布局
        minimizable:true, // 最大化
        maximizable:true, // 最小化
        frame:true,
        constrain:true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
        buttonAlign:"center", // 按钮显示的位置
        modal:true, // 模式窗口，弹出窗口后屏蔽掉其他组建
        resizable:false, // 是否可以调整窗口大小，默认TRUE。
        plain:true,// 将窗口变为半透明状态。
        items:[companyListGrid],
        closeAction:'hide'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });
    window.show();
    comListStore.load();
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