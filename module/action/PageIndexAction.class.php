<?php
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/ServiceDao.class.php");
require_once("module/dao/SalaryDao.class.php");
class PageIndexAction extends BaseAction{
    /**
     * @param $actionPath
     * @return AdminAction
     */
    function PageIndexAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new PageIndexForm();
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
            case "toCompanyTotalByTimeListPage"://根据工资月份查询某一个单位的所有总额包括（公积金、社保、基本工资、二次工资、年终奖）
                $this->toCompanyTotalByTimeListPage();
                break;
            case "toCompanyDuizhang"://
                $this->toCompanyDuizhang();
                break;
            default :
                $this->modelInput();
                break;
        }
    }
    function toCompanyTotalByTimeListPage (){
        $this->mode = 'toCompanyTotalByTimeListPage';
    }
    function toCompanyDuizhang () {
        $this->mode = 'toCompanyDuizhang';
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $companyName=$_REQUEST['companyName'];
        $accountsType=1;
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
        $where['companyName']=$companyName;
        if($accountDateb){
            $where['transactionDateb']=$accountDateb;
        }
        if($accountDatea){
            $where['transactionDatea']=$accountDatea;
        }
        $sum =$this->objDao->searchAccountListCount($where);
        $pagesize=PAGE_SIZE;
        //$sum=$rs['sum'];
        $count = intval($_REQUEST["c"]);
        $page = intval($_REQUEST["p"]);
        if ($count == 0){
            $count = $pagesize;
        }
        if ($page == 0){
            $page = 1;
        }

        $startIndex = ($page-1)*$count;
        $total = $sum;
        $pageindex=$page;

        $salaryTimeList=array();
        $result=$this->objDao->searchAccountListPage($startIndex,$pagesize,"transactionDate desc",$where);
        $i=0;
        while($row=mysql_fetch_array($result)){
            $salaryTimeList[$i]['id']=$row['id'];
            $salaryTimeList[$i]['companyName']=$row['companyName'];
            $com=$this->objDao->searchCompanyByName($row['companyName']);
            $salaryTimeList[$i]['companyId']=$com['id'];

            $salaryTimeList[$i]['transactionDate']=$row['transactionDate'];
            $salaryTimeList[$i]['value']=$row['accountsValue'];
            $salaryTimeList[$i]['remark']=$row['remark'];
            $salaryTimeList[$i]['accountsType']="收入";
            $salaryTimeList[$i]=$row;
            $i++;
        }
        $this->objForm->setFormData("startIndex",$startIndex);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("pageindex",$pageindex);
        $this->objForm->setFormData("pagesize",$pagesize);
        $this->objForm->setFormData("salaryList",$salaryTimeList);

    }


}


$objModel = new PageIndexAction($actionPath);
$objModel->dispatcher();

