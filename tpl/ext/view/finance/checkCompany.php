<?php
$errorMsg = $form_data ['error'];
$warn = $form_data ['warn'];
$admin = $_SESSION ['admin'];
// var_dump($files);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>单位审核</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="tpl/ext/lib/prettify/prettify.css" type="text/css"
          rel="stylesheet"/>
    <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
    <link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
    <script language="javascript" type="text/javascript"
            src="common/ext/ext-all.js" charset="utf-8"></script>

    <script language="javascript" type="text/javascript"
            src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript"
            src="tpl/ext/js/model.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript"
            src="tpl/ext/js/data.js" charset="utf-8"></script>
    <script language="javascript" type="text/javascript"
            src="common/js/jquery_last.js" charset="utf-8"></script>
    <script type="text/javascript">
        Ext.require([
            'Ext.grid.*',
            'Ext.toolbar.Paging',
            'Ext.data.*'
        ]);
        Ext.onReady(function () {
            //创建数据源

            var grid = Ext.create('Ext.grid.Panel', {
                store: checkCompanyStores,
                selType: 'checkboxmodel',
                id: 'comlist',
                columns: [
                    {text: "id", width: 120, dataIndex: 'id', sortable: true},
                    {text: "公司名称", flex: 200, dataIndex: 'company_name', sortable: true},
                    {text: "地址", flex: 200, dataIndex: 'company_address', sortable: true}
                ],
                height: 700,
                width: 1000,
                x: 0,
                y: 0,
                title: '单位审批',
                disableSelection: false,
                loadMask: true,
                renderTo: 'checkcom',
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },

                bbar: Ext.create('Ext.PagingToolbar', {
                    store: checkCompanyStores,
                    displayInfo: true,
                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                    emptyMsg: "没有数据"
                }),
                tbar: [
                    {
                        xtype: 'button',
                        id: 'cClear',
                        disabled: true,
                        handler: function (src) {
                            var model = grid.getSelectionModel();
                            var sel = model.getLastSelected();
                            messageClear(sel.data.id);
                        },
                        text: '审批通过',
                        iconCls: 'chakan'
                    },
                    '公司名称查询', {
                        id:'comname',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'comname',
                        onTriggerClick : function(src) {
                            checkCompanyStores.removeAll();
                            checkCompanyStores.load( {
                                params : {
                                    company_name : this.getValue(),
                                    start : 0,
                                    limit : 50
                                }
                            });
                        }
                    }
                ]
            });
            checkCompanyStores.on("beforeload", function () {

                Ext.apply(checkCompanyStores.proxy.extraParams, {Key: Ext.getCmp("comname").getValue()});

            });
            grid.getSelectionModel().on('selectionchange', function (selModel, selections) {
                //var sel=model.getLastSelected();
                Ext.getCmp("cClear").setDisabled(selections.length === 0);
            }, this);
            //通过ajax获取表头以及表格数据
            function messageClear(id) {
                var title="";
                var url = "index.php?action=ExtFinance&mode=companyClear";

                Ext.Ajax.request({
                    url: url,  //从json文件中读取数据，也可以从其他地方获取数据
                    method : 'POST',
                    params: {
                        comid : id
                    },
                    success : function(response) {
                        alert("审核通过！");
                        location.reload();
                    }
                });
            }
            checkCompanyStores.loadPage(1);
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
            }
        });

    </script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width: 960px"></div>
<?php include("tpl/commom/left.php"); ?>
<div id="right">
    <div id="checkcom">
    </div>
</div>

</body>
</html>