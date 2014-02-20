<?php
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/ServiceDao.class.php");
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
require_once("tools/sumSalary.class.php");
require_once("tools/Classes/PHPExcel.php");
class ExtServiceAction extends BaseAction{
    /*
     *
     * @param $actionPath
     * @return SalaryAction
     */
    function ExtServiceAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new ExtServiceForm();
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
            case "searchComListJosn" :
                $this->searchComListJosn();
                break;
            case "data" :
                $this->data();
                break;
            case "selectManageCompany" :
                $this->selectManageCompany();
                break;
            default :
                $this->modelInput();
                break;
        }



    }

    function modelInput(){

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
     * ExtService action 客服首页
     */
    function searchComListJosn(){
        $this->objDao = new ServiceDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $date = $_REQUEST['date'];
        $operationTime = $_REQUEST['operationTime'];
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where['companyName']=$companyName;
        $comList=array();
        if($date!=null) {
            $time   =   $this->AssignTabMonth($date,0);
            $where['$salTime']=$time["month"];
        }else{
            $date=date('Y-m',time())."-01";
            if(!$companyName&&!$operationTime) {
                $where['$salTime']= date('Y-m',time())."-01";
            }
        }
        if($operationTime!=null) {
            $where['opTime']    =  date("Y-m-d",strtotime($operationTime));
        }
        $sum =$this->objDao->manageCompanyCount($where);
        $comList['total']=$sum;
        $result = $this->objDao->manageCompanyPage($start, $limit, $sorts . " " . $dir, $where);
        $i=0;
        while ($row=mysql_fetch_array($result) ){
            //查询当月工资是否发放
            $results = $this->objDao->searchByComIdAndSalTime($row['id'],$where);
            $comList ['items'] [$i] ['id'] = $row ['id'];
            $comList ['items'] [$i] ['company_name'] = $row ['company_name'];
                if($results["salaryTime"]){
                    $comList ['items'] [$i] ['salDate'] =date("Y-m-d",strtotime($results["salaryTime"]));
                }else{
                    $comList ['items'] [$i] ['salDate'] =date("Y-m-d",strtotime($date));
                }
                if($results['op_salaryTime']){
                    $comList ['items'] [$i] ['op_salaryTime'] = date("Y-m-d",strtotime( $results['op_salaryTime']));
                }else{
                    $comList ['items'] [$i] ['op_salaryTime'] = "<span style=\"color: black\"> - - - - </span>";
                }

            if (!$results) {
                $comList ['items'] [$i]['salStat'] =0;
                $comList ['items'] [$i]['salTimeid'] = -1;
                $comList ['items'] [$i]['fa_state'] = -1;
                $result['id']=0;
            } else {
                $comList ['items'] [$i]['salStat'] = $results['id'];
                $comList ['items'] [$i]['salTimeid'] = $results['id'];
                $this->billInfo = new SalaryDao();
                $bill_fa = $this->billInfo->searchBillBySalaryTimeId($results['id'], 4);
                if ($bill = mysql_fetch_array($bill_fa)) {
                    $comList ['items'] [$i]['fa_state'] = $bill['bill_value'];
                } else {
                    $comList ['items'] [$i]['fa_state'] ="<span style=\"color: red\"> 未批准发放 </span>";
                }
            }
            $comList ['items'] [$i]['salNianStat'] = 0;
            $comList ['items'] [$i]['salOrStat'] = 0;
            $sqlOr = $this->objDao->searchOrSalTimeByComIdAndSalTime($row['id'], $results['salaryTime']);
            if ($sqlOr) {
                while ($rowEr = mysql_fetch_array($sqlOr)) {
                    if ($rowEr['salaryType'] == ER_SALARY_TIME_TYPE) {
                        $comList ['items'] [$i]['salOrStat'] = $rowEr['id'];
                    } elseif ($rowEr['salaryType'] == SALARY_TIME_TYPE) {
                        $comList ['items'] [$i]['salNianStat'] = $rowEr['id'];
                    }

                }
            }
            if ($results['salary_state'] < 1) {
                $comList ['items'] [$i]['fastat'] = 0;
            } else {
                $comList ['items'] [$i]['fastat'] = 1;
            }
            $comList ['items'] [$i]['mark'] = $results['mark'];
            $comList[$i] = $comList ['items'] [$i];
            $i++;
        }
        echo json_encode($comList);
        exit();
    }

    function  selectManageCompany(){
        $this->objDao = new ServiceDao();
        $result = $this->objDao->searchCompanyList();
        $comList=array();
        $i=0;
        while ($row=mysql_fetch_array($result) ){
            $comList["item"][i]["name"]    =  $row["company_name"] ;
            $i++;
        }
        $comList["total"]=10;
        echo json_encode($comList);
        exit();
    }

}


$objModel = new ExtServiceAction($actionPath);
$objModel->dispatcher();



?>




