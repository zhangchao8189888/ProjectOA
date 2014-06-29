<?php
require_once("module/form/PageIndexForm.class.php");
require_once("module/dao/ServiceDao.class.php");
require_once("module/dao/SalaryDao.class.php");
class AjaxJsonAction extends BaseAction{
    /**
     * @param $actionPath
     * @return AdminAction
     */
    function AjaxJsonAction($actionPath)
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
            case "getAdminCompanyListJson"://查询管理公司列表
                $this->getAdminCompanyListJson();
                break;
            default :
                $this->modelInput();
                break;
        }
    }
    function getAdminCompanyListJson (){
        $companyName = $_REQUEST['companyName'];
        $salTime = $_REQUEST['salTime'];
        if (empty($salTime)) {
            $salTime = date('Y-m').'-01';
        } else {
            $salTime = $this->AssignTabMonth($salTime,0);
            $salTime = $salTime["first"];
        }
        $where['companyName'] = $companyName;
        $start = $_REQUEST['start'];
        $limit = $_REQUEST['limit'];
        $sorts = $_REQUEST['sort'];
        $dir = $_REQUEST['dir'];
        if (!$start) {
            $start = 0;
        }
        if (!$limit) {
            $limit = 50;
        }
        $this->objDao = new SalaryDao();
        $result = $this->objDao->manageCompanyPage($start, $limit, $sorts . " " . $dir,$where);
        $i = 0;
        while ($row = mysql_fetch_array($result)) {
            $comList ['items'] [$i] ['salTime'] = $salTime;
            $comList ['items'] [$i] ['company_name'] = $row ['company_name'];
            $comList ['items'] [$i] ['id'] = $row['id'];
            //查询工资交中企总额
            $salTotal = $this->objDao->searhSalaryTimeListByComIdAndDate($salTime,$row['id']);
            if ($salTotal) {
                $comList ['items'] [$i] ['salTimeId'] = $salTotal['id'];
                $sqlResult = $this->objDao->searchSumSalaryListBy_SalaryTimeId($salTotal['id']);
                $salTotal = mysql_fetch_array($sqlResult);
                $comList ['items'] [$i] ['salTotal'] = $salTotal['sum_paysum_zhongqi'];
            } else {
                $comList ['items'] [$i] ['salTotal'] = 0;
                $comList ['items'] [$i] ['salTimeId'] = 0;
            }

            //查询二次工资交中企总额
            $salErTotal = $this->objDao->searchErSalaryTimeBySalaryTimeAndComId($salTime,$row['id']);
            $j =0;
            $comList ['items'] [$i] ['salErTotal'] = 0;
            while ($rowEr = mysql_fetch_array($salErTotal)) {
                $comList ['items'] [$i] ['salErTimeId'] [$j] = $rowEr['id'];
                $sqlResult = $this->objDao->searchSumErSalaryListBy_SalaryTimeId($rowEr['id']);
                $salErTotal = mysql_fetch_array($sqlResult);

                $comList ['items'] [$i] ['salErTotal'] += $salErTotal['sum_jiaozhongqi'];
                $j ++;
            }
            $comList ['items'] [$i] ['salErNum'] = $j;
            //查询年终奖交中企总额
            $salNainTotal = $this->objDao->searchNianSalaryTimeBySalaryTimeAndComId($salTime,$row['id']);
            $comList ['items'] [$i] ['salNianTotal'] = 0;
            if($salNainTotal) {
                $comList ['items'] [$i] ['salNianTimeId']  = $salNainTotal['id'];
                $sqlResult = $this->objDao->searchSumNianSalaryListBy_SalaryTimeId($salNainTotal['id']);
                $salNianTotal = mysql_fetch_array($sqlResult);
                $comList ['items'] [$i] ['salNianTotal'] = $salNianTotal['sum_jiaozhongqi'];
            }

            $i++;
        }
        echo json_encode($comList);
        exit();
    }


}


$objModel = new AjaxJsonAction($actionPath);
$objModel->dispatcher();

