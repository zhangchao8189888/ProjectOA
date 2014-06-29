<?php
require_once("common/BaseDao.class.php");
require_once("common/BaseForm.class.php");
class BaseAction
{
	//action路径
	var $actionPath;
	//Form对象
	var $objForm;
	//操作DB对象
	var $objDao;
	//页面模式
	var $mode;
	//页面
	var $pageId;
	//设置管理员属性
	var $admin;
    function BaseAction()
    {
    }
    function initBase()
    {
    	//开始SESSION
    	//startSession();
    	//页面ID设定
    	//setPageID($this->actionPath);
    	//页面ID取得
    	//$this->pageId = getPageID(); 
    }
    function view()
    {
        // 取得画面表示文件
        $nextPageFile = $this->objForm->getTpl($this->mode);
        // 取得画面表示数据
        $form_data = $this->objForm->getFormData();
        // 画面表示
        require_once("tpl/$nextPageFile");
        // 画面表示完了、清空SESSION
        //unsetNamespace(getPageID());
        // 对象释放
        unset($this->objForm);
        unset($this->objDao);
    }
   /**
    *关闭数据库
    */ 
   function closeDB()
    {
        if (isset($this->objDao)) {
            $this->objDao->closeConnect();
        }
    }
    function AssignTabMonth($date,$step){
        $date= date("Y-m-d",strtotime($step." months",strtotime($date)));//得到处理后的日期（得到前后月份的日期）
        $u_date = strtotime($date);
        $days=date("t",$u_date);// 得到结果月份的天数

        //月份第一天的日期
        $first_date=date("Y-m",$u_date).'-01';
        for($i=0;$i<$days;$i++){
            $for_day=date("Y-m-d",strtotime($first_date)+($i*3600*24));
        }
        $time = array ();
        $time["data"]   =  $date ;
        $time["next"]   =   (date("Y-m-d",strtotime("+1 day",strtotime($date))));
        $time["month"]  =   (date("Y-m",strtotime($date)));
        $time["first"]  =    $first_date;
        $time["last"]   =      $for_day;
        return $time;
    }
}
?>
