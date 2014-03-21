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
        {name: 'daikou', type: 'double'},
        {name: 'bukou', type: 'double'},
        {name: 'geshuiSum', type: 'double'},
        {name: 'salary_state', type: 'int'}
    ]
});
//BY孙瑞鹏
Ext.define('oa.common.nianjiang.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'companyid', type: 'int'},
        {name: 'companyname', type: 'string'}
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

//BY孙瑞鹏
Ext.define('oa.common.canjirenType.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'emp_name', type: 'string'},
        {name: 'emp_num', type: 'string'},
        {name: 'company_name', type: 'string'},
        {name: 'canjiren_Type', type: 'string'}

    ]
});
//BY孙瑞鹏
Ext.define('oa.common.canjirenXiangxi.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id1', type: 'int'},
        {name: 'emp_name1', type: 'string'},
        {name: 'emp_num1', type: 'string'},
        {name: 'company_name1', type: 'string'},
        {name: 'canjiren_Type1', type: 'string'}

    ]
});
//BY孙瑞鹏
Ext.define('oa.common.canjirentongji.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'com_id', type: 'int'},
        {name: 'com_name', type: 'string'},
        {name: 'sumCanjiren', type: 'string'}


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


Ext.define('oa.common.salaryTime.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'salaryTime', type: 'string'},
        {name: 'op_salaryTime', type: 'time'},
        {name: 'salary_state', type: 'int'},
        {name: 'sal_approve', type: 'string'},
        {name: 'sal_approve_id', type: 'int'},
        {name: 'fa_state', type: 'string'}
    ]
});

/**
 * 工资统计model
 */
Ext.define('oa.common.salTime.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'salaryTime', type: 'string'},
        {name: 'bill_date', type: 'string'},
        {name: 'bill_value', type: 'string'},
        {name: 'bill_money', type: 'string'},
        {name: 'bill_money_sum', type: 'string'},
        {name: 'cheque_date', type: 'string'},
        {name: 'cheque_money', type: 'string'},
        {name: 'cheque_money_sum', type: 'string'},
        {name: 'cheque_account_date', type: 'string'},
        {name: 'cheque_account_money', type: 'string'},
        {name: 'account_money_sum', type: 'string'},
        {name: 'sum_per_yingfaheji', type: 'string'},
        {name: 'sum_per_shiye', type: 'string'},
        {name: 'sum_per_yiliao', type: 'string'},
        {name: 'sum_per_yanglao', type: 'string'},
        {name: 'sum_per_gongjijin', type: 'string'},
        {name: 'sum_per_daikoushui', type: 'string'},
        {name: 'sum_per_koukuangheji', type: 'string'},
        {name: 'sum_per_shifaheji', type: 'string'},
        {name: 'sum_com_shiye', type: 'string'},
        {name: 'sum_com_yiliao', type: 'string'},
        {name: 'sum_com_yanglao', type: 'string'},
        {name: 'sum_com_gongshang', type: 'string'},
        {name: 'sum_com_shengyu', type: 'string'},
        {name: 'sum_com_gongjijin', type: 'string'},
        {name: 'sum_com_heji', type: 'string'},
        {name: 'sum_paysum_zhongqi', type: 'string'},
        {name: 'this_month_yue', type: 'string'},
        {name: 'sum_yue', type: 'string'},
        {name: 'state', type: 'string'}
    ]
});

