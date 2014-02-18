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
            {text: "id", width: 50, dataIndex: 'id', sortable: true,align:'center'},
            {text: "公司名称", flex: 240, dataIndex: 'company_name', sortable: true},
            {text: "操作月份", width: 100, dataIndex: 'sal_date', sortable: false,align:'center'},
            {text: "工资状态", flex: 120, dataIndex: 'sal_state', sortable: false,align:'center',
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="做工资" target="_top" onclick=makeSal(' + record.data['id'] + ',"' + record.data['sal_date'] + '","first")><span style="color: red"> 未做工资 </span></a>';
                    } else if (val > 0) {
                        return '<a style="color: green" href="#" title="做工资" target="_top" onclick=selectinfo(' + record.data['sal_state'] + ')>已做工资</span>.'
                        return '<span style="color: green" title="查看工资" _salTimeId="' + record.data['salTimeid'] + '"  id="check">已做工资</span>.';
                    }
                    return val;
                }
            },
            {text: "发票状态", width: 120, dataIndex: 'bill_state', sortable: false,align:'center'},
            {text: "支票状态", flex: 120, dataIndex: 'cheque_state', sortable: false,align:'center'},
            {text: "支票到账", width: 120, dataIndex: 'cheque_account', sortable: false,align:'center'},
            {text: "工资发放", flex: 120, dataIndex: 'sal_approve', sortable: false,align:'center'}
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
                    if (record) {
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
                                alert("取消成功！");
                                caiwuListStore.removeAll();
                                caiwuListStore.load({
                                    params: {
                                        date:  Ext.getCmp("STime").getValue(),
                                        start: 0,
                                        limit: 50
                                    }
                                });
                            }
                        });

                    } else {
                        alert('请选择一条记录');
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
                width: 150,
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
        Ext.apply(caiwuListStore.proxy.extraParams, {Key: Ext.getCmp("opComname").getValue(),companyName:Ext.getCmp("opComname").getValue()});
    });
    caiwuListStore.loadPage(1);
    function newWin(text) {
        var win = Ext.create('Ext.window.Window', {
            title: text,
            width: 300,
            height: 100,
            plain: true,
            closeAction: 'hide', // 关闭窗口
            maximizable: false, // 最大化控制 值为true时可以最大化窗体
            layout: 'border',
            contentEl: 'tab'
        });
        win.show();
    }

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
                    alert('请选择一条记录');
                }
            },
            text : '添加管理',
            iconCls : 'shanchu'
        }, '公司查询', {
            id:'comname',
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
        Ext.apply(comListStore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue()});
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

function selectinfo(timeId) {
    alert(timeId);
    //加载数据遮罩
    var mk=new Ext.LoadMask(Ext.getBody(),{
        msg:'加载数据中，请稍候！',removeMask:true
    });
    mk.show();
    var p = Ext.create("Ext.grid.Panel",{
        id:"salTimeListP",
        title:"详细信息",
        width:150,
        region:"west",
        columns : [],
        listeners: {
            'cellclick': function(iView, iCellEl, iColIdx, iStore, iRowEl, iRowIdx, iEvent) {
            }
        },
        split:true,
        colspan: 3,
        collapsible:true
    });

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
            //Ext.getCmp("infogrid").render();
            Ext.getCmp("infogrid").reconfigure(store, json.columns);
            //重新渲染表格

        }
    });
    wininfo.show();
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
            <input type="hidden" name="comId" id="comId" value=""/>
            <input type="hidden" name="sDate" id="sDate" value=""/>
            <input type="hidden" name="salType" id="salType" value=""/>
        </form>
    </div>
</div>
</body>
</html>