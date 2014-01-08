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
            case "toSalTimeList":
                $this->toSalTimeList();
                break;
            case "toErSalTimeList":
                $this->toErSalTimeList();
                break;
            case "tosearhSalaryNianTimeList":
            	$this->tosearhSalaryNianTimeList();
            	break;
            case "tosearchcompanyListJosn":
            	$this->tosearchcompanyListJosn();
            	break;
            default :
                $this->modelInput();
                break;
        }



    }
    
    function tosearchcompanyListJosn(){
    	$this->mode="tosearchcompanyListJosn";
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
    function toSalTimeList () {
        $this->mode="toSalTimeList";
    }
    function toErSalTimeList () {
        $this->mode="toErSalTimeList";
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

}


$objModel = new ExtAction($actionPath);
$objModel->dispatcher();



?>
