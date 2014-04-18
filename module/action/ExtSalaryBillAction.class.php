<?php
require_once ("module/form/".$actionPath."Form.class.php");
require_once ("module/dao/FinanceDao.class.php");
require_once ("module/dao/SalaryDao.class.php");
require_once ("module/dao/ServiceDao.class.php");
require_once ("tools/fileTools.php");
require_once ("tools/excel_class.php");
require_once ("tools/sumSalary.class.php");
require_once ("tools/Classes/PHPExcel.php");


class ExtSalaryBillAction extends BaseAction {
    function ExtSalaryBillAction($actionPath) {
        parent::BaseAction ();
        $this->objForm = new ExtSalaryBillForm ();
        $this->actionPath = $actionPath;
    }
    function dispatcher() {
        $this->setMode ();
        $this->initBase ( $this->actionPath );
        $this->controller ();
        $this->view ();
        $this->closeDB ();
    }
    function setMode() {
        $this->mode = $_REQUEST ['mode'];
    }
    function controller() {
        switch ($this->mode) {
            case "addInvoice":
                $this->addInvoice ();
                break;
            case "addCheque":
                $this->addCheque ();
                break;
            case "importCheque":
                $this->importCheque();
                break;
            default :
                $this->modelInput ();
                break;
        }
    }
    function modelInput() {
        echo "input null";
    }
    function toFinaceFirst () {
        $this->mode = "toFinaceFirst";
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
        $time["month"]  =   (date("Y-m",strtotime($date)));
        $time["first"]  =    $first_date;
        $time["last"]   =      $for_day;
        return $time;
    }

    /**
     * 添加发票
     * @throws Exception
     */
    function addInvoice() {
        $info=array();
        global $billType;
        $exmsg = new EC ();
        $salaryTime = $_REQUEST ['salaryTime'];
        $billname = $_REQUEST ['billname'];
        $billno = $_REQUEST ['billno'];
        $billval = $_REQUEST ['billval'];
        $memo = $_REQUEST ['memo'];
        $billArray = array ();
        $billArray ['salaryTime_id'] = $salaryTime;
        $billArray ['bill_type'] = $billType ['发票'];
        $billArray ['bill_date'] = date ( 'Y-m-d H:i:s' );
        $billArray ['bill_item'] = $billname;
        $billArray ['bill_no'] = $billno;
        $billArray ['bill_value'] = $billval;
        $billArray ['bill_state'] = 1; // 对应$billState['1']=>发票已开
        $billArray ['op_id'] = 0;
        $billArray ['text'] = $memo;
        $this->objDao = new SalaryDao ();
        $result = $this->objDao->saveSalaryBill ( $billArray );
        $lastid = $this->objDao->g_db_last_insert_id ();
        if ($result) {
            // 1代表$billState发票已开
            $this->objDao->updateSalaryTimeState ( 1, $salaryTime );
            $info['message'] = "发票添加成功";
        } else {
            $info['message']  = "发票添加失败！";
        }

        $adminPO = $_SESSION ['admin'];
        $adminInfo = $this->objDao->getAdmin ($adminPO['name']);
        if($adminInfo['admin_type']==3){
             $salaryApporval= $this->objDao->getApprovalBySalaryTimeId ($salaryTime);
            if(mysql_fetch_array($salaryApporval)){
            }else{
                $info['approval']=$salaryTime;
            }
        }
        $opLog = array ();
        $opLog ['who'] = $adminPO ['id'];
        $opLog ['what'] = $lastid;
        $opLog ['Subject'] = OP_LOG_ADD_BILL_INVOICE;
        $opLog ['memo'] = '';
        $rasult = $this->objDao->addOplog ( $opLog );
        if (! $rasult) {
            $exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
            throw new Exception ( $exmsg->error () );
        }
        echo json_encode($info);
        exit;
    }

