<?php 
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/ServiceDao.class.php");
class ExtAction extends BaseAction{
 /*
     *
     * @param $actionPath
     * @return AdminAction
     */
 function ExtAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new ExtForm();
        $this->objForm->setFormData("adminDomain",$this->admin);
        $this->actionPath = $actionPath;
    }
  function dispatcher(){
    // (1) mode set
        $this->setMode();
        // (2) COM initialize
        $this->initBase($this->actionPath);
        // (3) controll -> Model
        $this->controller();
        // (4) view
        $this->view();
        // (5) closeConnect
        $this->closeDB();
   }
   function setMode()
    {
        // 模式设定
        $this->mode = $_REQUEST["mode"];
    }
   function controller()
    {
        // Controller -> Model
        switch($this->mode) {
        	case "toExtTable" :
                $this->toExtTable();
                break;
            case "toExtJson" :
                $this->toExtJson();
                break;
            case "getExtJosn":
            	$this->getExtJosn();
                break;
            case "toExtPage":
            	$this->toExtPage();
                break;
            case "toExtDongTai":
            	$this->toExtDongTai();
                break;
            case "toExtTest":
                $this->toExtTest();
                break;
            case "tosalaryTongji":
                $this->tosalaryTongji();
                break;
            case "toSalaryComList":
                $this->toSalaryComList();
                break;
            case "toSalTimeList":
                $this->toSalTimeList();
                break;
            case "toErSalTimeList":
                $this->toErSalTimeList();
                break;
            case "tosearhSalaryNianTimeList":
            	$this->tosearhSalaryNianTimeList();
            	break;
            case "toCheckCompany":
            	$this->toCheckCompany ();
            	break;
            case "toFinanceIndex":
                $this->toFinanceIndex();
                break;
            case "toServiceIndex":
                $this->toServiceIndex();
                break;
            case "toSocialSecurityIndex":
                $this->toSocialSecurityIndex();
                break;
            case "toModifyPass":
                $this->toModifyPass();
                break;
            case "toTaxInfo":
                $this->toTaxInfo();
                break;
            case "toBusiness":
                $this->toBusiness();
                break;
            case "toInsurance":
                $this->toInsurance();
                break;
            case "toPersonsalary":
                $this->toPersonsalary();
                break;
            case "todemo":
                $this->todemo();
                break;
            case "toTeshushenfen":
                $this->toTeshushenfen();
                break;
            case "contractInfo":
                $this->contractInfo();
                break;
            case "toServiceApproval":
                $this->toServiceApproval();
                break;
            case "toFinanceApproval":
                $this->toFinanceApproval();
                break;
            case "toAccount":
                $this->toAccount();
                break;
            case "toSocialSecurity":
                $this->toSocialSecurity();
                break;
            case "toBund":
                $this->toBund();
                break;
            case "toCaiWuDuizhang":
                $this->toCaiWuDuizhang();
                break;
            case "getCaiWuDuizhangJosn":
                $this->getCaiWuDuizhangJosn();
                break;
            default :
                $this->modelInput();
                break;

        }



    }
    
    function toCheckCompany (){
    	$this->mode="toCheckCompany";
    }
    
    function tosearhSalaryNianTimeList() {
    	$this->mode="tosearhSalaryNianTimeList";
    }
    function toExtTable(){
    	$this->mode="toExtTable";
    }
    function toExtJson(){
    	$this->mode="toExtJson";
    }
    function toExtPage(){
    	$this->mode="toExtPage";
    }
    function toExtDongTai(){
    	$this->mode="toExtDongTai";
    }
    function toExtTest () {
        $this->mode="toExtTest";
    }
    function tosalaryTongji(){
        $this->mode =   "tosalaryTongji";
    }
    function toSalaryComList(){
        $this->mode =   "toSalaryComList";
    }
    function toSalTimeList () {
        $this->mode="toSalTimeList";
    }
    function toErSalTimeList () {
        $this->mode="toErSalTimeList";
    }

    function toFinanceIndex(){
        $this->mode =   "toFinanceIndex";
    }

    function toServiceIndex(){
        $this->mode =   "toServiceIndex";
    }

    function toSocialSecurityIndex(){
        $this->mode =   "toSocialSecurityIndex";
    }

    function toModifyPass(){
        $this->mode =   "toModifyPass";
    }

    function toTaxInfo(){
        $this->mode =   "toTaxInfo";
    }

    function toBusiness(){
        $this->mode="toBusiness";
    }

    function toInsurance(){
        $this->mode="toInsurance";
    }

    function toPersonsalary(){
        $this->mode =   "toPersonsalary";
    }

    function toTeshushenfen(){
        $this->mode =   "toTeshushenfen";
    }
    function getExtJosn(){
    	$jsonList=array();
    	//['salDate', 'op_salaryTime', 'company_name','salStat']
    	$jsonList[0]['salDate']='2013';
    	$jsonList[0]['op_salaryTime']='2013-01';
    	$jsonList[0]['company_name']='公司1';
    	$jsonList[0]['salStat']='无';
    	$jsonList[1]['salDate']='2013';
    	$jsonList[1]['op_salaryTime']='2013-01';
    	$jsonList[1]['company_name']='公司2';
    	$jsonList[1]['salStat']='有';
    	echo json_encode($jsonList);
    	exit;
    }

    function todemo(){
        $this->mode="todemo";
    }

    function contractInfo(){
        $this->mode="contractInfo";
    }

    function toServiceApproval(){
        $this->mode="toServiceApproval";
    }
    function toFinanceApproval(){
        $this->mode="toFinanceApproval";
    }

    function toAccount(){
        $this->mode="toAccount";
    }
    function toSocialSecurity(){
        $this->mode="toSocialSecurity";
    }
    function toBund(){
        $this->mode="toBund";
    }
    function toCaiWuDuizhang() {
        $this->mode="toCaiWuDuizhang";
    }
    function getCaiWuDuizhangJosn() {
        $start = $_REQUEST ['start'];
        $limit = $_REQUEST ['limit'];
        $sorts = $_REQUEST ['sort'];
        $dir = $_REQUEST ['dir'];
        if (! $start) {
            $start = 0;
        }
        if (! $limit) {
            $limit = 50;
        }
        if (! $sorts) {
            $sorts = "uncheckid";
        }
        $where = array ();
        $companyName=$_REQUEST['companyName'];
        $where['companyName']=$companyName;
        $this->objDao = new ServiceDao();
        // 查询公司列表
        $sum =$this->objDao->manageCompanyCount($where);
        $result=$this->objDao->manageCompanyPage($start,$limit,$sorts." ".$dir,$where);
        $comList['total']=$sum;
        $i = 0;
        while ( $row = mysql_fetch_array ( $result ) ) {
            $comList ['items'] [$i] ['id'] = $row ['id'];
            $comList ['items'] [$i] ['company_name'] = $row ['company_name'];
            $comList ['items'] [$i] ['account_value'] = $row ['account_value'];
            $i ++;
        }
        echo json_encode($comList);
        exit ();

    }
}


$objModel = new ExtAction($actionPath);
$objModel->dispatcher();



?>
