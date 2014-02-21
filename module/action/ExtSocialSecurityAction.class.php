<?php
require_once("module/form/" . $actionPath . "Form.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/SocialSecurityDao.class.php");
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
require_once("tools/sumSalary.class.php");
require_once("tools/Classes/PHPExcel.php");

class ExtSocialSecurityAction extends BaseAction {
    /*
     *
     * @param $actionPath
     * @return SalaryAction
     */
    function ExtSocialSecurityAction($actionPath) {
        parent::BaseAction();
        $this->objForm = new ExtSocialSecurityForm();
        $this->actionPath = $actionPath;
    }

    function dispatcher() {
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

    function setMode() {
        // 模式设定
        $this->mode = $_REQUEST['mode'];
    }

    function controller() {
        switch ($this->mode) {
            case "searchbusinessInfoListJson":
                $this->searchbusinessInfoListJson();
                break;
            case "changeBusiness":
                $this->changeBusiness();
                break;
            case "updateBusiness":
                $this->updateBusiness();
                break;
            default :
                $this->modelInput();
                break;
        }


    }

    function AssignTabMonth($date, $step) {
        $date = date("Y-m-d", strtotime($step . " months", strtotime($date))); //得到处理后的日期（得到前后月份的日期）
        $u_date = strtotime($date);
        $days = date("t", $u_date); // 得到结果月份的天数

        //月份第一天的日期
        $first_date = date("Y-m", $u_date) . '-01';
        for ($i = 0; $i < $days; $i++) {
            $for_day = date("Y-m-d", strtotime($first_date) + ($i * 3600 * 24));
        }
        $time = array();
        $time["data"] = $date;
        $time["next"] = (date("Y-m-d", strtotime("+1 day", strtotime($date))));
        $time["month"] = (date("Y-m", strtotime("+1 day", strtotime($date))));
        $time["first"] = $first_date;
        $time["last"] = $for_day;
        return $time;
    }

    function searchbusinessInfoListJson(){
        $this->objDao = new SocialSecurityDao();
        $start = $_REQUEST ['start'];
        $limit = $_REQUEST ['limit'];
        $sorts = $_REQUEST ['sort'];
        $dir = $_REQUEST ['dir'];
        $date   =   $_REQUEST['date'];
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
        $comList = array ();
        $sum =$this->objDao->searchBusinessCount($where);
        $result=$this->objDao->searchBusinessPage($start,$limit,$sorts." ".$dir,$where);
        $comList['total']=$sum;
        $i=0;
        while ( $row = mysql_fetch_array ( $result ) ) {
            $comList ['items'] [$i] ['id'] = $row ['id'];
            $comList ['items'] [$i] ['submitTime'] = $row ['submitTime'];
            $comList ['items'] [$i] ['updateTime'] = $row ['updateTime'];
            $comList ['items'] [$i] ['companyId'] = $row ['companyId'];
            $comList ['items'] [$i] ['companyName'] = $row ['companyName'];
            $comList ['items'] [$i] ['employId'] = $row ['employId'];
            $comList ['items'] [$i] ['employName'] = $row ['employName'];
            $comList ['items'] [$i] ['employStateId'] = $row ['employStateId'];
            $comList ['items'] [$i] ['employState'] = $row ['employState'];
            $comList ['items'] [$i] ['businessName'] = $row ['businessName'];
            $comList ['items'] [$i] ['remarks'] = $row ['remarks'];
            $comList ['items'] [$i] ['socialSecurityStateId'] = $row ['socialSecurityStateId'];
            $comList ['items'] [$i] ['socialSecurityStateId'] = $row ['socialSecurityStateId'];
            $comList ['items'] [$i] ['socialSecurityState'] = $row ['socialSecurityState'];
            $i ++;
        }
        echo json_encode($comList);
        exit ();
    }

    /**
     * 变更业务Action
     */
    function changeBusiness() {
        $adminId=$_SESSION['admin']['id'];
        $adminName  =  $_SESSION['admin']['name'];
        $this->objDao = new SocialSecurityDao();
        $exmsg=new EC();//设置错误信息类
        global $businessState;
        global $employState;
        $companyName = $_REQUEST['companyName'];
        $employName = $_REQUEST['employName'];
        $employNumber = $_REQUEST['employNumber'];
        $business = $_REQUEST['business'];
        $employStateNow = $_REQUEST['employState'];
        $remarks = $_REQUEST['remarks'];
        $com = $this->objDao->searchCompanyByName($companyName);
        $addBusinessLog = array();
        $addBusinessLog["companyId"]=$com["id"];
        $addBusinessLog["companyName"]=$companyName;
        $addBusinessLog["employName"]=$employName;
        $addBusinessLog["employNumber"]=$employNumber;
        $addBusinessLog["businessName"]=$business;
        $addBusinessLog["adminId"]=$adminId;
        $addBusinessLog["adminName"]=$adminName;
        $addBusinessLog["remarks"]=$remarks;
        $addBusinessLog["socialSecurityStateId"]="1";
        $addBusinessLog["socialSecurityState"]=$businessState['1'];
        $addBusinessLog["employStateId"]=$employStateNow;
        $addBusinessLog["employState"]=$employState[$employStateNow];
        $result = $this->objDao->addBusinessLog($addBusinessLog);
        if(!$result){
            $exmsg->setError(__FUNCTION__, "delete admin   faild ");
            //事务回滚
            $this->objDao->rollback();
            echo("操作失败了，请重新尝试一下！")  ;
            throw new Exception ($exmsg->error());
        }
        else{
            echo("提交成功，请等待办理！")  ;
        }
        exit;
    }

    function updateBusiness(){
        $companylist    =   $_POST["ids"];
        $this->objDao=new BaseDao();
        $arr=json_decode($companylist);
        foreach($arr as $key=>$value){
            $this->objDao->cancelManage($value);
        }
        exit;
    }
}

$objModel = new ExtSocialSecurityAction($actionPath);
$objModel->dispatcher();

?>
