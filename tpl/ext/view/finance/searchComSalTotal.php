<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>财务对账</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/monthPickerPlugin.js" charset="utf-8"></script>
<style type="text/css">
    <!--
    A { text-decoration: none}
    -->
</style>
<script type="text/javascript">
Ext.require([
    'Ext.grid.*',
    'Ext.toolbar.Paging',
    'Ext.data.*'
]);

Ext.onReady(function () {
    //创建对账公司列表Grid
    var salTimeListGrid = Ext.create('Ext.grid.Panel',{
        store: adminCompanyListStore,
        selType: 'checkboxmodel',
        id : 'adminComlist',
        columns: [
            {text: "编号", width: 80, dataIndex: 'id', sortable: true},
            {text: "单位名称", width: 500, dataIndex: 'company_name', sortable: true},
            {text: "工资月份", width: 100, dataIndex: 'salTime', sortable: true},
            {text: "一次工资总金额", width: 130, dataIndex: 'salTotal', sortable: true},
            {text: "二次工资总金额", width: 130, dataIndex: 'salErTotal', sortable: true},
            {text: "二次工资个数", width: 130, dataIndex: 'salErNum', sortable: true},
            {text: "年终奖总金额", width: 130, dataIndex: 'salNianTotal', sortable: true}

        ],
        height:700,
        width:1500,
        x:0,
        y:0,
        plugins: [{
            ptype: 'rowexpander',
            rowBodyTpl: [
                '<div id="{id}">',
                '</div>'
            ]
        }],
        title: '公司工资总额查询',
        disableSelection: false,
        loadMask: true,
        renderTo: 'demo',
        viewConfig: {
            id: 'gv',
            trackOver: false,
            stripeRows: false
        },
        bbar: Ext.create('Ext.PagingToolbar', {
            store: adminCompanyListStore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),
        tbar : [
            '公司名称查询', {
                id:'comname',
                xtype : 'trigger',
                triggerClass : 'x-form-search-trigger',
                name: 'comname',
                onTriggerClick : function(src) {
                    adminCompanyListStore.removeAll();
                    adminCompanyListStore.load( {
                        params : {
                            companyName : this.getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                }
            },
            {
                xtype : 'button',
                id : 'chaxun',
                disabled: false,
                handler : function(src) {
                    adminCompanyListStore.removeAll();
                    adminCompanyListStore.load( {
                        params : {
                            companyName : Ext.getCmp("comname").getValue(),
                            start : 0,
                            limit : 50
                        }
                    });
                },
                text : '查询',
                iconCls : 'chaxun'
            },
            {
                id:'salTime',
                name: 'salTime',
                xtype : 'monthfield',
                editable: true,
                width: 130,
                labelAlign: 'right',
                format: 'Y-m'
            },
            {
                xtype: 'button',
                id: 'opmonth',
                disabled: false,
                handler: function () {
                    adminCompanyListStore.removeAll();
                    adminCompanyListStore.load({
                        params: {
                            companyName : Ext.getCmp("comname").getValue(),
                            salTime : Ext.getCmp("salTime").getValue()
                        }
                    });

                },
                text: '按年工资月份查找'
            }
        ]
    });
    adminCompanyListStore.on("beforeload",function(){
        Ext.apply(adminCompanyListStore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),companyName:Ext.getCmp("comname").getValue()});

    });
    salTimeListGrid.view.on('expandBody', function (rowNode, record, expandRow, eOpts) {
        displayInnerGrid(record.get('id'));
    });

    salTimeListGrid.view.on('collapsebody', function (rowNode, record, expandRow, eOpts) {
        destroyInnerGrid(record);
    });
    adminCompanyListStore.loadPage(1);
});


</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width: 960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <div id="demo"></div>
    </div>
</div>
</body>
</html>
