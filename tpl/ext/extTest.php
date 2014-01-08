<?php 
$errorMsg=$form_data['error'];
//$succ=$form_data['succ'];
//$jisanlist=$form_data['jisanlist'];
$excelList=$form_data['salaryTimeList'];
$salarySumTimeList=$form_data['salarySumTimeList'];
/*session_start();
$_SESSION['excelList']=$excelList;*/
//exit;
//var_dump($files);
/**store赋值
 * var getLocalStore = function() {
                return Ext.create('Ext.data.Store', {//创建一个store 
                	fields: ['salDate', 'op_salaryTime', 'company_name',
                          	'salStat','salTimeid','fa_state','salNianStat',
                          	'salOrStat','fastat','opTime'], 
                	data: { 'items': <?php echo $comlist;?> 
                	}, 
                	proxy: { 
                	type: 'memory', 
                	reader: { 
                	type: 'json', 
                	root: 'items' 
                	} 
                	} 
                	});
            };
 */
/**
 * ////////////////////////////////////////////////////////////////////////////////////////
             // 定义弹出窗
             ////////////////////////////////////////////////////////////////////////////////////////
             
             var tabs= new Ext.TabPanel({
         	    activeTab:0,
         	    defaults:{autoScroll:true},
         	    region:"center",
         	    items:[
         	        {title:"标签1",html:"内容1"},      
         	        {title:"标签2",html:"内容2"},      
         	        {title:"标签3",html:"内容3",closable:true}      
         	    ]
         	});
         	 
         	var p = new Ext.Panel({
         	    title:"导航",
         	    width:150,
         	    region:"west",
         	    split:true,
         	    collapsible:true
         	});
         	// Create a window
          var window = new Ext.Window({
         	    title:"登陆", // 窗口标题
         	    width:700, // 窗口宽度
         	    height:350, // 窗口高度
         	    layout:"border",// 布局
         	    minimizable:true, // 最大化
         	    maximizable:true, // 最小化
         	    frame:true,
         	    constrain:true, // 防止窗口超出浏览器窗口,保证不会越过浏览器边界
         	    buttonAlign:"center", // 按钮显示的位置
         	    modal:true, // 模式窗口，弹出窗口后屏蔽掉其他组建
         	    resizable:false, // 是否可以调整窗口大小，默认TRUE。
         	    plain:true,// 将窗口变为半透明状态。
         	    items:[p,tabs],
         	    buttons:[{
         	        text:"登陆",
         	        handler:function() {
         	            Ext.Msg.alert("提示","登陆成功!");
         	        }
         	    }],
         	    closeAction:'hide'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
         	});
            var bt3 = Ext.create("Ext.Button", {
                renderTo: Ext.get("li3").dom,
                text: "事件实现3",
                id: "bt3",
                scale: 'large'
            });
            bt3.on("click", function () {
                //Ext.Msg.alert("提示", "第三个事件");
            	window.show();
            });
//         	Ext.get("window").on("click",function() {
//         	    window.show();
//         	});
 */
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
        Ext.Loader.setConfig({
            enabled: true
        });

        Ext.require([
            'Ext.grid.*',
            'Ext.data.*',
            'Ext.selection.CheckboxModel'
        ]);

        Ext.onReady(function(){
            Ext.define('Company', {
                extend: 'Ext.data.Model',
                fields: [
                    {name: 'company'},
                    {name: 'price', type: 'float'},
                    {name: 'change', type: 'float'},
                    {name: 'pctChange', type: 'float'},
                    {name: 'lastChange', type: 'date', dateFormat: 'n/j h:ia'},
                    {name: 'industry'},
                    {name: 'desc'}
                 ]
            });

            // Array data for the grids
            Ext.grid.dummyData = [
                ['3m Co',71.72,0.02,0.03,'9/1 12:00am', 'Manufacturing'],
                ['Alcoa Inc',29.01,0.42,1.47,'9/1 12:00am', 'Manufacturing'],
                ['Altria Group Inc',83.81,0.28,0.34,'9/1 12:00am', 'Manufacturing'],
                ['American Express Company',52.55,0.01,0.02,'9/1 12:00am', 'Finance'],
                ['American International Group, Inc.',64.13,0.31,0.49,'9/1 12:00am', 'Services'],
                ['AT&T Inc.',31.61,-0.48,-1.54,'9/1 12:00am', 'Services'],
                ['Boeing Co.',75.43,0.53,0.71,'9/1 12:00am', 'Manufacturing'],
                ['Caterpillar Inc.',67.27,0.92,1.39,'9/1 12:00am', 'Services'],
                ['Citigroup, Inc.',49.37,0.02,0.04,'9/1 12:00am', 'Finance'],
                ['E.I. du Pont de Nemours and Company',40.48,0.51,1.28,'9/1 12:00am', 'Manufacturing'],
                ['Exxon Mobil Corp',68.1,-0.43,-0.64,'9/1 12:00am', 'Manufacturing'],
                ['General Electric Company',34.14,-0.08,-0.23,'9/1 12:00am', 'Manufacturing'],
                ['General Motors Corporation',30.27,1.09,3.74,'9/1 12:00am', 'Automotive'],
                ['Hewlett-Packard Co.',36.53,-0.03,-0.08,'9/1 12:00am', 'Computer'],
                ['Honeywell Intl Inc',38.77,0.05,0.13,'9/1 12:00am', 'Manufacturing'],
                ['Intel Corporation',19.88,0.31,1.58,'9/1 12:00am', 'Computer'],
                ['International Business Machines',81.41,0.44,0.54,'9/1 12:00am', 'Computer'],
                ['Johnson & Johnson',64.72,0.06,0.09,'9/1 12:00am', 'Medical'],
                ['JP Morgan & Chase & Co',45.73,0.07,0.15,'9/1 12:00am', 'Finance'],
                ['McDonald\'s Corporation',36.76,0.86,2.40,'9/1 12:00am', 'Food'],
                ['Merck & Co., Inc.',40.96,0.41,1.01,'9/1 12:00am', 'Medical'],
                ['Microsoft Corporation',25.84,0.14,0.54,'9/1 12:00am', 'Computer'],
                ['Pfizer Inc',27.96,0.4,1.45,'9/1 12:00am', 'Medical'],
                ['The Coca-Cola Company',45.07,0.26,0.58,'9/1 12:00am', 'Food'],
                ['The Home Depot, Inc.',34.64,0.35,1.02,'9/1 12:00am', 'Retail'],
                ['The Procter & Gamble Company',61.91,0.01,0.02,'9/1 12:00am', 'Manufacturing'],
                ['United Technologies Corporation',63.26,0.55,0.88,'9/1 12:00am', 'Computer'],
                ['Verizon Communications',35.57,0.39,1.11,'9/1 12:00am', 'Services'],
                ['Wal-Mart Stores, Inc.',45.45,0.73,1.63,'9/1 12:00am', 'Retail'],
                ['Walt Disney Company (The) (Holding Company)',29.89,0.24,0.81,'9/1 12:00am', 'Services']
            ];

            // add in some dummy descriptions
            for(var i = 0; i < Ext.grid.dummyData.length; i++){
                Ext.grid.dummyData[i].push('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed metus nibh, sodales a, porta at, vulputate eget, dui. Pellentesque ut nisl. ');
            }


            Ext.QuickTips.init();

            var getLocalStore = function() {
                return Ext.create('Ext.data.ArrayStore', {
                    model: 'Company',
                    data: Ext.grid.dummyData
                });
            };
            Ext.define('KitchenSink.view.grid.LockingGrid', {
                extend: 'Ext.grid.Panel',
                requires: [
                    'Ext.grid.RowNumberer'
                ],
                xtype: 'locking-grid',
                store: getLocalStore(),
                columnLines: true,
                height: 350,
                width: 600,
                title: 'Locking Grid Column',
                viewConfig: {
                    stripeRows: true
                },


                initComponent: function () {
                    this.columns = [{
                            xtype: 'rownumberer'
                        }, {
                            text     : 'Company Name',
                            locked   : true,
                            width    : 230,
                            sortable : false,
                            dataIndex: 'company'
                        }, {
                            text     : 'Price',
                            lockable: false,
                            width    : 80,
                            sortable : true,
                            renderer : 'usMoney',
                            dataIndex: 'price'
                        }, {
                            text     : 'Tall<br>Header',
                            hidden   : true,
                            width    : 70,
                            sortable : false,
                            renderer : function(val) {
                                return Math.round(val * 3.14 * 100) / 10;
                            },
                            dataIndex: 'change'
                        }, {
                            text     : 'Change',
                            width    : 90,
                            sortable : true,
                            renderer : function(val) {
                                if (val > 0) {
                                    return '<span style="color:green;">' + val + '</span>';
                                } else if (val < 0) {
                                    return '<span style="color:red;">' + val + '</span>';
                                }
                                return val;
                            },
                            dataIndex: 'change'
                        }, {
                            text     : '% Change',
                            width    : 105,
                            sortable : true,
                            renderer : function(val) {
                                if (val > 0) {
                                    return '<span style="color:green;">' + val + '%</span>';
                                } else if (val < 0) {
                                    return '<span style="color:red;">' + val + '%</span>';
                                }
                                return val;
                            },
                            dataIndex: 'pctChange'
                        }, {
                            text     : 'Last Updated',
                            width    : 135,
                            sortable : true,
                            renderer : Ext.util.Format.dateRenderer('m/d/Y'),
                            dataIndex: 'lastChange'
                        }];

                    this.callParent();
                }
            });


            Ext.create('KitchenSink.view.grid.LockingGrid');
            ////////////////////////////////////////////////////////////////////////////////////////
            // Grid 2
            ////////////////////////////////////////////////////////////////////////////////////////
            var grid2 = Ext.create('Ext.grid.Panel', {
                id: 'grid2',
                store: getLocalStore(),
                selType: 'checkboxmodel',
                columns: [
                    {text: "Company",locked : true, width: 200, dataIndex: 'company'},
                    {text: "Price", renderer: Ext.util.Format.usMoney, dataIndex: 'price'},
                    {text: "Change", dataIndex: 'change'},
                    {text: "% Change", dataIndex: 'pctChange'},
                    {text: "Last Updated", width: 135, renderer: Ext.util.Format.dateRenderer('m/d/Y'), dataIndex: 'lastChange'}
                ],
                columnLines: true,
                width: 600,
                height: 300,
                frame: true,
                title: 'Framed with Checkbox Selection and Horizontal Scrolling',
                iconCls: 'icon-grid',
                margin: '0 0 20 0',
                renderTo: 'right'
            });


            ////////////////////////////////////////////////////////////////////////////////////////
            // Grid 4
            ////////////////////////////////////////////////////////////////////////////////////////
            var selModel = Ext.create('Ext.selection.CheckboxModel', {
                listeners: {
                    selectionchange: function(sm, selections) {
                        grid4.down('#removeButton').setDisabled(selections.length === 0);
                    }
                }
            });

            var grid4 = Ext.create('Ext.grid.Panel', {
                id:'button-grid',
                store: getLocalStore(),
                columns: [
                    {text: "Company", flex: 1, sortable: true, dataIndex: 'company'},
                    {text: "Price", width: 120, sortable: true, renderer: Ext.util.Format.usMoney, dataIndex: 'price'},
                    {text: "Change", width: 120, sortable: true, dataIndex: 'change'},
                    {text: "% Change", width: 120, sortable: true, dataIndex: 'pctChange'},
                    {text: "Last Updated", width: 120, sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), dataIndex: 'lastChange'}
                ],
                columnLines: true,
                selModel: selModel,

                // inline buttons
                dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    ui: 'footer',
                    layout: {
                        pack: 'center'
                    },
                    items: [{
                        minWidth: 80,
                        text: 'Save'
                    },{
                        minWidth: 80,
                        text: 'Cancel'
                    }]
                }, {
                    xtype: 'toolbar',
                    items: [{
                        text:'Add Something',
                        tooltip:'Add a new row',
                        iconCls:'add'
                    }, '-', {
                        text:'Options',
                        tooltip:'Set options',
                        iconCls:'option'
                    },'-',{
                        itemId: 'removeButton',
                        text:'Remove Something',
                        tooltip:'Remove the selected item',
                        iconCls:'remove',
                        disabled: true
                    }]
                }],

                width: 600,
                height: 300,
                frame: true,
                title: 'Support for standard Panel features such as framing, buttons and toolbars',
                iconCls: 'icon-grid',
                renderTo:'right'
            });
            Ext.create('Ext.grid.Panel',{
                renderTo:'right',
                title:'表格列类型展示',
                //要展示的数据
                store:Ext.create('Ext.data.ArrayStore',{
                    //数据结构
                    fields:[{
                        name:'name',type:'string'
                    },{
                        name:'address',type:'string'
                    },{
                        name:'isStudent',type:'boolean'
                    },{
                        name:'birthday',type:'date'
                    },{
                        name:'money',type:'int'
                    }],
                    //数据内容
                    data:[['张三','重庆','true','2012-8-2',2223],['李四','北京','false','1541-9-6',4311]]
                }),
                //在每一列的前面加一个单选框
                selModel:Ext.create('Ext.selection.CheckboxModel'),
                columns:[
                Ext.create('Ext.grid.RowNumberer'),//索引列
                {    //默认列类型
                    text:'名称',
                    dataIndex:'name'
                },{
                    //模板列
                    xtype:'templatecolumn',
                    text:'籍贯',
                    //模板
                    tpl:'{name}是{address}人'
                },{
                    //逻辑判断列
                    xtype:'booleancolumn',
                    text:'是否学生',
                    dataIndex:'isStudent',
                    //当数据为真的时候，显示的内容
                    trueText:'是',
                    //当数据为假的时候，显示的内容
                    falseText:'否'
                },{
                    //日期列
                    xtype:'datecolumn',
                    text:'出生日期',
                    dataIndex:'birthday',
                    format:'Y/m/d'
                },{
                    //数值列
                    xtype:'numbercolumn',
                    text:'收入',
                    dataIndex:'money',
                    //渲染内容
                    renderer: Ext.util.Format.usMoney
                }]
            });

        });
        
        </script>
  </head>
  <body>
  <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">

    </div>
    </div>
  </body>
</html>