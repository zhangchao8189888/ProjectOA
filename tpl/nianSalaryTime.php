<?php
$errorMsg = $form_data ['error'];
$warn = $form_data ['warn'];
$admin = $_SESSION ['admin'];
// var_dump($files);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>年终奖查询</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
    <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
    <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript" src="common/ext/ext-all.js" charset="utf-8"></script>
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
              var salNianTimeListGrid = Ext.create('Ext.grid.Panel',{
                  store: nianSalaryListStore,
               //   selType: 'checkboxmodel',
                  id : 'comlist',
                  columns: [
                      {text: "id", width: 120, dataIndex: 'id', sortable: true},
                      {text: "单位名称", flex: 200, dataIndex: 'company_name', sortable: true},
                      {text: "年终奖月份", flex: 200, dataIndex: 'salaryTime', sortable: true},
                      {text: "保存年终奖日期", flex: 200, dataIndex: 'op_salaryTime', sortable: true}
                  ],
                  height:700,
                  width:1000,
                  x:0,
                  y:0,
                  title: '年终奖查询',
                  disableSelection: false,
                  loadMask: true,
                  renderTo: 'demo',
                  viewConfig: {
                      id: 'gv',
                      trackOver: false,
                      stripeRows: false
                  },
                  bbar: Ext.create('Ext.PagingToolbar', {
                      store: nianSalaryListStore,
                      displayInfo: true,
                      displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                      emptyMsg: "没有数据"
                  }),
                  tbar : [
                      {
                          xtype : 'button',
                          id : 'searchSalBu',
                          disabled: true,
                          handler : function(src) {
                              var model = salNianTimeListGrid.getSelectionModel();
                              var sel=model.getLastSelected();
                              checkSalWin(sel.data.id);
                          },
                          text : '查看年终奖',
                          iconCls : 'chakan'
                      },
                      '公司名称查询', {
                          id:'comname',
                          xtype : 'trigger',
                          triggerClass : 'x-form-search-trigger',
                          name: 'comname',
                          onTriggerClick : function(src) {
                        	  nianSalaryListStore.removeAll();
                              nianSalaryListStore.load( {
                                  params : {
                                      companyName : this.getValue(),
                                      salTime : Ext.getCmp("salTime").getValue(),
                                      opTime : Ext.getCmp("opTime").getValue(),
                                      start : 0,
                                      limit : 50
                                  }
                              });
                          }
                      },
                      '年终奖月份', {
                          id:'salTime',
                          xtype : 'trigger',
                          triggerClass : 'x-form-search-trigger',
                          name: 'salTime',
                          onTriggerClick : function(src) {
                              nianSalaryListStore.removeAll();
                              nianSalaryListStore.load( {
                                  params : {
                                      companyName : Ext.getCmp("comname").getValue(),
                                      salTime : this.getValue(),
                                      opTime : Ext.getCmp("STime").getValue(),
                                      start : 0,
                                      limit : 50
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
                          id: 'opTime',
                          disabled: false,
                          handler: function () {
                              nianSalaryListStore.removeAll();
                              nianSalaryListStore.load({
                                  params: {
                                      companyName : Ext.getCmp("comname").getValue(),
                                      salTime : Ext.getCmp("salTime").getValue(),
                                      opTime : Ext.getCmp("STime").getValue(),
                                      start : 0,
                                      limit : 50
                                  }
                              });

                          },
                          text: '按操作时间查找'
                      }
                  ]
              });
              nianSalaryListStore.on("beforeload",function(){

                  Ext.apply(nianSalaryListStore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),companyName:Ext.getCmp("comname").getValue()});

              });
              var onSelectChange = function(selModel, selections){
                  alert("hello");
              };
              salNianTimeListGrid.getSelectionModel().on('selectionchange', function (selModel, selections) {
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
                  columns : [], //注意此行代码，至关重要
                  //displayInfo : true,
                  emptyMsg : "没有数据显示"
              });
              var salTimeList=Ext.define('salTimeList',{
                  extend: 'Ext.data.Model',
                  fields: [
                      {name: 'salTimeId', type: 'int'},
                      {name: 'salaryTime', type: 'string'}
                  ]
              });
              var salTimeListStore = Ext.create('Ext.data.Store', {
                  model: salTimeList,
                  proxy: {
                      type: 'ajax',
                      url : 'index.php?action=SaveSalary&mode=searchNianSalaryByIdJson'
                  }
              });
  //通过ajax获取表头以及表格数据
            	  function checkSalWin(timeId,time) {
                      //加载数据遮罩
                  	var mk=new Ext.LoadMask(Ext.getBody(),{
                  	msg:'加载数据中，请稍候！',removeMask:true
                  	});
                  	mk.show();
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
                      title: "查年终奖", // 窗口标题
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
                  var title="";
                  var url = "index.php?action=SaveSalary&mode=searchNianSalaryByIdJson";

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
                  //winSal.items=[p,salList];
                  winSal.show();
              }
              nianSalaryListStore.loadPage(1);

              function newWin() {
                  var win = Ext.create('Ext.window.Window', {
                      title: "查看工资"	,
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
    <div id="main" style="min-width: 960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right"> 
    <div id="demo">
			<!--导航栏-->
<!-- 			
		<div class="navigate">年终奖查询</div>
				<font color="red"></font>
			</div>
			<div class="manage">
				<form enctype="multipart/form-data" id="iform" action=""
					method="post">
					<input type="hidden" id="timeid" name="timeid" value="" /> <font
						color="red"><?=$warn?></font> <font color="green"></font>
				</form>
			</div>
			<div style="min-width: 830px">
			</div>
			-->	
		</div>
	</div>
</body>
</html>