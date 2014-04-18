<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>审批工资</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/MonthPickerPlugin.js" charset="utf-8"></script>
<style type="text/css">
    <!--
    A { text-decoration: none}
    -->
</style>
<script type="text/javascript">
Ext.require([
    'Ext.grid.*',
    'Ext.toolbar.Paging',
    'Ext.data.*'
]);
Ext.onReady(function () {
    var insurancewindow = Ext.create('Ext.grid.Panel',{
        store: salTimeListApprovalstore,
        selType: 'checkboxmodel',
        id : 'comlist',
        columns: [
            {text: "编号", width: 80, dataIndex: 'id', sortable: false,hidden:true},
            {text: "单位名称", width: 200, dataIndex: 'company_name', sortable: true,
                renderer: function (val, cellmeta, record) {
                    return '<a href="#" onclick="checkSalWin(' + record.data['id'] + ')">'+val+'</a>';
                }
            },
            {text: "工资月份", width: 100, dataIndex: 'salaryTime', sortable: true},
            {text: "保存工资日期", width: 100, dataIndex: 'op_salaryTime', sortable: true},
            {text: "实发合计", width: 100, dataIndex: 'sum_per_shifaheji', sortable: true},
            {text: "代扣税", width: 100, dataIndex: 'sum_per_daikoushui', sortable: true},
            {text: "缴中企合计", width: 100, dataIndex: 'sum_paysum_zhongqi', sortable: true},
            {text: "状态", flex: 120, dataIndex: 'bill_value', sortable: false,align:'center',
                renderer:function(val,cellmeta,record){
                    if(val == 0){
                        return '<a href="#" title="审核工资" onclick=updateSal(' + record.data['sal_approve_id'] + ')><span style="color: blue">等待审批</span></a>';
                    } else if(val ==1){
                        return  '<a href="#" title="审核工资" onclick=updateSal(' + record.data['sal_approve_id'] + ')><span style="color:green ">审批通过</span></a>';
                    } else if(val ==2){
                        return '<a href="#" title="审核工资" onclick=updateSal(' + record.data['sal_approve_id'] + ')><span style="color:gray ">审批未通过</span></a>';
                    }
                    return val;
                }
            }
        ],
        height:600,
        width:1000,
        x:0,
        y:0,
        title: '欢迎使用财务审批工资',
        renderTo: 'demo',
        viewConfig: {
            id: 'gv',
            enableTextSelection:true,
            trackOver: false,
            stripeRows: false
        },
        bbar: Ext.create('Ext.PagingToolbar', {
            store: salTimeListApprovalstore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),

        tbar : [
            {
                xtype: 'button',
                id: 'bt_deleteDocument',
                handler: function (src) {
                    var record = Ext.getCmp('comlist').getSelectionModel().getSelection();
                    // getSelection()
                    //var records = grid.getSelectionModel().getSelection();
                    if (record.length>0) {
                        Ext.MessageBox.show({
                            title:'删除已做工资',
                            msg: '是否要删除已做工资？（警告，本过程不可逆！）',
                            buttonText:{ok: '确认',no:'取消'},
                            animateTarget: 'mb4',
                            fn: function (btn) {
                                if("no"==btn){
                                    return false;
                                }
                                var itcIds = [];
                                //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                                for (var i = 0; i < record.length; i++) {
                                    itcIds.push(record[i].data.id);
                                }
                                Ext.Ajax.request({
                                    url: 'index.php?action=ExtSalary&mode=delSalayByTimeId',
                                    method: 'post',
                                    waitTitle : '请等待' ,
                                    waitMsg: '正在提交中',
                                    params: {
                                        ids: Ext.JSON.encode(itcIds)
                                    },
                                    success: function (response) {
                                        var text = response.responseText;
                                        // process server response here
                                        Ext.Msg.alert('信息',text);
                                        salTimeListApprovalstore.removeAll();
                                        salTimeListApprovalstore.load({
                                            params: {
                                                companyName: Ext.getCmp("comnamesecrch").getValue(),
                                                salTime: Ext.getCmp("salTime").getValue(),
                                                e_bill_value:Ext.getCmp("e_bill_value").getValue(),
                                                opTime : Ext.getCmp("STime").getValue(),
                                                start: 0,
                                                limit: 50
                                            }
                                        });
                                    }
                                });
                            },
                            icon: Ext.MessageBox.WARNING
                        })

                    } else {
                        Ext.Msg.alert('警告','请选择一条记录！');
                    }

                },
                text : '删除',
                iconCls : 'shanchu'
            },
            {
                xtype:'textfield',
                id:'comnamesecrch',
                width:150,
                emptyText:"筛选公司"
            },
            {
                id:'salTime',
                xtype : 'monthfield',
                width: 120,
                emptyText:"筛选工资月份",
                labelAlign: 'right',
                format: 'Y-m'
            },
            {
                id:'STime',
                name: 'STime',
                width: 130,
                xtype:'datefield',
                format:"Y-m-d",
                emptyText:"筛选操作时间",
                readOnly:false,
                anchor:'85%'
            },
            {
                xtype: 'combobox',
                id:"e_bill_value" ,
                emptyText: "筛选工资状态",
                editable:false,
                store: {
                    fields: ['abbr', 'name'],
                    data: [
                        {"abbr": "0", "name": "等待审批"},
                        {"abbr": "1", "name": "审批通过"},
                        {"abbr": "2", "name": "审批未通过"},
                        {"abbr": "", "name": "全部"}
                    ]
                },
                listeners: {
                    select: function (tab) {
                        salTimeListApprovalstore.removeAll();
                        salTimeListApprovalstore.load({
                            params: {
                                companyName: Ext.getCmp("comnamesecrch").getValue(),
                                salTime: Ext.getCmp("salTime").getValue(),
                                e_bill_value:Ext.getCmp("e_bill_value").getValue(),
                                opTime : Ext.getCmp("STime").getValue(),
                                start: 0,
                                limit: 50
                            }
                        });
                    }
                },
                valueField: 'abbr',
                displayField: 'name'
            },
            {
                xtype: 'button',
                id: 'search',
                disabled: false,
                handler: function () {
                    salTimeListApprovalstore.removeAll();
                    salTimeListApprovalstore.load({
                        params: {
                            companyName: Ext.getCmp("comnamesecrch").getValue(),
                            salTime: Ext.getCmp("salTime").getValue(),
                            e_bill_value:Ext.getCmp("e_bill_value").getValue(),
                            opTime : Ext.getCmp("STime").getValue(),
                            start: 0,
                            limit: 50
                        }
                    });
                },
                text: '筛选'
            }
        ]
    });
    salTimeListApprovalstore.on("beforeload", function () {
        Ext.apply(salTimeListApprovalstore.proxy.extraParams, {});
    });
    insurancewindow.getSelectionModel().on('selectionchange', function (selModel, selections) {
        Ext.apply(salTimeListApprovalstore.proxy.extraParams, {});
    }, this);
    salTimeListApprovalstore.loadPage(1);

});

