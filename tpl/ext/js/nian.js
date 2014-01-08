Ext.define('oa.common.salNianTime.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'salaryTime', type: 'string'},
        {name: 'op_salaryTime', type: 'time'},
        {name: 'salary_state', type: 'int'}
    ]
});


//FIXME!!!
//年终奖数据源
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
