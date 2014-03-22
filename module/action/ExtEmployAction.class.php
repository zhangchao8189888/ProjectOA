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
            }
        }
        echo json_encode($josnArray);;
        exit;
    }
}

$objModel = new ExtEmployAction($actionPath);
$objModel->dispatcher();
?>
