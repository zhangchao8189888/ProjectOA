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
                store: fapiaoStore,
                id : 'comlist1',
                columns: [
                    {text: "发票编号", width: 120, dataIndex: 'bill_no', sortable: true},
                    {text: "工资日期", width: 120, dataIndex: 'salaryTime', sortable: true},
                    {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
                    {text: "发票金额", flex: 200, dataIndex: 'bill_value', sortable: true}
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
                    id: 'gv1',
                    trackOver: false,
                    stripeRows: false
                },
                bbar: Ext.create('Ext.PagingToolbar', {
                    store: fapiaoStore,
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
            fapiaoStore.on("beforeload",function(){
                Ext.apply(fapiaoStore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),comname:Ext.getCmp("comname").getValue()});

            });

            var salTimeListGrid2 = Ext.create('Ext.grid.Panel',{
                store: daozhangListstore,
                id : 'comlist2',
                columns: [
                    {text: "工资日期", width: 120, dataIndex: 'daozhangTime', sortable: true},
                    {text: "单位名称", flex: 200, dataIndex: 'cname', sortable: true},
                    {text: "到账金额", flex: 200, dataIndex: 'daozhangValue', sortable: true}
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
                    id: 'gv2',
                    trackOver: false,
                    stripeRows: false
                },
                bbar: Ext.create('Ext.PagingToolbar', {
                    store: daozhangListstore,
                    displayInfo: true,
                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                    emptyMsg: "没有数据"
                }),

                tbar : [

                    {
                        xtype : 'button',
                        id : 'searchSalBu1',
                        handler : function(src) {
                            fapiaoStore.removeAll();
                            fapiaoStore.load( {
                                params : {
                                    comname :Ext.getCmp("comname").getValue(),
                                    salTime : Ext.getCmp("salTime").getValue(),
                                    start : 0,
                                    limit : 50
                                }
                            });
                            daozhangListstore.removeAll();
                            daozhangListstore.load( {
                                params : {
                                    comname :Ext.getCmp("comname").getValue(),
                                    salTime : Ext.getCmp("salTime").getValue(),
                                   start : 0,
                                    limit : 50
                                }
                            });
                        },
                        text : '查询',
                        iconCls : 'chakan'
                    }
                ]
            });

            daozhangListstore.on("beforeload",function(){
                Ext.apply(daozhangListstore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),comname:Ext.getCmp("comname").getValue()});

            });
            var duibiPanel = Ext.create('Ext.panel.Panel',{
                id : 'duibi',
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