function updateSal(eid){
    Ext.MessageBox.show({
        title:'审核工资',
        msg: '请选择审核结果：',
        width:300,
        buttonText:{ok: '同意',yes:'拒绝',no:'取消'},
        animateTarget: 'mb4',
        fn: function (btn) {
            var shenPiType;
            if("ok"==btn){
                shenPiType  =   1;
                Ext.Ajax.request({
                    url: 'index.php?action=ExtFinance&mode=opShenPi',
                    method: 'post',
                    params: {
                        billId:eid ,
                        shenPiType  :   shenPiType
                    },
                    success: function (response) {
                        var text = response.responseText;
                        Ext.Msg.alert("提示",text);
                        salTimeListApprovalstore.removeAll();
                        salTimeListApprovalstore.load({
                            params: {
                                companyName: Ext.getCmp("comnamesecrch").getValue(),
                                salTime: Ext.getCmp("salTime").getValue(),
                                e_bill_value:Ext.getCmp("e_bill_value").getValue(),
                                opTime : Ext.getCmp("STime").getValue(),
                                start: 0,
                                limit: 50
                            }
                        });
                    }

                });
            }
            else if("yes"==btn){
                shenPiType  =   2;
                Ext.Ajax.request({
                    url: 'index.php?action=ExtFinance&mode=opShenPi',
                    method: 'post',
                    params: {
                        billId:eid ,
                        shenPiType  :   shenPiType
                    },
                    success: function (response) {
                        var text = response.responseText;
                        Ext.Msg.alert("提示",text);
                        salTimeListApprovalstore.removeAll();
                        salTimeListApprovalstore.load({
                            params: {
                                companyName: Ext.getCmp("comnamesecrch").getValue(),
                                salTime: Ext.getCmp("salTime").getValue(),
                                e_bill_value:Ext.getCmp("e_bill_value").getValue(),
                                opTime : Ext.getCmp("STime").getValue(),
                                start: 0,
                                limit: 50
                            }
                        });
                    }

                });
            }
            else if("no"==btn){
                return false;
            }

        },
        icon: Ext.MessageBox.INFO
    })
}

var salListWidth=1200;
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
    //displayInfo : true,
    emptyMsg : "没有数据显示"
});
//通过ajax获取表头已经表格数据
function checkSalWin(timeId) {
    //加载数据遮罩
    var mk=new Ext.LoadMask(Ext.getBody(),{
        msg:'加载数据中，请稍候！',removeMask:true
    });
    mk.show();
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
                mk.hide();
                window.minimizable = true;
            },
            close:function(){
                mk.hide();
            }
        },
        closeAction:'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });
    var title="";
    var url = "index.php?action=SaveSalary&mode=searchSalaryByIdJosn";

    Ext.Ajax.request({
        url: url,  //从json文件中读取数据，也可以从其他地方获取数据
        method : 'POST',
        params: {
            timeId : timeId
        },
        success : function(response) {
            //将返回的结果转换为json对象，注意extjs4中decode函数已经变成了：Ext.JSON.decode
            mk.hide();
            var json = Ext.JSON.decode(response.responseText); //获得后台传递json

            //创建store
            var store = Ext.create('Ext.data.Store', {
                fields : json.fields,//把json的fields赋给fields
                data : json.data     //把json的data赋给data
            });

            //根据store和column构造表格
            Ext.getCmp("configGrid").reconfigure(store, json.columns);
            //重新渲染表格
            //Ext.getCmp("configGrid").render();
        }
    });
    winSal.show();
}
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
