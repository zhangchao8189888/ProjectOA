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

    Ext.Loader.setConfig({enabled: true});
    Ext.Loader.setPath('Ext.ux', 'tpl/ext/js/ux');
    Ext.require([
        'Ext.tip.QuickTipManager',
        'Ext.container.Viewport',
        'Ext.layout.*',
        'Ext.form.Panel',
        'Ext.form.Label',
        'Ext.grid.*',
        'Ext.data.*',
        'Ext.tree.*',
        'Ext.selection.*',
        'Ext.tab.Panel',
        'Ext.ux.layout.Center'
    ]);
    //
    // This is the main layout definition.
    //
    Ext.onReady(function(){
        Ext.tip.QuickTipManager.init();
// This is an inner body element within the Details panel created to provide a "slide in" effect
// on the panel body without affecting the body's box itself. This element is created on
// initial use and cached in this var for subsequent access.
        var detailEl;
// Gets all layouts examples
//        var layoutExamples = [];
//        Ext.Object.each(getBasicLayouts(), function(name, example) {
//            layoutExamples.push(example);
//        });
//        Ext.Object.each(getCombinationLayouts(), function(name, example){
//            layoutExamples.push(example);
//        });
//        Ext.Object.each(getCustomLayouts(), function(name, example){
//            layoutExamples.push(example);
//        });
// This is the main content center region that will contain each example layout panel.
// It will be implemented as a CardLayout since it will contain multiple panels with
// only one being visible at any given time.
        var contentPanel = {
            id: 'content-panel',
            region: 'center', // this is what makes this panel into a region within the containing layout
            layout: 'card',
            margins: '2 5 5 0',
            activeItem: 0,
            border: false,
            items: []
        };
        var store = Ext.create('Ext.data.TreeStore', {
            root: {
                expanded: true
            },
            proxy: {
                type: 'ajax',
                url: 'tree-data.json'
            }
        });
// Go ahead and create the TreePanel now so that we can use it below
        var treePanel = Ext.create('Ext.tree.Panel', {
            id: 'tree-panel',
            title: 'Sample Layouts',
            region:'north',
            split: true,
            height: 360,
            minSize: 150,
            rootVisible: false,
            autoScroll: true,
            store: {}
        });
// Assign the changeLayout function to be called on tree node click.
//        treePanel.getSelectionModel().on('select', function(selModel, record) {
//            if (record.get('leaf')) {
//                Ext.getCmp('content-panel').layout.setActiveItem(record.getId() + '-panel');
//                if (!detailEl) {
//                    var bd = Ext.getCmp('details-panel').body;
//                    bd.update('').setStyle('background','#fff');
//                    detailEl = bd.createChild(); //create default empty div
//                }
//                detailEl.hide().update(Ext.getDom(record.getId() + '-details').innerHTML).slideIn('l', {stopAnimation:true,duration: 200});
//            }
//        });
// This is the Details panel that contains the description for each example layout.
        var detailsPanel = {
            id: 'details-panel',
            title: 'Details',
            region: 'center',
            bodyStyle: 'padding-bottom:15px;background:#eee;',
            autoScroll: true,
            html: '<p class="details-info">When you select a layout from the tree, additional details will display here.</p>'
        };
// Finally, build the main layout once all the pieces are ready. This is also a good
// example of putting together a full-screen BorderLayout within a Viewport.
        Ext.create('Ext.Viewport', {
            layout: 'border',
            title: 'Ext Layout Browser',
            items: [{
                xtype: 'box',
                id: 'header',
                region: 'north',
                html: '<h1> Ext.Layout.Browser</h1>',
                height: 30
            },{
                layout: 'border',
                id: 'layout-browser',
                region:'west',
                border: false,
                split:true,
                margins: '2 0 5 5',
                width: 290,
                minSize: 100,
                maxSize: 500,
                items: [treePanel, detailsPanel]
            },
                contentPanel
            ],
            renderTo: Ext.getBody()
        });
    });

</script>
</head>

<body>

</body>
</html>


