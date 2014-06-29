<?php
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/ServiceDao.class.php");
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
            default :
                $this->modelInput();
                break;
        }
    }
    function toCompanyTotalByTimeListPage (){
        $this->mode = 'toCompanyTotalByTimeListPage';
    }


}


$objModel = new PageIndexAction($actionPath);
$objModel->dispatcher();

