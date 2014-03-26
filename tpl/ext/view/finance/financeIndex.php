<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>主页</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/MonthPickerPlugin.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
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
    //创建数据源

    var opManegeGrid = Ext.create('Ext.grid.Panel', {
        store: caiwuListStore,
        selType: 'checkboxmodel',
        id: 'manageComlist',
        stripeRows:true,
        columns: [
            {text: "id", width: 50, dataIndex: 'id', sortable: true,align:'center',hidden:true},
            {text: "公司名称", flex: 240, dataIndex: 'company_name', sortable: true,
                renderer: function (val, cellmeta, record) {
                    return '<a href="#" onclick=getEmploy("' + val + '") >' + val + '</a>';
                }
            },
            {text: "操作日期", width: 100, dataIndex: 'sal_date', sortable: false,align:'center'},
            {text: "工资状态", flex: 120, dataIndex: 'sal_state', sortable: false,align:'center',
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<span style="color: red"> 未做工资 </span>';
                    } else if (val > 0) {
                        return '<a style="color: green" href="#" title="查看工资" onclick=selectinfo(' + record.data['sal_state'] + ')><span style="color: green">已做工资</span></a>';
                    }
                    return val;
                }
            },
            {text: "发票状态", width: 120, dataIndex: 'bill_state', sortable: false,align:'center',
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<span style="color: red"> 未开发票 </span>';
                    } else if (val > 0) {
                        return '<a href="#" title="查看发票" onclick=billInfo(' + record.data['id'] + ',"' + record.data['sal_date'] + '")><span style="color: green">已开发票</span></a>';
                    }
                    return val;
                }
            },
            {text: "银行到账", width: 120, dataIndex: 'cheque_account', sortable: false,align:'center',
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="开支票" onclick=addCheque(' + record.data['id'] + ',"' + record.data['company_name'] + '","' + record.data['sal_state'] + '","' + record.data['sal_date'] + '")><span style="color: blue">支票未到账</span></a>';
                    } else if (val > 0) {
                        return '<a href="#" title="继续开支票" onclick=addCheque(' + record.data['id'] + ',"' + record.data['company_name'] + '","' + record.data['sal_state'] + '","' + record.data['sal_date'] + '")><span style="color: green">支票已到帐</span></a>';
                    }
                    return val;
                }
            },
            {text: "工资发放", flex: 120, dataIndex: 'sal_approve', sortable: false,align:'center',
                renderer:function(val,cellmeta,record){
                    if(val == 0){
                        return '<a href="#" title="审核工资" onclick=updateSal(' + record.data['sal_approve_id'] + ')><span style="color: blue">等待审批</span></a>';
                    } else if(val ==1){
                        return  '<a href="#" title="审核工资" onclick=updateSal(' + record.data['sal_approve_id'] + ')><span style="color:green ">审批通过</span></a>';
                    } else if(val ==2){
                        return '<a href="#" title="审核工资" onclick=updateSal(' + record.data['sal_approve_id'] + ')><span style="color:gray ">审批未通过</span></a>';
                    } else if(val ==-1){
                        return "<span style='color: fuchsia'>暂无审核</span>"
                    }
                    return val;
                }
            },
            {text: "工资发放id", flex: 120, dataIndex: 'sal_approve_id', sortable: false,align:'center',hidden:true}
        ],
        height: 600,
        width: 1000,
        x: 0,
        y: 0,
        title: '主页',
        disableSelection: false,
        loadMask: true,
        renderTo: 'checkcom',
        viewConfig: {
            id: 'gv2',
            trackOver: false,
            stripeRows: false
        },

        bbar: Ext.create('Ext.PagingToolbar', {
            store: caiwuListStore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),
        tbar: [
            {
                xtype: 'button',
                id: 'del',
                text: '取消管理',
                handler: function (src) {
                    var record = Ext.getCmp('manageComlist').getSelectionModel().getSelection();
                    // getSelection()
                    //var records = grid.getSelectionModel().getSelection();
                    if (record.length>0) {
                        var itcIds = [];
                        //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                        for (var i = 0; i < record.length; i++) {
                            itcIds.push(record[i].data.id);
                        }
                        Ext.Ajax.request({
                            url: 'index.php?action=ExtFinance&mode=cancelManage',
                            method: 'post',
                            params: {
                                ids: Ext.JSON.encode(itcIds)
                            },
                            success: function (response) {
                                var text = response.responseText;
                                Ext.Msg.alert("提示",text);
                                caiwuListStore.removeAll();
                                caiwuListStore.load({
                                    params: {
                                        start: 0,
                                        limit: 50
                                    }
                                });
                            }
                        });

                    } else {
                        Ext.Msg.alert("警告","请选择一条记录！");
                    }

                }
            },
            {
                xtype: 'button',
                id: 'cClear',
                text: '添加管理公司',
                handler: function (src) {
                    comListStore.load();
                    window.show();
                }
            },
            '公司名称查询', {
                id:'opComname',
                xtype : 'trigger',
                triggerClass : 'x-form-search-trigger',
                name: 'opComname',
                onTriggerClick : function(src) {
                    caiwuListStore.removeAll();
                    caiwuListStore.load( {
                        params : {
                            company_name : this.getValue(),
                            date:   Ext.getCmp("STime").getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                }
            },
            {
                id:'STime',
                name: 'STime',
                xtype : 'monthfield',
                editable: false,
                width: 200,
                labelAlign: 'right',
                format: 'Y-m'
            },
            {
                xtype: 'button',
                id: 'search1',
                disabled: false,
                handler: function () {
                    caiwuListStore.removeAll();
                    caiwuListStore.load({
                        params: {
                            company_name : Ext.getCmp("opComname").getValue(),
                            date:   Ext.getCmp("STime").getValue(),
                            start: 0,
                            limit: 50
                        }
                    });

                },
                text: '按月份查找'
            }
        ]
    });
    caiwuListStore.on("beforeload", function () {
        Ext.apply(caiwuListStore.proxy.extraParams, {Key: Ext.getCmp("opComname").getValue(),companyName:Ext.getCmp("opComname").getValue(),date:Ext.getCmp("STime").getValue()});
    });
    caiwuListStore.loadPage(1);

    //创建Grid
    var companyListGrid = Ext.create('Ext.grid.Panel',{
        store: comListStore,
        selType: 'checkboxmodel',
        id : 'companyList',
        columns: [
            {text: "id", width: 120, dataIndex: 'id', sortable: true},
            {text: "公司名称", flex: 200, dataIndex: 'company_name', sortable: true}
        ],
        height:400,
        width:520,
        x:20,
        y:40,
        title: '添加管理单位',
        disableSelection: false,
        loadMask: true,
        viewConfig: {
            id: 'gv',
            trackOver: false,
            stripeRows: false
        },

        bbar: Ext.create('Ext.PagingToolbar', {
            store: comListStore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),
        tbar : [{
            xtype : 'button',
            id : 'bt_deleteDocument',
            handler : function() {
                var record = Ext.getCmp('companyList').getSelectionModel().getSelection();
                // getSelection()
                //var records = grid.getSelectionModel().getSelection();
                if (record) {
                    var itcIds = [];
                    //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                    for(var i=0;i<record.length;i++){
                        itcIds.push(record[i].data.id);
                    }
                    Ext.Ajax.request({
                        url: 'index.php?action=Service&mode=addCaiwuOpCompanyListJson',
                        method: 'post',
                        params: {
                            ids : Ext.JSON.encode(itcIds)
                        },
                        success: function(response){
                            var text = response.responseText;
                            // process server response here
                            newWin(text);
                        }
                    });

                } else {
                    Ext.Msg.alert("提示","'请选择一条记录");
                    return;
                }
            },
            text : '添加管理',
            iconCls : 'shanchu'
        }, '公司查询', {
            id:'comnameid',
            xtype : 'trigger',
            triggerClass : 'x-form-search-trigger',
            name: 'search',
            onTriggerClick : function(src) {
                comListStore.removeAll();
                comListStore.load( {
                    params : {
                        Key : this.getValue(),
                        start : 0,
                        limit : 50
                    }
                });

            }

        }]
    });
    comListStore.on("beforeload",function(){
        Ext.apply(comListStore.proxy.extraParams, {Key:Ext.getCmp("comnameid").getValue(),companyName:Ext.getCmp("opComname").getValue()});
    });

    // Create a window
    var window = new Ext.Window({
        title:"管理", // 窗口标题
        width:530, // 窗口宽度
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
    ////////////////////////////////////////////////////////////////////////////////////////
    // 定义弹出窗
    ////////////////////////////////////////////////////////////////////////////////////////
    function newWin(text) {
        var win = Ext.create('Ext.window.Window', {
            title: text	,
            width: 300,
            height: 100,
            plain: true,
            closeAction: 'hide', // 关闭窗口
            maximizable: false, // 最大化控制 值为true时可以最大化窗体
            layout: 'border',
            contentEl: 'tab'
        });
        win.show();
        document.location='index.php?action=Ext&mode=toFinanceIndex';
    }

});

/**
 * 添加支票的方法
 */
function addCheque(comId,companyName,sal_state,sal_date) {
    if(sal_state==0){
        Ext.Msg.alert("提示","没有发工资是不能开支票的！");
        return false;
    }
    var items=[salList];

    Ext.getCmp("company_id").setValue(comId);
    Ext.getCmp("company_name").setValue(companyName);
    Ext.getCmp("salaryTime").setValue(sal_date);
    Ext.getCmp("sal_state").setValue(sal_state);

    var winSal = Ext.create('Ext.window.Window', {
        title: "添加支票", // 窗口标题
        width:460, // 窗口宽度
        height:300, // 窗口高度
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

var salList=Ext.create("Ext.form.Panel",{
    width: 450,
    height: 260,
    bodyPadding: 10,
    labelWidth:50,
    id : 'addBillForm',
    name : 'addBillForm',
    items: [
        {
            id:'company_id',
            xtype : 'hiddenfield',
            readonly:true,
            name: 'company_id'
        },
        {
            id:'sal_state',
            xtype : 'hiddenfield',
            readonly:true,
            name: 'sal_state'
        },

        {
            id:'company_name',
            xtype : 'displayfield',
            width:300,
            name: 'company_name',
            fieldLabel: '单位'
        },
        {
            id:'salaryTime',
            xtype : 'displayfield',
            readonly:true,
            width:150,
            name: 'salaryTime',
            fieldLabel:'月份'
        } ,
        {
            xtype: 'combobox',
            id:"chequeType" ,
            emptyText: "请选择支票类型",
            editable: false,
            allowBlank: false,
            store: {
                fields: ['abbr', 'name'],
                data: [
                    {"abbr": "2", "name": "到账支票"},
                    {"abbr": "3", "name": "银行到账"},
                ]
            },
            valueField: 'abbr',
            displayField: 'name',
            fieldLabel: '支票类型'
        },
        {
            id:'chequeValue',
            xtype : 'numberfield',
            width:300,
            name: 'chequeValue',
            emptyText: "请输入金额",
            fieldLabel:'金额'
        } ,
        {
            id:'chequeRemarks',
            xtype : 'textareafield',
            width:400,
            height:80,
            name: 'chequeRemarks',
            emptyText: "请输入备注信息",
            fieldLabel:'备注'
        }
    ],
    buttons: [
    {
        text: '提交',
        handler: function () {
            var companyId =  Ext.getCmp("company_id").getValue();
            var companyName =  Ext.getCmp("company_name").getValue();
            var sal_state =  Ext.getCmp("sal_state").getValue();
            var chequeType =   Ext.getCmp("chequeType").getValue();
            var chequeValue =   Ext.getCmp("chequeValue").getValue();
            var remarks =   Ext.getCmp("chequeRemarks").getValue();
            if(chequeValue==null){
                Ext.Msg.alert("提示","请您先输入发票金额！");
                return;
            }
            Ext.Ajax.request({
                url: "index.php?action=ExtSalaryBill&mode=addCheque",
                method : 'POST',
                params: {
                    companyId:companyId,
                    companyname:companyName,
                    salaryTime:sal_state,
                    chequeType:chequeType,
                    chequeval:chequeValue,
                    memo:remarks
                },
                success : function(response) {
                    var text=   response.responseText;
                    Ext.Msg.alert("提示",text);
                    document.location='index.php?action=Ext&mode=toFinanceIndex';
                }
            });
        }
    },
    '-',
    {
        text: '清空',
        handler: function () {
            Ext.getCmp("billNo").setValue("");
            Ext.getCmp("billItem").setValue("");
            Ext.getCmp("billValue").setValue("");
            Ext.getCmp("billRemarks").setValue("");
        }
    }
]
});

function billInfo(id,sal_date){
    $("#comId").val(id);
    $("#sDate").val(sal_date);
    $("#iform").attr("action", "index.php?action=Finance&mode=searchFaPiaoDaoZhang");
    $("#iform").submit();
}

function getEmploy(com) {
    $("#iform").attr("action", "index.php?action=Service&mode=getEmList");
    $("#comname").val(com);
    $("#iform").submit();
}

function makeSal(id, sDate, salType) {
    if (sDate == "") {
        alert("未找到做工资月份，请按工资月份查询后再做工资！");
        return;
    }
    $("#comId").val(id);
    $("#sDate").val(sDate);
    $("#salType").val(salType);
    $("#iform").attr("action", "index.php?action=Service&mode=makeSal");
    $("#iform").submit();
}

function addFa(id,sDate) {
    $("#comId").val(id);
    $("#sDate").val(sDate);
    $("#iform").attr("action", "index.php?action=SalaryBill&mode=toAddInvoice");
    $("#iform").submit();
}
var infolist=Ext.create("Ext.grid.Panel",{
    title:'',
    width:1150,
    height:450,
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
function selectinfo(timeId) {
    //加载数据遮罩
    var mk=new Ext.LoadMask(Ext.getBody(),{
        msg:'加载数据中，请稍候！',removeMask:true
    });
    mk.show();

    var items=[infolist];

    var wininfo = Ext.create('Ext.window.Window', {
        title: "详细信息", // 窗口标题
        width:1200, // 窗口宽度
        height:500, // 窗口高度
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
        closeAction:'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });
    var title="";
    var url = "index.php?action=SaveSalary&mode=searchSalaryByIdJosn";

    Ext.Ajax.request({
        url: url,  //从json文件中读取数据，也可以从其他地方获取数据
        method : 'POST',
        params: {
            timeId : timeId
        },
        success : function(response) {
            //将返回的结果转换为json对象，注意extjs4中decode函数已经变成了：Ext.JSON.decode
            mk.hide();
            var json = Ext.JSON.decode(response.responseText); //获得后台传递json

            //创建store
            var store = Ext.create('Ext.data.Store', {
                fields : json.fields,//把json的fields赋给fields
                data : json.data     //把json的data赋给data
            });

            //根据store和column构造表格
            Ext.getCmp("infogrid").reconfigure(store, json.columns);
            //重新渲染表格
            // Ext.getCmp("salTimeListP").render();
        }
    });
    wininfo.show();
}

function updateSal(eid){
    if(eid=="0"){
        Ext.Msg.alert('警告','没有做工资无法审核发放！');
        return false;
    }
    Ext.MessageBox.show({
        title:'审核工资',
        msg: '请选择审核结果：',
        width:300,
        buttonText:{ok: '同意',yes:'拒绝',no:'取消'},
        animateTarget: 'mb4',
        fn: function (btn) {
            var shenPiType;
            if("ok"==btn){
               shenPiType  =   1;
                Ext.Ajax.request({
                    url: 'index.php?action=ExtFinance&mode=opShenPi',
                    method: 'post',
                    params: {
                        billId:eid ,
                        shenPiType  :   shenPiType
                    },
                    success: function (response) {
                        var text = response.responseText;
                        Ext.Msg.alert("提示",text);
                        caiwuListStore.load( {
                                params: {
                                    start: 0,
                                    limit: 50
                                }
                            }
                        );
                    }

                });
            }
            else if("yes"==btn){
                shenPiType  =   2;
                Ext.Ajax.request({
                    url: 'index.php?action=ExtFinance&mode=opShenPi',
                    method: 'post',
                    params: {
                        billId:eid ,
                        shenPiType  :   shenPiType
                    },
                    success: function (response) {
                        var text = response.responseText;
                        Ext.Msg.alert("提示",text);
                        caiwuListStore.load( {
                                params: {
                                    start: 0,
                                    limit: 50
                                }
                            }
                        );
                    }

                });
            }
            else if("no"==btn){
               return false;
            }

        },
        icon: Ext.MessageBox.INFO
    })
}
</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <div id="tab" class="TipDiv"></div>
        <div id="checkcom"></div>
        <form enctype="multipart/form-data" id="iform" action="" target="_top" method="post">
            <input type="hidden" name="comname" id="comname" value=""/>
            <input type="hidden" name="comId" id="comId" value=""/>
            <input type="hidden" name="sDate" id="sDate" value=""/>
            <input type="hidden" name="salType" id="salType" value=""/>
        </form>
    </div>
</div>
</body>
</html>