Ext.define('oa.common.salarycom.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'mouth1', type: 'string'},
        {name: 'mouth2', type: 'string'},
        {name: 'mouth3', type: 'string'},
        {name: 'mouth4', type: 'string'},
        {name: 'mouth5', type: 'string'},
        {name: 'mouth6', type: 'string'},
        {name: 'mouth7', type: 'string'},
        {name: 'mouth8', type: 'string'},
        {name: 'mouth9', type: 'string'},
        {name: 'mouth10', type: 'string'},
        {name: 'mouth11', type: 'string'},
        {name: 'mouth12', type: 'string'} ,
        {name: 'nian', type: 'string'}
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
        {name: 'geshuiSum', type: 'double'}
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
//员工列表BY孙瑞鹏
Ext.define('oa.common.yuangong.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'e_name', type: 'string'},
        {name: 'e_company', type: 'string'},
        {name: 'e_num', type: 'string'},
        {name: 'bank_name', type: 'string'},
        {name: 'bank_num', type: 'string'},
        {name: 'e_type', type: 'string'},
        {name: 'shebaojishu', type: 'int'},
        {name: 'gongjijinjishu', type: 'int'},
        {name: 'laowufei', type: 'int'},
        {name: 'canbaojin', type: 'int'},
        {name: 'danganfei', type: 'int'},
        {name: 'memo', type: 'string'}
    ]
});
//增减员BY孙瑞鹏
Ext.define('oa.common.zengjian.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'submitTime', type: 'string'},
        {name: 'CName', type: 'string'},
        {name: 'Dept', type: 'string'},
        {name: 'EName', type: 'string'},
        {name: 'EmpNo', type: 'string'},
        {name: 'EmpType', type: 'string'},
        {name: 'zengjianbiaozhi', type: 'string'},
        {name: 'shebaojishu', type: 'string'},
        {name: 'gongjijinjishu', type: 'string'},
        {name: 'gongjijinsum', type: 'double'},
        {name: 'waiquzhuanru', type: 'string'},
        {name: 'sum', type: 'double'},
        {name: 'danweijishu', type: 'string'},
        {name: 'caozuoren', type: 'string'},
        {name: 'updateTime', type: 'string'},
        {name: 'shenbaozhuangtai', type: 'string'},
        {name: 'beizhu', type: 'string'},
        {name: 'tel', type: 'string'}
    ]
});

//发票列表BY孙瑞鹏
Ext.define('oa.common.fapiao.list',{
    extend: 'Ext.data.Model',
    fields: [

        {name: 'bill_no', type: 'string'},
        {name: 'salaryTime', type: 'string'},
        {name: 'company_name', type: 'string'},
        {name: 'bill_value', type: 'int'}

    ]
});

//到账列表BY孙瑞鹏
Ext.define('oa.common.daozhang.list',{
    extend: 'Ext.data.Model',
    fields: [

        {name: 'daozhangTime', type: 'string'},
        {name: 'cname', type: 'string'},
        {name: 'daozhangValue', type: 'inxxt'}
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
        {name: 'sal_id', type: 'string'},
        {name: 'sal_date', type: 'time'},
        {name: 'sal_state', type: 'string'},
        {name: 'bill_state', type: 'string'},
        {name: 'cheque_account', type: 'string'},
        {name: 'sal_approve', type: 'string'},
        {name: 'sal_approve_id', type:'string'}
    ]
});

/**
 * 个税查看model
 */
Ext.define('oa.common.tax.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'mouth1', type: 'string'},
        {name: 'mouth2', type: 'string'},
        {name: 'mouth3', type: 'string'},
        {name: 'mouth4', type: 'string'},
        {name: 'mouth5', type: 'string'},
        {name: 'mouth6', type: 'string'},
        {name: 'mouth7', type: 'string'},
        {name: 'mouth8', type: 'string'},
        {name: 'mouth9', type: 'string'},
        {name: 'mouth10', type: 'string'},
        {name: 'mouth11', type: 'string'},
        {name: 'mouth12', type: 'string'},
        {name: 'mouth13', type: 'string'}
    ]
});
/**
 * 工资查看model
 */
Ext.define('oa.common.gongzibiao.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'mouth1', type: 'string'},
        {name: 'mouth2', type: 'string'},
        {name: 'mouth3', type: 'string'},
        {name: 'mouth4', type: 'string'},
        {name: 'mouth5', type: 'string'},
        {name: 'mouth6', type: 'string'},
        {name: 'mouth7', type: 'string'},
        {name: 'mouth8', type: 'string'},
        {name: 'mouth9', type: 'string'},
        {name: 'mouth10', type: 'string'},
        {name: 'mouth11', type: 'string'},
        {name: 'mouth12', type: 'string'},
        {name: 'mouth13', type: 'string'}
    ]
});

/**
 * 客服首頁model
 */
