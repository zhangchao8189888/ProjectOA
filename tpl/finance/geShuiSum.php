<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>个税统计</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
    <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
    <link href="common/css/admin.css" rel="stylesheet" type="text/css" />

    <script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript" src="tpl/ext/js/monthPickerPlugin.js" charset="utf-8"></script>
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
                store: geshuiListstore,
                selType: 'checkboxmodel',
                id : 'comlist',
                columns: [
                    {text: "查年终奖", flex: 120, dataIndex: 'company_id', sortable: true},
                    {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
                    {text: "个税日期", flex: 200, dataIndex: 'salaryTime', sortable: true},
                    {text: "代扣税", flex: 200, dataIndex: 'daikou', sortable: true},
                    {text: "补扣税", flex: 200, dataIndex: 'bukou', sortable: true},
                    {text: "个税合计", flex: 200, dataIndex: 'geshuiSum', sortable: true}
                ],
                height:700,
                width:800,
                x:0,
                y:0,
                title: '个税统计',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },
//                bbar: Ext.create('Ext.PagingToolbar', {
////                    store: geshuiListstore,
////                    displayInfo: true,
////                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
////                    emptyMsg: "没有数据"
//                }),

                tbar : [

                    {
                        xtype : 'button',
                        id : 'searchSalBu',
                        handler : function(src) {
                            var times = [];
                            var names = [];
                            var nian = [];
                            var model = salTimeListGrid.getSelectionModel();
                            var i = 0;
                            geshuiListstore.each(function(record) {
                                    if(record.data.salaryTime != '<span style=\"color: red\">未作工资或免税</span>'){
                                     times.push(record.data.salaryTime);
                                     names.push(record.data.company_name);
                                        if(model.isSelected(i)){
                                            nian.push(1);
                                        }else{
                                            nian.push(0);
                                        }
                                     }
                                i++;
                        });
                            geShuiExcelExportStore.load( {
                                    params: {
                                        timeId : Ext.JSON.encode(names),
                                        time:Ext.JSON.encode(times),
                                        nian:Ext.JSON.encode(nian)
                                    }
                                }
                            );
                            checkSalWin();
                        },
                        text : '个税查看导出',
                        iconCls : 'chakan'
                    },
                    '公司名称查询', {
                        id:'comname',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'comname',
                        onTriggerClick : function(src) {
                            geshuiListstore.removeAll();
                            geshuiListstore.load( {
                                params : {
                                    companyName : this.getValue(),
                                    salTime : Ext.getCmp("salTime").getValue(),
                                    start : 0,
                                    limit : 50
                                }
                            });
                        }
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
                            geshuiListstore.removeAll();
                            geshuiListstore.load({
                                params: {
                                    companyName : Ext.getCmp("comname").getValue(),
                                    salTime : Ext.getCmp("salTime").getValue(),
                                    start : 0,
                                    limit : 50
                                }
                            });

                        },
                        text: '月份查找'
                    }
                ]
            });

            geshuiListstore.on("beforeload",function(){
                Ext.apply(geshuiListstore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),companyName:Ext.getCmp("comname").getValue()});

            });

            /**
             * 定义工资table
             */
            //创建表格,可以加入更多的属性。
            var salListWidth=1150;
            var salList=Ext.create("Ext.grid.Panel",{
                title:'',
                store:geShuiExcelExportStore,
                width:salListWidth,
                height:450,
                enableLocking : true,
                id : 'configGrid',
                name : 'configGrid',
                features: [{
                    ftype: 'summary'
                }],
                columns : [
                    {text: "个人编号", width: 120, dataIndex: 'company_id', sortable: true},
                    {text: "姓名", flex: 200, dataIndex: 'ename', sortable: true},
                    {text: "身份证号", flex: 200, dataIndex: 'e_num', sortable: true},
                    {text: "个税日期", flex: 200, dataIndex: 'salaryTime', sortable: true},
                    {text: "所在单位", width: 120, dataIndex: 'companyname', sortable: true},
                    {text: "代扣税", flex: 200, dataIndex: 'daikou', sortable: true},
                    {text: "补扣税", flex: 200, dataIndex: 'bukou', sortable: true},
                    {text: "年终奖扣税", flex: 200, dataIndex: 'nian', sortable: true},
                    {text: "个税合计", flex: 200, dataIndex: 'geshuiSum', sortable: true}
                ], //注意此行代码，至关重要
                tbar : [
                    {
                        xtype : 'button',
                        id : 'toExcel',
                        handler : function() {
                            $("#iform").attr("action","importGeshui.php");
                            $("#iform").submit();
                            Ext.Msg.confirm("提示","是否插入已报个税标识？",updateGeshuiType);
                        },
                        text : '导出',
                        iconCls : 'toExcel'
                    }
                ],
                //displayInfo : true,
                emptyMsg : "没有数据显示"



            });
