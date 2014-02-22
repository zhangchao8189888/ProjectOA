<?php
$comlist = $form_data['comList'];
$errorlist = $form_data['error'];
$searchType = $form_data['searchType'];
$comlist = json_encode($comlist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>客服首页</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
<link href="common/css/validator.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/MonthPickerPlugin.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<style type="text/css">
    <!--
    A { text-decoration: none}
    -->
</style>
<script language="javascript" type="text/javascript">
Ext.require([
    'Ext.grid.*',
    'Ext.toolbar.Paging',
    'Ext.data.*'
]);
Ext.onReady(function () {

    var serviceManage = Ext.create('Ext.grid.Panel', {
        id: 'grid2',
        store: serviceManagestore,
        stripeRows: true,
        selType: 'checkboxmodel',
        columns: [
            {text: "id", width: 50, dataIndex: 'id', sortable: true, align: 'center',hidden:true},
            {text: "工资月份", dataIndex: 'salDate', width: 85,sortable: false, align: 'center'},
            {text: "工资操作日期", dataIndex: 'op_salaryTime', width: 100, sortable: false,align: 'center'},
            {
                text: "单位名称",
                renderer: function (val, cellmeta, record) {
                    return '<a href="#" onclick=getEmploy("' + val + '") >' + val + '</a>';
                },
                dataIndex: 'company_name', width: 180},
            {
                text: "一次工资",
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="做工资" onclick=makeSal(' + record.data['id'] + ',"' + record.data['salDate'] + '","first")><span style="color: red"> 未做工资</span></a>';
                    } else if (val > 0) {
                        return '<a style="color: green" href="#" title="查看工资" onclick=selectinfo(' + record.data['salStat'] + ')>已做工资</span>';
                    }
                    return val;
                },
                dataIndex: 'salStat', sortable: false,align: 'center'
            },
            {
                text: "二次工资",
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="做二次工资" onclick=makeSal(' + record.data['id'] + ',"' + record.data['salDate'] + '","second")><span style="color: red">未做二次工资</span></a>';
                    } else if (val > 0) {
                        return '<a href="#" title="查看工资" _salTimeId="' + record.data['salOrStat'] + '" _salTime="' + record.data['salDate'] + '" _companyId="' + record.data['companyId'] + '"  id="check"><span style="color: green"> 已做二次工资 </span></a>';
                        return '<font color="green" title="查看工资" _salTimeId="' + record.data['salOrStat'] + '" _salTime="' + record.data['salDate'] + '" _companyId="' + record.data['companyId'] + '"  id="check"></font>';
                    }
                    return val;
                },
                dataIndex: 'salOrStat', sortable: false,align: 'center'
            },
            {
                text: "年终奖",
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="做年终奖" onclick=makeSal(' + record.data['id'] + ',"' + record.data['salDate'] + '","nian")><span style="color: red">未做年终奖</span></a>';
                    } else if (val > 0) {
                        return '<font color="green" title="查看年终奖" _salTimeId="' + record.data['salNianStat'] + '"  id="check">已做年终奖</font>';
                    }
                    return val;
                },
                dataIndex: 'salNianStat',sortable: false, align: 'center'
            },
            {
                text: "发票情况",
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="开发票" onclick=addBill(' + record.data['id'] + ',"' + record.data['company_name'] + '","' + record.data['salStat'] + '","' + record.data['salDate'] + '")><span style="color: red"> 添加发票 </span></a>';

                    } else if (val > 0) {
                        return '<a href="#" title="继续添加发票" onclick=addBill(' + record.data['id'] + ',"' + record.data['company_name'] + '","' + record.data['salStat'] + '","' + record.data['salDate'] + '")><span style="color: green"> 已添加发票</span></a>';
                    }
                    return val;
                },
                dataIndex: 'fastat',sortable: false, align: 'center'},
            {
                text: "审批状态",
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<font color=red>申请发放审批中</font>';
                    } else if (val == 1) {
                        return '<font color=green>批准发放</font>';
                    } else if (val == 2) {
                        return '<a href="#" onclick="send(' + record.data['salTimeid'] + ')" target="_self"><font color=red>未批准发放</font></a>';
                    } else if (val == -1) {
                        return '<font color=red>未批准发放</font>';
                    }
                    return "<span style=\"color: red\"> 未批准发放 </span>";
                    return '<a href="#" onclick="send(' + record.data['salTimeid'] + ')" target="_self">' + val + '</font></a>';
                },
                dataIndex: 'fa_state',sortable: false, align: 'center'},
            {text: "备注", dataIndex: 'mark',sortable: false, align: 'center'}
        ],
        listeners: {
            'cellclick': function (iView, iCellEl, iColIdx, iStore, iRowEl, iRowIdx, iEvent) {
                var rowEl = Ext.get(iEvent.getTarget());
                var zRec = iView.getRecord(iRowEl);
                var type = rowEl.getAttribute('id');
                if (iColIdx == 3 || iColIdx == 4 || iColIdx == 5) {
                    if (type == 'make') {
                        var comId = rowEl.getAttribute('companyId');
                        makeSalWin('做工资');
                    } else if (type == 'check') {
                        var timeId = rowEl.getAttribute('salTimeId');
                        checkSalWin("查看工资", iColIdx, timeId, rowEl);
                    }
                }

            }
        },
        columnLines: true,
        loadMask: true,
        width: 1000,
        height: 500,
        frame: true,
        title: '主页',
        iconCls: 'icon-grid',
        margin: '0 0 20 0',
        renderTo: 'tableList',
        viewConfig: {
            id: 'gv',
            trackOver: false,
            stripeRows: false
        },
        bbar: Ext.create('Ext.PagingToolbar', {
            store: serviceManagestore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),
        tbar: [
            {
                xtype: 'button',
                id: 'cClear',
                text: '添加管理公司',
                handler: function (src) {
                    comListStore.load();
                    window.show();
                }
            },
            {
                xtype: 'button',
                id: 'delc',
                text: '取消管理',
                handler: function (src) {
                    var record = Ext.getCmp('grid2').getSelectionModel().getSelection();
                    // getSelection()
                    //var records = grid.getSelectionModel().getSelection();
                    if (record.length>0) {
                        var itcIds = [];
                        //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                        for (var i = 0; i < record.length; i++) {
                            itcIds.push(record[i].data.id);
                        }
                        Ext.Ajax.request({
                            url: 'index.php?action=ExtFinance&mode=cancelManage',
                            method: 'post',
                            params: {
                                ids: Ext.JSON.encode(itcIds)
                            },
                            success: function (response) {
                                Ext.Msg.alert("提示","取消成功！");
                                serviceManagestore.removeAll();
                                serviceManagestore.load({
                                    params: {
                                        date: Ext.getCmp("STime").getValue(),
                                        start: 0,
                                        limit: 50
                                    }
                                });
                            }
                        });

                    } else {
                        Ext.Msg.alert("警告","请选择一条记录！");
                    }

                }
            },
            '公司名称查询',
            {
                id: 'comnamesecrch',
                xtype: 'trigger',
                triggerClass: 'x-form-search-trigger',
                name: 'comnamesecrch',
                onTriggerClick: function (src) {
                    serviceManagestore.removeAll();
                    serviceManagestore.load({
                        params: {
                            company_name: this.getValue(),
                            saldate: Ext.getCmp("STime").getValue(),
                            operationTime: Ext.getCmp("operationTime").getValue(),
                            start: 0,
                            limit: 50
                        }
                    });
                }
            },
            {
                id:'STime',
                name: 'STime',
                xtype : 'monthfield',
                editable: false,
                width: 100,
                labelAlign: 'right',
                format: 'Y-m'
            },
            {
                xtype: 'button',
                id: 'search1',
                disabled: false,
                handler: function () {
                    serviceManagestore.removeAll();
                    serviceManagestore.load({
                        params: {
                            company_name: Ext.getCmp("comnamesecrch").getValue(),
                            date: Ext.getCmp("STime").getValue(),
                            operationTime: Ext.getCmp("operationTime").getValue(),
                            start: 0,
                            limit: 50
                        }
                    });

                },
                text: '工资月份'
            },
            {
                id: 'operationTime',
                name: 'operationTime',
                xtype: 'datefield',
                width:100,
                format: "Y-m-d",
                readOnly: false,
                anchor: '95%'
            } ,
            {
                xtype: 'button',
                id: 'search2',
                disabled: false,
                handler: function () {
                    serviceManagestore.removeAll();
                    serviceManagestore.load({
                        params: {
                            company_name: Ext.getCmp("comnamesecrch").getValue(),
                            operationTime: Ext.getCmp("operationTime").getValue(),
                            date: Ext.getCmp("STime").getValue(),
                            start: 0,
                            limit: 50
                        }
                    });

                },
                text: '操作时间'
            }
        ]
    });
    serviceManagestore.on("beforeload", function () {
        Ext.apply(serviceManagestore.proxy.extraParams, {Key: Ext.getCmp("comnamesecrch").getValue(), companyName: Ext.getCmp("comnamesecrch").getValue()});
    });

    serviceManagestore.loadPage(1);

    /**
     *添加管理公司grid
     * @type {Ext.grid.Panel}
     */
    var companyListGrid = Ext.create('Ext.grid.Panel', {
        store: comListStore,
        selType: 'checkboxmodel',
        id: 'companyLis',
        columns: [
            {text: "id", width: 120, dataIndex: 'id', sortable: true},
            {text: "公司名称", flex: 200, dataIndex: 'company_name', sortable: true}
        ],
        height: 400,
        width: 520,
        x: 20,
        y: 40,
        title: '请选择操作单位',
        disableSelection: false,
        loadMask: true,
        viewConfig: {
            id: 'addcom',
            trackOver: false,
            stripeRows: false
        },

        bbar: Ext.create('Ext.PagingToolbar', {
            store: comListStore,
            displayInfo: true,
            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
            emptyMsg: "没有数据"
        }),
        tbar: [
            {
                xtype: 'button',
                id: 'addcombt',
                handler: function () {
                    var record = Ext.getCmp('companyLis').getSelectionModel().getSelection();
                    // getSelection()
                    //var records = grid.getSelectionModel().getSelection();
                    if (record) {
                        var itcIds = [];
                        //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                        for (var i = 0; i < record.length; i++) {
                            itcIds.push(record[i].data.id);
                        }
                        Ext.Ajax.request({
                            url: 'index.php?action=Service&mode=addCaiwuOpCompanyListJson',
                            method: 'post',
                            params: {
                                ids: Ext.JSON.encode(itcIds)
                            },
                            success: function (x) {
                                var text = x.responseText;
                                // process server response here
                                newWin(text);
                            }
                        });

                    } else {
                        Ext.Msg.alert("警告","请选择一条记录！");
                    }
                },
                text: '添加管理',
                iconCls: 'shanchu'
            },
            '公司查询',
            {
                id: 'addc',
                xtype: 'trigger',
                triggerClass: 'x-form-search-trigger',
                name: 'search',
                onTriggerClick: function (src) {
                    comListStore.loadPage(1);
                    comListStore.removeAll();
                    comListStore.load({
                        params: {
                            Key: Ext.getCmp("addc").getValue(),
                            start: 0,
                            limit: 50
                        }
                    });

                }

            }
        ]
    });
    comListStore.on("beforeload", function () {
        Ext.apply(comListStore.proxy.extraParams, {Key: Ext.getCmp("addc").getValue()});
    });
    function newWin(text) {
        var win = Ext.create('Ext.window.Window', {
            title: text,
            width: 300,
            height: 100,
            plain: true,
            closeAction: 'hide', // 关闭窗口
            maximizable: false, // 最大化控制 值为true时可以最大化窗体
            layout: 'border',
            contentEl: 'tab'
        });
        win.show();
        document.location='index.php?action=Ext&mode=toServiceIndex';
    };
    // Create a window
    var window = new Ext.Window({
        title: "管理", // 窗口标题
        width: 530, // 窗口宽度
        height: 440, // 窗口高度
        layout: "border",// 布局
        minimizable: true, // 最大化
        maximizable: true, // 最小化
        frame: true,
        constrain: true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
        buttonAlign: "center", // 按钮显示的位置
        modal: true, // 模式窗口，弹出窗口后屏蔽掉其他组建
        resizable: false, // 是否可以调整窗口大小，默认TRUE。
        plain: true,// 将窗口变为半透明状态。
        items: [companyListGrid],
        closeAction: 'hide'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });

    var salListWidth = 890;

    function makeSal(id, sDate, salType) {
        //alert(sDate);
        if (sDate == "") {
            alert("未找到做工资月份，请按工资月份查询后再做工资！");
            return;
        }
        $("#comId").val(id);
        $("#sDate").val(sDate);
        $("#salType").val(salType);
        $("#iform").attr("action", "index.php?action=Service&mode=makeSal");
        $("#iform").submit();
    }
});


