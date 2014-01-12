/**
 *客服管理公司list
 */
Ext.define('oa.service.company.adminList', {
        extend: 'Ext.data.Model',
        fields: ['salDate', 'op_salaryTime', 'company_name','companyId',
            'salStat','salTimeid','fa_state','salNianStat',
            'salOrStat','fastat','opTime']
});

/**
 * 客户公司list
 */
Ext.define('oa.common.company.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'}
    ]
});

//BY孙瑞鹏
Ext.define('oa.common.geshui.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'company_id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'salaryTime', type: 'string'},
        {name: 'geshuiSum', type: 'int'},
        {name: 'salary_state', type: 'int'}
    ]
});
//BY孙瑞鹏
Ext.define('oa.common.geshuitype.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'geshui_dateType', type: 'string'},
        {name: 'salary_state', type: 'int'}
    ]
});

/**
 * 审核公司数据源
 */
Ext.define('oa.common.checkcompany.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'company_address', type: 'string'},
    ]
});

/**
 * id	int(11)	No
 companyId	int(11)	No
 salaryTime	date	No
 op_salaryTime	datetime	No
 op_id	int(11)	Yes
 salary_state	int(2)	No	0
 salary_leijiyue	float(11,2)	Yes
 mark
 */
Ext.define('oa.common.salTime.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'salaryTime', type: 'string'},
        {name: 'op_salaryTime', type: 'time'},
        {name: 'salary_state', type: 'int'}
    ]
});

/**
 * 年终奖model
 */
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

Ext.define('oa.common.otherSalTime.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'salaryTime', type: 'string'},
        {name: 'op_salaryTime', type: 'time'},
        {name: 'salary_state', type: 'int'},
        {name: 'salaryType', type: 'int'}
    ]
});
