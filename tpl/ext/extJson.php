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
        <script type="text/javascript"><!--
        Ext.Loader.setConfig({
            enabled: true
        });
        
        Ext.onReady(function() {
        	Ext.define('User', {
        	    extend: 'Ext.data.Model',
        	    fields: [
     					{name:'salDate'},
    					{name:'op_salaryTime'},
    					{name:'company_name'},
    					{name:'salStat'}
    					]
        	});

        	var store = Ext.create('Ext.data.Store', {
        	    model: 'User',
        	    proxy: {
        	        type: 'ajax',
        	        url : 'index.php?action=Ext&mode=getExtJosn',
        	        reader: {
        	        type: 'json'
        	        }
        	    }
        	});
            store.load();
            new Ext.grid.GridPanel({
            	renderTo:'right',
                store:store,
                mode:'remote',
                title:'简单Grid表格示例',
                applyTo:'grid',
                width:250,
                height:150,
                frame:true,
                columns:[
                    {header:"工资日期",width:50,dataIndex:'salDate',sortable:true},
                    {header:"操作时间",width:80,dataIndex:'op_salaryTime',sortable:true},
                    {header:"单位名称",width:80,dataIndex:'company_name',sortable:true},
                    {header:"工资状态",width:80,dataIndex:'salStat',sortable:true}
                    
                ]
            });
            var grid2 = Ext.create('Ext.grid.Panel', {
                id: 'grid2',
                store: store,
                selType: 'checkboxmodel',
                columns: [
                    {text: "工资日期",locked: true, width: 200, dataIndex: 'salDate'},
                    {text: "操作时间",  dataIndex: 'op_salaryTime' , renderer: Ext.util.Format.dateRenderer('Y-m-d')},
                    {text: "单位名称", dataIndex: 'company_name'},
                    {text: "工资状态", dataIndex: 'salStat'}
                ],
                columnLines: true,
                width: 1000,
                height: 300,
                frame: true,
                title: 'ajax 传递json格式数据',
                iconCls: 'icon-grid',
                margin: '0 0 20 0',
                renderTo: 'right'
            });
        });
       
        
        --></script>
  </head>
  <body>
  <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">
    <div class="search" id="search" style="min-width:830px;">
        
        </div>
    </div>
    </div>
  </body>
</html>