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
		Ext.onReady(function(){
		 
		 	//创建表格,可以加入更多的属性。
			Ext.create("Ext.grid.Panel",{ 
				title:'动态表格~~~~~~~~~~~',
				width:400,
				height:300, 
				id : 'configGrid',  
				name : 'configGrid',  
				columns : [], //注意此行代码，至关重要
				displayInfo : true,  
				emptyMsg : "没有数据显示",  
				renderTo:'grid',//渲染到页面
				forceFit: true 
			});  
		       
		 	//通过ajax获取表头已经表格数据
		       Ext.Ajax.request({  
		           url: 'tpl/ext/data.json',  //从json文件中读取数据，也可以从其他地方获取数据 
		           method : 'POST',  
		           success : function(response) {  
		           	//将返回的结果转换为json对象，注意extjs4中decode函数已经变成了：Ext.JSON.decode
		            var json = Ext.JSON.decode(response.responseText); //获得后台传递json  
		            
		            //创建store
		            var store = Ext.create('Ext.data.Store', {  
			            fields : json.fields,//把json的fields赋给fields  
			            data : json.data     //把json的data赋给data  
		           	}); 
		           
		            //根据store和column构造表格
		            Ext.getCmp("configGrid").reconfigure(store, json.columns);  
		            //重新渲染表格
		            Ext.getCmp("configGrid").render();  
		           } 
		       }); 
		        
		})  	 
	</script>
  </head>
  
  <body>
  	<!-- 将表格渲染在此处 -->
    <div id="grid"></div>
  </body>
</html>
