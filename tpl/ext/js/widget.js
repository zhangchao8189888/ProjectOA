var companyListPanel = Ext.create('Ext.grid.Panel',{
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
var adminCompanyWindow = new Ext.Window({
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
    items:[companyListPanel],
    buttons:[{
        text:"登陆",
        handler:function() {
            Ext.Msg.alert("提示","登陆成功!");
        }
    }],
    closeAction:'hide'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
});
