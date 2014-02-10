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
                store: getCaiwuManageCompanyListStore,
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
                        salTongjistore.removeAll();
                        salTongjistore.load( {
                            params : {
                                comname :Ext.getCmp("comname").getValue(),
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
        getCaiwuManageCompanyListStore.loadPage(1) ;

        /**
         * 右侧查询栏
         */
        var salTimeListGrid2 = Ext.create('Ext.grid.Panel',{
            store: salTongjistore,
            id : 'comlist2',
            columns : [
                {text: "编号", width: 45, dataIndex: 'id', sortable: true},
                {text: "工资月份",width: 120,dataIndex: 'salaryTime', sortable: true},
                {text: "发票日期",width: 120, dataIndex: 'bill_date', sortable: true},
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
                {text: "实发合计", width: 100, dataIndex: 'sum_per_shifaheji', sortable: true},
                {text: "单位失业", width: 100, dataIndex: 'sum_com_shiye', sortable: true},
                {text: "单位医疗", width: 100, dataIndex: 'sum_com_yiliao', sortable: true},
                {text: "单位养老", width: 100, dataIndex: 'sum_com_yanglao', sortable: true},
                {text: "单位工伤", width: 100, dataIndex: 'sum_com_gongshang', sortable: true},
                {text: "单位生育", width: 100, dataIndex: 'sum_com_shengyu', sortable: true},
                {text: "单位公积金", width: 100, dataIndex: 'sum_com_gongjijin', sortable: true},
                {text: "单位合计", width: 100, dataIndex: 'sum_com_heji', sortable: true},
                {text: "缴中企基业合计", width: 100, dataIndex: 'sum_paysum_zhongqi', sortable: true},
                {text: "本月余额", width: 100, dataIndex: 'this_month_yue', sortable: true},
                {text: "累计余额", width: 100, dataIndex: 'sum_yue', sortable: true},
                {text: "状态", width: 200, dataIndex: 'state', sortable: true}

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

        });

        salTongjistore.on("beforeload",function(){
            Ext.apply(salTongjistore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),comname:Ext.getCmp("comname").getValue()});
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
