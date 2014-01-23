<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>工资统计</title>
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
    'Ext.data.*',

]);
Ext.onReady(function(){

    //创建Grid
    var salTimeListGrid = Ext.create('Ext.grid.Panel',{
        //store: geshuiListstore,
        selType: 'checkboxmodel',
        id : 'comlist',
        columns: [
            {text: "编号", width: 120, dataIndex: 'company_id', sortable: true},
            {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true}
        ],
        height:700,
        width:1000,
        x:0,
        y:0,
        title: '工资统计',
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
                    checkSalWin();
                },
                text : '工资统计查看',
                iconCls : 'chakan'
            }

        ]
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
      //  store:geShuiExcelExportStore,
        width:salListWidth,
        height:450,
        enableLocking : true,
        id : 'configGrid',
        name : 'configGrid',
        features: [{
            ftype: 'summary'
        }],
        columns : [
            {text: "编号", width: 120, dataIndex: 'company_id', sortable: true},
            {text: "单位名称", flex: 200, dataIndex: 'ename', sortable: true},
            {text: "工资月份", flex: 200, dataIndex: 'e_num', sortable: true},
            {text: "发票日期", flex: 200, dataIndex: 'salaryTime', sortable: true},
            {text: "发票项目", width: 120, dataIndex: 'companyname', sortable: true},
            {text: "发票金额", flex: 200, dataIndex: 'daikou', sortable: true},
            {text: "发票金额合计", flex: 200, dataIndex: 'bukou', sortable: true},
            {text: "支票日期", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "支票金额", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "支票金额合计", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "支票到账日期", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "支票到账金额", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "到账金额合计", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "个人应发合计", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "个人失业", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "个人医疗", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "个人养老", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "个人公积金", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "代扣税", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "个人扣款合计", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "实发合计", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "单位失业", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "单位医疗", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "单位养老", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "单位工伤", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "单位生育", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "单位公积金", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "单位合计", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "交中企基业合计", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "本月余额", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "累计余额", flex: 200, dataIndex: 'nian', sortable: true},
            {text: "状态", flex: 200, dataIndex: 'nian', sortable: true}

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
            title: "查看工资", // 窗口标题
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
<form id="iform" action="" method="post">
</form>
</body>
</html>
