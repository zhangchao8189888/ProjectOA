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
            default :
                $this->modelInput();
                break;
        }



    }

    function modelInput(){

    }

    function searchComListJosn(){
        $this->objDao = new ServiceDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $searchType = $_REQUEST['sType'];
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
        $year = $_REQUEST['yearDate'];
        if (empty($year)) {
            $date_reg = date("Y-m-d");
            $dateList = explode("-", $date_reg);
            $date = $dateList[0] . "-" . $dateList[1] . "-01";
        } else {
            $mon = $_REQUEST['monDate'];
            $date = $year . "-" . $mon . "-01";
        }
        $dateEnd = $year . "-" . $mon . "-31";
        $where['$salTime']=$date;
        $where['dateEnd']=$dateEnd;
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
                    $comList ['items'] [$i] ['op_salaryTime'] = $year . "-" . $mon;
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

    function getOtherAdminComListJosn(){
        $this->mode = "serviceFrist";
        $admin = $_SESSION["admin"];
        $searchType = $_REQUEST['sType'];
        if (empty($searchType)) {
            $searchType = 1;
        }
        $this->objDao = new ServiceDao();
        $result = $this->objDao->searchCompanyList($admin['id']);
        $comList = array();
        $year = $_REQUEST['yearDate'];
        if (empty($year)) {
            $date_reg = date("Y-m-d");
            $dateList = explode("-", $date_reg);
            $date = $dateList[0] . "-" . $dateList[1] . "-01";
        } else {
            $mon = $_REQUEST['monDate'];
            $date = $year . "-" . $mon . "-01";
        }
        $dateEnd = $year . "-" . $mon . "-31";
        $i = 0;
        while ($row = mysql_fetch_array($result)) {
            //查询当月工资是否发放
            $results = $this->objDao->searchSalTimeByComIdAndSalTime($row['companyId'], $date, $dateEnd, $searchType);
            $rowSal['company_name'] = $row['company_name'];
            $rowSal['companyId'] = $row['companyId'];
            if ($searchType == 1) {
                $rowSal['salDate'] = $date;
                $rowSal['op_salaryTime'] = $results['op_salaryTime'];
            } elseif ($searchType == 2) {
                $rowSal['salDate'] = $results['salaryTime'];
                if (empty($results['op_salaryTime'])) {
                    $rowSal['op_salaryTime'] = $year . "-" . $mon;
                } else {
                    //echo $results['op_salaryTime'].">>>>>>>>>>>>>>>";
                    $rowSal['op_salaryTime'] = $results['op_salaryTime'];
                }
            }
            if (!$results) {
                $rowSal['salStat'] = 0;
                $rowSal['salTimeid'] = -1;
                $rowSal['fa_state'] = -1;
            } else {
                $rowSal['salStat'] = $results['id'];
                $rowSal['salTimeid'] = $results['id'];
                $this->objDao = new SalaryDao();
                $bill_fa = $this->objDao->searchBillBySalaryTimeId($results['id'], 4);
                if ($bill = mysql_fetch_array($bill_fa)) {
                    $rowSal['fa_state'] = $bill['bill_value'];
                } else {
                    $rowSal['fa_state'] = '<font color=red>未批准发放</font>';
                }
            }
            $rowSal['salNianStat'] = 0;
            $rowSal['salOrStat'] = 0;
            $sqlOr = $this->objDao->searchOrSalTimeByComIdAndSalTime($row['companyId'], $results['salaryTime']);
            if ($sqlOr) {
                while ($rowEr = mysql_fetch_array($sqlOr)) {
                    if ($rowEr['salaryType'] == ER_SALARY_TIME_TYPE) {
                        $rowSal['salOrStat'] = $rowEr['id'];
                    } elseif ($rowEr['salaryType'] == SALARY_TIME_TYPE) {
                        $rowSal['salNianStat'] = $rowEr['id'];
                    }

                }
            }
            if ($results['salary_state'] < 1) {
                $rowSal['fastat'] = 0;
            } else {
                $rowSal['fastat'] = 1;
            }
            $rowSal['mark'] = $results['mark'];
            $comList[$i] = $rowSal;
            $i++;
        }
        echo json_encode($comList);
        exit;
    }
}


$objModel = new ExtServiceAction($actionPath);
$objModel->dispatcher();



?>




