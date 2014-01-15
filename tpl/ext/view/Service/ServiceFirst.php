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
<script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
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
        columns: [
            {text: "工资日期", dataIndex: 'salDate'},
            {text: "工资操作日期", dataIndex: 'op_salaryTime', width: 150},
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
                        return '<a href="#" title="做工资" onclick=makeSal(' + record.data['companyId'] + ',"' + record.data['salDate'] + '","first")><font color="red">未做工资</font></a>';
                    } else if (val > 0) {
                        return '<font color="green" title="查看工资" _salTimeId="' + record.data['salTimeid'] + '"  id="check">已做工资</font>';
                    }
                    return val;
                },
                dataIndex: 'salStat'
            },
            {
                text: "二次工资",

                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="做二次工资" onclick=makeSal(' + record.data['companyId'] + ',"' + record.data['salDate'] + '","second")><font color="red">无</font></a>';
                    } else if (val > 0) {
                        return '<font color="green" title="查看工资" _salTimeId="' + record.data['salOrStat'] + '" _salTime="' + record.data['salDate'] + '" _companyId="' + record.data['companyId'] + '"  id="check">已做二次工资</font>';
                    }
                    return val;
                },
                dataIndex: 'salOrStat'
            },
            {
                text: "年终奖",
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="做年终奖" onclick=makeSal(' + record.data['companyId'] + ',"' + record.data['salDate'] + '","nian")><font color="red">无</font></a>';
                    } else if (val > 0) {
                        return '<font color="green" title="查看年终奖" _salTimeId="' + record.data['salNianStat'] + '"  id="check">已做年终奖</font>';
                    }
                    return val;
                },
                dataIndex: 'salNianStat'
            },
            {
                text: "发票情况",
                renderer: function (val, cellmeta, record) {
                    if (val == 0) {
                        return '<a href="#" title="添加发票" onclick=addFa()><font color="red">添加发票</font></a>';
                    } else if (val > 0) {
                        return '<font color="green" title="查看发票情况"  id="check">已添加发票</font>';
                    }
                    return val;
                },
                dataIndex: 'fastat'},
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
                    return '<a href="#" onclick="send(' + record.data['salTimeid'] + ')" target="_self">' + val + '</font></a>';
                },
                dataIndex: 'fa_state'},
            {text: "添加管理时间", dataIndex: 'opTime'},
            {text: "备注", dataIndex: 'mark'}
        ],
        listeners: {
            'cellclick': function (iView, iCellEl, iColIdx, iStore, iRowEl, iRowIdx, iEvent) {
                var rowEl = Ext.get(iEvent.getTarget());
                var zRec = iView.getRecord(iRowEl);
                var type = rowEl.getAttribute('id');
                if (iColIdx == 3 || iColIdx == 4 || iColIdx == 5) {
                    if (type == 'make') {
                        var comId = rowEl.getAttribute('_comanyId');
                        makeSalWin('做工资');
                    } else if (type == 'check') {
                        var timeId = rowEl.getAttribute('_salTimeId');
                        checkSalWin("查看工资", iColIdx, timeId, rowEl);
                    }
                }

            }
        },
        columnLines: true,
        width: 1100,
        height: 500,
        frame: true,
        title: '客服管理公司首页',
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
                id: 'addManageCom',
                disabled: false,
                handler: function () {
                    comListStore.load();
                    window.show();
                },
                text: '管理公司',
                iconCls: 'chakan'
            },
            '公司名称查询',
            {
                id: 'comname',
                xtype: 'trigger',
                triggerClass: 'x-form-search-trigger',
                name: 'comname',
                onTriggerClick: function (src) {
                    serviceManagestore.removeAll();
                    serviceManagestore.load({
                        params: {
                            company_name:  this.getValue(),
                            start: 0,
                            limit: 50
                        }
                    });
                }
            },
            {
                id:'STime',
                name: 'STime',
                xtype:'datefield',
                format:"Y-m-d",
                readOnly:false,
                anchor:'95%'
            } ,
            {
                xtype: 'button',
                id: 'search1',
                disabled: false,
                handler: function () {
                    var data    =   Ext.getCmp("STime").getValue();
                    serviceManagestore.removeAll();
                    serviceManagestore.load({
                            params: {
                                date:  data,
                                sType:"1" ,
                                start: 0,
                                limit: 50
                            }
                    });

                },
                text: '按工资月份查找'
            },
            {
                xtype: 'button',
                id: 'search2',
                disabled: false,
                handler: function () {
                    var data    =   Ext.getCmp("STime").getValue();
                    serviceManagestore.removeAll();
                    serviceManagestore.load({
                        params: {
                            date:  data,
                            sType:"2",
                            start: 0,
                            limit: 50
                        }
                    });

                },
                text: '按操作时间查找'
            }
        ]
    });
    serviceManagestore.on("beforeload", function () {
    Ext.apply(serviceManagestore.proxy.extraParams, {Key: Ext.getCmp("comname").getValue(), companyName: Ext.getCmp("comname").getValue()});
});
    serviceManage.getSelectionModel().on('selectionchange', function (selModel, selections) {
        Ext.getCmp("addManageCom").setDisabled(selections.length === 0);
    }, this);
    serviceManagestore.loadPage(1);

    //创建Grid
    var companyListGrid = Ext.create('Ext.grid.Panel', {
        store: comListStore,
        selType: 'checkboxmodel',
        id: 'companyList',
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
                id: 'bt_deleteDocument',
                handler: function () {
                    var record = Ext.getCmp('companyList').getSelectionModel().getSelection();
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
                            success: function (response) {
                                var text = response.responseText;
                                // process server response here
                                newWin(text);
                                location.reload();
                            }
                        });

                    } else {
                        alert('请选择一条记录');
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

            },
            {
                xtype: 'button',
                id: 'del',
                text: '取消管理',
                iconCls: 'chakan',
                handler: function (src) {
                    var record = Ext.getCmp('companyList').getSelectionModel().getSelection();
                    // getSelection()
                    //var records = grid.getSelectionModel().getSelection();
                    if (record) {
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
                                alert("取消成功！");
                                location.reload();
                            }
                        });

                    } else {
                        alert('请选择一条记录');
                    }

                }
            }
        ]
    });
    comListStore.on("beforeload", function () {
        Ext.apply(comListStore.proxy.extraParams, {Key: Ext.getCmp("addc").getValue()});
    });
    // Create a window
    var window = new Ext.Window({
        title: "公司列表", // 窗口标题
        width: 530, // 窗口宽度
        height: 450, // 窗口高度
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
        closeAction: 'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });

    function newWin(text) {
        var win = Ext.create('Ext.window.Window', {
            title: text,
            width: 300,
            height: 100,
            plain: true,
            closeAction: 'close', // 关闭窗口
            maximizable: false, // 最大化控制 值为true时可以最大化窗体
            layout: 'border',
            contentEl: 'tab'
        });
        win.show();
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

</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <form enctype="multipart/form-data" id="iform" action="" target="_blank" method="post">
            <input type="hidden" name="comId" id="comId" value=""/>
            <input type="hidden" name="sDate" id="sDate" value=""/>
            <input type="hidden" name="salType" id="salType" value=""/>
        </form>
        <div class="submit">
            <div align="left">
                <select name="searchType" id="searchType">
                    <option value="1" <?php if ($searchType == 1) echo "selected" ?>>按工资月份查询</option>
                    <option value="2" <?php if ($searchType == 2) echo "selected" ?>>按操作时间查询</option>
                </select>
            </div>
        </div>
        <div id="tableList"></div>
    </div>
</div>
</body>
</html>