Ext.define('oa.common.service.list', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'company_name', type: 'string'},
        {name: 'company_id', type: 'int'},
        {name: 'salDate', type: 'string'},
        {name: 'op_salaryTime', type: 'string'},
        {name: 'companyId', type: 'string'},
        {name: 'salStat', type: 'string'},
        {name: 'salTimeid', type: 'string'},
        {name: 'fa_state', type: 'string'},
        {name: 'salNianStat', type: 'string'},
        {name: 'salOrStat', type: 'string'},
        {name: 'fastat', type: 'string'},
        {name: 'opTime', type: 'string'}
    ]

});

/**
 * 业务变更model
 */
Ext.define('oa.common.socialsecurity.business', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'submitTime', type: 'string'},
        {name: 'updateTime', type: 'date'},
        {name: 'companyId', type: 'int'},
        {name: 'companyName', type: 'string'},
        {name: 'employId', type: 'string'},
        {name: 'employName', type: 'string'},
        {name: 'employStateId', type: 'int'},
        {name: 'employState', type: 'string'},
        {name: 'serviceId', type: 'int'},
        {name: 'serviceName', type: 'string'},
        {name: 'adminId', type: 'int'},
        {name: 'adminName', type: 'string'},
        {name: 'businessName', type: 'string'},
        {name: 'remarks', type: 'string'},
        {name: 'updateTime', type: 'string'},
        {name: 'socialSecurityStateId', type: 'int'},
        {name: 'socialSecurityState', type: 'string'} ,
        {name: 'reimbursementTime', type: 'string'},
        {name: 'reimbursementValue', type: 'string'},
        {name: 'accountTime', type: 'string'},
        {name: 'accountValue', type: 'string'},
        {name: 'grantTime', type: 'int'},
        {name: 'grantValue', type: 'string'},
        {name: 'retireTime', type: 'string'},
        {name: 'accountComTime', type: 'string'},
        {name: 'accountComValue', type: 'string'},
        {name: 'accountPersonTime', type: 'string'},
        {name: 'accountPersonValue', type: 'int'} ,
        {name: 'tel', type: 'int'}
    ]

});

/**
 *社保首页model
 */
Ext.define('oa.common.socialsecurity.socialsecurityinfo', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'mattername', type: 'string'},
        {name: 'matterWait', type: 'string'},
        {name: 'matterDoing', type: 'string'},
        {name: 'matterClear', type: 'string'}
    ]

});

/**
 * 保险
 */
Ext.define('oa.common.socialsecurity.insurance', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'submitTime', type: 'string'},
        {name: 'companyName', type: 'string'},
        {name: 'employId', type: 'string'},
        {name: 'employName', type: 'string'},
        {name: 'idClass', type: 'string'},
        {name: 'serviceId', type: 'string'},
        {name: 'serviceName', type: 'string'},
        {name: 'adminId', type: 'string'},
        {name: 'adminName', type: 'string'},
        {name: 'base', type: 'string'},
        {name: 'paymentStartTime', type: 'string'},
        {name: 'paymentEndTime', type: 'string'},
        {name: 'paymentTime', type: 'string'},
        {name: 'paymentValue', type: 'float'},
        {name: 'paymentType', type: 'string'},
        {name: 'remark', type: 'string'},
        {name: 'unInsuranceReason', type: 'string'},
        {name: 'explainInfo', type: 'string'},
        {name: 'entryTime', type: 'string'},
        {name: 'tel', type: 'string'}
    ]

});

/**
 * 员工信息列表
 */
Ext.define('oa.common.employ.list',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'e_name', type: 'string'},
        {name: 'e_company', type: 'string'},
        {name: 'e_num', type: 'string'},
        {name: 'bank_name', type: 'string'},
        {name: 'bank_num', type: 'string'},
        {name: 'e_type', type: 'string'},
        {name: 'shebaojishu', type: 'int'},
        {name: 'gongjijinjishu', type: 'int'},
        {name: 'laowufei', type: 'int'},
        {name: 'canbaojin', type: 'int'},
        {name: 'danganfei', type: 'int'},
        {name: 'e_hetongnian', type: 'int'},
        {name: 'e_hetong_date', type: 'string'},
        {name: 'memo', type: 'string'}
    ]
});
