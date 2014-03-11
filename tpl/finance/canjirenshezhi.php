<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>残疾人设置</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
    <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
    <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
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
            var salTimeListGrid = Ext.create('Ext.grid.Panel',{
                store: canjirenTypestore,
                selType: 'checkboxmodel',
                id : 'comlist',
                columns: [
                    {text: "个人编号", width: 120, dataIndex: 'id', sortable: true},
                    {text: "姓名", flex: 200, dataIndex: 'emp_name', sortable: true},
                    {text: "身份证号", flex: 200, dataIndex: 'emp_num', sortable: true},
                    {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
                    {text: "是否残疾", flex: 200, dataIndex: 'canjiren_Type', sortable: true}
                ],
                height:700,
                width:800,
                x:0,
                y:0,
                title: '残疾人设置',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },
                tbar : [
                    {
                        xtype : 'button',
                        id : 'searchSalBu',
                        disabled: true,
                        handler : function(src) {
                            checkSalWin();
                        },
                        text : '设置是否残疾',
                        iconCls : 'shezhi'
                    },
                    '姓名查询', {
                        id:'ename',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'ename',
                        onTriggerClick : function(src) {
                            canjirenTypestore.removeAll();
                            canjirenTypestore.load( {
                                params : {
                                    ename : this.getValue(),
                                    empnum : Ext.getCmp("empnum").getValue(),
                                    start : 0,
                                    limit : 50
                                }
                            });
                        }
                    },
                    '身份证号', {
                        id:'empnum',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'empnum',
                        onTriggerClick : function(src) {
                            canjirenTypestore.removeAll();
                            canjirenTypestore.load( {
                                params : {
                                    empnum : this.getValue(),
                                    ename : Ext.getCmp("ename").getValue(),
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
                            canjirenTypestore.removeAll();
                            canjirenTypestore.load( {
                                params : {
                                    empnum : Ext.getCmp("empnum").getValue(),
                                    ename :  Ext.getCmp("ename").getValue(),
                                    start : 0,
                                    limit : 50
                                }
                            });
                        },
                        text : '查询',
                        iconCls : 'chaxun'
                    },
                    {
                        xtype: 'button',
                        id: 'xianshi',
                        disabled: false,
                        handler: function () {

                            if(salTimeListGrid1.isHidden())
                            {
                                salTimeListGrid1.show();
                            }else{
                                salTimeListGrid1.hide();
                            }

                        },
                        text: '显示/隐藏统计框'
                    }
                ]
            });
            canjirenTypestore.on("beforeload",function(){
                Ext.apply(geshuiTypestore.proxy.extraParams, {empnum:Ext.getCmp("empnum").getValue(),ename:Ext.getCmp("ename").getValue()});

            });
            salTimeListGrid.getSelectionModel().on('selectionchange', function (selModel, selections) {
                //var sel=model.getLastSelected();
                Ext.getCmp("searchSalBu").setDisabled(selections.length === 0);
            }, this);
            /**
             * 定义工资table
             */
            //创建表格,可以加入更多的属性。
           var salListWidth=1150;
            var salList=Ext.create("Ext.grid.Panel",{
                title:'',
                width:salListWidth,
                height:450,
                enableLocking : true,
                id : 'configGrid',
                name : 'configGrid',
                features: [{
                    ftype: 'summary'
                }],
                columns : [], //注意此行代码，至关重要
                tbar : [
 					'姓名', {
    				id:'companyname',
    				xtype : 'trigger',
    				readOnly:true,
    				triggerClass : 'x-form-search-trigger',
    				name: 'companyname',
    				onTriggerClick : function(src) {
   				     }
				},

                {
                    xtype : 'button',
                    id : 'bucan',
                    disabled: false,
                    handler : function(src) {
                  	  var model = salTimeListGrid.getSelectionModel();
                      var sel=model.getLastSelected();
                        setTypeCanjiren(sel.data.id,0);

                        canjirenTypestore.load( {
                            params: {

                                empnum : Ext.getCmp("empnum").getValue(),
                                ename :  Ext.getCmp("ename").getValue(),
                                start : 0,
                                limit : 50
                            }
                        }  );
                        canjirenTongjiStore.removeAll();
                        canjirenTongjiStore.load( {
                            params : {
                                cname : Ext.getCmp("cname").getValue()
                            }
                        });
                    },
                    text : '非残疾人',
                    iconCls : 'bucan'
                },
                {
                    xtype : 'button',
                    id : 'canjiren',
                    disabled: false,
                    handler : function(src) {
                       var model = salTimeListGrid.getSelectionModel();
                       var sel=model.getLastSelected();
                        setTypeCanjiren(sel.data.id,1);

                        canjirenTypestore.load( {
                            params: {

                                empnum : Ext.getCmp("empnum").getValue(),
                                ename :  Ext.getCmp("ename").getValue(),
                                start : 0,
                                limit : 50
                            }
                        }  );
                        canjirenTongjiStore.removeAll();
                        canjirenTongjiStore.load( {
                            params : {
                                cname : Ext.getCmp("cname").getValue()
                            }
                        });
                    },
                    text : '残疾人',
                    iconCls : 'canjiren'
                }
],
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
                var model = salTimeListGrid.getSelectionModel();
                var sel=model.getLastSelected();
                Ext.getCmp("companyname").setValue(sel.data.emp_name);

                var winSal = Ext.create('Ext.window.Window', {
                    title: "是否残疾", // 窗口标题
                    width:450, // 窗口宽度
                    height:100, // 窗口高度
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
            //通过ajax设置类型
            function setTypeCanjiren(timeId,type) {
                var url = "index.php?action=SaveSalary&mode=setTypeCanjiren";
                Ext.Ajax.request({
                    url: url,  //从json文件中读取数据，也可以从其他地方获取数据
                    method : 'POST',
                    params: {
                        timeId : timeId,
                        type : type
                    },
                    success : function(response) {

                    }
                });
            }
            canjirenTypestore.loadPage(1);



            var salTimeListGrid1 = Ext.create('Ext.grid.Panel',{
                store: canjirenTongjiStore,
                selType: 'checkboxmodel',
                id : 'comlist1',
                columns: [
                    {text: "编号", width: 50, dataIndex: 'com_id', sortable: true},
                    {text: "公司名称", width: 140, dataIndex: 'com_name', sortable: true},
                    {text: "残疾人数量", width: 100, dataIndex: 'sumCanjiren', sortable: true}
                ],
                height:700,
                width:350,
                x:800,
                y:-700,
                title: '残疾人统计',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv1',
                    trackOver: false,
                    stripeRows: false
                },
                tbar : [
                    {
                        xtype: 'button',
                        id: 'xiangxixinxi',
                        disabled: false,
                        handler: function () {
                            var model = salTimeListGrid1.getSelectionModel();
                            var sel=model.getLastSelected();
                            canjirenXiangxistore.removeAll();
                            canjirenXiangxistore.load( {
                                params : {
                                    cid : sel.data.com_id
                                }
                            });
                            xiangxiChakan();

                        },
                        text: '详细查看'
                    },
                    '名称', {
                        id:'cname',
                        width:150,
                        xtype : 'trigger',
                        name: 'cname',
                        onTriggerClick : function(src) {
                            canjirenTongjiStore.removeAll();
                            canjirenTongjiStore.load( {
                                params : {
                                    cname : this.getValue()
                                }
                            });
                        }
                    },
                    {
                        xtype: 'button',
                        id: 'chaxungongsi',
                        disabled: false,
                        handler: function () {
                            canjirenTongjiStore.removeAll();
                            canjirenTongjiStore.load( {
                                params : {
                                    cname : Ext.getCmp("cname").getValue()
                                }
                            });
                        },
                        text: '查询'
                    }

                ]

            });
            //创建表格,可以加入更多的属性。
            var salListWidth1=1150;
            var salList1=Ext.create("Ext.grid.Panel",{
                title:'',
                store: canjirenXiangxistore,
                width:salListWidth1,
                height:450,
                enableLocking : true,
                id : 'configGrid1',
                name : 'configGrid1',
                features: [{
                    ftype: 'summary'
                }],
                columns : [
                    {text: "个人编号", width: 100, dataIndex: 'id1', sortable: true},
                    {text: "姓名", width: 100, dataIndex: 'emp_name1', sortable: true},
                    {text: "身份证号", width: 150, dataIndex: 'emp_num1', sortable: true},
                    {text: "单位名称", width: 150, dataIndex: 'company_name1', sortable: true},
                    {text: "是否残疾", width: 100, dataIndex: 'canjiren_Type1', sortable: true}
                ], //注意此行代码，至关重要
                tbar : [
                ],
                //displayInfo : true,
                emptyMsg : "没有数据显示"
            });

//通过ajax获取表头已经表格数据
            function xiangxiChakan() {
                var p = Ext.create("Ext.grid.Panel",{
                    id:"salTimeListP1",
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
                var items1=[salList1];
                var winSal1 = Ext.create('Ext.window.Window', {
                    title: "是否残疾", // 窗口标题
                    width:600, // 窗口宽度
                    height:400, // 窗口高度
                    layout:"border",// 布局
                    minimizable:true, // 最大化
                    maximizable:true, // 最小化
                    frame:true,
                    constrain:true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
                    buttonAlign:"center", // 按钮显示的位置
                    modal:true, // 模式窗口，弹出窗口后屏蔽掉其他组建
                    resizable:true, // 是否可以调整窗口大小，默认TRUE。
                    plain:true,// 将窗口变为半透明状态。
                    items:items1,
                    listeners: {
                        //最小化窗口事件
                        minimize: function(window){
                            this.hide();
                            window.minimizable = true;
                        }
                    },
                    closeAction:'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
                });
                winSal1.show();
            }
            canjirenTongjiStore.loadPage(1);
            salTimeListGrid1.hide();
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
