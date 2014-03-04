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
            case "searchSocialsecurityInfoList":
                $this->searchSocialsecurityInfoList();
                break;
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
            case "searchInsuranceList":
                $this->searchInsuranceList();
                break;
            case "addInsurance":
                $this->addInsurance();
                break;
            case "updateInsurance":
                $this->updateInsurance();
                break;
            case "updateZengjianyuan":
                $this->updateZengjianyuan();
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

    function searchSocialsecurityInfoList(){
        $this->objDao = new SocialSecurityDao();
        global $businessInfo ;
        $where = array ();
        $comList = array ();
        $i=0;
        $where['shenbaozhuangtai']  =   "等待办理";
        $resultEmp   =   $this->objDao->searhZengjianTongjiCount($where);
        $comList ['items'] [$i] ["mattername"] = "增减员信息";
        $comList ['items'] [$i] ["matterWait"] = $resultEmp;
        $where['shenbaozhuangtai']  =   "正在办理";
        $resultEmp   =   $this->objDao->searhZengjianTongjiCount($where);
        $comList ['items'] [$i] ["mattername"] = "增减员信息";
        $comList ['items'] [$i] ["matterDoing"] = $resultEmp;
        $where['shenbaozhuangtai']  =   "办理成功";
        $resultEmp   =   $this->objDao->searhZengjianTongjiCount($where);
        $comList ['items'] [$i] ["mattername"] = "增减员信息";
        $comList ['items'] [$i] ["matterClear"] = $resultEmp;
        $i++;
        foreach ( $businessInfo as $key => $value ) {
            $where['businessName']=  $key;
            $where['socialSecurityStateId']= 1;
            $result =$this->objDao->searchBusinessCount($where);
            $comList ['items'] [$i] ["mattername"] = $value;
            $comList ['items'] [$i] ["matterWait"] = $result;
            $where['socialSecurityStateId']= 2;
            $result =$this->objDao->searchBusinessCount($where);
            $comList ['items'] [$i] ["mattername"] = $value;
            $comList ['items'] [$i] ["matterDoing"] = $result;
            $where['socialSecurityStateId']= 3;
            $result =$this->objDao->searchBusinessCount($where);
            $comList ['items'] [$i] ["mattername"] = $value;
            $comList ['items'] [$i] ["matterClear"] = $result;
            $i++;
        }
        $where['disType']   =   "1";
        $resultin   =   $this->objDao->searchInsuranceCount($where);
        $comList ['items'] [$i] ["mattername"] = "个人工资";
        $comList ['items'] [$i] ["matterDoing"] = $resultin;
        $where['disType']   =   "0";
        $i++;
        $resultsal   =   $this->objDao->searchInsuranceCount($where);
        $comList ['items'] [$i] ["mattername"] = "个人保险";
        $comList ['items'] [$i] ["matterDoing"] = $resultsal;
        echo json_encode($comList);
        exit ();
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
        $ids = $_REQUEST ['ids'];
        $ids=json_decode($ids);
        global $businessTable ;
        $businessArray  =array();
        $rowSalCol = array ();
        $rowFields = array();
        $i = 0;
        foreach($ids as $key=>$value){
            $result =$this->objDao->searchBusinessById($value);
            foreach ( $businessTable as $key => $value ) {
                if(!$result[$key]){
                    continue;
                }
                if($i==0){
                    $rowSalCol ['text'] = $value;
                    $rowSalCol ["dataIndex"] = $key;
                    $businessArray ['columns'] [] = $rowSalCol;
                    $rowFields ["name"] = $key;
                    $businessArray ['fields'] [] = $rowFields;
                    $rowData [$key] = $result [$key];
                }

            }
            $i++;
            $businessArray ['data'] [] = $rowData;
        }

        echo json_encode ( $businessArray );
        exit();
    }

    function searchBusinessInfoListJson(){
        $this->objDao = new SocialSecurityDao();
        $where = array ();
        $comList = array ();
        $searchType =   $_POST['searchType'];
        $companyName    = $_POST['companyName'];
        $employName    = $_POST['employName'];
        $date =   $_POST['submitTime'];
        $start = $_REQUEST ['start'];
        $limit = $_REQUEST ['limit'];
        $sorts = $_REQUEST ['sort'];
        $dir = $_REQUEST ['dir'];
        $socialSecurityStateId  =   $_REQUEST['socialSecurityStateId'];
        $businessLog    = $_REQUEST ['businessLog'];
        if($date!=null) {
            $time   =   $this->AssignTabMonth($date,0);
            $where['submitTime']=$time["last"];
        }else{

        }
        if($searchType=="其他"){
            $where['otherName'] ="其他";
        } else{
            $where['businessName']=$searchType;
        }
        $where['companyName']=$companyName;
        $where['employName']=$employName;
        $where['socialSecurityStateId']=$socialSecurityStateId;
        if (! $start) {
            $start = 0;
        }
        if (! $limit) {
            $limit = 50;
        }
        if (! $sorts) {
            $sorts = "uncheckid";
        }
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
            $comList ['items'] [$i] ['reimbursementTime'] = $row ['reimbursementTime'];
            $comList ['items'] [$i] ['reimbursementValue'] = $row ['reimbursementValue'];
            $comList ['items'] [$i] ['accountTime'] = $row ['accountTime'];
            $comList ['items'] [$i] ['accountValue'] = $row ['accountValue'];
            $comList ['items'] [$i] ['grantTime'] = $row ['grantTime'];
            $comList ['items'] [$i] ['grantValue'] = $row ['grantValue'];
            $comList ['items'] [$i] ['retireTime'] = $row ['retireTime'];
            $comList ['items'] [$i] ['accountComTime'] = $row ['accountComTime'];
            $comList ['items'] [$i] ['accountComValue'] = $row ['accountComValue'];
            $comList ['items'] [$i] ['accountPersonTime'] = $row ['accountPersonTime'];
            $comList ['items'] [$i] ['accountPersonValue'] = $row ['accountPersonValue'];
            $comList ['items'] [$i] ['tel'] = $row ['tel'];
            $comList ['items'] [$i] ['socialSecurityStateId'] = $row ['socialSecurityStateId'];
            $comList ['items'] [$i] ['socialSecurityState'] = $row ['socialSecurityState'];
            $i ++;
        }
        echo json_encode($comList);
        exit ();
    }
    //FIXME!! 保险list
    function searchInsuranceList(){
        $this->objDao = new SocialSecurityDao();
        $where = array ();
        $comList = array ();
        $where['disType']   =$_POST['disType'];
        $companyName    = $_POST['companyName'];
        $employName    = $_POST['employName'];
        $date =   $_POST['submitTime'];
        $start = $_REQUEST ['start'];
        $limit = $_REQUEST ['limit'];
        $sorts = $_REQUEST ['sort'];
        $dir = $_REQUEST ['dir'];
        if($date!=null) {
            $time   =   $this->AssignTabMonth($date,0);
            $where['submitTime']=$time["last"];
        }
        $where['companyName']=$companyName;
        $where['employName']=$employName;
        if (! $start) {
            $start = 0;
        }
        if (! $limit) {
            $limit = 50;
        }
        if (! $sorts) {
            $sorts = "uncheckid";
        }
        $sum =$this->objDao->searchInsuranceCount($where);
        $result=$this->objDao->searchInsurancePage($start,$limit,$sorts." ".$dir,$where);
        $comList['total']=$sum;
        $i=0;
        while ( $row = mysql_fetch_array ( $result ) ) {
            if(date("m-d",strtotime($row ['paymentTime']))<date('m-d')){
                $comList ['items'] [$i] ['paymentTime'] =0;
            }else{
                $comList ['items'] [$i] ['paymentTime'] =date("Y-m-d",strtotime($row ['paymentTime']));
            };
            $comList ['items'] [$i] ['id'] = $row ['id'];
            $comList ['items'] [$i] ['submitTime'] = $row ['submitTime'];
            $comList ['items'] [$i] ['companyName'] = $row ['companyName'];
            $comList ['items'] [$i] ['employId'] = $row ['employId'];
            $comList ['items'] [$i] ['employName'] = $row ['employName'];
            $comList ['items'] [$i] ['idClass'] = $row ['idClass'];
            $comList ['items'] [$i] ['serviceId'] = $row ['serviceId'];
            $comList ['items'] [$i] ['serviceName'] = $row ['serviceName'];
            $comList ['items'] [$i] ['base'] = $row ['base'];
            $comList ['items'] [$i] ['paymentStartTime'] =date("Y-m-d",strtotime($row ['paymentStartTime']));
            $comList ['items'] [$i] ['paymentEndTime'] =date("Y-m-d",strtotime($row ['paymentEndTime']));
            $comList ['items'] [$i] ['paymentValue'] = $row ['paymentValue'];
            $comList ['items'] [$i] ['paymentType'] = $row ['paymentType'];
            $comList ['items'] [$i] ['remark'] = $row ['remark'];
            $comList ['items'] [$i] ['unInsuranceReason'] = $row ['unInsuranceReason'];
            $comList ['items'] [$i] ['explainInfo'] = $row ['explainInfo'];
            $comList ['items'] [$i] ['entryTime'] = $row ['entryTime'];
            $comList ['items'] [$i] ['tel'] = $row ['tel'];
            $i ++;
        }
        echo json_encode($comList);
        exit ();
    }

    function addInsurance(){
        $insuranceInfo = array();
        $info = array();
        $adminId=$_SESSION['admin']['id'];
        $adminName  =  $_SESSION['admin']['name'];
        $this->objDao = new SocialSecurityDao();
        $exmsg=new EC();//设置错误信息类
        if($_REQUEST['employId-inputEl']) {
            $insuranceInfo['employId']  =$_REQUEST['employId-inputEl'];
        } else{
            $insuranceInfo['employId']  =$_REQUEST['inemployId-inputEl'];
        }
        if($_REQUEST['employId-inputEl']) {
            $insuranceInfo['employId']  =$_REQUEST['employId-inputEl'];
        } else{
            $insuranceInfo['employId']  =$_REQUEST['inemployId-inputEl'];
        }

        if($_REQUEST['companyName-inputEl']) {
            $insuranceInfo['companyName']  =$_REQUEST['companyName-inputEl'];
        } else{
            $insuranceInfo['incompanyName']  =$_REQUEST['incompanyName-inputEl'];
        }

        if($_REQUEST['employName-inputEl']) {
            $insuranceInfo['employName']  =$_REQUEST['employName-inputEl'];
        } else{
            $insuranceInfo['inemployName']  =$_REQUEST['inemployName-inputEl'];
        }

        $insuranceInfo['employName']  =$_REQUEST['employName-inputEl'];
        $insuranceInfo['idClass']  =$_REQUEST['idClass-inputEl'];
        $insuranceInfo['serviceId']  =$adminId;
        $insuranceInfo['serviceName']  =$adminName;
        $insuranceInfo['base']  =$_REQUEST['base-inputEl'];
        $insuranceInfo['paymentStartTime']  =$_REQUEST['payStart-inputEl'];
        $insuranceInfo['paymentEndTime']  =$_REQUEST['payEnd-inputEl'];
        $insuranceInfo['paymentTime']  =$_REQUEST['payTime-inputEl'];
        $insuranceInfo['paymentValue']  =$_REQUEST['payValue'];
        $insuranceInfo['paymentType']  =$_REQUEST['payType-inputEl'];
        $insuranceInfo['remark']  =$_REQUEST['remarks-inputEl'];
        $insuranceInfo['unInsuranceReason']  =$_REQUEST['unInsuranceReason-inputEl'];
        $insuranceInfo['explainInfo']  =$_REQUEST['explainInfo-inputEl'];
        $insuranceInfo['entryTime']  =$_REQUEST['entryTime-inputEl'];
        $insuranceInfo['tel']  =$_REQUEST['tel-inputEl'];

        $result = $this->objDao->addInsurance($insuranceInfo);
        if(!$result){
            $exmsg->setError(__FUNCTION__, "add business faild ");
            //事务回滚
            $this->objDao->rollback();
            $info['success']    =   false;
            $info['info']   =   "提交信息失败，请重试！";
            throw new Exception ($exmsg->error());
        }
        else{
            $info['info']   =   "提交信息成功，请等待办理！";
            $info['success']    =   true;
        }
        echo json_encode($info);
        exit;
    }

    function updateInsurance(){
        $this->objDao = new SocialSecurityDao();
        $exmsg=new EC();//设置错误信息类
        $paymentValue   =   $_POST["payValue"] ;
        $upId   =   $_POST["upid-inputEl"] ;
        $result = $this->objDao->updateInsurance($upId,$paymentValue);
        if(!$result){
            $exmsg->setError(__FUNCTION__, "add business faild ");
            //事务回滚
            $this->objDao->rollback();
            $info['success']    =   false;
            $info['info']   =   "提交信息失败，请重试！";
            throw new Exception ($exmsg->error());
        }
        else{
            $info['info']   =   "提交信息成功，请等待办理！";
            $info['success']    =   true;
        }
        echo json_encode($info);
        exit;
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
        $tel = $_REQUEST['tel'];
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
        $addBusinessLog["tel"]=$tel;
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
        $other['remarks']  =   $_POST["remarks"];
        $this->objDao = new SocialSecurityDao();
        $exmsg=new EC();//设置错误信息类
        if($updateId==null){
            echo "没有找到编号！";
            exit;
        }
        $result = $this->objDao->updateZengjian($updateId,$updateType, $other['remarks']);
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
