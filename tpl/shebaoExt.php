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
    'Ext.data.*'
]);
Ext.onReady(function(){
    //创建Grid
    var salTimeListGrid = Ext.create('Ext.grid.Panel',{
        store: zengjianListstore,
        selType: 'checkboxmodel',
        id : 'comlist',
        columns: [
            {text: "编号", width: 100, dataIndex: 'id', sortable: true},
            {text: "客服姓名", width: 100, dataIndex: 'CName', sortable: true},
            {text: "部门", width: 150, dataIndex: 'Dept', sortable: true},
            {text: "员工姓名", width: 100, dataIndex: 'EName', sortable: true},
            {text: "身份证号", width: 100, dataIndex: 'EmpNo', sortable: true},
            {text: "身份类别", width: 100, dataIndex: 'EmpType', sortable: true},
            {text: "操作标志", width: 100, dataIndex: 'zengjianbiaozhi', sortable: true},
            {text: "社保基数", width: 100, dataIndex: 'shebaojishu', sortable: true},
            {text: "外区转入/新参保", width: 200, dataIndex: 'waiquzhuanru', sortable: true},
            {text: "金额合计", width: 100, dataIndex: 'sum', sortable: true},
            {text: "用人单位基数", width: 150, dataIndex: 'danweijishu', sortable: true},
            {text: "操作人姓名", width: 150, dataIndex: 'caozuoren', sortable: true},
            {text: "申报状态", width: 100, dataIndex: 'shenbaozhuangtai', sortable: true},
            {text: "备注", width: 100, dataIndex: 'beizhu', sortable: true}
        ],
        height:700,
        width:1000,
        x:0,
        y:0,
        title: '增减员查看',
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
                xtype: 'button',
                id: 'bt_deleteDocument',
                handler: function (src) {
                    var model = salTimeListGrid.getSelectionModel();
                    var sel=model.getSelection();
                     if (sel) {
                         var ids = [];
                         for(var i = 0; i < sel.length ;i++){
                             ids.push(sel[i].data.id);
                         }
                        Ext.Ajax.request({
                            url: 'index.php?action=ExtSalary&mode=deleteZengjianyuan',
                            method: 'post',
                            params: {
                                ids: Ext.JSON.encode(ids)
                            },
                            success: function (response) {
                                var text = response.responseText;

                                zengjianListstore.load( {
                                        params: {

                                        }
                                    }
                                );
                            }
                        });

                    } else {
                        alert('请选择一条记录');
                    }

                },
                text : '删除',
                iconCls : 'shanchu'
            },

            {
                xtype : 'button',
                id : 'searchSalBu',
                handler : function(src) {
                    checkSalWin();
                },
                text : '增员按钮',
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
        width:salListWidth,
        height:450,
        enableLocking : true,
        id : 'configGrid',
        name : 'configGrid',
        features: [{
            ftype: 'summary'
        }],
        columns : [

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
            title: "增员信息", // 窗口标题
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
    zengjianListstore.loadPage(1);
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
