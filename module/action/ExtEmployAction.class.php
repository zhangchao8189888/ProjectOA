<?php
require_once("module/form/" . $actionPath . "Form.class.php");
require_once("module/dao/" . $actionPath . "Dao.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("tools/excel_class.php");
require_once("tools/Classes/PHPExcel.php");
require_once("tools/Util.php");

class ExtEmployAction extends BaseAction {

    function ExtEmployAction($actionPath) {
        parent::BaseAction();
        $this->objForm = new ExtEmployForm();
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
        // Controller -> Model
        switch ($this->mode) {
            case "input" :
                $this->modelInput();
                break;
            case "searchEmployList" :
                $this->searchEmployList();
                break;
            case "updateContractInfo":
                $this->updateContractInfo();
                break;
            case "searchContractInfo":
                $this->searchContractInfo();
                break;
            case "emUpdate":
                $this->emUpdate();
                break;
            case "emNoUpdate":
                $this->emNoUpdate();
                break;
            default :
                $this->modelInput();
                break;
        }
    }

    function modelInput() {
        $this->mode = "toadd";
    }

    function searchEmployList() {
        $this->objDao = new ExtEmployDao ();
        $where  =   array();
        $contractinfo   =$_REQUEST['contractinfo'];
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $where['e_company'] = $_REQUEST['e_company'];
        $where['e_type'] = $_REQUEST['e_type'];
        $where['e_name'] = $_REQUEST['e_name'];
        $where['e_num'] = $_REQUEST['e_num'];
        $where['contractinfo'] = $contractinfo;
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $josnArray = array();
        $sum = $this->objDao->getEmployListCount($where);
        $salaryList = $this->objDao->getEmployListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray['total']=$sum;
        if($contractinfo){
            $josnArray['total']=0;
        }
        $i = 0;
        while ($row = mysql_fetch_array($salaryList)) {
            $contractDate = date('Y-m-d',strtotime("+".$row ['e_hetongnian']." year",strtotime($row ['e_hetong_date'])));
            $contractaddDate = date('Y-m-d',strtotime("-60 day",strtotime($contractDate)));
            $nowDate= date('Y-m-d');
            if($contractaddDate>$nowDate){
                if(2==$contractinfo||"即将到期"==$contractinfo){
                    continue;
                }
                $josnArray ['items'] [$i] ['e_hetong_date'] = $row ['e_hetong_date'];
            }else{
                if($contractDate>$nowDate){
                    if(2==$contractinfo||3==$contractinfo){
                        continue;
                    }
                    $josnArray ['items'] [$i] ['e_hetong_date'] ="1";
                }else{
                    if("即将到期"==$contractinfo||3==$contractinfo){
                        continue;
                    }
                    $josnArray ['items'] [$i] ['e_hetong_date'] = 0;
                }
            }
            $josnArray ['items'] [$i] ['id'] = $row ['id'];
            $josnArray ['items'] [$i] ['e_name'] = $row ['e_name'];
            $josnArray ['items'] [$i] ['e_company'] = $row ['e_company'];
            $josnArray ['items'] [$i] ['e_num'] = $row ['e_num'];
            $josnArray ['items'] [$i] ['bank_name'] = $row ['bank_name'];
            $josnArray ['items'] [$i] ['bank_num'] = $row ['bank_num'];
            $josnArray ['items'] [$i] ['e_type'] = $row ['e_type'];
            $josnArray ['items'] [$i] ['shebaojishu'] = $row ['shebaojishu'];
            $josnArray ['items'] [$i] ['gongjijinjishu'] = $row ['gongjijinjishu'];
            $josnArray ['items'] [$i] ['laowufei'] = $row ['laowufei'];
            $josnArray ['items'] [$i] ['canbaojin'] = $row ['canbaojin'];
            $josnArray ['items'] [$i] ['danganfei'] = $row ['danganfei'];
            $josnArray ['items'] [$i] ['memo'] = $row ['memo'];
            $josnArray ['items'] [$i] ['e_hetongnian'] = $row ['e_hetongnian'];
            $i++;
        }
        echo json_encode($josnArray);
        exit ();
    }

    function updateContractInfo(){
        $this->objDao = new ExtEmployDao ();
        $exmsg=new EC();//设置错误信息类
        $info=  array();
        $id=$_REQUEST['up_id-inputEl'];
        $e_hetong_date=$_REQUEST['up_e_hetong_date-inputEl'];
        $e_hetongnian=$_REQUEST['up_e_hetongnian-inputEl'];

        if(null == $id||null == $e_hetong_date|| null ==$e_hetongnian){
            $info['success']    =   false;
            $info['info']   =   "更改失败，请重试！";
        }
        else{
            $result = $this->objDao->updateInsurance($id,$e_hetong_date,$e_hetongnian);
        }
        if(!$result){
            $exmsg->setError(__FUNCTION__, "update contractinfo faild ");
            //事务回滚
            $this->objDao->rollback();
            $info['success']    =   false;
            $info['info']   =   "更改失败，请重试！";
            throw new Exception ($exmsg->error());
        }
        else{
            $info['success']    =   true;
            $info['info']   =   "更改成功！";
        }
        echo json_encode($info);
        exit;

    }

    function searchContractInfo(){
        $this->objDao = new ExtEmployDao ();
        $id=$_REQUEST['id'];
        $josnArray=  array();
        $exmsg=new EC();//设置错误信息类
        if(null == $id){
            exit;
        }
        else{
            $result = $this->objDao->searchInsurance($id);
        }
        if(!$result){
            $exmsg->setError(__FUNCTION__, "search contractinfo faild ");
            //事务回滚
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        else{
            while ($row = mysql_fetch_array($result)) {
                $josnArray ['e_name'] = $row ['e_name'];
                $josnArray ['e_hetong_date'] = $row ['e_hetong_date'];
                $josnArray ['e_hetongnian'] = $row ['e_hetongnian'];
                $josnArray ['e_id'] = $row ['id'];
                $josnArray ['e_company'] = $row ['e_company'];
                $josnArray ['e_num'] = $row ['e_num'];
                $josnArray ['bank_name'] = $row ['bank_name'];
                $josnArray ['bank_num'] = $row ['bank_num'];

                $josnArray ['e_type'] = $row ['e_type'];
                $josnArray ['shebaojishu'] = $row ['shebaojishu'];
                $josnArray ['gongjijinjishu'] = $row ['gongjijinjishu'];
                $josnArray ['laowufei'] = $row ['laowufei'];
                $josnArray ['canbaojin'] = $row ['canbaojin'];
                $josnArray ['danganfei'] = $row ['danganfei'];
                $josnArray ['memo'] = $row ['memo'];
                $josnArray ['e_state'] = $row ['e_state'];
                if($row ['e_teshustate']==1){
                    $josnArray ['e_teshustate']="残疾人";
                }else if( $josnArray ['e_teshustate']==0){
                    $josnArray ['e_teshustate']="非残疾人";
                }
            }
        }
        echo json_encode($josnArray);;
        exit;
    }

    function emUpdate(){
        $exmsg=new EC();
        $employ=array();
        $employ['id']=$_POST['up_emp_id-inputEl'];
        $employ['e_name']=$_POST['up_emp_name-inputEl'];
        $employ['e_num']=$_POST['up_emp_e_num-inputEl'];
        $employ['bank_name']=$_POST['up_emp_bank_name-inputEl'];
        $employ['bank_num']=$_POST['up_emp_bank_num-inputEl'];
        $employ['e_type']=$_POST['up_emp_e_type-inputEl'];
        $employ['e_company']=$_POST['up_emp_e_company-inputEl'];
        $employ['shebaojishu']=$_POST['up_emp_shebaojishu-inputEl'];
        $employ['gongjijinjishu']=$_POST['up_emp_gongjijinjishu-inputEl'];
        $employ['laowufei']=$_POST['up_emp_laowufei-inputEl'];
        $employ['canbaojin']=$_POST['up_emp_canbaojin-inputEl'];
        $employ['danganfei']=$_POST['up_emp_danganfei-inputEl'];
        $employ['e_hetong_date']=$_POST['up_emp_e_hetong_date-inputEl'];
        $employ['e_hetongnian']=$_POST['up_emp_e_hetongnian-inputEl'];
        $employ['e_teshustate']=$_POST['up_emp_e_teshustate-inputEl'];
        $employ['memo']=$_POST['up_emp_memo-inputEl'];
        $this->objDao=new ExtEmployDao();
        $retult=$this->objDao->updateEm($employ);
        if(!$retult){
            $exmsg->setError(__FUNCTION__, "update employ  faild ");
            $info['success']    =   false;
            $info['info']   =   "修改失败！";
            throw new Exception ($exmsg->error());
        }
        $adminPO=$_SESSION['admin'];
        $opLog=array();
        $opLog['who']=$adminPO['id'];
        $opLog['what']=0;
        $opLog['Subject']=OP_LOG_UPDATE_EMPLOY;
        $opLog['memo']='';
        $rasult=$this->objDao->addOplog($opLog);
        if(!$rasult){
            $exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
            $info['success']    =   false;
            $info['info']   =   "修改操作失败！";
            throw new Exception ($exmsg->error());
        }
        $info['success']    =   true;
        $info['info']   =   "更改成功！";
        echo json_encode($info);
        exit;
    }

    function emNoUpdate() {
        $emid = $_POST['up_emp_id-inputEl'];
        $emNo = $_POST['up_emp_e_num-inputEl'];
        $this->objDao = new ExtEmployDao();
        $employ = $this->objDao->searchInsurance($emid);
        $emper = $this->objDao->getEmByEno($emNo);
        if (!empty($emper)) {
            $info['success']    =   false;
            $info['info']   =   "此员工身份证号已存在，请重新确认";
            exit;
        }
        $exmsg = new EC(); //设置错误信息类
        $this->objDao->beginTransaction();
        $result = $this->objDao->updateEmployNoByid($emid, $emNo);
        $error = 0;
        if (!$result) {
            $exmsg->setError(__FUNCTION__, "update emNo   faild ");
            $info['success']    =   false;
            $info['info']   =   "更新employ表失败";
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());

        }
        $this->objDao = new SalaryDao();
        $result = $this->objDao->updateSalaryEmNoByEmNo($emNo, $employ['e_num']);
        if (!$result) {
            $exmsg->setError(__FUNCTION__, "update emNo   faild ");
            $info['success']    =   false;
            $info['info']   =   "更新salary表失败";
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        $result = $this->objDao->updateNianSalaryEmNoByEmNo($emNo, $employ['e_num']);
        if (!$result) {
            $exmsg->setError(__FUNCTION__, "update emNo   faild ");
            $info['success']    =   false;
            $info['info']   =   "更新niansalary表失败";
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        $result = $this->objDao->updateErSalaryEmNoByEmNo($emNo, $employ['e_num']);
        if (!$result) {
            $exmsg->setError(__FUNCTION__, "update emNo   faild ");
            $info['success']    =   false;
            $info['info']   =   "更新ersalary表失败";
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        //事务提交
        if (!$error) {
            $this->objDao->commit();
        }
        $info['success']    =   true;
        $info['info']   =   "更改成功！";
        echo json_encode($info);
        exit;
    }
}

$objModel = new ExtEmployAction($actionPath);
$objModel->dispatcher();
?>
