<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>演示主页</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
<link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
<link href="common/css/admin.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/model.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/data.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="tpl/ext/js/socialsecurity.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript"src="tpl/ext/js/MonthPickerPlugin.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<style type="text/css">
    <!--
    A { text-decoration: none}
    -->
</style>
<script type="text/javascript">
Ext.require([
    'Ext.grid.*',
    'Ext.toolbar.Paging',
    'Ext.data.*'
]);
Ext.onReady(function () {
    var searchType;
    var tabs2 = Ext.widget('tabpanel', {
        renderTo: "demo",
        activeTab: 0,
        width: 1030,
        height: 620,
        plain: true,
        defaults: {
            autoScroll: true,
            bodyPadding: 10
        },
        items: [
            {
                title: '主页',
                items: [
                    Ext.create('Ext.grid.Panel',{
                        store: socialsecurityInfostore,
                        id : 'comlist',
                        columns: [
                            {text: "业务名称", width: 200, dataIndex: 'mattername', sortable: false
                            },
                            {text: "待办事项", width: 100, dataIndex: 'matter', sortable: true,
                                renderer: function (val, cellmeta, record) {
                                    return '<span style="color: red;font-size: 16px"> '+val+' </span>';
                                }
                            }
                        ] ,
                        height:340,
                        width:560
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        socialsecurityInfostore.loadPage(1);
                    }
                }
            },
            {
                title: '增减人员',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: zengjianListstore,
                        id : 'winzengjian',
                        columns: [
                            {text: "办理情况", width: 100, dataIndex: 'shenbaozhuangtai', sortable: true,
                                renderer: function (val, cellmeta, record) {
                                    if (val == "") {
                                        return '<span style="color: gray"> 已取消 </span>';
                                    } else if (val == "等待办理") {
                                        return '<a href="#" title="修改状态" onclick=changezengjianState(' + record.data['id'] + ')><span style="color: red"> 等待办理 </span></a>';
                                    } else if (val =="正在办理") {
                                        return '<a href="#" title="修改状态" onclick=changezengjianState(' + record.data['id'] + ')><span style="color: blue"> 正在办理 </span></a>';
                                    } else if (val =="办理成功") {
                                        return '<a href="#" title="修改状态" onclick=changezengjianState(' + record.data['id'] + ')><span style="color: green"> 办理成功 </span>';
                                    }
                                    return val;
                                }
                            },
                            {text: "提交时间", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "申报客服姓名", width: 100, dataIndex: 'CName', sortable: true},
                            {text: "部门", width: 150, dataIndex: 'Dept', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'EName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'EmpNo', sortable: true},
                            {text: "身份类别", width: 100, dataIndex: 'EmpType', sortable: true},
                            {text: "操作标志", width: 100, dataIndex: 'zengjianbiaozhi', sortable: true},
                            {text: "社保基数", width: 100, dataIndex: 'shebaojishu', sortable: true},
                            {text: "外区转入/新参保", width: 200, dataIndex: 'waiquzhuanru', sortable: true},
                            {text: "金额合计", width: 100, dataIndex: 'sum', sortable: true},
                            {text: "用人单位基数", width: 150, dataIndex: 'danweijishu', sortable: true},
                            {text: "操作人姓名", width: 150, dataIndex: 'caozuoren', sortable: true},
                            {text: "更新时间", width: 100, dataIndex: 'updateTime', sortable: true},
                            {text: "备注", width: 100, dataIndex: 'beizhu', sortable: true}
                        ],
                        height:560,
                        width:1000,
                        bbar: Ext.create('Ext.PagingToolbar', {
                            store: zengjianListstore,
                            displayInfo: true,
                            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                            emptyMsg: "没有数据"
                        }),
                        tbar : [
                            {
                                xtype:'textfield',
                                id:'zengjiancom',
                                width:150,
                                emptyText:"筛选公司"
                            },
                            {
                                xtype:'textfield',
                                id:'zengjianemp',
                                width:100,
                                emptyText:"筛选姓名"
                            },
                            {
                                xtype: 'combobox',
                                id:"businesszengjian",
                                emptyText: "筛选业务状态",
                                editable: true,
                                store: {
                                    fields: ['abbr', 'name'],
                                    data: [
                                        {"abbr": "等待办理", "name": "等待办理"},
                                        {"abbr": "正在办理", "name": "正在办理"},
                                        {"abbr": "已完成", "name": "已完成"},
                                        {"abbr": "已取消", "name": "已取消"}
                                    ]
                                },
                                valueField: 'abbr',
                                displayField: 'name'
                            },
                            {
                                xtype: 'combobox',
                                id:"zengjian",
                                emptyText: "筛选增减员类型",
                                editable: true,
                                store: {
                                    fields: ['abbr', 'name'],
                                    data: [
                                        {"abbr": "增员", "name": "增员"},
                                        {"abbr": "减员", "name": "减员"}
                                    ]
                                },
                                valueField: 'abbr',
                                displayField: 'name'
                            },
                            {
                                id:'zengjianTime',
                                xtype : 'monthfield',
                                editable: false,
                                width: 150,
                                labelAlign: 'right',
                                format: 'Y-m'
                            },
                            {
                                xtype : 'button',
                                id : 'searchzengjiani',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            shenbaozhuangtai : Ext.getCmp("businesszengjian").getValue(),
                                            zengjian : Ext.getCmp("zengjian").getValue(),
                                            submitTime: Ext.getCmp("zengjianTime").getValue(),
                                            companyName:Ext.getCmp("zengjiancom").getValue(),
                                            EName: Ext.getCmp("zengjianemp").getValue(),
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                    zengjianListstore.loadPage(1);
                                },
                                text : '筛选',
                                iconCls : 'chakan'
                            }
                        ]
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        zengjianListstore.on("beforeload", function () {
                            Ext.apply(zengjianListstore.proxy.extraParams, {companyName: Ext.getCmp("zengjiancom").getValue(),zengjian: Ext.getCmp("zengjian").getValue(),shenbaozhuangtai : Ext.getCmp("businesszengjian").getValue(),submitTime: Ext.getCmp("zengjianTime").getValue(),EName: Ext.getCmp("zengjianemp").getValue()});
                        });
                        zengjianListstore.loadPage(1);
                    }
                }
            },
            {
                title: '医疗报销',
                items: [
                    Ext.create('Ext.grid.Panel',{
                        store: businessLogstore,
                        id : 'winyiliaobao',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "办理情况", width: 100, dataIndex: 'socialSecurityStateId', sortable: false,
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
                            },
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 180, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
                            {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
                            {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false,
                                renderer: function (val, cellmeta, record) {
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 医疗报销 </span></a>';
                                }
                            },
                            {text: "备注", width: 100, dataIndex: 'remarks', sortable: false},
                            {text: "申请客服", width: 100, dataIndex: 'serviceName', sortable: false}

                        ],
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchyiliao',
                                handler : function(src) {
                                    var record = Ext.getCmp('winyiliaobao').getSelectionModel().getSelection();
                                    if (record.length>0) {
                                        var itcIds = [];
                                        //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                                        for (var i = 0; i < record.length; i++) {
                                            itcIds.push(record[i].data.id);
                                        }
                                        checkSalWin(itcIds);

                                    } else {
                                        Ext.Msg.alert("警告","请选择一条记录！");
                                    }
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype:'textfield',
                                id:'yiliaocom',
                                width:150,
                                emptyText:"筛选公司"
                            },
                            {
                                xtype:'textfield',
                                id:'yiliaoemp',
                                width:100,
                                emptyText:"筛选姓名"
                            },
                            {
                                xtype: 'combobox',
                                id:"businessyiliao",
                                emptyText: "筛选业务状态",
                                editable: true,
                                store: {
                                    fields: ['abbr', 'name'],
                                    data: [
                                        {"abbr": "1", "name": "等待办理"},
                                        {"abbr": "2", "name": "正在办理"},
                                        {"abbr": "3", "name": "已完成"},
                                        {"abbr": "0", "name": "已取消"}
                                    ]
                                },
                                valueField: 'abbr',
                                displayField: 'name'
                            },
                            {
                                id:'yiliaoTime',
                                name: 'yiliaoTime',
                                xtype : 'monthfield',
                                editable: false,
                                width: 150,
                                labelAlign: 'right',
                                format: 'Y-m'
                            },
                            {
                                xtype : 'button',
                                id : 'searchyiliaoi',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessyiliao").getValue(),
                                            submitTime: Ext.getCmp("yiliaoTime").getValue(),
                                            companyName:Ext.getCmp("yiliaocom").getValue(),
                                            employName: Ext.getCmp("yiliaoemp").getValue(),
                                            searchType:1,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                    businessLogstore.loadPage(1);
                                },
                                text : '筛选',
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
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        businessLogstore.on("beforeload", function () {
                            Ext.getCmp("searchT").setValue("1");
                            Ext.apply(businessLogstore.proxy.extraParams, {socialSecurityStateId : Ext.getCmp("businessyiliao").getValue(),searchType: Ext.getCmp("searchT").getValue(),submitTime: Ext.getCmp("yiliaoTime").getValue(),companyName: Ext.getCmp("yiliaocom").getValue(),employName: Ext.getCmp("yiliaoemp").getValue()});
                        });
                        businessLogstore.loadPage(1);
                    }
                }
            },
            {
                title: '工伤报销',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: businessLogstore,
                        id : 'wingongshang',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "办理情况", width: 100, dataIndex: 'socialSecurityStateId', sortable: false,
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
                            },
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 180, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
                            {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
                            {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false,
                                renderer: function (val, cellmeta, record) {
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 工伤报销 </span></a>';
                                }
                            },
                            {text: "备注", width: 100, dataIndex: 'remarks', sortable: false},
                            {text: "申请客服", width: 100, dataIndex: 'serviceName', sortable: false}
                        ],
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchgongshang',
                                handler : function(src) {
                                    var record = Ext.getCmp('wingongshang').getSelectionModel().getSelection();
                                    if (record.length>0) {
                                        var itcIds = [];
                                        //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                                        for (var i = 0; i < record.length; i++) {
                                            itcIds.push(record[i].data.id);
                                        }
                                        checkSalWin(itcIds);

                                    } else {
                                        Ext.Msg.alert("警告","请选择一条记录！");
                                    }
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype:'textfield',
                                id:'gongshangcom',
                                width:150,
                                emptyText:"筛选公司"
                            },
                            {
                                xtype:'textfield',
                                id:'gongshangemp',
                                width:100,
                                emptyText:"筛选姓名"
                            },
                            {
                                xtype: 'combobox',
                                id:"businessgongshang",
                                emptyText: "筛选业务状态",
                                editable: true,
                                store: {
                                    fields: ['abbr', 'name'],
                                    data: [
                                        {"abbr": "1", "name": "等待办理"},
                                        {"abbr": "2", "name": "正在办理"},
                                        {"abbr": "3", "name": "已完成"},
                                        {"abbr": "0", "name": "已取消"}
                                    ]
                                },
                                valueField: 'abbr',
                                displayField: 'name'
                            },
                            {
                                id:'gongshangTime',
                                name: 'gongshangTime',
                                xtype : 'monthfield',
                                editable: false,
                                width: 150,
                                labelAlign: 'right',
                                format: 'Y-m'
                            },
                            {
                                xtype : 'button',
                                id : 'searchgongshangi',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessgongshang").getValue(),
                                            submitTime:Ext.getCmp("gongshangTime").getValue,
                                            companyName:Ext.getCmp("gongshangcom").getValue(),
                                            employName: Ext.getCmp("gongshangemp").getValue(),
                                            searchType:2,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                    businessLogstore.loadPage(1);
                                },
                                text : '筛选',
                                iconCls : 'chakan'
                            },
                            {
                                xtype : 'hidden',
                                id : 'searchT',
                                value:"2",
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
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        businessLogstore.on("beforeload", function () {
                            Ext.getCmp("searchT").setValue("2");
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue(),submitTime : Ext.getCmp("gongshangTime").getValue(),companyName:Ext.getCmp("gongshangcom").getValue(),employName: Ext.getCmp("gongshangemp").getValue()});
                        });
                        businessLogstore.loadPage(1);
                    }
                }
            },
            {
                title: '失业申报',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: businessLogstore,
                        id : 'winshiye',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "办理情况", width: 100, dataIndex: 'socialSecurityStateId', sortable: false,
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
                            },
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 180, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
                            {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
                            {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false,
                                renderer: function (val, cellmeta, record) {
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 失业申报 </span></a>';
                                }
                            },
                            {text: "备注", width: 100, dataIndex: 'remarks', sortable: false},
                            {text: "申请客服", width: 100, dataIndex: 'serviceName', sortable: false}
                        ],
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchshiye',
                                handler : function(src) {
                                    var record = Ext.getCmp('winshiye').getSelectionModel().getSelection();
                                    if (record.length>0) {
                                        var itcIds = [];
                                        //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                                        for (var i = 0; i < record.length; i++) {
                                            itcIds.push(record[i].data.id);
                                        }
                                        checkSalWin(itcIds);

                                    } else {
                                        Ext.Msg.alert("警告","请选择一条记录！");
                                    }
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype:'textfield',
                                id:'shiyecom',
                                width:150,
                                emptyText:"筛选公司"
                            },
                            {
                                xtype:'textfield',
                                id:'shiyeemp',
                                width:100,
                                emptyText:"筛选姓名"
                            },
                            {
                                xtype: 'combobox',
                                id:"businessshiye",
                                emptyText: "筛选业务状态",
                                editable: true,
                                store: {
                                    fields: ['abbr', 'name'],
                                    data: [
                                        {"abbr": "1", "name": "等待办理"},
                                        {"abbr": "2", "name": "正在办理"},
                                        {"abbr": "3", "name": "已完成"},
                                        {"abbr": "0", "name": "已取消"}
                                    ]
                                },
                                valueField: 'abbr',
                                displayField: 'name'
                            },
                            {
                                id:'shiyeTime',
                                name: 'shiyeTime',
                                xtype : 'monthfield',
                                editable: false,
                                width: 150,
                                labelAlign: 'right',
                                format: 'Y-m'
                            },
                            {
                                xtype : 'button',
                                id : 'searchshiyei',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessshiye").getValue(),
                                            submitTime : Ext.getCmp("shiyeTime").getValue(),
                                            companyName:Ext.getCmp("shiyecom").getValue(),
                                            employName: Ext.getCmp("shiyeemp").getValue(),
                                            searchType:3,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                    businessLogstore.loadPage(1);
                                },
                                text : '筛选',
                                iconCls : 'chakan'
                            },
                            {
                                xtype : 'hidden',
                                id : 'searchT',
                                value:"3",
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
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        businessLogstore.on("beforeload", function () {
                            Ext.getCmp("searchT").setValue("3");
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue(),submitTime : Ext.getCmp("shiyeTime").getValue(),companyName:Ext.getCmp("shiyecom").getValue(),employName: Ext.getCmp("shiyeemp").getValue()});
                        });
                        businessLogstore.loadPage(1);
                    }
                }
            },
            {
                title: '生育医疗',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: businessLogstore,
                        id : 'winshengyuyi',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "办理情况", width: 100, dataIndex: 'socialSecurityStateId', sortable: false,
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
                            },
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 180, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
                            {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
                            {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false,
                                renderer: function (val, cellmeta, record) {
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 生育医疗申报 </span></a>';
                                }
                            },
                            {text: "备注", width: 100, dataIndex: 'remarks', sortable: false},
                            {text: "申请客服", width: 100, dataIndex: 'serviceName', sortable: false}
                        ],
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchshengyuyiliao',
                                handler : function(src) {
                                    var record = Ext.getCmp('winshengyuyi').getSelectionModel().getSelection();
                                    if (record.length>0) {
                                        var itcIds = [];
                                        //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                                        for (var i = 0; i < record.length; i++) {
                                            itcIds.push(record[i].data.id);
                                        }
                                        checkSalWin(itcIds);

                                    } else {
                                        Ext.Msg.alert("警告","请选择一条记录！");
                                    }
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype:'textfield',
                                id:'shengyucom',
                                width:150,
                                emptyText:"筛选公司"
                            },
                            {
                                xtype:'textfield',
                                id:'shengyuemp',
                                width:100,
                                emptyText:"筛选姓名"
                            },
                            {
                                xtype: 'combobox',
                                id:"businessshengyu",
                                emptyText: "筛选业务状态",
                                editable: true,
                                store: {
                                    fields: ['abbr', 'name'],
                                    data: [
                                        {"abbr": "1", "name": "等待办理"},
                                        {"abbr": "2", "name": "正在办理"},
                                        {"abbr": "3", "name": "已完成"},
                                        {"abbr": "0", "name": "已取消"}
                                    ]
                                },
                                valueField: 'abbr',
                                displayField: 'name'
                            },
                            {
                                id:'shengyuTime',
                                name: 'shengyuTime',
                                xtype : 'monthfield',
                                editable: false,
                                width: 150,
                                labelAlign: 'right',
                                format: 'Y-m'
                            },
                            {
                                xtype : 'button',
                                id : 'searchshengyui',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessshengyu").getValue(),
                                            submitTime : Ext.getCmp("shengyuTime").getValue(),
                                            companyName:Ext.getCmp("shengyucom").getValue(),
                                            employName: Ext.getCmp("shengyuemp").getValue(),
                                            searchType:4,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                    businessLogstore.loadPage(1);
                                },
                                text : '筛选',
                                iconCls : 'chakan'
                            },
                            {
                                xtype : 'hidden',
                                id : 'searchT',
                                value:"4",
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
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        businessLogstore.on("beforeload", function () {
                            Ext.getCmp("searchT").setValue("4");
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue(),submitTime : Ext.getCmp("shengyuTime").getValue(),companyName:Ext.getCmp("shengyucom").getValue(),employName: Ext.getCmp("shengyuemp").getValue()});
                        });
                        businessLogstore.loadPage(1);
                    }
                }
            },
            {
                title: '生育津贴',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: businessLogstore,
                        selType: 'checkboxmodel',
                        id : 'winshengyujin',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "办理情况", width: 100, dataIndex: 'socialSecurityStateId', sortable: false,
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
                            },
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 180, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
                            {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
                            {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false,
                                renderer: function (val, cellmeta, record) {
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 生育津贴申报 </span></a>';
                                }
                            },
                            {text: "备注", width: 100, dataIndex: 'remarks', sortable: false},
                            {text: "申请客服", width: 100, dataIndex: 'serviceName', sortable: false}
                        ],
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchshengyujintie',
                                handler : function(src) {
                                    var record = Ext.getCmp('winshengyujin').getSelectionModel().getSelection();
                                    if (record.length>0) {
                                        var itcIds = [];
                                        //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                                        for (var i = 0; i < record.length; i++) {
                                            itcIds.push(record[i].data.id);
                                        }
                                        checkSalWin(itcIds);

                                    } else {
                                        Ext.Msg.alert("警告","请选择一条记录！");
                                    }
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype:'textfield',
                                id:'shengyujincom',
                                width:150,
                                emptyText:"筛选公司"
                            },
                            {
                                xtype:'textfield',
                                id:'shengyujinemp',
                                width:100,
                                emptyText:"筛选姓名"
                            },
                            {
                                xtype: 'combobox',
                                id:"businessshengyujintie",
                                emptyText: "筛选业务状态",
                                editable: true,
                                store: {
                                    fields: ['abbr', 'name'],
                                    data: [
                                        {"abbr": "1", "name": "等待办理"},
                                        {"abbr": "2", "name": "正在办理"},
                                        {"abbr": "3", "name": "已完成"},
                                        {"abbr": "0", "name": "已取消"}
                                    ]
                                },
                                valueField: 'abbr',
                                displayField: 'name'
                            },
                            {
                                id:'shengyujinTime',
                                name: 'shengyujinTime',
                                xtype : 'monthfield',
                                editable: false,
                                width: 150,
                                labelAlign: 'right',
                                format: 'Y-m'
                            },
                            {
                                xtype : 'button',
                                id : 'searchshengyujintiei',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessshengyujintie").getValue(),
                                            submitTime : Ext.getCmp("shengyujinTime").getValue(),
                                            companyName:Ext.getCmp("shengyujincom").getValue(),
                                            employName: Ext.getCmp("shengyujinemp").getValue(),
                                            searchType:5,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                    businessLogstore.loadPage(1);
                                },
                                text : '筛选',
                                iconCls : 'chakan'
                            },
                            {
                                xtype : 'hidden',
                                id : 'searchT',
                                value:"5",
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
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        businessLogstore.on("beforeload", function () {
                            Ext.getCmp("searchT").setValue("5");
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue(),submitTime : Ext.getCmp("shengyujinTime").getValue(),companyName:Ext.getCmp("shengyujincom").getValue(),employName: Ext.getCmp("shengyujinemp").getValue()});
                        });
                        businessLogstore.loadPage(1);
                    }
                }
            },
            {
                title: '退休办理',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: businessLogstore,
                        selType: 'checkboxmodel',
                        id : 'wintui',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "办理情况", width: 100, dataIndex: 'socialSecurityStateId', sortable: false,
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
                            },
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 180, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
                            {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
                            {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false,
                                renderer: function (val, cellmeta, record) {
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 退休 </span></a>';
                                }
                            },
                            {text: "备注", width: 100, dataIndex: 'remarks', sortable: false},
                            {text: "申请客服", width: 100, dataIndex: 'serviceName', sortable: false}
                        ],
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchtuixiu',
                                handler : function(src) {
                                    var record = Ext.getCmp('wintui').getSelectionModel().getSelection();
                                    if (record.length>0) {
                                        var itcIds = [];
                                        //var cbgItem = Ext.getCmp('myForm').findById('cbg').items;
                                        for (var i = 0; i < record.length; i++) {
                                            itcIds.push(record[i].data.id);
                                        }
                                        checkSalWin(itcIds);

                                    } else {
                                        Ext.Msg.alert("警告","请选择一条记录！");
                                    }
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype:'textfield',
                                id:'tuixiucom',
                                width:150,
                                emptyText:"筛选公司"
                            },
                            {
                                xtype:'textfield',
                                id:'tuixiuemp',
                                width:100,
                                emptyText:"筛选姓名"
                            },
                            {
                                xtype: 'combobox',
                                id:"businesstuixiu",
                                emptyText: "筛选业务状态",
                                editable: true,
                                store: {
                                    fields: ['abbr', 'name'],
                                    data: [
                                        {"abbr": "1", "name": "等待办理"},
                                        {"abbr": "2", "name": "正在办理"},
                                        {"abbr": "3", "name": "已完成"},
                                        {"abbr": "0", "name": "已取消"}
                                    ]
                                },
                                valueField: 'abbr',
                                displayField: 'name'
                            },
                            {
                                id:'tuixiuTime',
                                name: 'tuixiuTime',
                                xtype : 'monthfield',
                                editable: false,
                                width: 150,
                                labelAlign: 'right',
                                format: 'Y-m'
                            },
                            {
                                xtype : 'button',
                                id : 'searchtuixiui',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businesstuixiu").getValue(),
                                            submitTime : Ext.getCmp("tuixiuTime").getValue(),
                                            companyName:Ext.getCmp("tuixiucom").getValue(),
                                            employName: Ext.getCmp("tuixiuemp").getValue(),
                                            searchType:4,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                    businessLogstore.loadPage(1);
                                },
                                text : '筛选',
                                iconCls : 'chakan'
                            },
                            {
                                xtype : 'hidden',
                                id : 'searchT',
                                value:"10",
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
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        businessLogstore.on("beforeload", function () {
                            Ext.getCmp("searchT").setValue("10");
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue(), submitTime : Ext.getCmp("tuixiuTime").getValue(),companyName:Ext.getCmp("tuixiucom").getValue(),employName: Ext.getCmp("tuixiuemp").getValue()});
                        });
                        businessLogstore.loadPage(1);
                    }
                }
            } ,
            {
                title: '个人保险',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: insurancestore,
                        id : 'personIn',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位名称", width: 150, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工身份类别", width: 100, dataIndex: 'idClass', sortable: false},
                            {text: "基数", width: 100, dataIndex: 'base', sortable: true},
                            {text: "缴费开始日期", width: 100, dataIndex: 'paymentStartTime', sortable: false},
                            {text: "缴费结束日期", width: 100, dataIndex: 'paymentEndTime', sortable: false},
                            {text: "缴费日期", width: 100, dataIndex: 'paymentTime', sortable: false,
                                renderer: function (val, cellmeta, record) {
                                    if(val==0){
                                        return '<a href="#" title="修改状态" onclick=pay(' + record.data['id'] + ')><span style="color: red;font-size: 14px">需要缴费</span>';
                                    }
                                    return val;
                                }
                            },
                            {text: "金额", width: 100, dataIndex: 'paymentValue', sortable: true},
                            {text: "缴费方式", width: 100, dataIndex: 'paymentType', sortable: false},
                            {text: "联系方式", width: 100, dataIndex: 'tel', sortable: false},
                            {text: "备注", width: 100, dataIndex: 'remark', sortable: false}
                        ],
                        height:560,
                        width:1000,
                        bbar: Ext.create('Ext.PagingToolbar', {
                            store: insurancestore,
                            displayInfo: true,
                            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                            emptyMsg: "没有数据"
                        }),

                        tbar : [
                            {
                                xtype : 'button',
                                id : 'addin',
                                handler : function(src) {
                                    addin();
                                },
                                text : '添加业务',
                                iconCls : 'chakan'
                            },
                            {
                                xtype : 'button',
                                id : 'disTypei',
                                hidden:true,
                                value:"0" ,
                                text : '显示个人保险',
                                iconCls : 'chakan'
                            }
                        ]
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        insurancestore.on("beforeload", function () {
                            Ext.apply(insurancestore.proxy.extraParams, {disType:"0"});
                        });
                        insurancestore.loadPage(1);
                    }
                }
            },
            {
                title: '个人工资',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: insurancestore,
                        id : 'personsal',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位名称", width: 150, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工身份类别", width: 100, dataIndex: 'idClass', sortable: false},
                            {text: "负责客服", width: 100, dataIndex: 'serviceName', sortable: false,hidden:true},
                            {text: "未上保险原因", width: 100, dataIndex: 'unInsuranceReason', sortable: false},
                            {text: "说明", width: 100, dataIndex: 'explainInfo', sortable: false},
                            {text: "入职时间", width: 100, dataIndex: 'entryTime', sortable: false},
                            {text: "备注", width: 100, dataIndex: 'remark', sortable: false}
                        ],
                        height:560,
                        width:1000,
                        bbar: Ext.create('Ext.PagingToolbar', {
                            store: insurancestore,
                            displayInfo: true,
                            displayMsg: '显示 {0} - {1} 条，共计 {2} 条',
                            emptyMsg: "没有数据"
                        }),

                        tbar : [
                            {
                                xtype : 'button',
                                id : 'addsar',
                                handler : function(src) {
                                    addsalary();
                                },
                                text : '添加业务',
                                iconCls : 'chakan'
                            }  ,
                            {
                                xtype : 'button',
                                id : 'disTypes',
                                hidden:true,
                                value:"1" ,
                                text : '显示个人工资',
                                iconCls : 'chakan'
                            }
                        ]
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        insurancestore.on("beforeload", function () {
                            Ext.apply(insurancestore.proxy.extraParams, {disType:"1"});
                        });
                        insurancestore.loadPage(1);
                    }
                }
            },
            {
                title: '其他',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: businessLogstore,
                        id : 'winqita',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "办理情况", width: 100, dataIndex: 'socialSecurityStateId', sortable: false,
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
                            },
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 180, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
                            {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
                            {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false},
                            {text: "备注", width: 100, dataIndex: 'remarks', sortable: false},
                            {text: "申请客服", width: 100, dataIndex: 'serviceName', sortable: false}
                        ],
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype:'textfield',
                                id:'qitacom',
                                width:150,
                                emptyText:"筛选公司"
                            },
                            {
                                xtype:'textfield',
                                id:'qitaemp',
                                width:100,
                                emptyText:"筛选姓名"
                            },
                            {
                                xtype: 'combobox',
                                id:"businessqita",
                                emptyText: "筛选业务状态",
                                editable: true,
                                store: {
                                    fields: ['abbr', 'name'],
                                    data: [
                                        {"abbr": "1", "name": "等待办理"},
                                        {"abbr": "2", "name": "正在办理"},
                                        {"abbr": "3", "name": "已完成"},
                                        {"abbr": "0", "name": "已取消"}
                                    ]
                                },
                                valueField: 'abbr',
                                displayField: 'name'
                            },
                            {
                                id:'qitaTime',
                                name: 'qitaTime',
                                xtype : 'monthfield',
                                editable: false,
                                width: 150,
                                labelAlign: 'right',
                                format: 'Y-m'
                            },
                            {
                                xtype : 'button',
                                id : 'searchqita',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessqita").getValue(),
                                            submitTime : Ext.getCmp("qitaTime").getValue(),
                                            companyName:Ext.getCmp("qitacom").getValue(),
                                            employName: Ext.getCmp("qitaemp").getValue(),
                                            searchType:"其他",
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                    businessLogstore.loadPage(1);
                                },
                                text : '筛选',
                                iconCls : 'chakan'
                            },
                            {
                                xtype : 'hidden',
                                id : 'searchT',
                                value:"其他",
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
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        businessLogstore.on("beforeload", function () {
                            Ext.getCmp("searchT").setValue("其他");
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue(),submitTime : Ext.getCmp("qitaTime").getValue(),companyName:Ext.getCmp("qitacom").getValue(),employName: Ext.getCmp("qitaemp").getValue()});
                        });
                        businessLogstore.loadPage(1);
                    }
                }
            }


        ]
    });
});



</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>
        <div id="demo"></div>
</div>
</body>
</html>