//插入已报个税记录
            function updateGeshuiType(btn) {
                if(btn=="yes")
                {
                    var times = [];
                    var names = [];
                    var nian = [];
                    var model = salTimeListGrid.getSelectionModel();
                    var i = 0;
                    geshuiListstore.each(function(record) {
                        if(record.data.salaryTime != '<span style=\"color: red\">未作工资或免税</span>'){
                            times.push(record.data.salaryTime);
                            names.push(record.data.company_id);
                            if(model.isSelected(i)){
                                nian.push(1);
                            }else{
                                nian.push(0);
                            }
                        }
                        i++;
                    });
                    var url = "index.php?action=SaveSalary&mode=insGeshuijilu";
                    Ext.Ajax.request({
                        url: url,  //从json文件中读取数据，也可以从其他地方获取数据
                        method : 'POST',
                        params: {
                            timeId : Ext.JSON.encode(names),
                            time:Ext.JSON.encode(times),
                            nian:Ext.JSON.encode(nian)
                        },
                        success : function(response) {

                        }
                    });
                }

            }
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
                    title: "查看个税", // 窗口标题
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
            geshuiListstore.loadPage(1);

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
            var salTimeListGrid1 = Ext.create('Ext.grid.Panel',{
               store: nianxuanStore,
                selType: 'checkboxmodel',
                id : 'comlist1',
                columns: [
                    {text: "编号", width: 80, dataIndex: 'companyid', sortable: true},
                    {text: "公司名称", width: 240, dataIndex: 'companyname', sortable: true}
                ],
                height:700,
                width:350,
                x:800,
                y:-700,
                title: '年终奖选择',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv1',
                    trackOver: false,
                    stripeRows: false
                },
                tbar : [

                    '名称', {
                        id:'cname',
                        width:80,
                        xtype : 'trigger',
                        name: 'companyname',
                        onTriggerClick : function(src) {
                            nianxuanStore.removeAll();
                            nianxuanStore.load( {
                                params : {
                                    cname : this.getValue(),
                                    leixing :  Ext.getCmp("leixing").getValue()
                                }
                            });
                        }
                    },
                    {
                        xtype: 'combobox',
                        id:"leixing" ,
                        width:100,
                        editable: false,
                        emptyText: "请选择",
                        allowBlank: false,
                        store: {
                            fields: ['abbr', 'name'],
                            data: [
                                {"abbr": 1, "name": "已报年终奖"},
                                {"abbr": 2, "name": "未报年终奖"},
                                {"abbr": 3, "name": "全部公司"}
                            ]
                        },
                        listeners: {
                            select: function () {
                                nianxuanStore.removeAll();
                                nianxuanStore.load( {
                                    params : {
                                        cname : Ext.getCmp("cname").getValue(),
                                        leixing :  Ext.getCmp("leixing").getValue()
                                    }
                                });
                            }
                        },
                        valueField: 'abbr',
                        displayField: 'name',
                        fieldLabel: ''
                    },
                    {
                        xtype: 'button',
                        id: 'xuanzhong',
                        disabled: false,
                        handler: function () {
                            var model = salTimeListGrid1.getSelectionModel();
                            var model1 = salTimeListGrid.getSelectionModel();
                            var sel=model.getSelection();
                            var datas = [];
                            for(var i = 0; i < sel.length ;i++){
                                var m=0;
                                geshuiListstore.each(function(record) {
                                    if(record.data.company_id ==   sel[i].data.companyid || model1.isSelected(m)){
                                       // salTimeListGrid.getSelectionModel().select(m);
                                        datas.push(geshuiListstore.getAt(m));
                                    }
                                    m++;
                                });

                            }

                            salTimeListGrid.getSelectionModel().select(datas);

                        },
                        text: '选中'
                    },
                    {
                        xtype: 'button',
                        id: 'paichu',
                        disabled: false,
                        handler: function () {
                            var model = salTimeListGrid1.getSelectionModel();
                            var model1 = salTimeListGrid.getSelectionModel();
                            var sel=model.getSelection();
                            var datas = [];
                            for(var i = 0; i < sel.length ;i++){
                                var m=0;
                                geshuiListstore.each(function(record) {
                                    if(record.data.company_id ==   sel[i].data.companyid){
                                        // salTimeListGrid.getSelectionModel().select(m);
                                        datas.push(geshuiListstore.getAt(m));
                                    }
                                    m++;
                                });

                            }

                            salTimeListGrid.getSelectionModel().deselect(datas);

                        },
                        text: '排除'
                    }
                ]

            });
            nianxuanStore.loadPage(1);

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
