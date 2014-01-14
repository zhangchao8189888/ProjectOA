Ext.Loader.setConfig({
    enabled: true
});

Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.selection.CheckboxModel'
]);

        Ext.onReady(function(){
            ////////////////////////////////////////////////////////////////////////////////////////
            // 加载数据
            ////////////////////////////////////////////////////////////////////////////////////////
           
        	Ext.define('ComAdmin', {
        	    extend: 'Ext.data.Model',
        	    fields: ['salDate', 'op_salaryTime', 'company_name','companyId',
                       	'salStat','salTimeid','fa_state','salNianStat',
                      	'salOrStat','fastat','opTime']
        	});

        	var store = Ext.create('Ext.data.Store', {
        	    model: 'ComAdmin',
        	    proxy: {
        	        type: 'ajax',
        	        url : 'index.php?action=Service&mode=getOtherAdminComList',
        	        reader: {
        	        type: 'json'
        	        }
        	    }
        	});
            store.load();
            ////////////////////////////////////////////////////////////////////////////////////////
            // 定义按钮
            ////////////////////////////////////////////////////////////////////////////////////////
            Ext.create('Ext.Button', {
                text      : '选择日期',
                renderTo  : 'bDate',
                iconAlign: 'right',
                menu      : [
                    {text: '当月',
                     handler: function () {
                    	$("#select").hide();
                    	store.removeAll();  
                    	store.load( {
                      });
                    }	
                    },
                    {text: '往月',
                     handler: function () {
                    	$("#select").show();
                    }	
                    }
                ],
                handler: function(scope) {
                	var aa=scope;
                }
            });
            ////////////////////////////////////////////////////////////////////////////////////////
            // 定义下拉框
            ////////////////////////////////////////////////////////////////////////////////////////
            function yourFunction(yourScope){
            	var mon=yourScope.lastValue;
            	store.removeAll();  
            	store.load( {
            	url : 'index.php?action=Service&mode=getOtherAdminComList',
                params : {  
            	monDate : mon, 
                yearDate: boxYear.getValue() 
               }  
              });
            }
            var year = Ext.create('Ext.data.Store', {
                fields: ['abbr', 'name'],
                data : [
                    {"abbr":"2013", "name":"2013"},
                    {"abbr":"2012", "name":"2012"},
                    {"abbr":"2011", "name":"2011"}
                ]
            });
            var mon = Ext.create('Ext.data.Store', {
                fields: ['abbr', 'name'],
                data : [
                    {"abbr":"01", "name":"01"},{"abbr":"02", "name":"02"},{"abbr":"03", "name":"03"},{"abbr":"04", "name":"04"},{"abbr":"05", "name":"05"},
                    {"abbr":"06", "name":"06"},{"abbr":"07", "name":"07"},{"abbr":"08", "name":"08"},{"abbr":"09", "name":"09"},
                    {"abbr":"10", "name":"10"},{"abbr":"11", "name":"11"},{"abbr":"12", "name":"12"}
                ]
            });
            var boxYear=Ext.create('Ext.form.ComboBox', {
                fieldLabel: '年',
                store: year,
                queryMode: 'local',
                displayField: 'name',
                valueField: 'abbr',
                renderTo: 'select',
                value : '2013',// 默认值,要设置为提交给后台的值，不要设置为显示文本,可选
                triggerAction : 'all',// 显示所有下列数据，一定要设置属性triggerAction为all
                allowBlank : false
            });
            // Create the combo box, attached to the states data store
            Ext.create('Ext.form.ComboBox', {
                fieldLabel: '月',
                store: mon,
                queryMode: 'local',
                displayField: 'name',
                valueField: 'abbr',
                editable : true,// 是否允许输入
                forceSelection : true,// 必须选择一个选项
                blankText : '请选择',
                listeners:{
                    scope: 'aa',
                    'select': yourFunction
               },
                renderTo: 'select'
            });
            ////////////////////////////////////////////////////////////////////////////////////////
            // 定义查看工资窗口
            ////////////////////////////////////////////////////////////////////////////////////////
            /**
             * 定义工资table
             */
          //创建表格,可以加入更多的属性。
            var salListWidth=890;
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
           	         url : 'index.php?action=SaveSalary&mode=searchErSalaryTimeListByIdJson'
                 }
             });
			 
		 	//通过ajax获取表头已经表格数据
                function checkSalWin(text,type,timeId,rowEl) {
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
                	var items=[];
                	if(type==4){
                		items=[p,salList];
                		salListWidth=750;
                	}else{
                		items=[salList];
                	}
                	
                	var winSal = Ext.create('Ext.window.Window', {
                		title:text, // 窗口标题
                 	    width:900, // 窗口宽度
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
                	if(type == 3){
                	 	var url = "index.php?action=SaveSalary&mode=searchSalaryByIdJosn";
                	}else if(type == 4){
                		var comId = rowEl.getAttribute('_companyId');
                		var salTime = rowEl.getAttribute('_salTime');
                		salTimeListStore.load( {  
                            params : {  
                             companyId : comId,  
                             salTime : salTime  
                            }  
                           });
                		var columns =[  
									{text: "二次工资月份",
									 width:130,
									 renderer : function(val, cellmeta, record) {
									       return '<font color="green" title="查看工资" _salTimeId="'+record.data['salTimeId']+'"  id="check">'+val+'</font>';
									},
									dataIndex: 'salaryTime'}
                		         ];  
                		Ext.getCmp("salTimeListP").reconfigure(salTimeListStore, columns); 
                		var url = "index.php?action=SaveSalary&mode=searchErSalaryByIdJson";
                	}else if(type == 5){
                		var url = "index.php?action=SaveSalary&mode=searchNianSalaryByIdJson";
                	}
                	Ext.Ajax.request({  
     		           url: url,  //从json文件中读取数据，也可以从其他地方获取数据 
     		           method : 'POST',
     		           params: {
                		  timeId : timeId 
                           },
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
     		            //Ext.getCmp("configGrid").render();  
     		           } 
     		       }); 
                	//winSal.items=[p,salList];
                	winSal.show();
                   	}
            ////////////////////////////////////////////////////////////////////////////////////////
            // 定义表格
            ////////////////////////////////////////////////////////////////////////////////////////
           
             var grid2 = Ext.create('Ext.grid.Panel', {
                id: 'grid2',
                store: store,
                columns: [
                    {text: "工资日期", dataIndex: 'salDate'},
                    {text: "工资操作日期",dataIndex: 'op_salaryTime',width: 150},
                    {
                      text: "单位名称",
                      renderer : function(val, cellmeta, record) {
                              //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                           return '<a href="#" onclick=getEmploy("'+val+'") >'+val+'</a>';
                      },
                      dataIndex: 'company_name'},//<a href="#" onclick="getEmploy('<?php  echo $row['company_name'];?>')" target="_self">
                    {
                     text: "一次工资",
                     renderer : function(val, cellmeta, record) {
                        if (val == 0) {
                            //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                            return '<a href="#" title="做工资" onclick=makeSal('+record.data['companyId']+',"'+record.data['salDate']+'","first")><font color="red">未做工资</font></a>';
                        } else if (val > 0) {
                            return '<font color="green" title="查看工资" _salTimeId="'+record.data['salTimeid']+'"  id="check">已做工资</font>';
                        }
                        return val;
                     },
                     dataIndex: 'salStat'
                    },
                    {
                      text: "二次工资", 
                     
                      renderer : function(val, cellmeta, record) {
                         if (val == 0) {
                            //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                            return '<a href="#" title="做二次工资" onclick=makeSal('+record.data['companyId']+',"'+record.data['salDate']+'","second")><font color="red">无</font></a>';
                         } else if (val > 0) {
                            return '<font color="green" title="查看工资" _salTimeId="'+record.data['salOrStat']+'" _salTime="'+record.data['salDate']+'" _companyId="'+record.data['companyId']+'"  id="check">已做二次工资</font>';
                         }
                         return val;
                      },
                      dataIndex: 'salOrStat'
                    },
                    {
                      text: "年终奖", 
                      renderer : function(val, cellmeta, record) {
                          if (val == 0) {
                             //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                             return '<a href="#" title="做年终奖" onclick=makeSal('+record.data['companyId']+',"'+record.data['salDate']+'","nian")><font color="red">无</font></a>';
                          } else if (val > 0) {
                             return '<font color="green" title="查看年终奖" _salTimeId="'+record.data['salNianStat']+'"  id="check">已做年终奖</font>';
                          }
                          return val;
                      },
                      dataIndex: 'salNianStat'
                    },
                    {
                    	text: "发票情况",
                    	renderer : function(val, cellmeta, record) {
                            if (val == 0) {
                               //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                               return '<a href="#" title="添加发票" onclick=addFa()><font color="red">添加发票</font></a>';
                            } else if (val > 0) {
                               return '<font color="green" title="查看发票情况"  id="check">已添加发票</font>';
                            }
                            return val;
                        },
                    	dataIndex: 'fastat'},
                    {
                    		text: "审批状态",
                    		renderer : function(val, cellmeta, record) {
	                    		if(val == 0){
		        		  		 	return '<font color=red>申请发放审批中</font>';
		        		  		 }else if(val == 1){
		        		  			return '<font color=green>批准发放</font>';
		        		  		 }else if(val == 2){
		        		  			return '<a href="#" onclick="send('+record.data['salTimeid']+')" target="_self"><font color=red>未批准发放</font></a>';
		        		  		 }else if(val == -1){
		        		  			return '<font color=red>未批准发放</font>';
		        		  		 }
                                return '<a href="#" onclick="send('+record.data['salTimeid']+')" target="_self">'+val+'</font></a>';
                            },
                    		dataIndex: 'fa_state'},
                    {text: "添加管理时间", dataIndex: 'opTime'},
                    {text: "备注", dataIndex: 'mark'} ],
                    listeners: {
            		    'cellclick': function(iView, iCellEl, iColIdx, iStore, iRowEl, iRowIdx, iEvent) {
            	            var rowEl=Ext.get(iEvent.getTarget());
            	            var zRec = iView.getRecord(iRowEl);
            	            var type = rowEl.getAttribute('id');
            	            if(iColIdx == 3||iColIdx == 4||iColIdx == 5){
            	            	if(type == 'make'){
            	            	var comId = rowEl.getAttribute('_comanyId');
            	            	makeSalWin('做工资');
            	            	}else if(type == 'check'){
            	            		var timeId = rowEl.getAttribute('_salTimeId');
            	            		checkSalWin("查看工资",iColIdx,timeId,rowEl);
            	            	}
            	            }
            	            
            		    }
            		},
                columnLines: true,
                width: 1000,
                height: 400,
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
                    store: salTimeListstore,
                    displayInfo: true,
                    displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                    emptyMsg: "没有数据"
                }),
                tbar: []
             });
             ////////////////////////////////////////////////////////////////////////////////////////
             // 定义button
             ////////////////////////////////////////////////////////////////////////////////////////
             var bt3 = Ext.create("Ext.Button", {
                 renderTo: Ext.get("li3").dom,
                 text: "添加管理公司",
                 id: "bt3"
             });
             bt3.on("click", function () {
             	comListStore.load();
             	window.show();
             });
             ////////////////////////////////////////////////////////////////////////////////////////
             // 定义弹出窗
             ////////////////////////////////////////////////////////////////////////////////////////
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
             ////////////////////////////////////////////////////////////////////////////////////////
             // 定义管理公司数据
             ////////////////////////////////////////////////////////////////////////////////////////
             
             Ext.define('MyData',{
                 extend: 'Ext.data.Model',
                 fields: [
                     {name: 'id', type: 'int'},
                     {name: 'company_name', type: 'string'}
                 ]
             });
             //创建数据源
             var comListStore = Ext.create('Ext.data.Store', {
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
             var companyList = Ext.create('Ext.grid.Panel',{
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
                    },

                     '公司查询', {
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
                  
                    }, {
                         xtype: 'button',
                         id: 'del',
                         text: '取消管理',
                         iconCls: 'chakan',
                         handler: function (src) {
                             var record = Ext.getCmp('comlist').getSelectionModel().getSelection();
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
             comListStore.on("beforeload",function(){ 
               	 Ext.apply(comListStore.proxy.extraParams, {Key:Ext.getCmp("comname").getValue(),companyName:Ext.getCmp("comname").getValue()});
               	 });
            
         	// Create a window
          var window = new Ext.Window({
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
         	    items:[companyList],
         	    buttons:[{
         	        text:"登陆",
         	        handler:function() {
         	            Ext.Msg.alert("提示","登陆成功!");
         	        }
         	    }],
         	    closeAction:'hide'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
         	});

        });