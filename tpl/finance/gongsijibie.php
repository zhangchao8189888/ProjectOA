<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>公司级别设置</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
    <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
    <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="common/ext/ux/RowExpander.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
    <script type="text/javascript">
        var innerGrid=[];
        var insideGridStore=[];
        var  salLister=[];
        var  winSaler = [];
        var winSal;
        Ext.require([
            'Ext.grid.*',
            'Ext.toolbar.Paging',
            'Ext.data.*'
        ]);
        function displayInnerGrid(renderId) {

              insideGridStore[renderId] = Ext.create('Ext.data.Store', {
                //分页大小
                pageSize: 50,
                model: 'oa.common.erjigongsi.list',
                //是否在服务端排序
                remoteSort: true,
                proxy: {
                    //异步获取数据，这里的URL可以改为任何动态页面，只要返回JSON数据即可
                    type: 'ajax',
                    actionMethods: {
                        create : 'POST',
                        read   : 'POST', // by default POST
                        update : 'POST',
                        destroy: 'POST'
                    },
                    url : 'index.php?action=ExtSalary&mode=searchErjigongsi',

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
             innerGrid[renderId] = Ext.create('Ext.grid.Panel', {
                store: insideGridStore[renderId],
                selType: 'checkboxmodel',
                columns: [
                    {text: "编号", width: 120, dataIndex: 'id', sortable: false},
                    {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: false},
                    {text: "公司级别", flex: 200, dataIndex: 'company_level', sortable: false}
                ],
                columnLines: true,
//                autoWidth: true,
                 width:500,
                autoHeight: true,
                disableSelection: false,
                frame: false,
                title: '二级单位',
                tbar : [
                    {
                        xtype : 'button',
                        id : 'tiquan'+renderId,
                        disabled: false,
                        handler : function(src) {
                            var record = innerGrid[renderId].getSelectionModel().getSelection();
                            if (record) {
                                var itcIds = [];
                                if(record.length>0){
                                for(var i=0;i<record.length;i++){
                                    itcIds.push(record[i].data.id);
                                }

                                Ext.Ajax.request({
                                    url:'index.php?action=SaveSalary&mode=setTypeGongsijibie',
                                    method: 'post',
                                    params: {
                                        superId:0,
                                        ids : Ext.JSON.encode(itcIds)
                                    },
                                    success: function(response){
                                        var text = response.responseText;
                                        Ext.Msg.alert("提示",text);
                                        gongsijibie.removeAll();
                                        gongsijibie.load( {
                                            params : {
                                                start : 0,
                                                limit : 50
                                            }
                                        });
                                    }
                                });
                                }else{
                                    Ext.Msg.alert("提示","请选择公司。");
                                    return;
                                }

                            } else {
                                Ext.Msg.alert("提示","'请选择一条记录");
                                return;
                            }
                        },
                        text : '设为一级公司',
                        iconCls : 'tiquan'+renderId
                    },
                    {
                        xtype : 'button',
                        id : 'zhuanyi'+renderId,
                        disabled: false,
                        handler : function(src) {
                            var model =  innerGrid[renderId].getSelectionModel();
                            var sel=model.getSelection();
                            if(sel[0]&& !sel[1]){
                            checkSalWinEr(sel[0].data.id);
                                comListSuperStore.removeAll();
                                comListSuperStore.load( {
                                    params : {

                                    }
                                });
                            }else{
                                Ext.Msg.alert("提示","请选择一个公司并且只选择一个公司保证操作准确");
                                return;
                            }
                        },
                        text : '改变所属公司',
                        iconCls : 'zhuanyi'+renderId
                    }
                ],
                iconCls: 'icon-grid',
                renderTo: renderId
            });
            insideGridStore[renderId].removeAll();
            insideGridStore[renderId].load( {
                params : {
                       superId:renderId
                }
            });
            innerGrid[renderId].getEl().swallowEvent([
                'mousedown', 'mouseup', 'click',
                'contextmenu', 'mouseover', 'mouseout',
                'dblclick', 'mousemove'
            ]);
             salLister[renderId]=Ext.create("Ext.grid.Panel",{
                store: comListSuperStore,
                selType: 'checkboxmodel',
                id : 'companyListEr'+renderId,
                columns: [
                    {text: "id", width: 120, dataIndex: 'id', sortable: true},
                    {text: "公司名称", flex: 200, dataIndex: 'company_name', sortable: true}
                ],
                height:400,
                width:500,
                title: '',
                disableSelection: false,
                loadMask: true,
                viewConfig: {
                    id: 'gv'+renderId,
                    trackOver: false,
                    stripeRows: false
                },

                bbar: Ext.create('Ext.PagingToolbar', {
                    store: comListSuperStore,
                    displayInfo: true,
                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                    emptyMsg: "没有数据"
                }),
                tbar : [{
                    xtype : 'button',
                    id : 'bt_deleteDocument'+renderId,
                    handler : function() {
                        var model =  salLister[renderId].getSelectionModel();
                        var sel=model.getSelection();
                        var model0 =  innerGrid[renderId].getSelectionModel();
                        var sel0=model0.getSelection();
                        var ids = [];
                        if(sel[0]&& !sel[1]){
                            ids[0]=sel0[0].data.id;
                            Ext.Ajax.request({
                                url:'index.php?action=SaveSalary&mode=setTypeGongsijibie',
                                method: 'post',
                                params: {
                                    superId:sel[0].data.id,
                                    ids : Ext.JSON.encode(ids)
                                },
                                success: function(response){
                                    var text = response.responseText;
                                    Ext.Msg.alert("提示",text);
                                    winSaler[renderId].hide();
                                    insideGridStore[renderId].removeAll();
                                    insideGridStore[renderId].load( {
                                        params : {
                                            superId:renderId
                                        }
                                    });
                                }
                            });
                        }else{
                            Ext.Msg.alert("提示","只可以为二级公司设置一个父公司");
                            return;
                        }

                    },
                    text : '设为父公司',
                    iconCls : 'shanchu'+renderId
                }, '公司查询', {
                    id:'comnameid'+renderId,
                    xtype : 'trigger',
                    triggerClass : 'x-form-search-trigger',
                    onTriggerClick : function(src) {
                        comListSuperStore.removeAll();
                        comListSuperStore.load( {
                            params : {
                                Key : this.getValue(),
                                start : 0,
                                limit : 50
                            }
                        });

                    }

                }]
            });
            comListSuperStore.on("beforeload",function(){
                Ext.apply(comListSuperStore.proxy.extraParams, {Key:Ext.getCmp('comnameid'+renderId).getValue()});

            });
            function checkSalWinEr(fuid) {
                var itemser=[salLister[renderId]];
                 winSaler[renderId] = Ext.create('Ext.window.Window', {
                    title: "二级公司设置", // 窗口标题
                    width:510, // 窗口宽度
                    height:450, // 窗口高度
                    layout:"border",// 布局
                    minimizable:true, // 最大化
                    maximizable:true, // 最小化
                    frame:true,
                    constrain:true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
                    buttonAlign:"center", // 按钮显示的位置
                    modal:true, // 模式窗口，弹出窗口后屏蔽掉其他组建
                    resizable:true, // 是否可以调整窗口大小，默认TRUE。
                    plain:true,// 将窗口变为半透明状态。
                    items:itemser,
                    listeners: {
                        //最小化窗口事件
                        minimize: function(window){
                            this.hide();
                            window.minimizable = true;
                        }
                    },
                    closeAction:'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
                });
                winSaler[renderId] .show();
            }

        }


        function destroyInnerGrid(record) {

            var parent = document.getElementById(record.get('id'));
            var child = parent.firstChild;

            while (child) {
                child.parentNode.removeChild(child);
                child = child.nextSibling;
            }

        }
        Ext.onReady(function(){

            //创建Grid
            var salTimeListGrid = Ext.create('Ext.grid.Panel',{
                store: gongsijibie,
                selType: 'checkboxmodel',
                id : 'comlist',
                columns: [
                    {text: "编号", width: 120, dataIndex: 'id', sortable: true},
                    {text: "单位名称", width: 200, dataIndex: 'company_name', sortable: true},
                    {text: "公司级别", width: 200, dataIndex: 'company_level', sortable: true},
                    {text: "下属单位个数", width: 200, dataIndex: 'geshu', sortable: true}
                ],
                height:700,
                width:1000,
                x:0,
                y:0,
                plugins: [{
                    ptype: 'rowexpander',
                    rowBodyTpl: [
                        '<div id="{id}">',
                        '</div>'
                    ]
                }],
                title: '公司级别设置',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },
                bbar: Ext.create('Ext.PagingToolbar', {
                    store: gongsijibie,
                    displayInfo: true,
                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                    emptyMsg: "没有数据"
                }),
                tbar : [
                    {
                        xtype : 'button',
                        id : 'searchSalBu',
                        disabled: true,
                        handler : function(src) {
                            var model =  salTimeListGrid.getSelectionModel();
                            var sel=model.getSelection();
                            if(sel[0]&& !sel[1]){
                                checkSalWin(sel[0].data.id);
                                comListStore.removeAll();
                                comListStore.load( {
                                    params : {
                                        start : 0,
                                        limit : 50
                                    }
                                });
                            }else{
                                Ext.Msg.alert("提示","请选择一个公司并且只选择一个公司保证操作准确");
                                return;
                            }
                        },
                        text : '设置下属公司',
                        iconCls : 'shezhi'
                    },
                    '公司名称查询', {
                        id:'comname',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'comname',
                        onTriggerClick : function(src) {
                            gongsijibie.removeAll();
                            gongsijibie.load( {
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
                            gongsijibie.removeAll();
                            gongsijibie.load( {
                                params : {
                                    companyName : Ext.getCmp("comname").getValue(),
                                    start : 0,
                                    limit : 50
                                }
                            });
                        },
                        text : '查询',
                        iconCls : 'chaxun'
                    }
                ]
            });


            salTimeListGrid.view.on('expandBody', function (rowNode, record, expandRow, eOpts) {
                displayInnerGrid(record.get('id'));
            });

            salTimeListGrid.view.on('collapsebody', function (rowNode, record, expandRow, eOpts) {
                destroyInnerGrid(record);
            });
            gongsijibie.on("beforeload",function(){
                Ext.apply(gongsijibie.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),companyName:Ext.getCmp("comname").getValue()});

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
            var salList=Ext.create("Ext.grid.Panel",{
                store: comListStore,
                selType: 'checkboxmodel',
                id : 'companyList',
                columns: [
                    {text: "id", width: 120, dataIndex: 'id', sortable: true},
                    {text: "公司名称", flex: 200, dataIndex: 'company_name', sortable: true}
                ],
                height:400,
                width:500,
                title: '',
                disableSelection: false,
                loadMask: true,
                viewConfig: {
                    id: 'gv1',
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
                        var model =  salTimeListGrid.getSelectionModel();
                        var sel=model.getLastSelected();
                        var record = Ext.getCmp('companyList').getSelectionModel().getSelection();
                        if (record) {
                            var itcIds = [];
                            for(var i=0;i< record.length;i++){
                                itcIds.push(record[i].data.id);
                            }
                            Ext.Ajax.request({
                                url:'index.php?action=SaveSalary&mode=setTypeGongsijibie',
                                method: 'post',
                                params: {
                                    superId:sel.data.id,
                                    ids : Ext.JSON.encode(itcIds)
                                },
                                success: function(response){
                                    var text = response.responseText;
                                    Ext.Msg.alert("提示",text);
                                    winSal.hide();
                                    gongsijibie.removeAll();
                                    gongsijibie.load( {
                                        params : {
                                            start : 0,
                                            limit : 50
                                        }
                                    });

                                }
                            });

                        } else {
                            Ext.Msg.alert("提示","'请选择一条记录");
                            return;
                        }
                    },
                    text : '设为下属公司',
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
                Ext.apply(comListStore.proxy.extraParams, {Key:Ext.getCmp("comnameid").getValue(),companyName:Ext.getCmp("comnameid").getValue()});

            });
//通过ajax获取表头已经表格数据
            function checkSalWin(timeId) {
                var items=[salList];
                 winSal = Ext.create('Ext.window.Window', {
                    title: "二级公司设置", // 窗口标题
                    width:510, // 窗口宽度
                    height:450, // 窗口高度
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
            gongsijibie.loadPage(1);
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
