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
            case "searchBusinessInfoListJson":
                $this->searchBusinessInfoListJson();
                break;
            case "changeBusiness":
                $this->changeBusiness();
                break;
            case "searchBusinessInfoById":
                $this->searchBusinessInfoById();
                break;
            case "searchBusinessInfoByIdJson":
                $this->searchBusinessInfoByIdJson();
                break;
            case "updateZengjianyuan":
                $this->updateZengjianyuan();
                break;
            case "updateBusiness":
                $this->updateBusiness();
                break;
            case "updateZengjianyuan":
                $this->updateZengjianyuan();
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

    function searchBusinessInfoById(){
        $this->objDao = new SocialSecurityDao();
        $id = $_REQUEST ['id'];
        $result =$this->objDao->searchBusinessById($id);
        echo json_encode ( $result );
        exit();
    }

    function searchBusinessInfoByIdJson(){
        $this->objDao = new SocialSecurityDao();
        $id = $_REQUEST ['id'];
        $result =$this->objDao->searchBusinessById($id);
        $i = 0;
        $businessArray  =array();
        global $businessTable ;
        foreach ( $businessTable as $key => $value ) {
            if(!$result[$key]){
                continue;
            }
            $rowSalCol = array ();
            $rowFields = array ();
            if ($i == 0) {
                $rowSalCol ['text'] = $value;
                $rowSalCol ["dataIndex"] = $key;

                // summaryType: 'count',
                $businessArray ['columns'] [] = $rowSalCol;
            }
            $rowFields ["name"] = $key;
            $businessArray ['fields'] [] = $rowFields;
            $rowData [$key] = $result [$key];
            $i ++;
        }
        $businessArray ['data'] [] = $rowData;
        echo json_encode ( $businessArray );
        exit();
    }

    function searchBusinessInfoListJson(){
        $this->objDao = new SocialSecurityDao();
        $searchType =   $_POST['searchType'];
        $start = $_REQUEST ['start'];
        $limit = $_REQUEST ['limit'];
        $sorts = $_REQUEST ['sort'];
        $dir = $_REQUEST ['dir'];
        $businessLog    = $_REQUEST ['businessLog'];
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
        $where['businessName']=$searchType;
        $sum =$this->objDao->searchBusinessCount($businessLog,$where);
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
            $comList ['items'] [$i] ['serviceId'] = $row ['serviceId'];
            $comList ['items'] [$i] ['serviceName'] = $row ['serviceName'];
            $comList ['items'] [$i] ['adminId'] = $row ['adminId'];
            $comList ['items'] [$i] ['adminName'] = $row ['adminName'];
            $comList ['items'] [$i] ['employStateId'] = $row ['employStateId'];
            $comList ['items'] [$i] ['employState'] = $row ['employState'];
            $comList ['items'] [$i] ['businessName'] = $row ['businessName'];
            $comList ['items'] [$i] ['remarks'] = $row ['remarks'];
            if($row ['updateTime']){
                $comList ['items'] [$i] ['updateTime'] = date("Y-m-d",strtotime($row ['updateTime']));
            } else{
                $comList ['items'] [$i] ['updateTime'] = "<span>- - - -</span>";
            }
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
        if(!$employName){
           echo("添加失败！您没有输入员工姓名！");
           exit;
        }
        if(!$employNumber){
            echo("添加失败！您没有输入员工身份证号！");
            exit;
        }
        if(!$business){
            echo("添加失败！您没有输入业务名称！");
            exit;
        }
        if(!$employStateNow){
            echo("添加失败！您没有输入员工状态！");
            exit;
        }
        $com = $this->objDao->searchCompanyByName($companyName);
        $addBusinessLog = array();
        $addBusinessLog["companyId"]=$com["id"];
        $addBusinessLog["companyName"]=$companyName;
        $addBusinessLog["employName"]=$employName;
        $addBusinessLog["employNumber"]=$employNumber;
        $addBusinessLog["businessName"]=$business;
        $addBusinessLog["serviceId"]=$adminId;
        $addBusinessLog["serviceName"]=$adminName;
        $addBusinessLog["remarks"]=$remarks;
        $addBusinessLog["socialSecurityStateId"]="1";
        $addBusinessLog["socialSecurityState"]=$businessState['1'];
        $addBusinessLog["employStateId"]=$employStateNow;
        $addBusinessLog["employState"]=$employState[$employStateNow];
        $result = $this->objDao->addBusinessLog($addBusinessLog);
        if(!$result){
            $exmsg->setError(__FUNCTION__, "add business faild ");
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
        $updateType    =   $_POST["updateType"];
        $other  =   array();

        $other['reimbursementTime']     =   $_POST["reimbursementTime"];
        $other['reimbursementValue']     =   $_POST["reimbursementValue"];
        $other['accountTime']    =   $_POST["accountTime"];
        $other['accountValue']    =   $_POST["accountValue"];
        $other['grantTime']   =   $_POST["grantTime"];
        $other['grantValue']    =   $_POST["grantValue"];
        $other['accountComTime']    =   $_POST["accountComTime"];
        $other['accountComValue']    =   $_POST["accountComValue"];
        $other['accountPersonTime']   =   $_POST["accountPersonTime"];
        $other['accountPersonValue']    =   $_POST["accountPersonValue"];
        $other['remarks']  =   $_POST["remarks"];
        $other['retireTime'] =    $_POST["retireTime"];
        $this->objDao = new SocialSecurityDao();
        $loginType = $this->objDao->loginType();
        $exmsg=new EC();//设置错误信息类
        global $businessState;

        $arr=json_decode($companylist);
        if($arr==null){
           echo "没有找到编号！";
           exit;
        }

        foreach($arr as $key=>$value){
            $result = $this->objDao->updateBusinessLog($loginType['admin_type'],$value,$updateType,$businessState[$updateType],$other);
            if(!$result){
                $exmsg->setError(__FUNCTION__, "add business faild ");
                //事务回滚
                $this->objDao->rollback();
                echo("操作失败了，请重新尝试一下！")  ;
                throw new Exception ($exmsg->error());
            }
        }
        echo "操作成功！";
        exit;
    }

    function updateZengjianyuan(){
        $updateId    =   $_POST["updateId"];
        $updateType    =   $_POST["updateType"];
        $this->objDao = new SocialSecurityDao();
        $exmsg=new EC();//设置错误信息类
        if($updateId==null){
            echo "没有找到编号！";
            exit;
        }
        $result = $this->objDao->updateZengjian($updateId,$updateType);
            if(!$result){
                $exmsg->setError(__FUNCTION__, "add business faild ");
                //事务回滚
                $this->objDao->rollback();
                echo("操作失败了，请重新尝试一下！")  ;
                throw new Exception ($exmsg->error());

        }
        echo "操作成功！";
        exit;
    }


}

$objModel = new ExtSocialSecurityAction($actionPath);
$objModel->dispatcher();

?>
