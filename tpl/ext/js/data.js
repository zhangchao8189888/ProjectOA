//创建数据源
var comListStore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.company.list',
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

var adminCompanyListstore = Ext.create('Ext.data.Store', {
    model: 'oa.service.company.adminList',
    proxy: {
        type: 'ajax',
        url : 'index.php?action=Service&mode=getOtherAdminComList',
        reader: {
            type: 'json'
        }
    }
});
//BY孙瑞鹏
var geshuiListstore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.geshui.list',
    //是否在服务端排序
    remoteSort: true,
    proxy: {
        //异步获取数据，这里的URL可以改为任何动态页面，只要返回JSON数据即可
        type: 'ajax',
        actionMethods: {
            create : 'POST',
            read   : 'POST', // by default POST
            update : 'POST',
            destroy: 'POST'
        },
        url : 'index.php?action=ExtSalary&mode=searchGeshuiListJosn',

        reader: {
            root: 'items',
            totalProperty  : 'total'
        },
        simpleSortMode: true
    },
    sorters: [{
        //排序字段。
        property: 'company_id',
        //排序类型，默认为 ASC
        direction: 'DESC'
    }]
});

//BY孙瑞鹏
var geshuiTypestore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.geshuitype.list',
    //是否在服务端排序
    remoteSort: true,
    proxy: {
        //异步获取数据，这里的URL可以改为任何动态页面，只要返回JSON数据即可
        type: 'ajax',
        actionMethods: {
            create : 'POST',
            read   : 'POST', // by default POST
            update : 'POST',
            destroy: 'POST'
        },
        url : 'index.php?action=ExtSalary&mode=searchGeshuiTypeJosn',

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

/**
 * 年终奖store
 * @type {Ext.data.Store}
 */
var nianSalaryListStore	=	Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.salNianTime.list',
    //是否在服务端排序
    remoteSort: false,
    proxy: {
        //异步获取数据，这里的URL可以改为任何动态页面，只要返回JSON数据即可
        type: 'ajax',
        actionMethods: {
            create : 'POST',
            read   : 'POST', // by default POST
            update : 'POST',
            destroy: 'POST'
        },
        url : 'index.php?action=ExtSalary&mode=searhSalaryNianTimeListJosn',
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
/**
 *审核公司数据源
 */
var checkCompanyStores = Ext.create('Ext.data.Store', {
    pageSize: 50,
    model: 'oa.common.checkcompany.list',
    remoteSort: true,
    proxy: {
        type: 'ajax',
        actionMethods: {
            create: 'POST',
            read: 'POST',
            update: 'POST',
            destroy: 'POST'
        },
        url: 'index.php?action=ExtFinance&mode=searchcompanyListJosn',

        reader: {
            root: 'items',
            totalProperty: 'total'
        },
        simpleSortMode: true
    },
    sorters: [
        {
            property: 'id',
            direction: 'DESC'
        }
    ]
});

/**
 * 工资查询store
 * @type {Ext.data.Store}
 */
var salTimeListstore = Ext.create('Ext.data.Store', {
    // 分页大小
    pageSize: 50,
    model: 'oa.common.salTime.list',
    //是否在服务端排序
    remoteSort: true,
    proxy: {
        //异步获取数据，这里的URL可以改为任何动态页面，只要返回JSON数据即可
        type: 'ajax',
        actionMethods: {
            create : 'POST',
            read   : 'POST', // by default POST
            update : 'POST',
            destroy: 'POST'
        },
        url : 'index.php?action=ExtSalary&mode=searchSalaryTimeListJosn',

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

var erSalTimeListstore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.otherSalTime.list',
    //是否在服务端排序
    remoteSort: true,
    proxy: {
        //异步获取数据，这里的URL可以改为任何动态页面，只要返回JSON数据即可
        type: 'ajax',
        actionMethods: {
            create : 'POST',
            read   : 'POST', // by default POST
            update : 'POST',
            destroy: 'POST'
        },
        url : 'index.php?action=ExtSalary&mode=searchErSalaryTimeListJosn',

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
var getCaiwuManageCompanyListStore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.caiwuManageCom.list',
    //是否在服务端排序
    remoteSort: true,
    proxy: {
        //异步获取数据，这里的URL可以改为任何动态页面，只要返回JSON数据即可
        type: 'ajax',
        actionMethods: {
            create : 'POST',
            read   : 'POST', // by default POST
            update : 'POST',
            destroy: 'POST'
        },
        url : 'index.php?action=ExtFinance&mode=searchCaiwuManageComListJosn',

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