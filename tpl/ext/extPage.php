<?php 
$comlist=$form_data['comList'];
$errorlist=$form_data['error'];
$searchType=$form_data['searchType'];
$comlist=json_encode($comlist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>{$title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
        <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
            <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
   <link href="common/css/validator.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
        <script type="text/javascript">  
        Ext.require([
                     'Ext.grid.*',
                     'Ext.toolbar.Paging',
                     'Ext.data.*'
                 ]);
                 Ext.onReady(function(){
                     Ext.define('MyData',{
                         extend: 'Ext.data.Model',
                         fields: [
                             {name: 'id', type: 'int'},
                             {name: 'company_name', type: 'string'}
                         ]
                     });
                     //创建数据源
                     var store = Ext.create('Ext.data.Store', {
                         //分页大小
                         pageSize: 50,
                         model: 'MyData',
                         //是否在服务端排序
                         remoteSort: true,
                         proxy: {
                            //异步获取数据，这里的URL可以改为任何动态页面，只要返回JSON数据即可
                             type: 'ajax',
                   	         url : 'index.php?action=Service&mode=getOpCompanyListJson',
                             
                             reader: {
                                 root: 'items',
                                 totalProperty  : 'total'
                             },
                             simpleSortMode: true
                         },
                         sorters: [{
                             //排序字段。
                             property: 'id',
                             //排序类型，默认为 ASC
                             direction: 'DESC'
                         }]
                     });
                     
                     //创建Grid
                     var grid = Ext.create('Ext.grid.Panel',{
                         store: store,
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
                         title: 'ExtJS4 Grid 分页示例',
                         disableSelection: false,
                         loadMask: true,
                         renderTo: 'demo',
                         viewConfig: {
                             id: 'gv',
                             trackOver: false,
                             stripeRows: false
                         },
                         
                         bbar: Ext.create('Ext.PagingToolbar', {
                             store: store,
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
                             text : '删除',  
                             iconCls : 'shanchu'  
                            }, '公司查询', {
                             id:'comname',  
                             xtype : 'trigger',  
                             triggerClass : 'x-form-search-trigger', 
                             name: 'search',
                             onTriggerClick : function(src) {  
                              store.removeAll();  
                              store.load( {  
                               params : {  
                                Key : this.getValue(), 
                                start : 0,  
                                limit : 50  
                               }  
                              });  
                          
                             }  
                          
                            }] 
                     });
                     store.on("beforeload",function(){ 
                       	 
                       	 Ext.apply(store.proxy.extraParams, {Key:Ext.getCmp("comname").getValue()}); 
                       	  
                       	 });  
                     store.loadPage(1);

                     function newWin(text) {
                      	var win = Ext.create('Ext.window.Window', {
                      	title: text	,
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
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
    <div id="tab" class="TipDiv">提示</div>
    <div id="demo"></div>  
    <div id="center"></div>  
    </div>
    </div>
  </body>
</html>
