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
                            {text: "业务名称", width: 200, dataIndex: 'mattername', sortable: false,
                                renderer: function (val, cellmeta, record) {
                                    return '<span style="color: blue;font-size: 16px"> '+val+' </span>';
                                }
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
                            {text: "编号", width: 100, dataIndex: 'id', sortable: true},
                            {text: "客服姓名", width: 100, dataIndex: 'CName', sortable: true},
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
                            {text: "申报状态", width: 100, dataIndex: 'shenbaozhuangtai', sortable: true,
                                renderer: function (val, cellmeta, record) {
                                    if (val == "") {
                                        return '<span style="color: gray"> 已取消 </span>';
                                    } else if (val == "等待受理") {
                                        return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: red"> 等待办理 </span></a>';
                                    } else if (val =="正在办理") {
                                        return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: blue"> 正在办理 </span></a>';
                                    } else if (val =="办理成功") {
                                        return '<a href="#" title="修改状态" onclick=changeState(' + record.data['id'] + ')><span style="color: green"> 办理成功 </span>';
                                    }
                                    return val;
                                }
                            },
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
                            '公司名称查询', {
                                id:'comname',
                                xtype : 'trigger',
                                triggerClass : 'x-form-search-trigger',
                                name: 'comname',
                                onTriggerClick : function(src) {
                                    zengjianListstore.removeAll();
                                    zengjianListstore.load( {
                                        params : {
                                            companyName : this.getValue(),
                                            zengjian : Ext.getCmp("zengjian").getValue(),
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                }
                            },
                            '增减类型查询', {
                                id:'zengjian',
                                xtype : 'trigger',
                                triggerClass : 'x-form-search-trigger',
                                name: 'zengjian',
                                onTriggerClick : function(src) {
                                    zengjianListstore.removeAll();
                                    zengjianListstore.load( {
                                        params : {
                                            companyName : Ext.getCmp("comname").getValue(),
                                            zengjian : this.getValue(),
                                            start : 0,
                                            limit : 50
                                        }
                                    });
                                }
                            }

                        ]
                    })
                ],
                listeners: {
                    activate: function (tab) {
                        zengjianListstore.on("beforeload", function () {
                            Ext.apply(zengjianListstore.proxy.extraParams, {companyName: Ext.getCmp("comname").getValue(),zengjian: Ext.getCmp("zengjian").getValue()});
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
                        selType: 'checkboxmodel',
                        id : 'winyiliaobao',
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
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 医疗报销 </span></a>';
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
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchyiliao',
                                handler : function(src) {
                                    var model = Ext.getCmp("winyiliaobao").getSelectionModel();
                                    var sel=model.getLastSelected();
                                    checkSalWin(sel.data.id);
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype: 'combobox',
                                id:"businessyiliao",
                                emptyText: "筛选业务状态",
                                allowBlank: false,
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
                                xtype : 'button',
                                id : 'searchyiliaoi',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessyiliao").getValue(),
                                            searchType:1,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
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
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue()});
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
                        selType: 'checkboxmodel',
                        id : 'wingongshang',
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
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 工伤报销 </span></a>';
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
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchgongshang',
                                handler : function(src) {
                                    var model = Ext.getCmp("wingongshang").getSelectionModel();
                                    var sel=model.getLastSelected();
                                    checkSalWin(sel.data.id);
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype: 'combobox',
                                id:"businessgongshang",
                                emptyText: "筛选业务状态",
                                allowBlank: false,
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
                                xtype : 'button',
                                id : 'searchgongshangi',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessgongshang").getValue(),
                                            searchType:2,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
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
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue()});
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
                        selType: 'checkboxmodel',
                        id : 'winshiye',
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
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 失业申报 </span></a>';
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
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchSalBu',
                                handler : function(src) {
                                    var model = Ext.getCmp("winshiye").getSelectionModel();
                                    var sel=model.getLastSelected();
                                    checkSalWin(sel.data.id);
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype: 'combobox',
                                id:"businessshiye",
                                emptyText: "筛选业务状态",
                                allowBlank: false,
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
                                xtype : 'button',
                                id : 'searchshiyei',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessshiye").getValue(),
                                            searchType:3,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
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
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue()});
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
                        selType: 'checkboxmodel',
                        id : 'winshengyuyi',
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
                                    return '<a href="#" title="添加信息" onclick=insertState(' + record.data['id'] + ',' + record.data['socialSecurityStateId'] + ',' + record.data['businessName'] + ')><span style="color: slateblue"> 生育医疗申报 </span></a>';
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
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchshengyuyiliao',
                                handler : function(src) {
                                    var model = Ext.getCmp("winshengyuyi").getSelectionModel();
                                    var sel=model.getLastSelected();
                                    checkSalWin(sel.data.id);
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype: 'combobox',
                                id:"businessshengyu",
                                emptyText: "筛选业务状态",
                                allowBlank: false,
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
                                xtype : 'button',
                                id : 'searchshengyui',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessshengyu").getValue(),
                                            searchType:4,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
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
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue()});
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
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 150, dataIndex: 'companyName', sortable: true},
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
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchshengyujintie',
                                handler : function(src) {
                                    var model = grid.getId("winshengyujin").getSelectionModel();
                                    var sel=model.getLastSelected();
                                    checkSalWin(sel.data.id);
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype: 'combobox',
                                id:"businessshengyujintie",
                                emptyText: "筛选业务状态",
                                allowBlank: false,
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
                                xtype : 'button',
                                id : 'searchshengyujintiei',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businessshengyujintie").getValue(),
                                            searchType:5,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
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
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue()});
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
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 150, dataIndex: 'companyName', sortable: true},
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
                        height:560,
                        width:1000,
                        tbar : [
                            {
                                xtype : 'button',
                                id : 'searchtuixiu',
                                handler : function(src) {
                                    var model = Ext.getCmp("wintui").getSelectionModel();
                                    var sel=model.getLastSelected();
                                    checkSalWin(sel.data.id);
                                },
                                text : '查看详细',
                                iconCls : 'chakan'
                            },
                            {
                                xtype: 'combobox',
                                id:"businesstuixiu",
                                emptyText: "筛选业务状态",
                                allowBlank: false,
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
                                xtype : 'button',
                                id : 'searchtuixiui',
                                handler : function(src) {
                                    businessLogstore.removeAll();
                                    businessLogstore.load( {
                                        params : {
                                            socialSecurityStateId : Ext.getCmp("businesstuixiu").getValue(),
                                            searchType:4,
                                            start : 0,
                                            limit : 50
                                        }
                                    });
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
                            searchT=3 ;
                            Ext.getCmp("searchT").setValue("10");
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue()});
                        });
                        businessLogstore.loadPage(1);
                    }
                }
            } ,
            {
                title: '其他',
                items:[
                    Ext.create('Ext.grid.Panel',{
                        store: businessLogstore,
                        selType: 'checkboxmodel',
                        id : 'winqita',
                        columns: [
                            {text: "编号", width: 100, dataIndex: 'id', sortable: false,hidden:true},
                            {text: "提交日期", width: 100, dataIndex: 'submitTime', sortable: true},
                            {text: "单位id", width: 100, dataIndex: 'companyId', sortable: false,hidden:true},
                            {text: "单位名称", width: 150, dataIndex: 'companyName', sortable: true},
                            {text: "员工姓名", width: 100, dataIndex: 'employName', sortable: true},
                            {text: "身份证号", width: 100, dataIndex: 'employId', sortable: false},
                            {text: "员工状态id", width: 100, dataIndex: 'employStateId', sortable: false,hidden:true},
                            {text: "员工状态", width: 100, dataIndex: 'employState', sortable: false},
                            {text: "业务名称", width: 100, dataIndex: 'businessName', sortable: false},
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
                        height:560,
                        width:1000,
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
                            Ext.apply(businessLogstore.proxy.extraParams, {searchType: Ext.getCmp("searchT").getValue()});
                        });
                        businessLogstore.loadPage(1);
                    }
                }
            }
        ]
    });
    socialsecurityInfostore.loadPage(1);
});



</script>
</head>
<body>
<?php include("tpl/commom/top.html"); ?>

        <div id="demo"></div>


</div>
</body>
</html>
