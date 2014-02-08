<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>工资统计</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
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
    Ext.onReady(function () {

        //创建Grid
        var salTongjiGrid = Ext.create('Ext.grid.Panel', {
            store: getCaiwuManageCompanyListStore,
            id: 'comlist',
            columns: [
                {text: "id", width: 120, dataIndex: 'id', sortable: true},
                {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
            ],
            height: 650,
            width: 1000,
            x: 0,
            y: 0,
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
                store: getCaiwuManageCompanyListStore,
                displayInfo: true,
                displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                emptyMsg: "没有数据"
            }),

            tbar: [
                {
                    xtype: 'button',
                    id: 'searchSalBu',
                    disabled: false,
                    handler: function (src) {
                        var model = salTongjiGrid.getSelectionModel();
                        var sel = model.getLastSelected();
                        checkSalWin(sel.data.id);
                    },
                    text: '查询',
                    iconCls: 'chakan'
                },
                '公司名称查询',
                {
                    id: 'comname',
                    xtype: 'trigger',
                    triggerClass: 'x-form-search-trigger',
                    name: 'comname',
                    onTriggerClick: function (src) {
                        getCaiwuManageCompanyListStore.removeAll();
                        getCaiwuManageCompanyListStore.load({
                            params: {
                                companyName: this.getValue(),
                                start: 0,
                                limit: 50
                            }
                        });
                    }
                }
            ]
        });
        getCaiwuManageCompanyListStore.on("beforeload", function () {

            Ext.apply(getCaiwuManageCompanyListStore.proxy.extraParams, {Key: Ext.getCmp("comname").getValue(), companyName: Ext.getCmp("comname").getValue()});

        });
        salTongjiGrid.getSelectionModel().on('selectionchange', function (selModel, selections) {
            //var sel=model.getLastSelected();
            Ext.getCmp("searchSalBu").setDisabled(selections.length === 0);
        }, this);


        var salListWidth = 1150;
        var salList = Ext.create("Ext.grid.Panel", {
            title: '',
            width: salListWidth,
            height: 550,
            enableLocking: true,
            id: 'configGrid',
            name: 'configGrid',
            features: [
                {
                    ftype: 'summary'
                }
            ],
            columns: [], //注意此行代码，至关重要
            //displayInfo : true,
            emptyMsg: "没有数据显示"
        });


        var salTimeList = Ext.define('salTimeList', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'salTimeId', type: 'int'},
                {name: 'salaryTime', type: 'string'}
            ]
        });


        function checkSalWin(timeId) {
            //加载数据遮罩
            var mk = new Ext.LoadMask(Ext.getBody(), {
                msg: '加载数据中，请稍候！', removeMask: true
            });
            mk.show();
            var items = [salList];
            var winSal = Ext.create('Ext.window.Window', {
                title: "工资统计", // 窗口标题
                width: 1200, // 窗口宽度
                height: 600, // 窗口高度
                layout: "border",// 布局
                minimizable: true, // 最大化
                maximizable: true, // 最小化
                frame: true,
                constrain: true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
                buttonAlign: "center", // 按钮显示的位置
                modal: true, // 模式窗口，弹出窗口后屏蔽掉其他组建
                resizable: true, // 是否可以调整窗口大小，默认TRUE。
                plain: true,// 将窗口变为半透明状态。
                items: items,
                listeners: {
                    //最小化窗口事件
                    minimize: function (window) {
                        this.hide();
                        mk.hide();
                        window.minimizable = true;
                    },
                    close: function () {
                        mk.hide();
                    }
                },
                closeAction: 'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
            });
            var url = "index.php?action=ExtSalary&mode=searchSalaryTongji";

            Ext.Ajax.request({
                url: url,  //从json文件中读取数据，也可以从其他地方获取数据
                method: 'POST',
                params: {
                    timeId: timeId
                },
                success: function (response) {
                    //将返回的结果转换为json对象，注意extjs4中decode函数已经变成了：Ext.JSON.decode
                    mk.hide();
                    var json = Ext.JSON.decode(response.responseText); //获得后台传递json

                    //创建store
                    var store = Ext.create('Ext.data.Store', {
                        fields: json.fields,//把json的fields赋给fields
                        data: json.data     //把json的data赋给data

                    });

                    //根据store和column构造表格
                    Ext.getCmp("configGrid").reconfigure(store, json.columns);
                    //重新渲染表格
                    //Ext.getCmp("configGrid").render();
                }
            });
            //winSal.items=[p,salList];
            winSal.show();
        }

        getCaiwuManageCompanyListStore.loadPage(1)
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
