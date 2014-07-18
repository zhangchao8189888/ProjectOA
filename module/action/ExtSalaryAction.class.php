<?php
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/EmployDao.class.php");
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
require_once("tools/sumSalary.class.php");
require_once("tools/Classes/PHPExcel.php");
class ExtSalaryAction extends BaseAction{
    /*
     *
     * @param $actionPath
     * @return SalaryAction
     */
    function ExtSalaryAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new ExtSalaryForm();
        $this->actionPath = $actionPath;
    }
    function dispatcher(){
        // (1) mode set
        $this->setMode();
        // (2) COM initialize
        $this->initBase($this->actionPath);
        // (3)验证SESSION是否过期
        //$this->checkSession();
        // (4) controll -> Model
        $this->controller();
        // (5) view
        $this->view();
        // (6) closeConnect
        $this->closeDB();
    }
    function setMode()
    {
        // 模式设定
        $this->mode = $_REQUEST['mode'];
    }
    function controller()
    {
        switch($this->mode) {
            case "searchSalaryTimeListJosn" :
                $this->searchSalaryTimeListJosn();
                break;
            case "searchSalaryTimeApprovalListJosn":
                $this->searchSalaryTimeApprovalListJosn();
                break;
            case "searchSalaryTongji":
                $this->searchSalaryTongji();
                break;
            case "searchLeijiYueByTimeId":
                $this->searchLeijiYueByTimeId();
                break;
            case "searchErSalaryTimeListJosn":
                $this->searchErSalaryTimeListJosn();
                break;
            case "searhSalaryNianTimeListJosn":
            	$this->searhSalaryNianTimeListJosn();
            case "searchGeshuiListJosn" :
            	$this->searchGeshuiListJosn();
            	break;
            case "searchFapiaoTypeJosn" :
                $this->searchFapiaoTypeJosn();
                break;
            case "searchDaozhangTypeJosn" :
                $this->searchDaozhangTypeJosn();
                break;
            case "searchZengjianJosn" :
                $this->searchZengjianJosn();
                break;
            case "searchGeshuiTypeJosn" :
            	$this->searchGeshuiTypeJosn();
            	break;
            case "searchGongsijibie" :
                $this->searchGongsijibie();
                break;
            case "searchErjigongsi" :
                $this->searchErjigongsi();
                break;
            case "deleteZengjianyuan" :
            	$this->deleteZengjianyuan();
            	break;
            case "searchEmploy":
                $this->searchEmploy();
                break;
            case "searchCanjirenType":
                $this->searchCanjirenType();
                break;
            case "getCnameListExt":
                 $this->getCnameListExt();
                  break;
            case "getCanjirenListExt":
                 $this->getCanjirenListExt();
                  break;
            case "searchCanjirenXiangxi":
                 $this->searchCanjirenXiangxi();
                  break;
            case "delSalayByTimeId":
                $this->delSalayByTimeId();
                break;
            case "upload":
                $this->salaryUpload();
                break;
            case "accountList":
                $this->accountList();
                break;
            case "accountListByComId":
                $this->accountListByComId();
                break;
            case "importAccounts":
                $this->importAccounts();
                break;
            case "selectExpenses":
                $this->selectExpenses();
                break;
            case "updateAccount":
                $this->updateAccount();
                break;
            case "getImportAccountsTemplate":
                $this->getImportAccountsTemplate();
                break;
            case "delAccountsById":
                $this->delAccountsById();
                break;
            default :
                $this->modelInput();
                break;

        }



    }


    /**
     * 查询年终奖集合
     */
    function searhSalaryNianTimeListJosn() {
    	$this->objDao=new SalaryDao();
    	$start=$_REQUEST['start'];
    	$limit=$_REQUEST['limit'];
    	$sorts=$_REQUEST['sort'];
    	$dir=$_REQUEST['dir'];
    	$companyName=$_REQUEST['companyName'];
    	$salTime=$_REQUEST['salTime'];
    	$opTime=$_REQUEST['opTime'];
    	
    	if(!$start){
    		$start=0;
    	}
    	if(!$limit){
    		$limit=50;
    	}
        $where=array();
        if($salTime){
            $timesal=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$timesal["month"];
        }
        if($opTime) {
            $time=$this->AssignTabMonth($opTime,0);
            $where['op_salaryTime']=$time["next"];
            $where['op_time']   =   $time["data"];
        }
    	$where['companyName']=$companyName;
    	$sum =$this->objDao->searhSalaryNianTimeListCount($where);
    	$salaryNianTimeList=$this->objDao->searhSalaryNianTimeListPage($start,$limit,$sorts." ".$dir,$where);
    	$josnArray=array();
    	$josnArray['total']=$sum;
    	$i=0;
    	while ($row=mysql_fetch_array($salaryNianTimeList) ){
    		$josnArray['items'][$i]['id']=$row['id'];
    		$josnArray['items'][$i]['company_name']=$row['company_name'];
    		$josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
    		$josnArray['items'][$i]['op_salaryTime']=$row['op_salaryTime'];
    		$i++;
    	}
    	echo json_encode($josnArray);
    	exit;
    }
    
    
    /**
     * 查询工资日期集合
     */
    function searchSalaryTimeListJosn(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $salTime=$_REQUEST['salTime'];
        $opTime=$_REQUEST['opTime'];
        $e_sal_approve  =   $_REQUEST['e_sal_approve'];
        $e_fa_state  =   $_REQUEST['e_fa_state'];
        $where['e_sal_approve']=$e_sal_approve;
        $where['e_fa_state']=$e_fa_state;
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where=array();
        if($opTime) {
            $time=$this->AssignTabMonth($opTime,0);
            $where['op_salaryTime']=$time["next"];
            $where['op_time']   =   $time["data"];
        }
        $where['salaryTime']=$salTime;
        if($salTime) {
            $time=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$time["month"];
        }
        $where['companyName']=$companyName;
        $sum =$this->objDao->searhSalaryTimeListCount($where);
        $salaryTimeList=$this->objDao->searhSalaryTimeListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $results = $this->objDao->searchByComIdAndSalTime($row['companyId'], $row['salaryTime']);
            if (!$results) {
                $josnArray ['items'] [$i]['fa_state'] = -1;
                $josnArray ['items'] [$i] ['sal_approve'] = -1;
                $result['id'] = 0;
            } else {
                $josnArray ['items'] [$i]['fa_state'] = -1;
                $josnArray ['items'] [$i] ['sal_approve'] = -1;
                $josnArray ['items'] [$i]['salStat'] = $results['id'];
                $josnArray ['items'] [$i]['salTimeid'] = $results['id'];
                $this->billInfo = new SalaryDao();
                $bill_fa = $this->billInfo->searchBillBySalaryTimeId($results['id'], 4);
                if ($bill = mysql_fetch_array($bill_fa)) {
                    $josnArray ['items'] [$i] ['sal_approve_id'] =$bill ['id'];
                    if($bill['bill_value']){
                        $josnArray ['items'] [$i]['fa_state'] = $bill['bill_value'];
                        if ($bill ['bill_type'] == '4') {
                            $josnArray ['items'] [$i] ['sal_approve'] =$bill ['bill_value'];
                        }
                    }
                }
            }
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['company_name']=$row['company_name'];
            $josnArray['items'][$i]['salaryTime']=date("Y-m-d", strtotime($row['salaryTime']));
            $josnArray['items'][$i]['op_salaryTime']=date("Y-m-d", strtotime($row['op_salaryTime']));
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }

    function searchSalaryTimeApprovalListJosn(){
        $where=array();
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $where['companyName']=$_REQUEST['companyName'];
        $salTime=$_REQUEST['salTime'];
        $opTime=$_REQUEST['opTime'];
        $where['e_sal_approve']  =   $_REQUEST['e_sal_approve'];
        $where['bill_value']    =    $_REQUEST['e_bill_value'];
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        if($opTime) {
            $time=$this->AssignTabMonth($opTime,0);
            $where['op_salaryTime']=$time["next"];
            $where['op_time']   =   $time["data"];
        }
        if($salTime) {
            $time=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$time["month"];
        }
        $sum =$this->objDao-> searchSalaryTimeApprovalCount($where);
        $results=$this->objDao->searchSalaryTimeApprovalPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        while ($row = mysql_fetch_array($results)) {
            $salaryHejiList = $this->objDao->searchSumSalaryListBy_SalaryTimeId ( $row ['id'] );
            while ( $rowheji = mysql_fetch_array ( $salaryHejiList ) ) {
                $josnArray['items'][$i]['sum_per_shifaheji']=$rowheji['sum_per_shifaheji'];
                $josnArray['items'][$i]['sum_per_daikoushui']=$rowheji['sum_per_daikoushui'];
                $josnArray['items'][$i]['sum_paysum_zhongqi']=$rowheji['sum_paysum_zhongqi'];
            }
            $josnArray['items'][$i]['bill_value'] = $row['bill_value'];
            $josnArray['items'][$i]['sal_approve_id'] = $row['billid'];
            $josnArray['items'][$i]['id'] = $row['id'];
            $josnArray['items'][$i]['company_name'] = $row['company_name'];
            $josnArray['items'][$i]['salaryTime'] = date("Y-m-d", strtotime($row['salaryTime']));
            $josnArray['items'][$i]['op_salaryTime'] = date("Y-m-d", strtotime($row['op_salaryTime']));
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
    /**
     * 工资统计Ext
     */
    function searchSalaryTongji(){
        $this->objDao=new SalaryDao();
        $comId  =   $_POST['comid'];
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];

        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $salaryTimeList = $this->objDao->searchSalaryListByComId ( $comId, 1 ); // 1什么也不代表
        $sum =$this->objDao->searchSalaryListCountByComId( $comId, 1);
        $josnArray=array();
        $josnArray['total']=$sum;
        $leiji = 0.0;
        $i = 0;

        while ( $row = mysql_fetch_array ( $salaryTimeList ) ) {
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['salaryTime']=$row['salaryTime'];

            //发票部分
            $salaryFaList = $this->objDao->searchBillBySalaryTimeId ( $row ['id'], 1 );
            $sumvalue_fa = 0.0;
            while ( $rowbill = mysql_fetch_array ( $salaryFaList ) ) {
                $josnArray['items'][$i]['bill_date']=$rowbill['bill_date'];
                $josnArray['items'][$i]['bill_value']=$rowbill['bill_item'];
                $josnArray['items'][$i]['bill_money']=$rowbill['bill_value'];
                $sumvalue_fa += $rowbill ['bill_value'];
            }
            $josnArray['items'][$i]['bill_money_sum']= $sumvalue_fa;

            //支票部分
            $salaryChequeList = $this->objDao->searchBillBySalaryTimeId ( $row ['id'], 2 );
            $sumvalue_cheque = 0.0;
            $josnArray['items'][$i]['cheque_date']="<span style=\"color: blue\">没有支票</span>";
            $josnArray['items'][$i]['cheque_money']="<span style=\"color: blue\">没有支票</span>";
            while ( $rowcheque = mysql_fetch_array ( $salaryChequeList ) ) {
                $josnArray['items'][$i]['cheque_date']=$rowcheque['bill_date'];
                $josnArray['items'][$i]['cheque_money']=$rowcheque['bill_value'];
                $sumvalue_cheque += $rowcheque ['bill_value'];
            }
            $josnArray['items'][$i]['cheque_money_sum']=$sumvalue_cheque;

            //支票到账部分
            $sumvalue_chequeaccount = 0.0;
            $salaryChequeAccountList = $this->objDao->searchBillBySalaryTimeId ( $row ['id'], 3 );
            while ( $rowchequeaccount = mysql_fetch_array ( $salaryChequeAccountList ) ) {
                $josnArray['items'][$i]['cheque_account_date']=$rowchequeaccount['bill_date'];
                $josnArray['items'][$i]['cheque_account_money']=$rowchequeaccount['bill_value'];
                $sumvalue_chequeaccount += $rowchequeaccount ['bill_value'];
            }
            $josnArray['items'][$i]['account_money_sum']=$sumvalue_chequeaccount;

            //合计查询部分
            $yu_e=0.0;
            $salaryHejiList = $this->objDao->searchSumSalaryListBy_SalaryTimeId ( $row ['id'] );
            while ( $rowheji = mysql_fetch_array ( $salaryHejiList ) ) {
                $josnArray['items'][$i]['sum_per_yingfaheji']=$rowheji['sum_per_yingfaheji'];
                $josnArray['items'][$i]['sum_per_shiye']=$rowheji['sum_per_shiye'];
                $josnArray['items'][$i]['sum_per_yiliao']=$rowheji['sum_per_yiliao'];
                $josnArray['items'][$i]['sum_per_yanglao']=$rowheji['sum_per_yanglao'];
                $josnArray['items'][$i]['sum_per_gongjijin']=$rowheji['sum_per_gongjijin'];
                $josnArray['items'][$i]['sum_per_daikoushui']=$rowheji['sum_per_daikoushui'];
                $josnArray['items'][$i]['sum_per_koukuangheji']=$rowheji['sum_per_koukuangheji'];
                $josnArray['items'][$i]['sum_per_shifaheji']=$rowheji['sum_per_shifaheji'];
                $josnArray['items'][$i]['sum_com_shiye']=$rowheji['sum_com_shiye'];
                $josnArray['items'][$i]['sum_com_yiliao']=$rowheji['sum_com_yiliao'];
                $josnArray['items'][$i]['sum_com_yanglao']=$rowheji['sum_com_yanglao'];
                $josnArray['items'][$i]['sum_com_gongshang']=$rowheji['sum_com_gongshang'];
                $josnArray['items'][$i]['sum_com_shengyu']=$rowheji['sum_com_shengyu'];
                $josnArray['items'][$i]['sum_com_gongjijin']=$rowheji['sum_com_gongjijin'];
                $josnArray['items'][$i]['sum_com_heji']=$rowheji['sum_com_heji'];
                $josnArray['items'][$i]['sum_paysum_zhongqi']=$rowheji['sum_paysum_zhongqi'];
                $josnArray['items'][$i]['sum_yue']=$rowheji['sum_com_heji'];
                $yu_e = $sumvalue_chequeaccount - $rowheji['sum_paysum_zhongqi'];
                if ($row ['salary_leijiyue'] == null) {
                    $yu_e_l = $sumvalue_chequeaccount - $rowheji ['sum_paysum_zhongqi'] + $leiji;
                } else {
                    $yu_e_l = $row ['salary_leijiyue'];
                }
            }

            $leiji = $yu_e_l;
            $yu_e_l = sprintf ( "%01.2f", $yu_e_l );
            $josnArray['items'][$i]['this_month_yue']=$yu_e;
            $josnArray['items'][$i]['sum_yue']=$yu_e_l;

            if ($yu_e == 0) {
                $josnArray['items'][$i]['state']="<span style=\"color: green\">正常</span>";
            } elseif ($yu_e < 0) {
                $josnArray['items'][$i]['state']="<span style=\"color: red\">公司垫付</span>";
            } else {
                $josnArray['items'][$i]['state']="<span style=\"color: blue\">该公司有剩余资金</span>";
            }

            $i++;
        }

        echo json_encode ( $josnArray );
        exit ();
    }

    /**
     * 工资统计余额修改ext
     */
    function searchLeijiYueByTimeId(){
        $yue=$_REQUEST['yue'];
        $updateid=$_REQUEST['updateid'];
        $salarytimeMonth=$_REQUEST['salarytimeMonth'];
        $josnArray=array();
        $josnArray["updateid"] =  $updateid  ;
        $josnArray["yue"]=  $yue  ;
        $josnArray["salarytimeMonth"] =  $salarytimeMonth  ;
        echo json_encode ( $josnArray );
        exit();
    }

    //残疾人统计BY孙瑞鹏
    function getCanjirenListExt(){
        $this->objDao=new SalaryDao();
        $where = $_REQUEST ['cname'];
        $salaryTimeList= $this->objDao->searchCompanyListByCanjiren($where);
        $josnArray=array();
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['com_id']=$row['id'];
            $josnArray['items'][$i]['com_name']=$row['company_name'];
            $josnArray['items'][$i]['sumCanjiren']=$row['sumcanjiren'];
            $josnArray['items'][$i]['sumCanjiren']=$josnArray['items'][$i]['sumCanjiren'].'人';
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
    //是否查询年终奖设置记录BY孙瑞鹏
    function getCnameListExt(){
        $this->objDao=new SalaryDao();
        $where = $_REQUEST ['cname'];
        $type = $_REQUEST ['leixing'];
        $salaryTimeList= $this->objDao->searchCompanyListByName($where,$type);
        $josnArray=array();
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){

            $josnArray['items'][$i]['companyname']=$row['company_name'];
            $josnArray['items'][$i]['companyid']=$row['id'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
   //删除增减员记录BY孙瑞鹏
    function deleteZengjianyuan(){
        $this->objDao=new SalaryDao();
        $ids = $_REQUEST ['ids'];
        $ids=str_replace('\"','"',$ids);
        $ids=json_decode($ids);
        for($h=0;$h<(count($ids));$h++){
        $this->objDao->deleteZengjian($ids[$h]);
        }
        $josnArray=array();
        echo json_encode($josnArray);
        exit;
    }
    //增减员统计BY孙瑞鹏
    function searchZengjianJosn(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $search_type=$_REQUEST['search_type'];
        $STime=$_REQUEST['STime'];
        if($STime) {
            $time1=$this->AssignTabMonth($STime,0);
        }

        $companyName=$_REQUEST['companyName'];
        $zengjian=$_REQUEST['zengjian'];
        $shenbaozhuangtai   =   $_REQUEST['shenbaozhuangtai'];
        $submitTime =   $_REQUEST['submitTime'];
        $ename  =   $_REQUEST['EName'];
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where=array();
        if($submitTime) {
            $time=$this->AssignTabMonth($submitTime,0);
            $where['submitTime']=$time["last"];
        }
        $where['first']=$time1['first'];
        $where['last']=$time1['last'];
        $where['ename']=$ename;
        $where['shenbaozhuangtai']=$shenbaozhuangtai;
        $where['companyName']=$companyName;
        $where['zengjian']=$zengjian;
        $where['search_type']=$search_type;
        $sum =$this->objDao->searhZengjianTongjiPage($where);
        $salaryTimeList=$this->objDao->searhZengjianListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        $sumShebao = 0;
        $sumGongjijin = 0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['submitTime']=$row['submitTime'];
            $josnArray['items'][$i]['CName']=$row['CName'];
            $josnArray['items'][$i]['Dept']=$row['Dept'];
            $josnArray['items'][$i] ['EName'] = $row ['EName'];
            $josnArray['items'][$i]['EmpNo']=$row['EmpNo'];
            $josnArray['items'][$i]['EmpType']=$row['EmpType'];
            $josnArray['items'][$i]['zengjianbiaozhi']=$row['zengjianbiaozhi'];
            $josnArray['items'][$i] ['shebaojishu'] = $row ['shebaojishu'];
            $josnArray['items'][$i]['waiquzhuanru']=$row['waiquzhuanru'];
            $josnArray['items'][$i]['sum']=$row['sum'];
            $josnArray['items'][$i]['danweijishu']=$row['danweijishu'];
            $josnArray['items'][$i]['caozuoren']=$row['caozuoren'];
            $josnArray['items'][$i]['updateTime']=$row['updateTime'];
            $josnArray['items'][$i]['shenbaozhuangtai']=$row['shenbaozhuangtai'];
            $josnArray['items'][$i]['beizhu']=$row['beizhu'];
            $josnArray['items'][$i]['tel']=$row['tel'];
            $josnArray['items'][$i]['gongjijinjishu']=$row['gongjijinjishu'];
            $josnArray['items'][$i]['gongjijinsum']=$row['gongjijinsum'];
            $sumShebao +=  $josnArray['items'][$i]['sum'];
            $sumGongjijin += $josnArray['items'][$i]['gongjijinsum'];
            $i++;
        }
        $josnArray['items'][$i]['sum'] +=  $sumShebao;
        $josnArray['items'][$i]['gongjijinsum'] +=  $sumGongjijin;
        echo json_encode($josnArray);
        exit;
    }
    //发票统计BY孙瑞鹏
    function searchFapiaoTypeJosn(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['comname'];
        $salTime=$_REQUEST['salTime'];
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where=array();
        if($salTime) {
            $time=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$time["month"];
        }
        $where['companyName']=$companyName;
        $sum =$this->objDao->searhFapiaoCount($where);
        $salaryTimeList=$this->objDao->searhFapiaoListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['bill_no']=$row['bill_no'];
            $josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
            $josnArray['items'][$i]['company_name']=$row['company_name'];
            $josnArray['items'][$i] ['bill_value'] = $row ['bill_value'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }

    //到账统计BY孙瑞鹏
    function searchDaozhangTypeJosn(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['comname'];
        $salTime=$_REQUEST['salTime'];
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where=array();
        $where['companyName']=$companyName;
        if($salTime) {
            $time=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$time["month"];
        }
        $sum =$this->objDao->searhDaozhangCount($where);
        $salaryTimeList=$this->objDao->searhDaozhangListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['daozhangTime']=$row['daozhangTime'];
            $josnArray['items'][$i]['cname']=$row['cname'];
            $josnArray['items'][$i]['daozhangValue']=$row['daozhangValue'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }

    //个税统计BY孙瑞鹏
    function searchGeshuiListJosn(){
    	$this->objDao=new SalaryDao();
    	$start=$_REQUEST['start'];
    	$limit=$_REQUEST['limit'];
    	$sorts=$_REQUEST['sort'];
    	$dir=$_REQUEST['dir'];
    	$companyName=$_REQUEST['companyName'];
    	$salTime=$_REQUEST['salTime'];
        $where=array();
    	if(!$start){
    		$start=0;
    	}
    	if(!$limit){
    		$limit=50;
    	}
        if($salTime){
            $time=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$time["month"];
        }
    	$where['companyName']=$companyName;

           if ($where ['salaryTime'] == "") {
                $where ['salaryTime']=date("Y-m");
            }

    	$sum =$this->objDao->searhSalaryTimeCount();
        $salaryNameList=$this->objDao->searchCompanyListByName($companyName,null);
    	$josnArray=array();
    	$josnArray['total']=$sum;
    	$i=0;
        $daisum = 0;
        $busum = 0;
        $zongsum = 0;
    	while ($nameList=mysql_fetch_array($salaryNameList) ){
            $josnArray['items'][$i]['company_id']=$nameList['id'];
            $josnArray['items'][$i]['company_name']=$nameList['company_name'];
            $salaryTimeList=$this->objDao->searhGeshuiListPage($where,$nameList['id']);
            $row=mysql_fetch_array($salaryTimeList);
    		$josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
            $josnArray['items'][$i] ['daikou'] = $row ['daikou'];
            $daisum += $josnArray['items'][$i] ['daikou'];
            $josnArray['items'][$i] ['bukou'] = $row ['bukou'];
            $busum +=  $josnArray['items'][$i] ['bukou'];
    		$josnArray['items'][$i]['geshuiSum']=$row['geshuiSum'];
            $zongsum += $josnArray['items'][$i]['geshuiSum'];
            if(!$josnArray['items'][$i]['salaryTime']){
                $josnArray['items'][$i]['salaryTime']='<span style="color: red">未作工资或免税</span>';
            }

    		$i++;
    	}
        $josnArray['items'][$i] ['daikou']= $daisum;
        $josnArray['items'][$i] ['bukou']= $busum;
        $josnArray['items'][$i]['geshuiSum'] =$zongsum;
        //导出
        $hang=0;
        $salaryListExcel=array();
        $salaryListExcel[$hang][0]="公司编号";
        $salaryListExcel[$hang][1]="单位名称";
        $salaryListExcel[$hang][2]="个税日期";
        $salaryListExcel[$hang][3]="代扣税";
        $salaryListExcel[$hang][4]="补扣税";
        $salaryListExcel[$hang][5]="个税合计";
        $hang++;

        foreach ($josnArray['items'] as $value) {
            $salaryListExcel[$hang][0]=$value['company_id'];
            $salaryListExcel[$hang][1]=$value['company_name'];
            $salaryListExcel[$hang][2]=$value['salaryTime'];
            if( $salaryListExcel[$hang][2]=='<span style="color: red">未作工资或免税</span>'){
                $salaryListExcel[$hang][2]='未作或免税';
            }
            $salaryListExcel[$hang][3]=$value['daikou'];
            $salaryListExcel[$hang][4]=$value['bukou'];
            $salaryListExcel[$hang][5]=$value['geshuiSum'];
            $hang++;

        }
        // var_dump($salaryListExcel);
        session_start();
        $_SESSION['excelListGeshuiBySum']=$salaryListExcel;
    	echo json_encode($josnArray);
    	exit;
    }

    //残疾人详细BY孙瑞鹏
    function searchCanjirenXiangxi(){
        $this->objDao=new SalaryDao();
        $cid=$_REQUEST['cid'];
        $salaryTimeList=$this->objDao->searhCanjirenXiangxi($cid);
        $josnArray=array();
        $josnArray['total']=null;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['id1']=$row['id'];
            $josnArray['items'][$i]['emp_name1']=$row['e_name'];
            $josnArray['items'][$i]['emp_num1']=$row['e_num'];
            $josnArray['items'][$i]['company_name1']=$row['e_company'];
            if($row['e_teshu_state']==0){
                $josnArray['items'][$i]['canjiren_Type1']="非残疾人";
            }
            elseif ($row['e_teshu_state']==1){
                $josnArray['items'][$i]['canjiren_Type1']="残疾人";
            }
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
    //残疾人类型BY孙瑞鹏
    function searchCanjirenType(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $ename=$_REQUEST['ename'];
        $empnum=$_REQUEST['empnum'];

        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where=array();
        $where['empnum']=$empnum;
        $where['ename']=$ename;
        if($empnum!="" || $ename!="" ){
        $salaryTimeList=$this->objDao->searhCanjireniTypePage($start,$limit,$sorts." ".$dir,$where);
        }
        $josnArray=array();
        $josnArray['total']=null;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['emp_name']=$row['e_name'];
            $josnArray['items'][$i]['emp_num']=$row['e_num'];
            $josnArray['items'][$i]['company_name']=$row['e_company'];
            if($row['e_teshu_state']==0){
                $josnArray['items'][$i]['canjiren_Type']="非残疾人";
            }
            elseif ($row['e_teshu_state']==1){
                $josnArray['items'][$i]['canjiren_Type']="残疾人";
            }
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
    //二级公司查询BY孙瑞鹏
    function searchErjigongsi(){
        $superId=$_REQUEST['superId'];
        $this->objDao=new SalaryDao();
        $salaryTimeList=$this->objDao->searhErjigongsi($superId);
        $josnArray=array();
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
                $josnArray['items'][$i]['id']=$row['id'];
                $josnArray['items'][$i]['company_name']=$row['company_name'];
                $josnArray['items'][$i]['company_level']="二级公司";
                $i++;
        }
        echo json_encode($josnArray);
        exit;
    }

    //公司级别查询BY孙瑞鹏
    function searchGongsijibie(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $salTime=$_REQUEST['salTime'];

        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where=array();
        $where['companyName']=$companyName;
        $where['salaryTime']=$salTime;

        $sum =$this->objDao->searhGongsijibieCount($where);

        $salaryTimeList=$this->objDao->searhGongsijibiePage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){

            if($row['company_level']==0){
                $josnArray['items'][$i]['id']=$row['id'];
                $josnArray['items'][$i]['company_name']=$row['company_name'];
                $josnArray['items'][$i]['company_level']="一级公司";
                $josnArray['items'][$i]['geshu']=$row['geshu'];
                $josnArray['items'][$i]['geshu'].='      家';
                $i++;
            }
            else{

            }

        }
        echo json_encode($josnArray);
        exit;
    }
    //个税类型BY孙瑞鹏
    function searchGeshuiTypeJosn(){
    	$this->objDao=new SalaryDao();
    	$start=$_REQUEST['start'];
    	$limit=$_REQUEST['limit'];
    	$sorts=$_REQUEST['sort'];
    	$dir=$_REQUEST['dir'];
    	$companyName=$_REQUEST['companyName'];
    	$salTime=$_REQUEST['salTime'];
    
    	if(!$start){
    		$start=0;
    	}
    	if(!$limit){
    		$limit=50;
    	}
    	$where=array();
    	$where['companyName']=$companyName;
    	$where['salaryTime']=$salTime;
    
    	$sum =$this->objDao->searhSalaryTypeCount($where);
    
    	$salaryTimeList=$this->objDao->searhGeshuiTypePage($start,$limit,$sorts." ".$dir,$where);
    	$josnArray=array();
    	$josnArray['total']=$sum;
    	$i=0;
    	while ($row=mysql_fetch_array($salaryTimeList) ){
    		$josnArray['items'][$i]['id']=$row['id'];
    		$josnArray['items'][$i]['company_name']=$row['company_name'];
    		if($row['geshui_dateType']==1){
    			$josnArray['items'][$i]['geshui_dateType']="本月报本月";
    		}
    		elseif ($row['geshui_dateType']==2){
    			$josnArray['items'][$i]['geshui_dateType']="本月报上月";
    		}	elseif ($row['geshui_dateType']==3){
                $josnArray['items'][$i]['geshui_dateType']="免税公司";
            }
    		$i++;
    	}
    	echo json_encode($josnArray);
    	exit;
    }
     
    function searchErSalaryTimeListJosn(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $salTime=$_REQUEST['salTime'];
        $opTime=$_REQUEST['opTime'];
        $where=array();
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        if($salTime){
            $timesal=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$timesal["month"];
        }
        $where['companyName']=$companyName;
        $where['op_salaryTime']=$opTime;
        $sum =$this->objDao->searhErSalaryTimeListCount($where);
        $salaryTimeList=$this->objDao->searhErSalaryTimeListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['company_name']=$row['company_name'];
            $josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
            $josnArray['items'][$i]['op_salaryTime']=$row['op_salaryTime'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }

    function searchEmploy(){
        $this->objDao=new SalaryDao();
        $employNumber=$_REQUEST['employNumber'];
        $result=$this->objDao->getEmByEno($employNumber);
        echo json_encode($result);
        exit;
    }

    function delSalayByTimeId() {
        $salaryTimeId = $_REQUEST ['timeid'];
        $exmsg = new EC (); // 设置错误信息类
        $this->objDao = new SalaryDao ();
        // 开始事务
        $ids   =   $_POST["ids"];
        $arr=json_decode($ids);
        foreach($arr as $key=>$value){
            $this->objDao->beginTransaction ();
            $salaryList = $this->objDao->searchSalaryTimeBy_id ( $value );
            $result = $this->objDao->delSalaryMovement_BySalaryId ( $value );
            if (! $result) {
                $exmsg->setError ( __FUNCTION__, "del   salaryMovement  faild " );
                // 事务回滚
                $this->objDao->rollback ();
                echo("删除工资动态字段时失败！");
                throw new Exception ( $exmsg->error () );
            }
            $result = $this->objDao->delSalaryBy_TimeId ( $value );
            if (! $result) {
                $exmsg->setError ( __FUNCTION__, "del   salary  faild " );
                // 事务回滚
                $this->objDao->rollback ();
                echo("删除工资固定字段时失败！");
                throw new Exception ( $exmsg->error () );
            }
            $result = $this->objDao->delSalaryTimeBy_Id ( $value );
            if (! $result) {
                $exmsg->setError ( __FUNCTION__, "del   salaryTime  faild " );
                // 事务回滚
                $this->objDao->rollback ();
                echo("删除工资时间表时失败！");
                throw new Exception ( $exmsg->error () );
            }
            $adminPO = $_SESSION ['admin'];
            $opLog = array ();
            $opLog ['who'] = $adminPO ['id'];
            $opLog ['what'] = 0;
            $opLog ['Subject'] = OP_LOG_DEL_SALARY;
            $opLog ['memo'] = $salaryList ['company_name'] . ':' . $salaryList ['salaryTime'];
            $rasult = $this->objDao->addOplog ( $opLog );
            if (! $rasult) {
                $exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
                echo("添加删除工资操作日志失败！");
                // 事务回滚
                $this->objDao->rollback ();
                throw new Exception ( $exmsg->error () );
            }
            // 事务提交
            $this->objDao->commit ();
        }
        echo("操作成功！");
        exit;
    }

    function salaryUpload() {
        $info   =   array();
        $file = $_FILES['photo-path'];
        if($file['type']=='application/vnd.ms-excel'){
                $movefile=   move_uploaded_file($file["tmp_name"],"upload/" . $file["name"]);
            if($movefile){
                $info['success']    =   true;
                $info['message'] = $file["name"];
            }else{
                $info['success']    =   false;
                $info['message'] = $file["tmp_name"].$file["name"];
            }

        }else{
            $info['success']    =   false;
            $info['message'] = "只允许上传.xls文件";
        }
        echo json_encode($info);
        exit;
    }

    function accountList(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $accountsType=$_REQUEST['accountsType'];
        $accountsRemark=$_REQUEST['accountsRemark'];
        $accountDateb=$_REQUEST['transactionDateb'];
        $accountDatea=$_REQUEST['transactionDatea'];
        $where=array();
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where['accountsType']=$accountsType;
        $where['accountsRemark']=$accountsRemark;
        $where['companyName']=$companyName;
        if($accountDateb){
            $time   =   $this->AssignTabMonth($accountDateb,0);
            $where['transactionDateb']=$time["data"];
        }
        if($accountDatea){
            $time   =   $this->AssignTabMonth($accountDatea,0);
            $where['transactionDatea']=$time["data"];
        }

        $sum =$this->objDao->searchAccountListCount($where);
        $salaryTimeList=$this->objDao->searchAccountListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['companyName']=$row['companyName'];
            $josnArray['items'][$i]['accountsType']=$row['accountsType'];
            $com=$this->objDao->searchCompanyByName($row['companyName']);
            $josnArray['items'][$i]['companyId']=$com['id'];
            $josnArray ['items'] [$i]['salaryTime'] =-1;
            if($row['accountsType']==2){
                $josnArray ['items'] [$i]['salType'] = 1;
            }else if($row['accountsType']==1){
                $josnArray ['items'] [$i]['salType'] = 0;
            }

            $josnArray['items'][$i]['transactionDate']=$row['transactionDate'];
            $josnArray['items'][$i]['value']=$row['accountsValue'];
            $josnArray['items'][$i]['accountsType']=$row['accountsType'];
            $josnArray['items'][$i]['accountsRemark']=$row['accountsRemark'];
            $josnArray['items'][$i]['companyBank']=$row['companyBank'];
            $josnArray['items'][$i]['remark']=$row['remark'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
    function accountListByComId() {
        //取得公司名称
        $companyId=$_REQUEST['superId'];
        global $accountType;
        $accountsType=$accountType['收入'];
        $where=array();
        $where['companyId']=$companyId;
        $where['accountsType']=$accountsType;
        //查询支出数据
        $this->objDao=new SalaryDao();
        $accountList=$this->objDao->searchAccountListPage(null,null,null.null,$where);
        $i=0;
        while ($row=mysql_fetch_array($accountList) ){
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['companyName']=$row['companyName'];
            $josnArray['items'][$i]['accountsType']=$row['accountsType'];
            $josnArray['items'][$i]['companyId']=$companyId;
            $time = $this->AssignTabMonth($row['transactionDate'], 0);//查询收入日期月份
            $salTimeInfo = $this->objDao->searhSalaryTimeListByComIdAndDate($time["month"], $companyId);
            if ($salTimeInfo) {
                $salaryHejiList = $this->objDao->searchSumSalaryListBy_SalaryTimeId($salTimeInfo['id']);
                while ($rowheji = mysql_fetch_array($salaryHejiList)) {
                    $josnArray['items'][$i]['shifaheji'] = $rowheji['sum_per_shifaheji'];
                    $josnArray['items'][$i]['daikoushui'] = $rowheji['sum_per_daikoushui'];
                    $josnArray['items'][$i]['shebaoheji'] =
                        $rowheji['sum_per_shiye']+$rowheji['sum_per_yiliao']+
                        $rowheji['sum_per_yanglao']+$rowheji['sum_per_gongjijin']+
                        $rowheji['sum_com_shiye']+$rowheji['sum_com_yiliao']+
                        $rowheji['sum_com_yanglao']+$rowheji['sum_com_gongshang']+
                        $rowheji['sum_com_shengyu']+$rowheji['sum_com_gongjijin'];
                }
                $bill_fa = $this->objDao->searchBillBySalaryTimeId($salTimeInfo['id'], 4);
                if ($bill = mysql_fetch_array($bill_fa)) {
                    if ($bill['bill_value']) {
                        if ($bill['bill_value'] == 0) {
                            $josnArray['items'][$i]['salType'] = '<span style="color: blue">等待审批</span>';
                        } elseif ($bill['bill_value'] == 1) {
                            $josnArray['items'][$i]['salType'] = '<span style="color:green ">审批通过</span>';
                        } elseif ($bill['bill_value'] == 2) {
                            $josnArray['items'][$i]['salType'] = '<span style="color:gray ">审批未通过</span>';
                        }
                    }
                }
            }
            $josnArray['items'][$i]['transactionDate']=$row['transactionDate'];
            $josnArray['items'][$i]['value']=$row['accountsValue'];
            $josnArray['items'][$i]['accountsType']=$row['accountsType'];
            $josnArray['items'][$i]['accountsRemark']=$row['accountsRemark'];
            $josnArray['items'][$i]['companyBank']=$row['companyBank'];
            $josnArray['items'][$i]['remark']=$row['remark'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
    function getAccountListByCompany(){
        $this->objDao=new SalaryDao();
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $where=array();
        $where['accountsType']=1;//收入
        $where['companyName']=$companyName;
        $salaryTimeList=$this->objDao->searchAccountListPage(null,null,$sorts." ".$dir,$where);
        $josnArray=array();
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['companyName']=$row['companyName'];
            $josnArray['items'][$i]['accountsType']=$row['accountsType'];
            $com=$this->objDao->searchCompanyByName($row['companyName']);
            $josnArray['items'][$i]['companyId']=$com['id'];
            $josnArray ['items'] [$i]['salaryTime'] =-1;
            if($row['accountsType']==2){
                $josnArray ['items'] [$i]['salType'] = 1;
            }else if($row['accountsType']==1){
                $josnArray ['items'] [$i]['salType'] = 0;
            }

            $josnArray['items'][$i]['transactionDate']=$row['transactionDate'];
            $josnArray['items'][$i]['value']=$row['accountsValue'];
            $josnArray['items'][$i]['accountsType']=$row['accountsType'];
            $josnArray['items'][$i]['accountsRemark']=$row['accountsRemark'];
            $josnArray['items'][$i]['companyBank']=$row['companyBank'];
            $josnArray['items'][$i]['remark']=$row['remark'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
    function importAccounts() {
        $exmsg = new EC ();
        $filename = $_REQUEST["filename"];
        $info = array();
        $err = Read_Excel_File("upload/" . $filename, $return);
        $info['success'] = true;
        if ($err != 0) {
            $info['success'] = false;
            $info['message'] = '读取表格发生错误！';
        }
        $companyName    =   -1;
        $transactionDate    =  -1;
        $accountsin    =   -1;
        $accountsout    =   -1;
        $remark    =  -1;
        $accountsRemark    =   -1;
        $companyBank    =  -1;
        $accountsYue = -1;
        for ($i = 0; $i < count($return['Sheet1'][0]); $i++) {
            if($return['Sheet1'][0][$i] == "交易日"||$return['Sheet1'][0][$i] == "交易日期" ){
                $transactionDate    =   $i;
            }
            if($return['Sheet1'][0][$i] == "收(付)方名称"||$return['Sheet1'][0][$i] == "单位名称" ){
                $companyName    =   $i;
            }
            if($return['Sheet1'][0][$i] == "收入"||$return['Sheet1'][0][$i] == "借" ){
                $accountsin    =   $i;
            }
            if($return['Sheet1'][0][$i] == "贷"||$return['Sheet1'][0][$i] == "支出"){
                $accountsout    =   $i;
            }
            if($return['Sheet1'][0][$i] == "摘要"||$return['Sheet1'][0][$i] == "备注"){
                $remark    =   $i;
            }
            if($return['Sheet1'][0][$i] == "收(付)方帐号"){
                $companyBank    =   $i;
            }
            if($return['Sheet1'][0][$i] == "交易类型" ){
                $accountsRemark    =   $i;
            }
            if ($return['Sheet1'][0][$i] == "余额" ) {
                $accountsYue    =   $i;
            }
        }
        if($companyName!=-1&&$transactionDate!=-1&&$accountsin!=-1&&$accountsout!=-1&&$remark!=-1&&$remark!=-1&&$accountsRemark!=-1&&$companyBank!=-1&&$accountsYue!=-1){
            $info['message'] = '成功读取表格！';
        }else{
            echo json_encode($info);
            exit;
        }
        $this->objDao = new SalaryDao ();
        //开始事务
        $this->objDao->beginTransaction();
        //查询出当前账户余额
        $presentPo = $this->objDao->searchAccoutValuePresent();
        $yueDuiBi = $presentPo['accountsValue'];
        for ($i = 1; $i < count($return['Sheet1']); $i++) {
            $accountsArray  =   array();
            $accountsArray['companyName']   =  $return['Sheet1'][$i][$companyName] ;
            $com=$this->objDao->searchCompanyByName($accountsArray['companyName']);
            if ($com['id']) {
                $accountsArray['companyId']=$com['id'];
            } else {
                $accountsArray['companyId']=0;
            }
            if ($yueDuiBi) {
                $accountsArray['companyId']=$com['id'];
                if($return['Sheet1'][$i][$accountsout] && $return['Sheet1'][$i][$accountsout] > 0){
                    //更新公司收入金额
                    $accountValue = $yueDuiBi + $return['Sheet1'][$i][$accountsout];

                } elseif ($return['Sheet1'][$i][$accountsin] && $return['Sheet1'][$i][$accountsin] > 0) {
                    $accountValue = $yueDuiBi - $return['Sheet1'][$i][$accountsout];
                }
                if ($accountValue != $return['Sheet1'][$i][$accountsYue]) {
                    $info['message'] = '中企账户余额（'.$accountValue.'）和导入余额（'.$return['Sheet1'][$i][$accountsYue].'）不符';
                    // 事务回滚
                    $this->objDao->rollback ();
                    echo json_encode($info);
                    exit;
                }
                $yueDuiBi = $accountValue;
            }
            $accountsArray['transactionDate']   =  $return['Sheet1'][$i][$transactionDate] ;
            if($return['Sheet1'][$i][$accountsout]){
                $accountsArray['accountsType']   =  1 ;
                $accountsArray['jiaoyiJin']   =  $return['Sheet1'][$i][$accountsout] ;//交易金额
            }else if($return['Sheet1'][$i][$accountsin]){
                $accountsArray['accountsType']   =  2 ;
                $accountsArray['jiaoyiJin']   =  $return['Sheet1'][$i][$accountsin] ;//交易金额
            }

            $accountsArray['remark']   =  $return['Sheet1'][$i][$remark] ;
            $accountsArray['accountsValue']   =  $return['Sheet1'][$i][$accountsYue] ;//总账户余额
            $accountsArray['accountsRemark']   =  $return['Sheet1'][$i][$accountsRemark] ;
            $accountsArray['companyBank']   =  $return['Sheet1'][$i][$companyBank] ;
            $result = $this->objDao->insertAccounts($accountsArray);
            $lastid = $this->objDao->g_db_last_insert_id();
            if ($result) {
                $info['message']  ="添加成功";
                $adminPO = $_SESSION ['admin'];
                $opLog ['who'] = $adminPO ['id'];
                $opLog ['what'] = $lastid;
                $opLog ['memo'] = '';
                $rasult = $this->objDao->addOplog($opLog);
                if (!$rasult) {
                    $exmsg->setError(__FUNCTION__, "delsalary  add oplog  faild ");
                    $info['message'] ='添加日志失败！';
                    throw new Exception ($exmsg->error());
                }
            }
        }
        // 事务提交
        $this->objDao->commit ();
        echo json_encode($info);
        exit;
    }

    function selectExpenses() {
        $this->objDao = new SalaryDao ();
        $salTime = $_REQUEST['salTime'];
        $comId = $_REQUEST['comId'];
       if($comId==0){
           $jsonArray['info']="数据库中没有该公司！";
           echo json_encode($jsonArray);
           exit ();
       }
        $money = $_REQUEST['money'];
        $time = $this->AssignTabMonth($salTime, 0);
        $resultCom = $this->objDao->getComByComLevel($comId);
        if(!mysql_fetch_array($resultCom)){
            $resultCom = $this->objDao->getCompanyById($comId);
        }
        $jsonArray = array();
        $i = 0;
        $sumShifa=0;
        $sumDaikoushui  =   0;
        $sumPay =   0;
        global $expensesInfo;
        while ($row = mysql_fetch_array($resultCom)) {
            $expenses = array();
            $expenses['单位名称'] = $row['company_name'];
            $expenses['工资月份'] = $salTime;
            $salType = $this->objDao->searhSalaryTimeListByComIdAndDate($time["month"], $row['id']);
            if ($salType) {
                $salaryHejiList = $this->objDao->searchSumSalaryListBy_SalaryTimeId($salType['id']);
                while ($rowheji = mysql_fetch_array($salaryHejiList)) {
                    $expenses['实发合计'] = $rowheji['sum_per_shifaheji'];
                    $sumShifa   =  $rowheji['sum_per_shifaheji']+ $sumShifa;
                    $expenses['代扣税'] = $rowheji['sum_per_daikoushui'];
                    $sumDaikoushui   =  $rowheji['sum_per_daikoushui']+ $sumDaikoushui;
                    $expenses['缴中企合计'] = $rowheji['sum_paysum_zhongqi'];
                    $sumPay   =  $rowheji['sum_paysum_zhongqi']+ $sumPay;
                }
                $bill_fa = $this->objDao->searchBillBySalaryTimeId($salType['id'], 4);
                if ($bill = mysql_fetch_array($bill_fa)) {
                    if ($bill['bill_value']) {
                        if ($bill['bill_value'] == 0) {
                            $expenses['状态'] = '<span style="color: blue">等待审批</span>';
                        } elseif ($bill['bill_value'] == 1) {
                            $expenses['状态'] = '<span style="color:green ">审批通过</span>';
                        } elseif ($bill['bill_value'] == 2) {
                            $expenses['状态'] = '<span style="color:gray ">审批未通过</span>';
                        }
                    }
                }
            }
            if( $expenses['状态']==null){
                $expenses['状态']="工资未申请审核";
            }
            foreach ($expensesInfo as $key => $value) {
                $rowSalCol = array();
                $rowFields = array();
                if ($i == 0) {
                    $rowSalCol ['text'] = $value;
                    $rowSalCol ["dataIndex"] = $key;
                    if ($key == 1) {
                        $rowSalCol ["width"] = 200;
                    } else {
                        $rowSalCol ["width"] = 120;
                    }
                    $jsonArray ['columns'] [] = $rowSalCol;
                }
                $rowFields ["name"] = $key;
                $rowFields ["type"] = 'string';
                $jsonArray ['fields'] [] = $rowFields;
                $rowData [$key] = $expenses [$value];

            }
            $jsonArray ['data'] [] = $rowData;
            $i++;
        }
        $sum=$sumShifa+$sumDaikoushui+$sumPay;
        $expenses = array();
        $expenses['单位名称'] =  '<span style="color: green;font-weight:bold">        合计</span>';
        $expenses['工资月份'] =  '<span style="color: green;font-weight:bold">——</span>';
        $expenses['实发合计'] =  '<span style="color: green;font-weight:bold">'.$sumShifa.'</span>';
        $expenses['代扣税'] ='<span style="color: green;font-weight:bold">'.$sumDaikoushui.'</span>';
        $expenses['缴中企合计'] ='<span style="color: green;font-weight:bold">'.$sumPay.'</span>';
        $expenses['状态'] ='<span style="color: green;font-weight:bold">'.$sum.'</span>';
        foreach ($expensesInfo as $key => $value) {
            $rowFields = array();
            $rowFields ["name"] = $key;
            $rowFields ["type"] = 'string';
            $jsonArray ['fields'] [] = $rowFields;
            $rowData [$key] = $expenses [$value];
        }
        $jsonArray ['data'] [] = $rowData;
        $expenses = array();
        $expenses['单位名称'] =  '<span style="color: green;font-weight:bold">      ——</span>';
        $expenses['工资月份'] =  '<span style="color: green;font-weight:bold">——</span>';
        $expenses['实发合计'] =  '<span style="color: green;font-weight:bold">      ——</span>';
        $expenses['代扣税'] = '<span style="color: green;font-weight:bold">      ——</span>';
        $expenses['缴中企合计'] ='<span style="color: green;font-weight:bold">'."导入金额".'</span>';
        $expenses['状态'] ='<span style="color: blue;font-weight:bold">'.$money.'</span>';
        foreach ($expensesInfo as $key => $value) {
            $rowFields = array();
            $rowFields ["name"] = $key;
            $rowFields ["type"] = 'string';
            $jsonArray ['fields'] [] = $rowFields;
            $rowData [$key] = $expenses [$value];
        }
        $jsonArray ['data'] [] = $rowData;
        echo json_encode($jsonArray);
        exit ();
    }

    function updateAccount(){
        $info = array();
        $exmsg = new EC ();
        $this->objDao=new SalaryDao();
        $id=$_REQUEST['id'];
        $newCom=$_REQUEST['newCom'];
        $result = $this->objDao->updateAccount($id,$newCom);
        $lastid = $this->objDao->g_db_last_insert_id();
        if ($result) {
            $info['message']  ="更新成功！";
            $adminPO = $_SESSION ['admin'];
            $opLog ['who'] = $adminPO ['id'];
            $opLog ['what'] = $lastid;
            $opLog ['memo'] = '';
            $rasult = $this->objDao->addOplog($opLog);
            if (!$rasult) {
                $exmsg->setError(__FUNCTION__, "delsalary  add oplog  faild ");
                $info['message'] ='添加日志失败！';
                throw new Exception ($exmsg->error());
            }
        }else{
            $info['message']  ="更新失败！";
        }
        echo json_encode($info);
        exit;
    }

    function delAccountsById() {
        $exmsg = new EC (); // 设置错误信息类
        $this->objDao = new SalaryDao ();
        // 开始事务
        $ids   =   $_POST["ids"];
        $arr=json_decode($ids);
        foreach($arr as $key=>$value){
            $this->objDao->beginTransaction ();
            $result = $this->objDao->delAccountsById ( $value );
            if (! $result) {
                $exmsg->setError ( __FUNCTION__, "del   salaryMovement  faild " );
                // 事务回滚
                $this->objDao->rollback ();
                echo("删除失败！");
                throw new Exception ( $exmsg->error () );
            }

            $adminPO = $_SESSION ['admin'];
            $opLog = array ();
            $opLog ['who'] = $adminPO ['id'];
            $opLog ['what'] = 0;
            $opLog ['Subject'] = OP_LOG_DEL_SALARY;
            $opLog ['memo'] = "delete account info from id=".$value;
            $rasult = $this->objDao->addOplog ( $opLog );
            if (! $rasult) {
                $exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
                echo("添加删除操作日志失败！");
                // 事务回滚
                $this->objDao->rollback ();
                throw new Exception ( $exmsg->error () );
            }
            // 事务提交
            $this->objDao->commit ();
        }
        echo("操作成功！");
        exit;
    }

    function getImportAccountsTemplate() {
        $json = '{"path":"template/importAccountsTemplate.xls"}';
        echo $json;
        exit;
    }
}


$objModel = new ExtSalaryAction($actionPath);
$objModel->dispatcher();



?>
