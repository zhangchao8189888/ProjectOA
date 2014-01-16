<?php
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/EmployDao.class.php");
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
require_once("tools/sumSalary.class.php");
require_once("tools/Classes/PHPExcel.php");
class ExtSalaryAction extends BaseAction{
    /*
     *
     * @param $actionPath
     * @return SalaryAction
     */
    function ExtSalaryAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new ExtSalaryForm();
        $this->actionPath = $actionPath;
    }
    function dispatcher(){
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
    function setMode()
    {
        // 模式设定
        $this->mode = $_REQUEST['mode'];
    }
    function controller()
    {
        switch($this->mode) {
            case "searchSalaryTimeListJosn" :
                $this->searchSalaryTimeListJosn();
                break;
            case "searchErSalaryTimeListJosn" :
                $this->searchErSalaryTimeListJosn();
                break;
            case "searhSalaryNianTimeListJosn":
            	$this->searhSalaryNianTimeListJosn();
            case "searchGeshuiListJosn" :
            	$this->searchGeshuiListJosn();
            	break;
            case "searchGeshuiTypeJosn" :
            	$this->searchGeshuiTypeJosn();
            	break;
            default :
                $this->modelInput();
                break;
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
        $time["first"]  =    $first_date;
        $time["last"]   =      $for_day;
        return $time;
    }

    /**
     * 查询年终奖集合
     */
    function searhSalaryNianTimeListJosn() {
    	$this->objDao=new SalaryDao();
    	$start=$_REQUEST['start'];
    	$limit=$_REQUEST['limit'];
    	$sorts=$_REQUEST['sort'];
    	$dir=$_REQUEST['dir'];
    	$companyName=$_REQUEST['companyName'];
    	$salTime=$_REQUEST['salTime'];
    	$opTime=$_REQUEST['opTime'];
    	
    	if(!$start){
    		$start=0;
    	}
    	if(!$limit){
    		$limit=50;
    	}
        $where=array();
        if($opTime) {
            $time=$this->AssignTabMonth($opTime,0);
            $where['op_salaryTime']=$time["next"];
            $where['op_time']   =   $time["data"];
        }
    	$where['companyName']=$companyName;
    	$where['salaryTime']=$salTime;
    	$sum =$this->objDao->searhSalaryNianTimeListCount($where);
    	$salaryNianTimeList=$this->objDao->searhSalaryNianTimeListPage($start,$limit,$sorts." ".$dir,$where);
    	$josnArray=array();
    	$josnArray['total']=$sum;
    	$i=0;
    	while ($row=mysql_fetch_array($salaryNianTimeList) ){
    		$josnArray['items'][$i]['id']=$row['id'];
    		$josnArray['items'][$i]['company_name']=$row['company_name'];
    		$josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
    		$josnArray['items'][$i]['op_salaryTime']=$row['op_salaryTime'];
    		$i++;
    	}
    	echo json_encode($josnArray);
    	exit;
    }
    
    
    /**
     * 查询工资日期集合
     */
    function searchSalaryTimeListJosn(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $salTime=$_REQUEST['salTime'];
        $opTime=$_REQUEST['opTime'];

        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where=array();
        if($opTime) {
            $time=$this->AssignTabMonth($opTime,0);
            $where['op_salaryTime']=$time["next"];
            $where['op_time']   =   $time["data"];
        }

        $where['companyName']=$companyName;
        $where['salaryTime']=$salTime;
        $sum =$this->objDao->searhSalaryTimeListCount($where);

        $salaryTimeList=$this->objDao->searhSalaryTimeListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        /**
         * companyId	int(11)	No
        salaryTime	date	No
        op_salaryTime	datetime	No
        op_id	int(11)	Yes
        salary_state	int(2)	No	0
        salary_leijiyue	float(11,2)	Yes
         */
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['company_name']=$row['company_name'];
            $josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
            $josnArray['items'][$i]['op_salaryTime']=$row['op_salaryTime'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
    

    //个税统计BY孙瑞鹏
    function searchGeshuiListJosn(){
    	$this->objDao=new SalaryDao();
    	$start=$_REQUEST['start'];
    	$limit=$_REQUEST['limit'];
    	$sorts=$_REQUEST['sort'];
    	$dir=$_REQUEST['dir'];
    	$companyName=$_REQUEST['companyName'];
    	$salTime=$_REQUEST['salTime'];
    
    	if(!$start){
    		$start=0;
    	}
    	if(!$limit){
    		$limit=50;
    	}
    	$where=array();
    	$where['companyName']=$companyName;
    	$where['salaryTime']=$salTime;
    
    	$sum =$this->objDao->searhSalaryTimeCount($where);
    
    	$salaryTimeList=$this->objDao->searhGeshuiListPage($start,$limit,$sorts." ".$dir,$where);
    	$josnArray=array();
    	$josnArray['total']=$sum;
    	$i=0;
    	/**
    	 * companyId	int(11)	No
    	 salaryTime	date	No
    	 op_salaryTime	datetime	No
    	 op_id	int(11)	Yes
    	 salary_state	int(2)	No	0
    	 salary_leijiyue	float(11,2)	Yes
    	 */
    	while ($row=mysql_fetch_array($salaryTimeList) ){
    		$josnArray['items'][$i]['company_id']=$row['company_id'];
    		$josnArray['items'][$i]['company_name']=$row['company_name'];
    		$josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
            $josnArray['items'][$i] ['daikou'] = $row ['daikou'];
            $josnArray['items'][$i] ['bukou'] = $row ['bukou'];
            $josnArray['items'][$i] ['nian'] = $row ['nian'];
    		$josnArray['items'][$i]['geshuiSum']=$row['geshuiSum'];
    		$i++;
    	}
    	echo json_encode($josnArray);
    	exit;
    }
    
    
    //个税类型BY孙瑞鹏
    function searchGeshuiTypeJosn(){
    	$this->objDao=new SalaryDao();
    	$start=$_REQUEST['start'];
    	$limit=$_REQUEST['limit'];
    	$sorts=$_REQUEST['sort'];
    	$dir=$_REQUEST['dir'];
    	$companyName=$_REQUEST['companyName'];
    	$salTime=$_REQUEST['salTime'];
    
    	if(!$start){
    		$start=0;
    	}
    	if(!$limit){
    		$limit=50;
    	}
    	$where=array();
    	$where['companyName']=$companyName;
    	$where['salaryTime']=$salTime;
    
    	$sum =$this->objDao->searhSalaryTypeCount($where);
    
    	$salaryTimeList=$this->objDao->searhGeshuiTypePage($start,$limit,$sorts." ".$dir,$where);
    	$josnArray=array();
    	$josnArray['total']=$sum;
    	$i=0;
    	/**
    	 * companyId	int(11)	No
    	 salaryTime	date	No
    	 op_salaryTime	datetime	No
    	 op_id	int(11)	Yes
    	 salary_state	int(2)	No	0
    	 salary_leijiyue	float(11,2)	Yes
    	 */
    	while ($row=mysql_fetch_array($salaryTimeList) ){
    		$josnArray['items'][$i]['id']=$row['id'];
    		$josnArray['items'][$i]['company_name']=$row['company_name'];
    		if($row['geshui_dateType']==1){
    			$josnArray['items'][$i]['geshui_dateType']="本月报本月";
    		}
    		elseif ($row['geshui_dateType']==2){
    			$josnArray['items'][$i]['geshui_dateType']="本月报上月";
    		}
    		$i++;
    	}
    	echo json_encode($josnArray);
    	exit;
    }
     
    function searchErSalaryTimeListJosn(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['companyName'];
        $salTime=$_REQUEST['salTime'];
        $opTime=$_REQUEST['opTime'];

        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where=array();
        $where['companyName']=$companyName;
        $where['salaryTime']=$salTime;
        $where['op_salaryTime']=$opTime;

        $sum =$this->objDao->searhErSalaryTimeListCount($where);

        $salaryTimeList=$this->objDao->searhErSalaryTimeListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;

        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['id']=$row['id'];
            $josnArray['items'][$i]['company_name']=$row['company_name'];
            $josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
            $josnArray['items'][$i]['op_salaryTime']=$row['op_salaryTime'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }
}



$objModel = new ExtSalaryAction($actionPath);
$objModel->dispatcher();



?>
