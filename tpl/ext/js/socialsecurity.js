/**
 * Created by Alice on 14-2-25.
 */
var incexWin    =   Ext.create('Ext.grid.Panel',{
    store: businessLogstore,
    selType: 'checkboxmodel',
    id : 'win',
    columns: [
        {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
        {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
        {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
        {text: "单位名称", width: 150, dataIndex: 'companyName', sortable: true},
        {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
        {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
        {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
        {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
        {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false,
            renderer: function (val, cellmeta, record) {
                switch (val){
                    case "1":
                        return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 医疗报销 </span></a>';
                        break;
                    case "2":
                        return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 工伤报销 </span></a>';
                        break;
                    case "3":
                        return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 失业申报 </span></a>';
                        break;
                    case "4":
                        return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 生育医疗申报 </span></a>';
                        break;
                    case "5":
                        return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 生育津贴申报 </span></a>';
                        break;
                    case "10":
                        return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 退休 </span></a>';
                        break;
                    default :
                        return val;
                }
                return val;
            }
        },
        {text: "备注", width: 100, dataIndex: 'remarks', sortable: false},
        {text: "申请客服", width: 100, dataIndex: 'serviceName', sortable: false},
        {text: "办理情况", width: 200, dataIndex: 'socialSecurityStateId', sortable: false,
            renderer: function (val, cellmeta, record) {
                if (val == 0) {
                    return '<span style="color: gray"> 已取消 </span>';
                } else if (val == 1) {
                    return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: red"> 等待办理 </span></a>';
                } else if (val ==2) {
                    return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: blue"> 正在办理 </span></a>';
                } else if (val ==3) {
                    return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: green"> 办理成功 </span>';
                }
                return val;
            }
        }
    ],
    height:360,
    width:760,
    tbar : [
        {
            xtype : 'button',
            id : 'searchSalBu',
            handler : function(src) {
                var model = businessLogWindow.getSelectionModel();
                var sel=model.getLastSelected();
                checkSalWin(sel.data.id);
            },
            text : '查看详细',
            iconCls : 'chakan'
        },
        {
            xtype : 'hidden',
            id : 'searchT',
            value:"1",
            text : '分类',
            iconCls : 'chakan'
        }
    ],
    bbar: Ext.create('Ext.PagingToolbar', {
        store: businessLogstore,
        displayInfo: true,
        displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
        emptyMsg: "没有数据"
    })
});

function insertState(updateId,stateId,businessName){
    var url = "index.php?action=ExtSocialSecurity&mode=searchBusinessInfoById";
    Ext.Ajax.request({
        url: url,  //从json文件中读取数据，也可以从其他地方获取数据
        method : 'POST',
        params: {
            id:updateId
        },
        success: function(response){
            var json = Ext.JSON.decode(response.responseText);
            Ext.getCmp("updateComId").setValue(json["id"]);
            Ext.getCmp("businessName").setValue(json["businessName"]);
            Ext.getCmp("remarks").setValue(json["remarks"]);
            Ext.getCmp("reimbursementTime").setValue(json["reimbursementTime"]);
            Ext.getCmp("reimbursementValue").setValue(json["reimbursementValue"]);
            Ext.getCmp("accountTime").setValue(json["accountTime"]);
            Ext.getCmp("accountValue").setValue(json["accountValue"]);
            Ext.getCmp("grantTime").setValue(json["grantTime"]);
            Ext.getCmp("grantValue").setValue(json["grantValue"]);
            Ext.getCmp("accountComTime").setValue(json["accountComTime"]);
            Ext.getCmp("accountComValue").setValue(json["accountComValue"]);
            Ext.getCmp("accountPersonTime").setValue(json["accountPersonTime"]);
            Ext.getCmp("accountPersonValue").setValue(json["accountPersonValue"]);
            Ext.getCmp("retireTime").setValue(json["retireTime"]);
        }
    });
    if(stateId!=2){
        Ext.Msg.alert("警告", "状态不允许修改！（只允许修改正在办理的业务）");
        return false;
    }else{
        var items=[addBusinessWindow];

        Ext.getCmp("reimbursementTime").hide();
        Ext.getCmp("reimbursementValue").hide();
        Ext.getCmp("accountTime").hide();
        Ext.getCmp("accountValue").hide();
        Ext.getCmp("grantTime").hide();
        Ext.getCmp("grantValue").hide();
        Ext.getCmp("accountComTime").hide();
        Ext.getCmp("accountComValue").hide();
        Ext.getCmp("accountPersonTime").hide();
        Ext.getCmp("accountPersonValue").hide();
        Ext.getCmp("retireTime").hide();

        switch (businessName){
            case 1:
            case 2:
            case 3:
            case 4:
                Ext.getCmp("reimbursementTime").show();
                Ext.getCmp("reimbursementValue").show();
                Ext.getCmp("accountTime").show();
                Ext.getCmp("accountValue").show();
                Ext.getCmp("grantTime").show();
                Ext.getCmp("grantValue").show();
                break;
            case 5:
                Ext.getCmp("reimbursementTime").show();
                Ext.getCmp("reimbursementValue").show();
                Ext.getCmp("accountComTime").show();
                Ext.getCmp("accountComValue").show();
                Ext.getCmp("accountPersonTime").show();
                Ext.getCmp("accountPersonValue").show();
                break;
            case 10:
                Ext.getCmp("retireTime").show();
                break;
            default :
                break;
        }

        winSal = Ext.create('Ext.window.Window', {
            title: "业务变更", // 窗口标题
            width:600, // 窗口宽度
            height:360, // 窗口高度
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
        winSal.show();
    }
}
var addBusinessWindow = Ext.create('Ext.form.Panel', {
    bodyPadding: 15,
    width: 580,
    height: 320,
    items: [
        {
            xtype: 'fieldcontainer',
            fieldLabel: '请输入数据',
            defaultType: 'checkboxfield',
            items: [
                {
                    xtype: 'numberfield',
                    id:"updateComId",
                    editable:false,
                    hidden:true,
                    allowBlank: false,
                    fieldLabel: '操作id'
                },
                {
                    xtype: 'displayfield',
                    id:"businessName",
                    editable:false,
                    allowBlank: false,
                    hidden:true,
                    fieldLabel: '业务id'
                },
                {
                    id: 'retireTime',
                    name: 'retireTime',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    readOnly: false,
                    hidden:true,
                    fieldLabel: '退休时间'
                },
                {
                    id: 'reimbursementTime',
                    name: 'reimbursementTime',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    readOnly: false,
                    hidden:true,
                    fieldLabel: '报销时间'
                },
                {
                    id: 'reimbursementValue',
                    name: 'reimbursementValue',
                    xtype: 'numberfield',
                    hidden:true,
                    fieldLabel: '报销金额'
                },
                {
                    id: 'accountTime',
                    name: 'accountTime',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    readOnly: false,
                    hidden:true,
                    fieldLabel: '到账时间'
                },
                {
                    id: 'accountValue',
                    name: 'accountValue',
                    xtype: 'numberfield',
                    hidden:true,
                    fieldLabel: '到账金额'
                },
                {
                    id: 'grantTime',
                    name: 'grantTime',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    readOnly: false,
                    hidden:true,
                    fieldLabel: '发放时间'
                },
                {
                    id: 'grantValue',
                    name: 'grantValue',
                    xtype: 'numberfield',
                    hidden:true,
                    fieldLabel: '发放金额'
                },
                {
                    id: 'accountComTime',
                    name: 'accountComTime',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    readOnly: false,
                    hidden:true,
                    fieldLabel: '返单位时间'
                },
                {
                    id: 'accountComValue',
                    name: 'accountComValue',
                    xtype: 'numberfield',
                    hidden:true,
                    fieldLabel: '返单位金额'
                },
                {
                    id: 'accountPersonTime',
                    name: 'accountPersonTime',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    readOnly: false,
                    hidden:true,
                    fieldLabel: '返个人时间'
                },
                {
                    id: 'accountPersonValue',
                    name: 'accountPersonValue',
                    xtype: 'numberfield',
                    hidden:true,
                    fieldLabel: '返个人金额'
                },
                {
                    xtype: 'textareafield',
                    id:"remarks",
                    width:400,
                    height:80,
                    emptyText: "请输入备注信息",
                    fieldLabel: '备注'
                }
            ]
        }
    ],
    buttons: [
        {
            text: '提交',
            handler: function () {

                var updateComId = Ext.getCmp("updateComId").getValue();
                var submitInfo = this.up('form').getForm().isValid();
                if (!submitInfo) {
                    Ext.Msg.alert("警告！", "请输入完整的信息！");
                    return false;
                }
                var itcIds = [];
                itcIds.push(updateComId);
                Ext.Ajax.request({
                    url: "index.php?action=ExtSocialSecurity&mode=updateBusiness",
                    method: 'POST',
                    params: {
                        ids: Ext.JSON.encode(itcIds),
                        updateType:2,
                        reimbursementTime: Ext.getCmp("reimbursementTime").getValue(),
                        reimbursementValue: Ext.getCmp("reimbursementValue").getValue(),
                        accountTime: Ext.getCmp("accountTime").getValue(),
                        accountValue: Ext.getCmp("accountValue").getValue(),
                        grantTime: Ext.getCmp("grantTime").getValue(),
                        grantValue: Ext.getCmp("grantValue").getValue(),
                        accountComTime: Ext.getCmp("accountComTime").getValue(),
                        accountComValue: Ext.getCmp("accountComValue").getValue(),
                        accountPersonTime: Ext.getCmp("accountPersonTime").getValue(),
                        accountPersonValue: Ext.getCmp("accountPersonValue").getValue(),
                        remarks: Ext.getCmp("remarks").getValue(),
                        retireTime:Ext.getCmp("retireTime").getValue()
                    },
                    success: function (response) {
                        var text = response.responseText;
                        Ext.Msg.alert("提示", text);
                        document.location = 'index.php?action=Ext&mode=toSocialSecurityIndex';
                    }
                });

            }
        }
        ,
        {
            text: '清空',
            handler: function () {
                var updateComId = Ext.getCmp("updateComId").getValue();
                var businessName = Ext.getCmp("businessName").getValue();
                this.up('form').getForm().reset();
                Ext.getCmp("businessName").setValue(businessName);
                Ext.getCmp("updateComId").setValue(updateComId);
            }
        }
    ]
});
function changeState(updateId) {
    Ext.MessageBox.show({
        title:'更改状态',
        msg: '请选择修改的状态',
        width:300,
        buttonText:{ok: '正在办理', yes: '办理成功',no:'无法办理'},
        animateTarget: 'mb4',
        fn: function (btn) {
            var updateType;
            if(updateId==null){
                return false;
            }
            if("ok"==btn){
                updateType=2;
            } else if("yes"==btn){
                updateType=3;
            }else if("no"==btn){
                updateType=4;
            }else{
                return false;
            }
            var itcIds = [];
            itcIds.push(updateId);
            Ext.Ajax.request({
                url: 'index.php?action=ExtSocialSecurity&mode=updateBusiness',
                method: 'post',
                params: {
                    ids: Ext.JSON.encode(itcIds),
                    updateType:updateType
                },
                success: function (response) {
                    var text = response.responseText;
                    Ext.Msg.alert("提示",text);
                    if("no"==btn){
                        Ext.MessageBox.show({
                            title: '无法办理',
                            msg: '请输入无法办理原因：',
                            width:300,
                            buttons: Ext.MessageBox.OKCANCEL,
                            multiline: true,
                            fn: function(btn, text){
                                Ext.Ajax.request({
                                    url: 'index.php?action=ExtSocialSecurity&mode=updateBusiness',
                                    method: 'post',
                                    params: {
                                        ids: Ext.JSON.encode(itcIds),
                                        updateType:4,
                                        remarks:text
                                    },
                                    success: function (response) {
                                        var text = response.responseText;
                                        Ext.Msg.alert("提示",text);
                                        businessLogstore.load( {
                                                params: {
                                                    start: 0,
                                                    limit: 50
                                                }
                                            }
                                        );
                                    }
                                });
                            },
                            animateTarget: 'mb3'
                        });
                    } ;
                    businessLogstore.load( {
                            params: {
                                start: 0,
                                limit: 50
                            }
                        }
                    );
                }
            });
        },
        icon: Ext.MessageBox.INFO
    })
}
function changezengjianState(updateId) {
    Ext.MessageBox.show({
        title:'更改状态',
        msg: '请选择修改的状态',
        width:300,
        buttonText:{ok: '正在办理', yes: '办理成功',no:'无法办理'},
        animateTarget: 'mb4',
        fn: function (btn) {
            var updateType;
            if(updateId==null){
                return false;
            }
            if("ok"==btn){
                updateType="正在办理";
            } else if("yes"==btn){
                updateType="办理成功";
            }else if("no"==btn){
                updateType="无法办理";
            }else{
                return false;
            }

            Ext.Ajax.request({
                url: 'index.php?action=ExtSocialSecurity&mode=updateZengjianyuan',
                method: 'post',
                params: {
                    updateId:updateId,
                    updateType:updateType
                },
                success: function (response) {
                    var text = response.responseText;
                    Ext.Msg.alert("提示",text);
                    if("no"==btn){
                        Ext.MessageBox.show({
                            title: '无法办理',
                            msg: '请输入无法办理原因：',
                            width:300,
                            buttons: Ext.MessageBox.OKCANCEL,
                            multiline: true,
                            fn: function(btn, text){
                                Ext.Ajax.request({
                                    url: 'index.php?action=ExtSocialSecurity&mode=updateZengjianyuan',
                                    method: 'post',
                                    params: {
                                        updateId:updateId,
                                        updateType:updateType,
                                        remarks:text
                                    },
                                    success: function (response) {
                                        var text = response.responseText;
                                        Ext.Msg.alert("提示",text);
                                        zengjianListstore.load( {
                                                params: {
                                                    start: 0,
                                                    limit: 50
                                                }
                                            }
                                        );
                                    }
                                });
                            },
                            animateTarget: 'mb3'
                        });
                    };
                    zengjianListstore.load( {
                            params: {
                                start: 0,
                                limit: 50
                            }
                        }
                    );
                }
            });
        },
        icon: Ext.MessageBox.INFO
    })
};

var salList = Ext.create("Ext.grid.Panel", {
    title: '',
    width: 1200,
    height: 250,
    enableLocking: true,
    id: 'configGrid',
    name: 'configGrid',
    features: [
        {
            ftype: 'summary'
        }
    ],
    columns: [], //注意此行代码，至关重要
    //displayInfo : true,
    emptyMsg: "没有数据显示"
});

function checkSalWin(itcIds) {
    //加载数据遮罩
    var mk=new Ext.LoadMask(Ext.getBody(),{
        msg:'加载数据中，请稍候！',removeMask:true
    });
    mk.show();

    var items=[salList];
    var winSal = Ext.create('Ext.window.Window', {
        title: "查看详细信息", // 窗口标题
        width:1200, // 窗口宽度
        height:200, // 窗口高度
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
            close:function(){
                mk.hide();
            }
        },
        closeAction:'close'//hide:单击关闭图标后隐藏，可以调用show()显示。如果是close，则会将window销毁。
    });

    var url = "index.php?action=ExtSocialSecurity&mode=searchBusinessInfoByIdJson";
    Ext.Ajax.request({
        url: url,  //从json文件中读取数据，也可以从其他地方获取数据
        method : 'POST',
        params: {
            ids : Ext.JSON.encode(itcIds)
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
//            //重新渲染表格
//            //Ext.getCmp("configGrid").render();
        }
    });
    //winSal.items=[p,salList];
    winSal.show();
}


function pay(id) {
    var items=[paysal];
    Ext.getCmp("upid").setValue(id);
    winSal = Ext.create('Ext.window.Window', {
        title: "添加个人上保险", // 窗口标题
        width:380, // 窗口宽度
        height:150, // 窗口高度
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
    winSal.show();

}
var paysal = Ext.create('Ext.form.Panel', {
    bodyPadding: 15,
    width: 360,
    height: 150,
    items: [
        {
            id: 'upid',
            xtype: 'numberfield',
            hidden:true,
            allowBlank: false
        },
        {
            id: 'payValue',
            name: 'payValue',
            xtype: 'numberfield',
            allowBlank: false,
            emptyText: "请输入缴费金额",
            fieldLabel: '缴费金额'
        }
    ],
    buttons: [
        {
            text: '提交',
            handler: function () {
                var submitInfo = this.up('form').getForm().isValid();
                if (!submitInfo) {
                    Ext.Msg.alert("警告！", "请输入完整的信息！");
                    return false;
                }
                this.up('form').getForm().submit(
                    {
                        url: "index.php?action=ExtSocialSecurity&mode=updateInsurance",
                        method: 'POST',
                        waitTitle : '请等待' ,
                        waitMsg: '正在提交中',
                        success: function (form,action) {
                            var text = form.responseText;
                            Ext.Msg.alert("提示", action.result.info);
//                        document.location = 'index.php?action=Ext&mode=toInsurance';
                        },
                        failure:function(form,action){
                            Ext.Msg.alert('提示',action.result.info);
                        }
                    }
                );


            }
        }
        ,
        {
            text: '清空',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ]
});

function addsalary() {
    var items=[addInsurancWindow];
    winSal = Ext.create('Ext.window.Window', {
        title: "添加个人工资", // 窗口标题
        width:600, // 窗口宽度
        height:400, // 窗口高度
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
    winSal.show();

}
var addInsurancWindow = Ext.create('Ext.form.Panel', {
    bodyPadding: 15,
    width: 580,
    height: 460,
    items: [
        {
            xtype: 'fieldcontainer',
            fieldLabel: '请输入数据',
            defaultType: 'checkboxfield',
            items: [
                {
                    xtype: 'textfield',
                    id:"inemployId",
                    allowBlank: false,
                    emptyText: "请输入身份证号",
                    fieldLabel: '身份证号'
                },
                {
                    xtype: 'textfield',
                    id:"inemployName",
                    emptyText: "请输入姓名",
                    allowBlank: false,
                    fieldLabel: '姓名'
                },
                {
                    xtype: 'textfield',
                    id:"incompanyName" ,
                    emptyText: "请输入所在公司",
                    allowBlank: false,
                    fieldLabel: '单位'
                },
                {
                    xtype: 'textfield',
                    id:"unInsuranceReason" ,
                    emptyText: "请输入未上保险原因",
                    allowBlank: false,
                    fieldLabel: '未上保险原因'
                },
                {
                    id: 'entryTime',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    emptyText: "请选择入职时间",
                    allowBlank: false,
                    readOnly: false,
                    fieldLabel: '入职时间'
                },
                {
                    xtype: 'textfield',
                    id:"explainInfo" ,
                    emptyText: "请输入说明",
                    allowBlank: false,
                    fieldLabel: '说明'
                },
                {
                    xtype: 'textareafield',
                    id:"remarks",
                    width:400,
                    height:80,
                    emptyText: "请输入备注信息",
                    fieldLabel: '备注'
                }
            ]
        }
    ],
    buttons: [
        {
            text: '提交',
            handler: function () {
                var submitInfo = this.up('form').getForm().isValid();
                if (!submitInfo) {
                    Ext.Msg.alert("警告！", "请输入完整的信息！");
                    return false;
                }
                this.up('form').getForm().submit(
                    {
                        url: "index.php?action=ExtSocialSecurity&mode=addInsurance",
                        method: 'POST',
                        waitTitle : '请等待' ,
                        waitMsg: '正在提交中',
                        success: function (form,action) {
                            var text = form.responseText;
                            Ext.Msg.alert("提示", action.result.info);
                            document.location = 'index.php?action=Ext&mode=todemo';
                        },
                        failure:function(form,action){
                            Ext.Msg.alert('提示',action.result.info);
                        }
                    }
                );


            }
        }
        ,
        {
            text: '清空',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ]
});


function addin() {
    var items=[addinwin];
    winSal = Ext.create('Ext.window.Window', {
        title: "添加个人保险", // 窗口标题
        width:600, // 窗口宽度
        height:450, // 窗口高度
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
    winSal.show();

}
var addinwin = Ext.create('Ext.form.Panel', {
    bodyPadding: 15,
    width: 580,
    height: 460,
    items: [
        {
            xtype: 'fieldcontainer',
            fieldLabel: '请输入数据',
            defaultType: 'checkboxfield',
            items: [
                {
                    xtype: 'textfield',
                    id:"employId",
                    allowBlank: false,
                    emptyText: "请输入身份证号",
                    fieldLabel: '身份证号'
                },
                {
                    xtype: 'textfield',
                    id:"employName",
                    emptyText: "请输入姓名",
                    allowBlank: false,
                    fieldLabel: '姓名'
                },
                {
                    xtype: 'textfield',
                    id:"base" ,
                    emptyText: "请输入基数",
                    allowBlank: false,
                    fieldLabel: '基数'
                },
                {
                    xtype: 'combobox',
                    id:"idClass" ,
                    emptyText: "请选择身份类别",
                    editable: false,
                    allowBlank: false,
                    store: {
                        fields: ['abbr', 'name'],
                        data: [
                            {"abbr": "本市城镇职工", "name": "本市城镇职工"},
                            {"abbr": "外埠城镇职工", "name": "外埠城镇职工"},
                            {"abbr": "本市农村劳动力", "name": "本市农村劳动力"},
                            {"abbr": "外地农村劳动力", "name": "外地农村劳动力"}
                        ]
                    },
                    valueField: 'abbr',
                    displayField: 'name',
                    fieldLabel: '身份类别'
                },
                {
                    id: 'payStart',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    emptyText: "请选择缴费开始时间",
                    allowBlank: false,
                    readOnly: false,
                    fieldLabel: '缴费开始时间'
                },
                {
                    id: 'payEnd',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    emptyText: "请选择缴费结束时间",
                    allowBlank: false,
                    readOnly: false,
                    fieldLabel: '缴费结束时间'
                },
                {
                    xtype: 'numberfield',
                    id:"payValue" ,
                    emptyText: "请输入缴费金额",
                    allowBlank: false,
                    fieldLabel: '金额'
                },
                {
                    id: 'payTime',
                    xtype: 'datefield',
                    width:255,
                    format: "Y-m-d",
                    emptyText: "请选择缴费时间",
                    allowBlank: false,
                    readOnly: false,
                    fieldLabel: '缴费时间'
                },
                {
                    id: 'entryTime',
                    xtype: 'textfield',
                    emptyText: "请输入联系方式",
                    fieldLabel: '联系方式'
                },
                {
                    xtype: 'textareafield',
                    id:"saremarks",
                    width:400,
                    height:80,
                    emptyText: "请输入备注信息",
                    fieldLabel: '备注'
                }
            ]
        }
    ],
    buttons: [
        {
            text: '提交',
            handler: function () {
                var submitInfo = this.up('form').getForm().isValid();
                if (!submitInfo) {
                    Ext.Msg.alert("警告！", "请输入完整的信息！");
                    return false;
                }
                this.up('form').getForm().submit(
                    {
                        url: "index.php?action=ExtSocialSecurity&mode=addInsurance",
                        method: 'POST',
                        waitTitle : '请等待' ,
                        waitMsg: '正在提交中',
                        success: function (form,action) {
                            var text = form.responseText;
                            Ext.Msg.alert("提示", action.result.info);
                            document.location = 'index.php?action=Ext&mode=todemo';
                        },
                        failure:function(form,action){
                            Ext.Msg.alert('提示',action.result.info);
                        }
                    }
                );


            }
        }
        ,
        {
            text: '清空',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ]
});

//FIXME!!  修改密码window
var changePassWindow = Ext.create('Ext.form.Panel', {
    bodyPadding: 10,
    width: 480,
    height: 240,
    items: [
        {
            xtype: 'fieldcontainer',
            fieldLabel: '请输入数据',
            defaultType: 'checkboxfield',
            items: [
                {
                    xtype: 'textfield',
                    id:"nowpass",
                    allowBlank: false,
                    emptyText: "请输入当前密码",
                    fieldLabel: '当前密码<span style="color: red;font-size: 12px">(必填)</span>'
                },
                {
                    xtype: 'textfield',
                    id:"newpass",
                    inputType: 'password',
                    emptyText: "新密码",
                    regex: /^([a-zA-Z0-9]{6,})$/i,
                    regexText: '密码必须包含字母或数字,且最少有6位',
                    allowBlank: false,
                    fieldLabel: '请输入新密码'
                },
                {
                    xtype: 'textfield',
                    id:"repass",
                    inputType: 'password',
                    emptyText: "确认密码",
                    regex: /^([a-zA-Z0-9]{6,})$/i,
                    regexText: '密码必须包含字母或数字,且最少有6位',
                    allowBlank: false,
                    fieldLabel: '请再输入一次',
                    validator: function(value){
                        var pw = this.previousSibling().value;
                        if(value != pw){
                            return '两次输入的密码不一致';
                        }else{
                            return true;
                        }

                    }
                },
                {
                    id:'hint',
                    xtype : 'displayfield',
                    readonly:true,
                    width:200,
                    height:50,
                    value:"<span style='color: red'><br>注：修改密码成功需要重新登录。</span>",
                    name: 'hint'
                }
            ]
        }
    ],
    buttons: [
        {
            text: '提交',
            handler: function () {
                var nowpass = Ext.getCmp("nowpass").getValue();
                var newpass = Ext.getCmp("newpass").getValue();
                var repass = Ext.getCmp("repass").getValue();
                var submitInfo = this.up('form').getForm().isValid();
                if (!submitInfo) {
                    Ext.Msg.alert("警告！", "请输入完整的信息！");
                    return false;
                }
                Ext.Ajax.request({
                    url: "index.php?action=Admin&mode=modifyPass",
                    method: 'POST',
                    params: {
                        nowpass: nowpass,
                        newpass: newpass,
                        repass: repass
                    },
                    success: function (response) {
                        var text = response.responseText;
                        Ext.Msg.alert("提示！", text);
                        window.location.reload();
                    }
                });
            }
        },
        {
            text: '清空',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ]
});

function modifyPass(){
    var items=[changePassWindow];
    modi = Ext.create('Ext.window.Window', {
        title: "修改账户密码", // 窗口标题
        width:500, // 窗口宽度
        height:260, // 窗口高度
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
    modi.show();
}
