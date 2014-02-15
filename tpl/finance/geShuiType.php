<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>个税类型设置</title>
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
                store: geshuiTypestore,
                selType: 'checkboxmodel',
                id : 'comlist',
                columns: [
                    {text: "编号", width: 120, dataIndex: 'id', sortable: true},
                    {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
                    {text: "个税类型", flex: 200, dataIndex: 'geshui_dateType', sortable: true}
                ],
                height:700,
                width:1000,
                x:0,
                y:0,
                title: '个税类型设置',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },
                bbar: Ext.create('Ext.PagingToolbar', {
                    store: geshuiTypestore,
                    displayInfo: true,
                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                    emptyMsg: "没有数据"
                }),
                tbar : [
//                         {
//                     xtype : 'button',
//                     id : 'bt_deleteDocument',
//                     handler : function(src) {

//                     },
//                     text : '删除',
//                     iconCls : 'shanchu'
//                     },
                    {
                        xtype : 'button',
                        id : 'searchSalBu',
                        disabled: true,
                        handler : function(src) {
                            var model = salTimeListGrid.getSelectionModel();
                            var sel=model.getLastSelected();
                            checkSalWin(sel.data.id);
                        },
                        text : '设置个税类型',
                        iconCls : 'shezhi'
                    },
                    '公司名称查询', {
                        id:'comname',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'comname',
                        onTriggerClick : function(src) {
                            geshuiTypestore.removeAll();
                            geshuiTypestore.load( {
                                params : {
                                    companyName : this.getValue(),
                                    salTime : Ext.getCmp("salTime").getValue(),
                                    start : 0,
                                    limit : 50
                                }
                            });
                        }
                    },
                    '个税类型(输入数字：1.报本月、2.报上月)', {
                        id:'salTime',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'salTime',
                        onTriggerClick : function(src) {
                            geshuiTypestore.removeAll();
                            geshuiTypestore.load( {
                                params : {
                                    companyName : Ext.getCmp("comname").getValue(),
                                    salTime : this.getValue(),
                                    start : 0,
                                    limit : 50
                                }
                            });
                        }
                    }
                ]
            });
            geshuiTypestore.on("beforeload",function(){
                Ext.apply(geshuiTypestore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),companyName:Ext.getCmp("comname").getValue()});

            });
            var onSelectChange = function(selModel, selections){
                alert("");
            };
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
 					'公司名称', {
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
                    id : 'benyue',
                    disabled: false,
                    handler : function(src) {
                  	  var model = salTimeListGrid.getSelectionModel();
                      var sel=model.getLastSelected();
                      setBenyue(sel.data.id);
                    },
                    text : '本月报本月',
                    iconCls : 'benyue'
                },
                {
                    xtype : 'button',
                    id : 'shangyue',
                    disabled: false,
                    handler : function(src) {
                       var model = salTimeListGrid.getSelectionModel();
                       var sel=model.getLastSelected();
                       setShangyue(sel.data.id);
                    },
                    text : '本月报上月',
                    iconCls : 'shangyue'
                }],
                //displayInfo : true,
                emptyMsg : "没有数据显示"
            });

//通过ajax获取表头已经表格数据
            function checkSalWin(timeId) {
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
                    title: "个税类型", // 窗口标题
                    width:500, // 窗口宽度
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
                var title="";
                var url = "index.php?action=SaveSalary&mode=searchGeshuiTypeByIdJosn";

                Ext.Ajax.request({
                    url: url,  //从json文件中读取数据，也可以从其他地方获取数据
                    method : 'POST',
                    params: {
                        timeId : timeId
                    },
                    success : function(response) {
                        //将返回的结果转换为json对象，注意extjs4中decode函数已经变成了：Ext.JSON.decode
                        var json = Ext.JSON.decode(response.responseText); //获得后台传递json
                        Ext.getCmp("companyname").setValue(json.data[0].company_name);
						if(json.data[0].geshui_dateType ==2){
							Ext.getCmp("shangyue").setDisabled(true);
						}
						else if(json.data[0].geshui_dateType ==1){
							Ext.getCmp("benyue").setDisabled(true);
						}


                    }
                });
                winSal.show();
            }

			//通过ajax设置类型
            function setShangyue(timeId) {
                var url = "index.php?action=SaveSalary&mode=setShangyueType";
                Ext.Ajax.request({
                    url: url,  //从json文件中读取数据，也可以从其他地方获取数据
                    method : 'POST',
                    params: {
                        timeId : timeId
                    },
                    success : function(response) {
                        Ext.getCmp("shangyue").setDisabled(true);
                        Ext.getCmp("benyue").setDisabled(false);
                    }
                });
            }
            //通过ajax设置类型
            function setBenyue(timeId) {
                var url = "index.php?action=SaveSalary&mode=setBenyueType";
                Ext.Ajax.request({
                    url: url,  //从json文件中读取数据，也可以从其他地方获取数据
                    method : 'POST',
                    params: {
                        timeId : timeId
                    },
                    success : function(response) {
                       Ext.getCmp("benyue").setDisabled(true);
                       Ext.getCmp("shangyue").setDisabled(false);
                    }
                });
            }
            geshuiTypestore.loadPage(1);

            function newWin() {
                var win = Ext.create('Ext.window.Window', {
                    title: "个税类型"	,
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
