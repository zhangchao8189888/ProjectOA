<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>发票到账查看</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
    <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
    <link href="common/css/admin.css" rel="stylesheet" type="text/css" />

    <script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
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

        Ext.onReady(function(){

            //创建Grid
            var salTimeListGrid1 = Ext.create('Ext.grid.Panel',{
                id : 'comlist1',
                columns: [
                    {text: "发票编号", width: 120, dataIndex: 'company_id', sortable: true},
                    {text: "发票日期", width: 120, dataIndex: 'company_id', sortable: true},
                    {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
                    {text: "发票金额", flex: 200, dataIndex: 'name', sortable: true}
                ],
                height:500,
                width:550,
                x:0,
                y:0,
                title: '发票信息',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },
                bbar: Ext.create('Ext.PagingToolbar', {
                    store: geshuiListstore,
                    displayInfo: true,
                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                    emptyMsg: "没有数据"
                }),

                tbar : [

                    '公司名称', {
                        id:'comname',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'comname',
                        onTriggerClick : function(src) {

                        }
                    },
                    '查看月份', {
                        id:'salTime',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'salTime',
                        onTriggerClick : function(src) {

                        }
                    }
                ]

            });
            var salTimeListGrid2 = Ext.create('Ext.grid.Panel',{
                id : 'comlist2',
                columns: [
                    {text: "到账日期", width: 120, dataIndex: 'company_id', sortable: true},
                    {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
                    {text: "到账金额", flex: 200, dataIndex: 'company', sortable: true}
                ],
                height:500,
                width:550,
                x:550,
                y:-500,
                title: '银行进账',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },
                bbar: Ext.create('Ext.PagingToolbar', {
                    store: geshuiListstore,
                    displayInfo: true,
                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                    emptyMsg: "没有数据"
                }),

                tbar : [

                    {
                        xtype : 'button',
                        id : 'searchSalBu',
                        handler : function(src) {

                        },
                        text : '查询',
                        iconCls : 'chakan'
                    }
                ]

            });
            var salTimeListGrid = Ext.create('Ext.panel.Panel',{
                id : 'comlist',
                height:200,
                width:1100,
                x:0,
                y:-500,
                title: '对比结论',
                renderTo: 'demo'


            });



        });

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
<form id="iform" action="" method="post">
</form>
</body>
</html>