function a() {
    $("#iform").attr("action", "index.php?action=Salary&mode=rename");
    $("#nfname").val($("#newfname").val());
    $("#iform").submit();
}
function getEmploy(com) {
    $("#iform").attr("action", "index.php?action=Service&mode=getEmList");
    $("#comname").val(com);
    $("#iform").submit();
}
function getOther() {
    $("#iform").attr("action", "index.php?action=Service&mode=getOtherAdminComList");
    if ($("#mon").val() == -1) {
        alert("请选择月份");
        return;
    }
    $("#yearDate").val($("#year").val());
    $("#monDate").val($("#mon").val());
    $("#sType").val($("#searchType").val());
    store.load({
        url: 'index.php?action=Service&mode=getOtherAdminComList',
        params: {
            yearDate: $("#year").val(),
            monDate: $("#mon").val(),
            sType: $("#searchType").val()
        }
    });

}
function other() {
    $("#select").attr("style", "display:block");
}
function dangyue() {
    $("#iform").attr("action", "index.php?action=Service&mode=getAdminComList");
    $("#sType").val($("#searchType").val());
    $("#iform").submit();
}
function makeSal(id, sDate, salType) {
    //alert(sDate);
    if (sDate == "") {
        alert("未找到做工资月份，请按工资月份查询后再做工资！");
        return;
    }
    $("#comId").val(id);
    $("#sDate").val(sDate);
    $("#salType").val(salType);
    $("#iform").attr("action", "index.php?action=Service&mode=makeSal");
    $("#iform").submit();
}
function cancel(id) {
    if (confirm('确定取消对该公司管理吗?')) {
        $("#comId").val(id);
        $("#iform").attr("action", "index.php?action=Service&mode=cancelService");
        $("#iform").submit();
    }
}
function send(eid) {
    if (confirm('确定要申请发放工资吗?')) {
        $("#iform").attr("action", "index.php?action=Service&mode=salarySend");
        $("#timeid").val(eid)
        $("#iform").submit();
        //
    }
}
function addFa() {
    $("#iform").attr("action", "index.php?action=SalaryBill&mode=toAddInvoice");
    $("#iform").submit();
}

