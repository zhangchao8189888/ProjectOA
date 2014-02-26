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

//BY发票孙瑞鹏
var fapiaoStore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.fapiao.list',
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
        url : 'index.php?action=ExtSalary&mode=searchFapiaoTypeJosn',

        reader: {
            root: 'items',
            totalProperty  : 'total'
        },
        simpleSortMode: true
    },
    sorters: [{
        //排序字段。
        property: 'bill_no',
        //排序类型，默认为 ASC
        direction: 'DESC'
    }]
});

//BY增减员孙瑞鹏
var zengjianListstore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.zengjian.list',
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
        url : 'index.php?action=ExtSalary&mode=searchZengjianJosn',

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
//BY到账孙瑞鹏
var daozhangListstore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.daozhang.list',
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
        url : 'index.php?action=ExtSalary&mode=searchDaozhangTypeJosn',

        reader: {
            root: 'items',
            totalProperty  : 'total'
        },
        simpleSortMode: true
    },
    sorters: [{
        //排序字段。
        property: 'daozhangTime',
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

var salaryComListstore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.salarycom.list',
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
        url : 'index.php?action=ExtSalary&mode=salaryComListJosn',

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
 * 工资查询store
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

/**
 * 工资统计store
 */
var salTongjistore = Ext.create('Ext.data.Store', {
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
        url : 'index.php?action=ExtSalary&mode=searchSalaryTongji',

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
 * 二次工资
 * @type {Ext.data.Store}
 */
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
var geShuiExcelExportStore = Ext.create('Ext.data.Store', {
    //分页大小
  //  pageSize: 50,
    model: 'oa.common.geshui.detail.list',
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
        url : 'index.php?action=SaveSalary&mode=searchGeshuiByIdJosn',

        reader: {
            root: 'items'
           // totalProperty  : 'total'
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

//员工查询BY孙瑞鹏
var empExtListStore = Ext.create('Ext.data.Store', {
    //分页大小
    //  pageSize: 50,
    model: 'oa.common.yuangong.list',
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
        url : 'index.php?action=Employ&mode=getEmListExt',

        reader: {
            root: 'items'
            // totalProperty  : 'total'
        },
        simpleSortMode: true
    },
    sorters: [{
        //排序字段。
        property: 'e_name',
        //排序类型，默认为 ASC
        direction: 'DESC'
    }]
});
/**
 *财务首页数据源
 * @type {Ext.data.Store}
 */
var caiwuListStore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.finance.list',
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
        url : 'index.php?action=ExtFinance&mode=searchCaiwuListJosn',

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
 * 个税查看数据源
 * @type {Ext.data.Store}
 */
var taxstore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.tax.list',
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
        url : 'index.php?action=ExtFinance&mode=comTaxListJosn',

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

/**
 * 客服首页数据源
 * @type {Ext.data.Store}
 */
var serviceManagestore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.service.list',
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
        url : 'index.php?action=ExtService&mode=searchComListJosn',

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
 * 变更业务公司列表
 */
var businessLogstore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.socialsecurity.business',
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
        url : 'index.php?action=ExtSocialSecurity&mode=searchBusinessInfoListJson',

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
 * 社保首页store
 */
var socialsecurityInfostore = Ext.create('Ext.data.Store', {
    //分页大小
    pageSize: 50,
    model: 'oa.common.socialsecurity.socialsecurityinfo',
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
        url : 'index.php?action=ExtSocialSecurity&mode=searchSocialsecurityInfoList',

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
