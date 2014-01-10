<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>工资列表查询test</title>
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
            ////////////////////////////////////////////////////////////////////////////////////////
            // 定义grid
            ////////////////////////////////////////////////////////////////////////////////////////
            var companyListView = Ext.create('Ext.grid.Panel',{
                store: comListStore,
                selType: 'checkboxmodel',
                id : 'comlist',
                columns: [
                    {text: "id", width: 120, dataIndex: 'id', sortable: true},
                    {text: "公司名称", flex: 200, dataIndex: 'company_name', sortable: true}
                ],
                height:400,
                width:520,
                x:20,
                y:40,
                title: '添加管理单位',
                disableSelection: false,
                loadMask: true,
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },

                bbar: Ext.create('Ext.PagingToolbar', {
                    store: comListStore,
                    displayInfo: true,
                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                    emptyMsg: "没有数据"
                }),
                tbar : [{
                    xtype : 'button',
                    id : 'bt_deleteDocument',
                    handler : function(src) {
                        var record = Ext.getCmp('comlist').getSelectionModel().getSelection();
                        // getSelection()
                        //var records = grid.getSelectionModel().getSelection();
                        if (record) {
                            var itcIds = [];
                            //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                            for(var i=0;i<record.length;i++){
                                itcIds.push(record[i].data.id);
                            }
                            Ext.Ajax.request({
                                url: 'index.php?action=Service&mode=addOpCompanyListJson',
                                method: 'post',
                                params: {
                                    ids : Ext.JSON.encode(itcIds)
                                },
                                success: function(response){
                                    var text = response.responseText;
                                    // process server response here
                                    newWin(text);

                                }
                            });

                        } else {
                            alert('请选择一条记录');
                        }
                    },
                    text : '添加管理',
                    iconCls : 'shanchu'
                }, '公司查询', {
                    id:'comname',
                    xtype : 'trigger',
                    triggerClass : 'x-form-search-trigger',
                    name: 'search',
                    onTriggerClick : function(src) {
                        comListStore.loadPage(1);
                        comListStore.removeAll();
                        comListStore.load( {
                            params : {
                                Key : this.getValue(),
                                start : 0,
                                limit : 50
                            }
                        });

                    }

                }]
            });
            comListStore.on("beforeload",function(){

                Ext.apply(comListStore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue()});

            });
            ////////////////////////////////////////////////////////////////////////////////////////
            // 定义button
            ////////////////////////////////////////////////////////////////////////////////////////
            var selectComBtn = Ext.create("Ext.Button", {
                renderTo: Ext.get("li3").dom,
                text: "添加管理公司",
                id: "bt3"
            });
            selectComBtn.on("click", function () {
                comListStore.load();
                companyListWindow.show();
            });
            ////////////////////////////////////////////////////////////////////////////////////////
            // 定window
            ////////////////////////////////////////////////////////////////////////////////////////
            var companyListWindow = new Ext.Window({
                title:"管理", // 窗口标题
                width:530, // 窗口宽度
                height:500, // 窗口高度
                layout:"border",// 布局
                minimizable:true, // 最大化
                maximizable:true, // 最小化
                frame:true,
                constrain:true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
                buttonAlign:"center", // 按钮显示的位置
                modal:true, // 模式窗口，弹出窗口后屏蔽掉其他组建
                resizable:false, // 是否可以调整窗口大小，默认TRUE。
                plain:true,// 将窗口变为半透明状态。
                items:[companyListView],
                buttons:[{
                    text:"登陆",
                    handler:function() {
                        Ext.Msg.alert("提示","登陆成功!");
                    }
                }],
                closeAction:'hide'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
            });
        });

    </script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
<div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
        <div id="div1" class="content">
            <ul>
                <li id="li1"></li>
                <li id="li3"></li>
            </ul>
        </div>>
    </div>
</div>
</body>
</html>