function addBill(comId,companyName,sal_state,sal_date) {
    if(sal_state==0){
        Ext.Msg.alert("警告","没有发工资是不能开发票的");
        return false;
    }
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

    Ext.getCmp("company_id").setValue(comId);
    Ext.getCmp("company_name").setValue(companyName);
    Ext.getCmp("salaryTime").setValue(sal_date);
    Ext.getCmp("sal_state").setValue(sal_state);

    var winSal = Ext.create('Ext.window.Window', {
        title: "添加发票", // 窗口标题
        width:500, // 窗口宽度
        height:350, // 窗口高度
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

var salList=Ext.create("Ext.form.Panel",{
    width: 480,
    height: 300,
    bodyPadding: 10,
    labelWidth:50,
    id : 'addBillForm',
    name : 'addBillForm',
    items: [
        {
            id:'company_id',
            xtype : 'hiddenfield',
            readonly:true,
            name: 'company_id'
        },
        {
            id:'sal_state',
            xtype : 'hiddenfield',
            readonly:true,
            name: 'sal_state'
        },

        {
            id:'company_name',
            xtype : 'displayfield',
            width:300,
            name: 'company_name',
            fieldLabel: '单位'
        },
        {
            id:'salaryTime',
            xtype : 'displayfield',
            readonly:true,
            width:200,
            name: 'salaryTime',
            fieldLabel:'月份'
        } ,
        {
            id:'billNo',
            xtype : 'numberfield',
            width:300,
            name: 'billNo',
            allowBlank: false,
            emptyText: "请输入发票编号",
            fieldLabel:'发票编号'
        } ,
        {
            id:'billItem',
            xtype : 'textfield',
            width:300,
            name: 'billItem',
            allowBlank: false,
            emptyText: "请输入发票项目",
            fieldLabel:'发票项目'
        } ,
        {
            id:'billValue',
            xtype : 'numberfield',
            width:300,
            name: 'billValue',
            allowBlank: false,
            emptyText: "请输入金额",
            fieldLabel:'金额'
        } ,
        {
            id:'billRemarks',
            xtype : 'textareafield',
            width:400,
            height:80,
            name: 'billRemarks',
            emptyText: "请输入备注信息",
            fieldLabel:'备注'
        }
    ],
    buttons: [
        {
            text: '提交',
            handler: function () {
                var companyId =  Ext.getCmp("company_id").getValue();
                var companyName =  Ext.getCmp("company_name").getValue();
                var sal_state =  Ext.getCmp("sal_state").getValue();
                var billNo =   Ext.getCmp("billNo").getValue();
                var billItem =   Ext.getCmp("billItem").getValue();
                var billValue =   Ext.getCmp("billValue").getValue();
                var remarks =   Ext.getCmp("billRemarks").getValue();
                var submitInfo = this.up('form').getForm().isValid();
                if(!submitInfo){
                    Ext.Msg.alert("警告！","请输入完整的信息！");
                    return false;
                }
                Ext.Ajax.request({
                    url: "index.php?action=ExtSalaryBill&mode=addInvoice",
                    method : 'POST',
                    params: {
                        companyId:companyId,
                        companyname:companyName,
                        salaryTime:sal_state,
                        billno:billNo,
                        billname:billItem,
                        billval:billValue,
                        memo:remarks
                    },
                    success : function(response) {
                        var text=   response.responseText;
                        alert(text);
                        document.location='index.php?action=Ext&mode=toServiceIndex';
                    }
                });
            }
        },
        '-',
        {
            text: '清空',
            handler: function () {
                Ext.getCmp("billNo").setValue("");
                Ext.getCmp("billItem").setValue("");
                Ext.getCmp("billValue").setValue("");
                Ext.getCmp("billRemarks").setValue("");
            }
        }
    ]
});


var infolist=Ext.create("Ext.grid.Panel",{
    title:'',
    width:1150,
    height:450,
    enableLocking : true,
    id : 'infogrid',
    name : 'infogrid',
    features: [{
        ftype: 'summary'
    }],
    columns : [], //注意此行代码，至关重要
    //displayInfo : true,
    emptyMsg : "没有数据显示"
});
function selectinfo(timeId) {
    //加载数据遮罩
    var mk=new Ext.LoadMask(Ext.getBody(),{
        msg:'加载数据中，请稍候！',removeMask:true
    });
    mk.show();
    var p = Ext.create("Ext.grid.Panel",{
        id:"salTimeListP",
        title:"详细信息",
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

    var items=[infolist];

    var wininfo = Ext.create('Ext.window.Window', {
        title: "详细信息", // 窗口标题
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
            Ext.getCmp("infogrid").reconfigure(store, json.columns);
            //重新渲染表格
            // Ext.getCmp("salTimeListP").render();
        }
    });
    wininfo.show();
}
</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <div id="tableList"></div>
        <div id="tab" class="TipDiv"></div>
        <form enctype="multipart/form-data" id="iform" action="" target="_top" method="post">
            <input type="hidden" name="comname" id="comname" value=""/>
            <input type="hidden" name="comId" id="comId" value=""/>
            <input type="hidden" name="sDate" id="sDate" value=""/>
            <input type="hidden" name="salType" id="salType" value=""/>
        </form>

    </div>
</div>
</body>
</html>