    /**
     * 添加支票
     */
    function addCheque() {
        $info=array();
        $exmsg = new EC ();
        global $billType;
        $opLog = array ();
        $salaryTime = $_REQUEST ['salaryTime'];
        $chequeType = $_REQUEST ['chequeType'];
        $chequeval = $_REQUEST ['chequeval'];
        if ($chequeType == 2) {
            $billname = "到账支票";
            $opLog ['Subject'] = OP_LOG_ADD_BILL_ZHI;
            $billState = 2;
        } else {
            $billname = "银行到账";
            $opLog ['Subject'] = OP_LOG_ADD_BILL_ZHIDAO;
            $billState = 3;
        }
        $memo = $_REQUEST ['memo'];
        $billArray = array ();
        $billArray ['salaryTime_id'] = $salaryTime;
        $billArray ['bill_type'] = $billType [$billname];
        $billArray ['bill_date'] = date ( 'Y-m-d H:i:s' );
        $billArray ['bill_item'] = $billname;
        $billArray ['bill_value'] = $chequeval;
        $billArray ['bill_state'] = $billState; // 对应$billState['']=>""
        $billArray ['op_id'] = 0;
        $billArray ['text'] = $memo;

        $this->objDao = new SalaryDao ();
        $result = $this->objDao->saveSalaryBill ( $billArray );
        $lastid = $this->objDao->g_db_last_insert_id ();
        if ($result) {
            // 1代表$billState发票已开
            $result = $this->objDao->updateSalaryTimeState ( $billState, $salaryTime );
            $info['message'] = $billname."添加成功";
            $adminPO = $_SESSION ['admin'];
            $opLog ['who'] = $adminPO ['id'];
            $opLog ['what'] = $lastid;
            $opLog ['memo'] = '';
            $rasult = $this->objDao->addOplog ( $opLog );
            if (! $rasult) {
                $exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
                throw new Exception ( $exmsg->error () );
            }
        } else {
            $info['message'] = $billname."添加失败";
        }
        echo json_encode($info);
        exit;
    }

    function importCheque() {
        $exmsg = new EC ();
        $filename = $_REQUEST["filename"];
        $info = array();
        $err = Read_Excel_File("upload/" . $filename, $return);
        $info['success'] = true;
        if ($err != 0) {
            $info['success'] = false;
            $info['message'] = '读取表格发生错误！';
        }
        if ($return['Sheet1'][0][0] == "单位名称" && $return['Sheet1'][0][1] == "工资日期" && $return['Sheet1'][0][2] == "到账金额" && $return['Sheet1'][0][3] == "备注") {
            $info['message'] = '成功读取表格！';
            $this->objDao = new SalaryDao ();
            for ($i = 0; $i < count($return['Sheet1']); $i++) {
                $salId = $this->objDao->searchSalTimeIdByCompanyName($return['Sheet1'][$i][0], $return['Sheet1'][$i][1]);
                while ($row = mysql_fetch_array($salId)) {
                    $billArray = array();
                    $billArray ['salaryTime_id'] = $row['id'];
                    $billArray ['bill_type'] = 3;
                    $billArray ['bill_date'] = $return['Sheet1'][$i][1];
                    $billArray ['bill_item'] = "银行到账";
                    $billArray ['bill_value'] = $return['Sheet1'][$i][2];
                    $billArray ['bill_state'] = 3; // 对应$billState['']=>""
                    $billArray ['op_id'] = 0;
                    $billArray ['text'] = $return['Sheet1'][$i][3];
                    $result = $this->objDao->saveSalaryBill($billArray);
                    $lastid = $this->objDao->g_db_last_insert_id();
                    if ($result) {
                        $result = $this->objDao->updateSalaryTimeState($billArray ['bill_state'], $billArray ['bill_date']);
                        $info['message']  = $billArray ['bill_item'] . "添加成功";
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
            }
        }
        echo json_encode($info);
        exit;
    }
}


$objModel = new ExtSalaryBillAction ( $actionPath );
$objModel->dispatcher ();

?>
