<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>工资列表查询</title>
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

            var salTimeListGrid = Ext.create('Ext.grid.Panel',{
                id : 'comlist',
                columns: [
                ],
                height:700,
                width:1000,
                x:0,
                y:0,
                title: '个人工资查询',
                disableSelection: false,
                loadMask: true,
                renderTo: 'demo',
                viewConfig: {
                    id: 'gv',
                    trackOver: false,
                    stripeRows: false
                },
                tbar : [
                    '身份证号', {
                        id:'comname',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'comname',
			onTriggerClick : function(src) {
                            //alert(this.getValue());
                         //   checkSalWin(this.getValue());
                        }
                    },
                    '工资月份', {
                        id:'salTime',
                        xtype : 'trigger',
                        triggerClass : 'x-form-search-trigger',
                        name: 'salTime',
            			onTriggerClick : function(src) {
                            //alert(this.getValue());
                         //   checkSalWin(this.getValue());
                        }
                    },
                    {
                        xtype : 'button',
                        id : 'bt_selectDocument',
                        handler : function(src) {
                        	checkSalWin(Ext.getCmp("comname").getValue(),Ext.getCmp("salTime").getValue());
                        },
                        text : '查询',
                        iconCls : 'chaxun'
                        }
                ]
            });
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
//                            {text: "工资月份", width: 120, dataIndex: 'id', sortable: true},
//                            {text: "个人应发合计", flex: 200, dataIndex: 'company_name', sortable: true},
//                            {text: "个人失业", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "个人医疗", flex: 200, dataIndex: 'op_salaryTime', sortable: true},
//                            {text: "个人养老", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "个人公积金", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "代扣税", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "个人扣款合计", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "实发合计", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "单位失业", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "单位医疗", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "单位养老", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "单位工伤", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "单位生育", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "单位公积金", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "单位合计", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "劳务费", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "残保金", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "档案费", flex: 200, dataIndex: 'salaryTime', sortable: true},
//                            {text: "交中企合计", flex: 200, dataIndex: 'salaryTime', sortable: true}
                           ], //注意此行代码，至关重要
                //displayInfo : true,
                emptyMsg : "没有数据显示"
            });
            
            //通过ajax获取表头已经表格数据
            function checkSalWin(timeId,time) {
                //加载数据遮罩
            	var mk=new Ext.LoadMask(Ext.getBody(),{
            	msg:'玩命加载数据中，请稍候！',removeMask:true
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
                        //关闭事件
                        close : function(window){
                           
                            mk.hide();
                            
                        }
                    },
                    closeAction:'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
                });
                var title="";
                var url = "index.php?action=Salary&mode=toSalaryUpdate";

                Ext.Ajax.request({
                    url: url,  //从json文件中读取数据，也可以从其他地方获取数据
                    method : 'POST',
                    params: {
                        timeId : timeId,
                        time:time
                    },
                    success : function(response) {
                   	    mk.hide();
                        //将返回的结果转换为json对象，注意extjs4中decode函数已经变成了：Ext.JSON.decode
                        var json = Ext.JSON.decode(response.responseText); //获得后台传递json
                       // 创建store
                       // alert(json.data);
                       // var data = json.data;
                       // var fields = ["个人应发合计","个人失业","个人医疗","个人养老","个人公积金","代扣税","个人扣款合计","实发合计","单位失业","单位医疗","单位养老","单位工伤","单位生育","单位公积金","单位合计","劳务费","残保金","档案费","交中企合计"];
                        var store = Ext.create('Ext.data.Store', {
                        fields : json.fields,//把json的fields赋给fields
                        data : json.data     //把json的data赋给data
                         }

                        );
                       // 根据store和column构造表格
                        Ext.getCmp("configGrid").reconfigure(store, json.columns);
                        //重新渲染表格
                        //Ext.getCmp("configGrid").render();
                     
                    }
                });
                //winSal.items=[p,salList];
                winSal.show();
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
</body>
</html>
