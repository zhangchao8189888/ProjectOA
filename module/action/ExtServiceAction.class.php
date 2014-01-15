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
            case "getOtherAdminComListJosn" :
                $this->searchComListJosn();
                break;
            case "data" :
                $this->data();
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
        $time["first"]  =    $first_date;
        $time["last"]   =      $for_day;
        return $time;
    }

    function searchComListJosn(){
        $this->objDao = new ServiceDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $searchType = $_REQUEST['sType'];
        $date = $_REQUEST['date'];
        if (empty($searchType)) {
            $searchType = 1;
        }
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where['companyName']=$companyName;
        $where['searchType']=$searchType;
        $comList=array();
        if($date!=null) {
            $time   =   $this->AssignTabMonth($date,0);
            if(1==$searchType){
                $date=  $time["data"];
            }elseif(2 == $searchType){
                $date   =    $time["first"];
                $dateEnd   =    $time["last"];
                $where['$salTime']=$date;
                $where['dateEnd']=$dateEnd;
            }
        }else{
            $date=date('Y-m',time())."-01";
        }
        $sum =$this->objDao->searhManageComCount($where);
        $comList['total']=$sum;
        $result = $this->objDao->searhManageComPage($start, $limit, $sorts . " " . $dir, $where);
        $i=0;
        while ($row=mysql_fetch_array($result) ){
            $comList ['items'] [$i] ['id'] = $row ['id'];
            //查询当月工资是否发放
            $results = $this->objDao->searchSalTimeByComIdAndSalTime($row['id'], $date, $dateEnd, $searchType);
            $comList ['items'] [$i] ['id'] = $row ['id'];
            $comList ['items'] [$i] ['company_name'] = $row ['company_name'];
            $comList ['items'] [$i] ['companyId'] = $row ['companyId'];
            if ($searchType == 1) {
                $comList ['items'] [$i] ['salDate'] = $date;
                $comList ['items'] [$i] ['op_salaryTime'] = $results['op_salaryTime'];
            } elseif ($searchType == 2) {
                $comList ['items'] [$i] ['salDate'] = $results['salaryTime'];
                if (empty($results['op_salaryTime'])) {
                    $comList ['items'] [$i] ['op_salaryTime'] = $date;
                } else {
                    $comList ['items'] [$i] ['op_salaryTime'] = $results['op_salaryTime'];
                }
            }
            if (!$results) {
                $comList ['items'] [$i]['salStat'] = 0;
                $comList ['items'] [$i]['salTimeid'] = -1;
                $comList ['items'] [$i]['fa_state'] = -1;
            } else {
                $comList ['items'] [$i]['salStat'] = $results['id'];
                $comList ['items'] [$i]['salTimeid'] = $results['id'];
                $this->objDao = new SalaryDao();
                $bill_fa = $this->objDao->searchBillBySalaryTimeId($results['id'], 4);
                if ($bill = mysql_fetch_array($bill_fa)) {
                    $comList ['items'] [$i]['fa_state'] = $bill['bill_value'];
                } else {
                    $comList ['items'] [$i]['fa_state'] = '<font color=red>未批准发放</font>';
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

}


$objModel = new ExtServiceAction($actionPath);
$objModel->dispatcher();



?>




