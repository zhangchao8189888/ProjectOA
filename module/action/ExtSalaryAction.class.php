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
            case "deleteZengjianyuan" :
            	$this->deleteZengjianyuan();
            	break;
            case "searchEmploy":
                $this->searchEmploy();
                break;

            case "getCnameListExt":
                 $this->getCnameListExt();
                  break;
            default :
                $this->modelInput();
                break;

        }



    }

    function AssignTabMonth($date,$step){
        $date= date("Y-m-d",strtotime($step." months",strtotime($date)));//得到处理后的日期（得到前后月份的日期）
        $u_date = strtotime($date);
        $days=date("t",$u_date);// 得到结果月份的天数

        //月份第一天的日期
        $first_date=date("Y-m",$u_date).'-01';
        for($i=0;$i<$days;$i++){
            $for_day=date("Y-m-d",strtotime($first_date)+($i*3600*24));
        }
        $time = array ();
        $time["data"]   =  $date ;
        $time["next"]   =   (date("Y-m-d",strtotime("+1 day",strtotime($date))));
        $time["month"]  =   (date("Y-m",strtotime("+1 day",strtotime($date))));
        $time["first"]  =    $first_date;
        $time["last"]   =      $for_day;
        return $time;
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
        /**
         * companyId	int(11)	No
        salaryTime	date	No
        op_salaryTime	datetime	No
        op_id	int(11)	Yes
        salary_state	int(2)	No	0
        salary_leijiyue	float(11,2)	Yes
         */
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
    //是否查询年终奖设置记录BY孙瑞鹏
    function getCnameListExt(){
        $this->objDao=new SalaryDao();
        $where = $_REQUEST ['cname'];
        $type = $_REQUEST ['leixing'];
        $salaryTimeList= $this->objDao->searchCompanyListByName($where,$type);
        $josnArray=array();
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['companyid']=$row['id'];
            $josnArray['items'][$i]['companyname']=$row['company_name'];
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
        $where['ename']=$ename;
        $where['shenbaozhuangtai']=$shenbaozhuangtai;
        $where['companyName']=$companyName;
        $where['zengjian']=$zengjian;
        $sum =$this->objDao->searhZengjianTongjiPage($where);
        $salaryTimeList=$this->objDao->searhZengjianListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
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
            $i++;
        }
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
    	while ($nameList=mysql_fetch_array($salaryNameList) ){
            $josnArray['items'][$i]['company_id']=$nameList['id'];
            $josnArray['items'][$i]['company_name']=$nameList['company_name'];
            $salaryTimeList=$this->objDao->searhGeshuiListPage($where,$nameList['id']);
            $row=mysql_fetch_array($salaryTimeList);
    		$josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
            $josnArray['items'][$i] ['daikou'] = $row ['daikou'];
            $josnArray['items'][$i] ['bukou'] = $row ['bukou'];
    		$josnArray['items'][$i]['geshuiSum']=$row['geshuiSum'];
            if(!$josnArray['items'][$i]['salaryTime']){
                $josnArray['items'][$i]['salaryTime']='<span style="color: red">未作工资或免税</span>';
            }

    		$i++;
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

}



$objModel = new ExtSalaryAction($actionPath);
$objModel->dispatcher();



?>
