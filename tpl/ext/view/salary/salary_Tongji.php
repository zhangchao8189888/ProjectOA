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
            store: caiwuListStore,
            id: 'comlist',
            title: '公司列表',
            columns: [
                {text: "id", width: 50, dataIndex: 'id', sortable: true},
                {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
            ],
            height: 650,
            width: 350,
            x: 0,
            y: 0,

            disableSelection: false,
            loadMask: true,
            renderTo: 'demo',
            viewConfig: {
                id: 'gv',
                trackOver: false,
                stripeRows: false
            },
            bbar: Ext.create('Ext.PagingToolbar', {
                store: caiwuListStore,
                displayInfo: true,
                displayMsg: '{2}',
                emptyMsg: "空"
            }),

            tbar: [
                {
                    xtype: 'button',
                    id: 'searchSalBu',
                    disabled: false,
                    handler: function (src) {
                        var model = salTongjiGrid.getSelectionModel();
                        var sel=model.getLastSelected();
                        salTongjistore.removeAll();
                        salTongjistore.load( {
                            params : {
                                comid:sel.data.id,
                                start : 0,
                                limit : 50
                            }
                        });
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
                        caiwuListStore.removeAll();
                        caiwuListStore.load({
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
        caiwuListStore.on("beforeload", function () {

            Ext.apply(caiwuListStore.proxy.extraParams, {Key: Ext.getCmp("comname").getValue(), companyName: Ext.getCmp("comname").getValue()});

        });
        salTongjiGrid.getSelectionModel().on('selectionchange', function (selModel, selections) {
            //var sel=model.getLastSelected();
            Ext.getCmp("searchSalBu").setDisabled(selections.length === 0);
        }, this);
        caiwuListStore.loadPage(1) ;

        /**
         * 右侧查询栏
         */
        var salTimeListGrid2 = Ext.create('Ext.grid.Panel',{
            store: salTongjistore,
            id : 'comlist2',
            loadMask:true,
            columns : [
                {text: "编号", width: 50, dataIndex: 'id', sortable: true,align:'center'},
                {text: "状态", width: 130, dataIndex: 'state', sortable: true},
                {text: "缴中企基业合计", width: 110, dataIndex: 'sum_paysum_zhongqi', sortable: true},
                {text: "本月余额", width: 100, dataIndex: 'this_month_yue', sortable: true},
                {text: "累计余额", width: 100, dataIndex: 'sum_yue', sortable: true},
                {text: "工资月份",width: 90,dataIndex: 'salaryTime', sortable: true,align:'center'},
                {text: "个人实发合计", width: 100, dataIndex: 'sum_per_shifaheji', sortable: true},
                {text: "单位合计", width: 100, dataIndex: 'sum_com_heji', sortable: true},
                {text: "发票日期",width: 90, dataIndex: 'bill_date', sortable: true,align:'center'},
                {text: "发票项目", width: 100, dataIndex: 'bill_value', sortable: true},
                {text: "发票金额",width: 100, dataIndex: 'bill_money', sortable: true},
                {text: "发票金额合计",width: 100, dataIndex: 'bill_money_sum', sortable: true},
                {text: "支票日期",width: 100, dataIndex: 'cheque_date', sortable: true},
                {text: "支票金额",width: 100, dataIndex: 'cheque_money', sortable: true},
                {text: "支票金额合计",width: 100, dataIndex: 'cheque_money_sum', sortable: true},
                {text: "支票到账日期",width: 100, dataIndex: 'cheque_account_date', sortable: true},
                {text: "支票到账金额",width: 100, dataIndex: 'cheque_account_money', sortable: true},
                {text: "到账金额合计",width: 100, dataIndex: 'account_money_sum', sortable: true},
                {text: "个人应发合计",width: 100, dataIndex: 'sum_per_yingfaheji', sortable: true},
                {text: "个人失业",width: 100, dataIndex: 'sum_per_shiye', sortable: true},
                {text: "个人医疗",width: 100, dataIndex: 'sum_per_yiliao', sortable: true},
                {text: "个人养老",width: 100, dataIndex: 'sum_per_yanglao', sortable: true},
                {text: "个人公积金",width: 100, dataIndex: 'sum_per_gongjijin', sortable: true},
                {text: "代扣税",width: 100, dataIndex: 'sum_per_daikoushui', sortable: true},
                {text: "个人扣款合计", width: 100, dataIndex: 'sum_per_koukuangheji', sortable: true},
                {text: "单位失业", width: 100, dataIndex: 'sum_com_shiye', sortable: true},
                {text: "单位医疗", width: 100, dataIndex: 'sum_com_yiliao', sortable: true},
                {text: "单位养老", width: 100, dataIndex: 'sum_com_yanglao', sortable: true},
                {text: "单位工伤", width: 100, dataIndex: 'sum_com_gongshang', sortable: true},
                {text: "单位生育", width: 100, dataIndex: 'sum_com_shengyu', sortable: true},
                {text: "单位公积金", width: 100, dataIndex: 'sum_com_gongjijin', sortable: true}
            ],
            height:650,
            width:800,
            x:350,
            y:-650,
            title: '工资统计详细',
            disableSelection: false,
            loadMask: true,
            renderTo: 'demo',
            viewConfig: {
                id: 'gv2',
                trackOver: false,
                stripeRows: false
            },
            bbar: Ext.create('Ext.PagingToolbar', {
                store: salTongjistore,
                displayInfo: true,
                displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                emptyMsg: "没有数据"
            }),
            tbar: [
                {
                    xtype: 'button',
                    id: 'searchsSalBu',
                    disabled: false,
                    handler: function (src) {
                    },
                    text: '查看详细',
                    iconCls: 'chakan'
                },
                {
                    xtype: 'button',
                    id: 'edit',
                    disabled: false,
                    handler: function (src) {
                        var model = salTongjiGrid.getSelectionModel();
                        var sel=model.getLastSelected();
                        checkSalWin(sel.data.sum_yue);
                    },
                    text: '修改余额',
                    iconCls: 'chakan'
                }
            ]
        });

        salTongjistore.on("beforeload",function(){
            Ext.apply(salTongjistore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),comname:Ext.getCmp("comname").getValue()});
        });

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
                title: "修改余额", // 窗口标题
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
                {
                    id:'salaryTime',
                    xtype : 'textfield',
                    name: 'salaryTime'
                },
                '余额',
                {
                    id:'sum_yue',
                    xtype : 'textfield',
                    name: 'sum_yue'
                },
                {
                    xtype : 'button',
                    id : 'benyue',
                    handler : function(src) {
                        var model = salTimeListGrid.getSelectionModel();
                        var sel=model.getLastSelected();
                        setBenyue(sel.data.id);
                    },
                    text : '修改',
                    iconCls : 'update'
                }
            ],
            //displayInfo : true,
            emptyMsg : "没有数据显示"
        });
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
