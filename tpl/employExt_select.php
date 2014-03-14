<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>员工列表查询</title>
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

            var salTimeListGrid = Ext.create('Ext.grid.Panel',{
                id : 'comlist',
                columns: [
                ],
                height:700,
                width:1000,
                x:0,
                y:0,
                title: '员工列表查询',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },
                tbar : [
                    '单位名称', {
                        id:'company_name',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'company_name',
                        onTriggerClick : function(src) {

                        }
                    },
                    '员工姓名', {
                        id:'emp_name',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'emp_name',
                        onTriggerClick : function(src) {

                        }
                    },
                    '身份证号', {
                        id:'emp_num',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'emp_num',
                        onTriggerClick : function(src) {

                        }
                    },

                    {
                        xtype : 'button',
                        id : 'bt_selectDocument',
                        handler : function(src) {
                            var model = salTimeListGrid.getSelectionModel();
                            var sel=model.getSelection();
                            empExtListStore.load( {
                                    params: {
                                        company_name : Ext.getCmp("company_name").getValue(),
                                        emp_name : Ext.getCmp("emp_name").getValue(),
                                        emp_num : Ext.getCmp("emp_num").getValue()
                                    }
                                }
                            );
                            checkSalWin();
                        },
                        text : '查询',
                        iconCls : 'chaxun'
                    }
                ]
            });
            //创建表格,可以加入更多的属性。
            var salListWidth=1150;
            var salList=Ext.create("Ext.grid.Panel",{
                title:'',
                store:empExtListStore,
                width:salListWidth,
                height:450,
                enableLocking : true,
                selType: 'checkboxmodel',
                id : 'configGrid',
                name : 'configGrid',
                features: [{
                    ftype: 'summary'
                }],
                columns : [

                            {text: "姓名", width: 120, dataIndex: 'e_name', sortable: true},
                            {text: "单位名称", flex: 200, dataIndex: 'e_company', sortable: true},
                            {text: "身份证号", flex: 200, dataIndex: 'e_num', sortable: true},
                            {text: "开户行", flex: 200, dataIndex: 'bank_name', sortable: true},
                            {text: "银行账号", flex: 200, dataIndex: 'bank_num', sortable: true},
                            {text: "身份类别", flex: 200, dataIndex: 'e_type', sortable: true},
                            {text: "社保基数", flex: 200, dataIndex: 'shebaojishu', sortable: true},
                            {text: "公积金基数", flex: 200, dataIndex: 'gongjijinjishu', sortable: true},
                            {text: "劳务费", flex: 200, dataIndex: 'laowufei', sortable: true},
                            {text: "残保金", flex: 200, dataIndex: 'canbaojin', sortable: true},
                            {text: "档案费", flex: 200, dataIndex: 'danganfei', sortable: true},
                            {text: "备注", flex: 200, dataIndex: 'memo', sortable: true}

                ], //注意此行代码，至关重要
                //displayInfo : true,
                emptyMsg : "没有数据显示"
            });

            //通过ajax获取表头已经表格数据
            function checkSalWin() {
                var p = Ext.create("Ext.grid.Panel",{
                    id:"salTimeListP",
                    title:"导航",
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
                var items=[salList];


                var winSal = Ext.create('Ext.window.Window', {
                    title: "查看员工 ", // 窗口标题
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
                            window.minimizable = true;
                        }
                    },
                    closeAction:'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
                });
                winSal.show();

            }
            //geshuiListstore.loadPage(1);

            function newWin() {
                var win = Ext.create('Ext.window.Window', {
                    title: "查看个税"	,
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
</body>
</html>
