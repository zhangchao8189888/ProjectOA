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
        {name: 'daikou', type: 'string'},
        {name: 'bukou', type: 'string'},
        {name: 'nian', type: 'string'},
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
        {name: 'company_address', type: 'string'}
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
Ext.define('oa.common.geshui.detail.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'company_id', type: 'int'},
        {name: 'ename', type: 'string'},
        {name: 'e_num', type: 'string'},
        {name: 'salaryTime', type: 'string'},
        {name: 'companyname', type: 'string'},
        {name: 'daikou', type: 'string'},
        {name: 'bukou', type: 'string'},
        {name: 'nian', type: 'string'},
        {name: 'geshuiSum', type: 'int'}
    ]
});

Ext.define('oa.common.caiwuManageCom.list',{
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

/**
 * 财务首页model
 */
Ext.define('oa.common.finance.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'sal_date', type: 'time'},
        {name: 'sal_state', type: 'string'},
        {name: 'bill_state', type: 'string'},
        {name: 'cheque_state', type: 'string'},
        {name: 'cheque_account', type: 'string'},
        {name: 'sal_approve', type: 'string'}
    ]
});

/**
 * 个税查看model
 */
Ext.define('oa.common.tax.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'company_name', type: 'string'},
        {name: 'january', type: 'string'},
        {name: 'february ', type: 'string'},
        {name: 'march', type: 'string'},
        {name: 'april ', type: 'string'},
        {name: 'may ', type: 'string'},
        {name: 'june', type: 'string'},
        {name: 'july', type: 'string'},
        {name: 'august ', type: 'string'},
        {name: 'septmber', type: 'string'},
        {name: 'october', type: 'string'},
        {name: 'november', type: 'string'},
        {name: 'december', type: 'string'}
    ]
});

/**
 * 客服首頁model
 */
Ext.define('oa.common.service.list', {
    extend: 'Ext.data.Model',
    fields: ['salDate', 'op_salaryTime', 'company_name','companyId',
        'salStat','salTimeid','fa_state','salNianStat',
        'salOrStat','fastat','opTime']
});
