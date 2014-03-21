<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>年度工资查看</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/data.js" charset="utf-8"></script>
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
        store: gongzibiaostore,
        id: 'manageComlist',
        stripeRows:true,
        columns: [
            {text: "id", width: 45, dataIndex: 'id', sortable: true,align:'center'},
            {text: "公司名称", width: 200, dataIndex: 'company_name', sortable: true},
            {text: "一月", width: 80, dataIndex: 'mouth1', sortable: false,align:'center'},
            {text: "二月", width: 80, dataIndex: 'mouth2', sortable: false,align:'center'},
            {text: "三月", width: 80, dataIndex: 'mouth3', sortable: false,align:'center'},
            {text: "四月", width: 80, dataIndex: 'mouth4', sortable: false,align:'center'},
            {text: "五月", width: 80, dataIndex: 'mouth5', sortable: false,align:'center'},
            {text: "六月", width: 80, dataIndex: 'mouth6', sortable: false,align:'center'},
            {text: "七月", width: 80, dataIndex: 'mouth7', sortable: false,align:'center'},
            {text: "八月", width: 80, dataIndex: 'mouth8', sortable: false,align:'center'},
            {text: "九月", width: 80, dataIndex: 'mouth9', sortable: false,align:'center'},
            {text: "十月", width: 80, dataIndex: 'mouth10', sortable: false,align:'center'},
            {text: "十一月", width: 80, dataIndex: 'mouth11', sortable: false,align:'center'},
            {text: "十二月", width: 80, dataIndex: 'mouth12', sortable: false,align:'center'},
            {text: "年终奖", width: 80, dataIndex: 'mouth13', sortable: false,align:'center'}
        ],
        height: 700,
        width: 1100,
        x: 0,
        y: 0,
        title: '工资查看',
        disableSelection: false,
        loadMask: true,
        renderTo: 'checkcom',
        viewConfig: {
            id: 'gv2',
            trackOver: false,
            stripeRows: false
        },

        bbar: Ext.create('Ext.PagingToolbar', {
            store: gongzibiaostore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),
        tbar: [
            '公司名称查询', {
                id:'opComname',
                xtype : 'trigger',
                triggerClass : 'x-form-search-trigger',
                name: 'opComname',
                onTriggerClick : function(src) {
                    gongzibiaostore.removeAll();
                    gongzibiaostore.load( {
                        params : {
                            company_name : this.getValue(),
                            date:   Ext.getCmp("opDate").getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                }
            },
            '查找年份', {
                id:'opDate',
                xtype : 'trigger',
                triggerClass : 'x-form-search-trigger',
                name: 'opDate',
                onTriggerClick : function(src) {
                    gongzibiaostore.removeAll();
                    gongzibiaostore.load( {
                        params : {
                            company_name : Ext.getCmp("opComname").getValue(),
                            year:   this.getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                }
            }
        ]
    });
    gongzibiaostore.on("beforeload", function () {
        Ext.apply(gongzibiaostore.proxy.extraParams, {Key: Ext.getCmp("opComname").getValue(),companyName:Ext.getCmp("opDate").getValue()});
    });
    gongzibiaostore.loadPage(1);
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
    ////////////////////////////////////////////////////////////////////////////////////////
    // 定义管理公司数据
    ////////////////////////////////////////////////////////////////////////////////////////

    Ext.define('companyModel',{
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id', type: 'int'},
            {name: 'company_name', type: 'string'}
        ]
    });
    //创建数据源
    var comListStore = Ext.create('Ext.data.Store', {
        //分页大小
        pageSize: 50,
        model: 'companyModel',
        //是否在服务端排序
        remoteSort: true,
        proxy: {
            //异步获取数据，这里的URL可以改为任何动态页面，只要返回JSON数据即可
            type: 'ajax',
            url : 'index.php?action=Service&mode=getOpCompanyListJson',

            reader: {
                root: 'items',
                totalProperty  : 'total'
            },
            simpleSortMode: true
        },
        sorters: [{
            //排序字段。
            property: 'id',
            //排序类型，默认为 ASC
            direction: 'DESC'
        }]
    });

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
                comListStore.loadPage(1);
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

});

</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <div id="tab" class="TipDiv"></div>
        <div id="checkcom"></div>
    </div>
</div>
</body>
</html>