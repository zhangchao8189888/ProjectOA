<?php
//DB define
//session
require_once( 'lib/db.inc' );
require_once( 'lib/functions.inc' );
require_once( 'lib/log.class.inc' );
require_once( 'lib/EC.class.inc' );

/* define('DB_HOST','qdm-001.hichina.com');
define('DB_NAME','qdm0010465_db');
define('DB_USER','qdm0010465');
define('DB_PASS','l2x2y0t5o8');*/
define('DB_HOST','localhost');
define('DB_NAME','company_OA');
define('DB_USER','root');
define('DB_PASS','');
define('UPLOADPATH','upload/');
define('DUIBIUPLOADPATH','upload/duiBi/');
//define('UPLOADPATH','$(UPLOADPATH)');
define('UPLOAD_FILE_NAME','upload.');
//分页一页长度
define('PAGE_SIZE',40);
//设置管理员类型
define('ADMIN_TYPE_SYS',1);
define('ADMIN_TYPE_MON',2);
/**
 * 操作日志
 */

//
define('OP_LOG_LOGIN','管理员登录');
define('OP_LOG_DELETE_ADMIN','删除管理员');
//操作日志添加管理员
define('OP_LOG_ADD_ADMIN','添加管理员');
define('OP_LOG_ADD_EMPLOY','添加员工');
define('OP_LOG_IMPORT_EMPLOY','导入员工');
define('OP_LOG_UPDATE_EMPLOY','修改员工');
define('OP_LOG_DEL_EMPLOY','删除员工');
define('OP_LOG_DEL_COMPANY','删除公司');
define('OP_LOG_UPLOAD_FILE','上传文件');
/**
 * 工资
 */
define('OP_LOG_SAVE_SALARY','保存工资');
define('OP_LOG_SAVE__NIAN_SALARY','保存年终奖');
define('OP_LOG_SAVE__ER_SALARY','保存二次工资');
define('OP_LOG_DEL_SALARY','删除工资');
define('OP_LOG_DEL_NIAN_SALARY','删除年终奖');
define('OP_LOG_UPDATE_PER_SALARY','修改个人工资');
define('OP_LOG_SEND_SALARY','工资发放');
define('OP_LOG_ADD_BILL_INVOICE','添加发票');
define('OP_LOG_ADD_BILL_ZHI','添加支票');
define('OP_LOG_ADD_BILL_ZHIDAO','添加支票到账');
define('ERROR_LOG_PATH','log/');
//设置年终奖类别
define('SALARY_TIME_TYPE',5);
//设置二次工资类别
define('ER_SALARY_TIME_TYPE',6);
global $userType;
$userType=array(
   

);
global $billType;
$billType=array(
    '发票'=>'1',
	'支票'=>'2',
	'到账支票'=>'3',
    '工资发放'=>'4',
);
global $billState;
$billState=array(
    '0'=>'工资已保存',
    '1'=>'发票已开',
	'2'=>'支票已取',
	'3'=>'资金已经到账',
	'4'=>'工资已发放',
);
//发放类型
global $faSalState;
$salState=array(
    '0'=>'未批准发放',
    '1'=>'批准发放',
);
//工资类型
global $salState;
$salState=array(
    '0'=>'工资以保存',
    '1'=>'工资以修改',
);
//员工类型
global $employState;
$employState=array(
    'zhengchang'=>0,
    'lizhi'=>1,
    'hetongdaoqi'=>2,
    '1'=>'正常',
    '2'=>'离职',
    '3'=>'合同到期',
);
//客服工作流程类型
global $serviceState;
$serviceState=array(
    '1'=>'导入员工',
    '2'=>'做工资表',
    '3'=>'添加发票',
    '4'=>'添加支票',
    '5'=>'申请发放',
);
//工资表字段
global $salaryTable;
$salaryTable=array(
    '部门' =>'部门',
    '姓名' =>'姓名',
    '身份证号' =>'身份证号',
    '基本工资' =>'基本工资',
    '考核工资' =>'考核工资',
    '职务工资' =>'职务工资',
    '银行卡号' =>'银行卡号',
    '身份类别' =>'身份类别',
    '社保基数' =>'社保基数',
    '公积金基数' =>'公积金基数',
    'per_yingfaheji' =>'个人应发合计',
  	'per_shiye' =>'个人失业',
  	'per_yiliao' =>'个人医疗',
  	'per_yanglao' =>'个人养老',
  	'per_gongjijin' =>'个人公积金',
  	'per_daikoushui' =>'代扣税',
  	'per_koukuangheji' =>'个人扣款合计',
  	'per_shifaheji' =>'实发合计',
  	'com_shiye' =>'单位失业',
  	'com_yiliao' =>'单位医疗',
  	'com_yanglao' =>'单位养老',
  	'com_gongshang' =>'单位工伤',
  	'com_shengyu' =>'单位生育',
  	'com_gongjijin' =>'单位公积金',
  	'com_heji' =>'单位合计',
  	'laowufei' =>'劳务费',
  	'canbaojin' =>'残保金',
  	'danganfei' =>'档案费',
  	'paysum_zhongqi' =>'交中企基业合计',
);

//工资表字段
global $salNianTable;
$salNianTable=array(
    'comName' => '单位',
    'e_name' => '姓名',
    'employid' => '身份证号',
    'nianzhongjiang' => '年终奖',
    'nian_daikoushui' => '年终奖代扣税',
    'yingfaheji' => '当月应发合计',
    'shifajinka' => '实发进卡',
    'jiaozhongqi' => '缴纳中企基业合计',
);

// 个税字段
global $salaryTypeTable;
$salaryTypeTable=array(
		'company_id' =>'个人编号',
		'ename' =>'姓名',
		'e_num' =>'身份证号',
		'salaryTime' =>'个税日期',
		'companyname' =>'所在单位',
		'geshuiSum' =>'个税合计',
);

global $salErTable;
$salErTable=array(
        'dangyueyingfa' =>
        array(
        	'key'=>'当月发放工资',
            'width'=>'',
        ),
        'ercigongziheji' =>
        array(
        	'key'=>'二次工资合计',
            'width'=>'',
        ),
        'yingfaheji' =>array(
        	'key'=>'实际应发合计',
            'width'=>'',
        ),
        'shiye' =>array(
        	'key'=>'失业',
            'width'=>'',
        ),
        'yiliao' =>array(
        	'key'=>'医疗',
            'width'=>'',
        ),
        'yanglao' =>array(
        	'key'=>'养老',
            'width'=>'',
        ),
        'gongjijin' =>array(
        	'key'=>'公积金',
            'width'=>'',
        ),
        'yingkoushui' =>array(
        	'key'=>'应扣税',
            'width'=>'',
        ),
        'yikoushui' =>array(
        	'key'=>'已扣税',
            'width'=>'',
        ),
        'bukoushui' =>array(
        	'key'=>'补扣税',
            'width'=>'',
        ),
        'jinka' =>array(
        	'key'=>'双薪进卡',
            'width'=>'',
        ),
        'jiaozhongqi' =>array(
        	'key'=>'交中企基业合计',
            'width'=>'',
        ),
);

//业务状态字段
global $businessState;
$businessState=array(
    '1'=>'等待处理',
    '2'=>'正在办理',
    '3'=>'已完成',
);

?>
