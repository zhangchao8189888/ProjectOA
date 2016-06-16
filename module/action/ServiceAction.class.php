<?php 
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/".$actionPath."Dao.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/EmployDao.class.php");
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
class ServiceAction extends BaseAction{
 /*
     *
     * @param $actionPath
     * @return AdminAction
     */
 function ServiceAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new ServiceForm();
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
            case "old" :
                $this->old();
                break;
            case "getAdminComList" :
                $this->getAdminCompanyList();
                break;
            case "getComAdminListJosn":
            	$this->getComAdminListJosn();
                break;
            case "toOpCompanyList":
            	$this->toOpCompanyList();
                break;
            case "getOpCompanyListJson":
            	$this->getOpCompanyListJson();
                break;
            case "getSuperCompany":
                $this->getSuperCompany();
                break;
            case "addServiceCompany":
            	$this->addServiceCompany();
                break;
            case "addOpCompanyListJson":
            	$this->addOpCompanyListJson();
                break;
            case "addCaiwuOpCompanyListJson":
                $this->addCaiwuOpCompanyListJson();
                break;
            case "getOtherAdminComList":
            	$this->getOtherAdminComList();
                break;
            case "getEmList":
            	$this->getEmployList();
                break;
            case"toEmployAdd":
            	$this->toEmployAdd();
                break;
            case"addEmp":
            	$this->addEmp();
                break;
            case"getEmp":
            	$this->getEmployById();
                break;
            case"emUpdate":
            	$this->emUpdate();
                break;
             case"searchSalaryByOther":
            	$this->searchSalaryByOther();
                break;   
             case "makeSal":
             	$this->makeSal();
                break;
             case "toHedaoqi":
             	$this->toHedaoqi();
                break; 
             case  "cancelService":
             	$this->cancelService();
                break; 
             case  "tiaoguoFaPiao":
             	$this->tiaoguoFaPiao();
                break;
             case  "lizhiEm":
             	$this->updateEmployStat();
                break;
             case "salarySend":
             	$this->salarySend();
                break;
             case "toUpdateEmpList":
             	$this->toUpdateEmpList();
                break;
             case "updateEmpList":
             	$this->updateEmpList();
                break;
             case "getJson":
             	$this->getJson();
                break;
            case "toAddNotice":
                $this->toAddNotice();
                break;
            case "addNotice":
                $this->addNotice();
                break;
            case "delNotice":
                $this->delNotice();
                break;
            case "updateNotice":
                $this->updateNotice();
                break;
            case "toModifyPassword":
                $this->toModifyPassword();
                break;
            case "getUserListJson":
                $this->getUserListJson();
                break;
            case "updatePassword":
                $this->updatePassword();
                break;
            default :
                $this->modelInput();
                break;
        }



    }
    function updatePassword() {
        $u_id = $_REQUEST['uid'];
        $this->objDao=new ServiceDao();
        $result = $this->objDao->modifyUserPass($u_id);
        $data = array();
        if ($result) {
            $data['mess'] ='密码修改成功';
            $data['code'] ='100000';

        } else {
            $data['mess'] ='密码修改失败';
            $data['code'] ='100001';
        }
        echo json_encode($data);
        exit;
    }
    function getUserListJson() {
        $e_num = $_REQUEST['e_num'];
        $this->objDao=new ServiceDao();
        $result = $this->objDao->getUserList($e_num);
        $list = array();
        while ($row = mysql_fetch_array($result)) {
            $list[] =$row;
        }
        echo json_encode($list);
        exit;
    }
    function toModifyPassword() {
        $this->mode="toModifyPassword";
    }
    function addNotice () {
        $notice = array();
        $title = $_REQUEST['title'];
        $content = $_REQUEST['content'];
        $company_name = $_REQUEST['company_name'];
        $this->objDao=new ServiceDao();
        $company = $this->objDao->searchCompanyByName($company_name);
        if ($title== '' || $content== '' || $company_name== ''){
            $msg['code'] = 10002;
            $msg['msg'] = '字段不能为空';
            echo json_encode($msg);
            exit;
        }
        $notice['title'] = $title;
        $notice['content'] = $content;
        $notice['company_id'] = $company['id'];
        $result = $this->objDao->saveNotice($notice);
        if ($result){
            $msg['code'] = 10000;
            $msg['msg'] = '添加成功';
            echo json_encode($msg);
            exit;
        } else {
            $msg['code'] = 10002;
            $msg['msg'] = '添加失败';
            echo json_encode($msg);
            exit;
        }

    }
    function delNotice () {
        $noticeId = $_REQUEST['notice_id'];
        $this->objDao=new ServiceDao();
        $result = $this->objDao->delNotice($noticeId);
        if ($result){
            $msg['code'] = 10000;
            $msg['msg'] = '添加成功';
            echo json_encode($msg);
            exit;
        } else {
            $msg['code'] = 10002;
            $msg['msg'] = '添加失败';
            echo json_encode($msg);
            exit;
        }
    }
    function updateNotice () {
        $noticeId = $_REQUEST['notice_id'];
        $title= $_REQUEST['title'];
        $contnt = $_REQUEST['content'];
        $notice['id'] = $noticeId;
        $notice['title'] = $title;
        $notice['content'] = $contnt;
        $this->objDao=new ServiceDao();
        $result = $this->objDao->updateNotice($notice);
        if ($result){
            $msg['code'] = 10000;
            $msg['msg'] = '添加成功';
            echo json_encode($msg);
            exit;
        } else {
            $msg['code'] = 10002;
            $msg['msg'] = '添加失败';
            echo json_encode($msg);
            exit;
        }
    }
  function toAddNotice (){
      $this->mode="toAddNotice";
      $this->objDao=new ServiceDao();
      $sum =$this->objDao->searchCountNoticeList();
      $pagesize=NOTICE_PAGE_SIZE;
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
      $result=$this->objDao->searchNoticeList();
      $noticeList = array();
      $i = 0;
      while ($row = mysql_fetch_array($result)) {
          $company = $this->objDao->getCompanyById($row['company_id']);
          $row['company_name'] = $company['company_name'];
          $noticeList[$i] = $row;
          $i++;
      }
      $this->objForm->setFormData("startIndex",$startIndex);
      $this->objForm->setFormData("total",$total);
      $this->objForm->setFormData("pageindex",$pageindex);
      $this->objForm->setFormData("pagesize",$pagesize);
      $this->objForm->setFormData("noticeList",$noticeList);
  }
  function getJson(){
  	$arry=array();
  }
    function old(){
        $this->mode="old";
    }
  function getAdminCompanyList(){
  	$this->mode="serviceFrist";
  }
  function getComAdminListJosn(){
  	//得到管理员
    $admin=$_SESSION["admin"];
  	$searchType=$_POST['sType'];
  	if(empty($searchType)){
  		$searchType=1;
  	}
  	$this->objDao=new ServiceDao();
  	$adminCompany=$this->objDao->getAdminOpComListByAdminId($admin['id']);
  	$comArray=array();
  	$date_reg=date("Y-m-d");
  	$dateList=explode("-",$date_reg);
  	$dateNow=$dateList[0]."-".$dateList[1]."-01";
  	$dateEnd=$dateList[0]."-".$dateList[1]."-31";
  	$i=0;
  	while($row=mysql_fetch_array($adminCompany)){
  		//查询当月一次工资是否发放
  		$salTimePo=$this->objDao->searchSalTimeByComIdAndSalTime($row['companyId'],$dateNow,$dateEnd,$searchType);
  	    $rowSal['company_name']=$row['company_name'];
  	    $rowSal['companyId']=$row['companyId'];
  		if($searchType==1){//第一条件按照工资月份查询
  	    	$rowSal['salDate']=$dateNow;
  	        $rowSal['op_salaryTime']=$salTimePo['op_salaryTime'];
  	    }elseif($searchType==2){//第二种条件按照操作时间查询
  	    	$rowSal['salDate']=$salTimePo['salaryTime'];
  	    	if(empty($salTimePo['op_salaryTime'])){
  	    	$rowSal['op_salaryTime']=$dateList[0]."-".$dateList[1];
  	    	}else{
  	        $rowSal['op_salaryTime']=$salTimePo['op_salaryTime'];
  	    	}
  	    }
  		if(!$salTimePo){
  		$rowSal['salStat']='<a href="#" onclick=makeSal('.$row['companyId'].',"'.$dateNow.'","first") target="_self"><font color="red">未做工资</font></a>';
  		$rowSal['salTimeid']=-1;
  		$rowSal['fa_state']='<font color=blue>未提交审批</font>';
  	    }else{
  			$rowSal['salStat']='<a href="index.php?action=SaveSalary&mode=searchSalaryById&id='.$salTimePo['id'].'" target="_blank">
          <font color="green">已做工资</font></a>';
  			$rowSal['salTimeid']=$salTimePo['id'];
  			$this->objDao=new SalaryDao();
  			$bill_fa=$this->objDao->searchBillBySalaryTimeId($salTimePo['id'],4);
	  		if($bill=mysql_fetch_array($bill_fa)){
		  		 if($bill['bill_value']==0){
		  		 	$rowSal['fa_state']='申请发放审批中';
		  		 }elseif($bill['bill_value']==1){
		  		 	$rowSal['fa_state']='<font color=green>批准发放</font>';
		  		 }elseif($bill['bill_value']==2){
		  		 	$rowSal['fa_state']='<font color=red>未批准发放</font>';
		  		 }
	  	    }else{
	  	    	 $rowSal['fa_state']='<a href="index.php?action=Service&mode=salarySend&timeid='.$salTimePo['id'].'" title="申请发放审批" target="_self">未提交审批</a>';
	        }
  	    //$row['fa_value']=$bill['bill_value'];
  	    }
  		$rowSal['salNianStat']='<a href="#" onclick=makeSal('.$row['companyId'].',"'.$dateNow.'","nian") ><font color="red">无</font></a>';
  		$rowSal['salOrStat']='<a href="#" onclick=makeSal('.$row['companyId'].',"'.$dateNow.'","second") ><font color="red">无</font></a>';
  	    $sqlOr=$this->objDao->searchOrSalTimeByComIdAndSalTime($row['companyId'],$salTimePo['salaryTime']);
  			if($sqlOr){
  				while ($rowEr=mysql_fetch_array($sqlOr)){
  					if($rowEr['salaryType']==ER_SALARY_TIME_TYPE){
  						$rowSal['salOrStat']='<a href="index.php?action=SaveSalary&mode=searchErSalaryById&id='.$rowEr['id'].'" target="_blank"><font color="green">已做二次工资</font></a>';
  					}elseif($rowEr['salaryType']==SALARY_TIME_TYPE){
  						$rowSal['salNianStat']='<a href="index.php?action=SaveSalary&mode=searchErSalaryById&id='.$rowEr['id'].'" target="_blank"><font color="green">已做年终奖</font></a>';
  					}

  				}
  			}
  		if($salTimePo['salary_state']<1){
  		    $rowSal['fastat']="<font color='red'>未开发票</font>";
  	     }else{
  		    $rowSal['fastat']="<font color='green'>已开发票</font>";
  	    }
  	    $rowSal['opTime']=$row['opTime'];

  		$comArray[$i]=$rowSal;
  		$i++;
  	}
  	echo json_encode($comArray);
  	exit;
  }
  function getOtherAdminComList(){
  	$this->mode="serviceFrist";
  	$admin=$_SESSION["admin"];
  	$searchType=$_REQUEST['sType'];
  if(empty($searchType)){
  		$searchType=1;
  	}
  	$this->objDao=new ServiceDao();
  	$result=$this->objDao->getAdminOpComListByAdminId($admin['id']);
  	$comList=array();
    $year=$_REQUEST['yearDate'];
  	if(empty($year)){
    $date_reg=date("Y-m-d");
  	$dateList=explode("-",$date_reg);
  	$date=$dateList[0]."-".$dateList[1]."-01";
  	}else{
  	$mon=$_REQUEST['monDate'];
  	$date=$year."-".$mon."-01";
  	}
  	$dateEnd=$year."-".$mon."-31";
  	$i=0;
  	while($row=mysql_fetch_array($result)){
  		//查询当月工资是否发放
  		$results=$this->objDao->searchSalTimeByComIdAndSalTime($row['companyId'],$date,$dateEnd,$searchType);
  		$rowSal['company_name']=$row['company_name'];
  		$rowSal['companyId']=$row['companyId'];
  		if($searchType==1){
  	    	$rowSal['salDate']=$date;
  	        $rowSal['op_salaryTime']=$results['op_salaryTime'];
  	    }elseif($searchType==2){
  	    	$rowSal['salDate']=$results['salaryTime'];
  	    	if(empty($results['op_salaryTime'])){
  	    		$rowSal['op_salaryTime']=$year."-".$mon;
  	    	}else{
  	    		//echo $results['op_salaryTime'].">>>>>>>>>>>>>>>";
  	        $rowSal['op_salaryTime']=$results['op_salaryTime'];
  	    	}
  	    }
  		if(!$results){
  			$rowSal['salStat']=0;
  			$rowSal['salTimeid']=-1;
  			$rowSal['fa_state']=-1;
  		}else{
  			$rowSal['salStat']=$results['id'];
  			$rowSal['salTimeid']=$results['id'];
  		$this->objDao=new SalaryDao();
  			$bill_fa=$this->objDao->searchBillBySalaryTimeId($results['id'],4);
  		if($bill=mysql_fetch_array($bill_fa)){
  			$rowSal['fa_state']=$bill['bill_value'];
  	    }else{
  	    	$rowSal['fa_state']='<font color=red>未批准发放</font>';
  	    }
  		}
  	  $rowSal['salNianStat']=0;
  	  $rowSal['salOrStat']=0;
  	  $sqlOr=$this->objDao->searchOrSalTimeByComIdAndSalTime($row['companyId'],$results['salaryTime']);
  	     if($sqlOr){
  				while ($rowEr=mysql_fetch_array($sqlOr)){
  					if($rowEr['salaryType']==ER_SALARY_TIME_TYPE){
  					      $rowSal['salOrStat']=$rowEr['id'];
  					}elseif($rowEr['salaryType']==SALARY_TIME_TYPE){
  					      $rowSal['salNianStat']=$rowEr['id'];
  					}

  				}
  			}
  		if($results['salary_state']<1){
  		    $rowSal['fastat']=0;
  	     }else{
  		    $rowSal['fastat']=1;
  	    }
  	    $rowSal['mark']=$results['mark'];
  		$comList[$i]=$rowSal;
  		$i++;
  	}
  	echo json_encode($comList);
  	exit;
  }
  function toOpCompanyList(){
  	$this->mode="toOpCompanyList";
  	$this->objDao=new SalaryDao();
  	$salaryTimeList=$this->objDao->searchCompanyList();
  	$this->objForm->setFormData("salaryTimeList",$salaryTimeList);
  }
//获得所有一级公司BY孙瑞鹏
    function getSuperCompany(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $key=$_REQUEST['Key'];
        /**
         * sorts = Replace(Trim(Request.Form("sort")),"'","")
        dir = Replace(Trim(Request.Form("dir")),"'","")
         */
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        $where="1=1";
        if($key){
            $where.=" and company_name like '%$key%'";
        }
        $sum =$this->objDao->g_db_countSuper("OA_company","*",$where);
        $salaryTimeList=$this->objDao->searchCompanyListSuper($start,$limit,$sorts." ".$dir,$where);
        $comArray=array();
        $comArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $comArray['items'][$i]['id']=$row['id'];
            $comArray['items'][$i]['company_name']=$row['company_name'];
            $i++;
        }
        echo json_encode($comArray);
        exit;
    }
  function getOpCompanyListJson(){
  	$this->objDao=new SalaryDao();
  	$start=$_REQUEST['start'];
  	$limit=$_REQUEST['limit'];
  	$sorts=$_REQUEST['sort'];
  	$dir=$_REQUEST['dir'];
  	$key=$_REQUEST['Key'];
  	/**
  	 * sorts = Replace(Trim(Request.Form("sort")),"'","")
dir = Replace(Trim(Request.Form("dir")),"'","")
  	 */
  	if(!$start){
  		$start=0;
  	}
  	if(!$limit){
  		$limit=50;
  	}
  	$where="1=1";
  	if($key){
  		$where.=" and company_name like '%$key%'";
  	}
  	$sum =$this->objDao->g_db_count("OA_company","*",$where);
  	$salaryTimeList=$this->objDao->searchCompanyListAll($start,$limit,$sorts." ".$dir,$where);
  	$comArray=array();
  	$comArray['total']=$sum;
  	$i=0;
  	while ($row=mysql_fetch_array($salaryTimeList) ){
  		$comArray['items'][$i]['id']=$row['id'];
  		$comArray['items'][$i]['company_name']=$row['company_name'];
  		$i++;
  	}
  	echo json_encode($comArray);
  	exit;
  }
  function addServiceCompany(){
  	$salTime=$_POST['timeid'];
	$salTimeList=array();
	$admin=$_SESSION["admin"];
	$salTimeList=explode("*",$salTime);
	//$date_reg=date("Y-m-d");
	$errorMage=array();
	for($i=0;$i<(count($salTimeList)-1);$i++){
		$this->objDao=new ServiceDao();
		$adminCom=array();
		$adminCom['adminId']=$admin['id'];
		$adminCom['companyId']=$salTimeList[$i];
		//先查询
		$result=$this->objDao->searchAdminCompany($adminCom['companyId']);
		if(!$result){
		$result=$this->objDao->addAdminCompany($adminCom);
		}else{
			//$errorMage
			$com=$this->objDao->getCompanyById($adminCom['companyId']);
			if($result['adminId']==$adminCom['adminId']){
				$error="该客服已经添加该公司：{$com['company_name']}";
			}else{
				$admin=$this->objDao->getAdminById($adminCom['adminId']);
			    $error="客服:{$admin['name']}，已经添加该公司：{$com['company_name']}";
			}
			$errorMage[$i]=$error;
		}
	}
	$this->getAdminCompanyList();
	$this->objForm->setFormData("error",$errorMage);
  }
  function addOpCompanyListJson(){
    $salTimeList=$_POST['ids'];
	$admin=$_SESSION["admin"];
	$salTimeList=json_decode($salTimeList);
	$html="";
	for($i=0;$i<(count($salTimeList));$i++){
		$this->objDao=new ServiceDao();
		$adminCom=array();
		$adminCom['adminId']=$admin['id'];
		$adminCom['companyId']=$salTimeList[$i];
		//先查询
		$result=$this->objDao->searchAdminCompany($adminCom['companyId']);
		$com=$this->objDao->getCompanyById($adminCom['companyId']);
		if(!$result){
		$result=$this->objDao->addAdminCompany($adminCom);
		$html.="<div><font color=green>{$com['company_name']} :添加管理成功</font></div>";
		}else{

			if($result['adminId']==$adminCom['adminId']){
				$html.="<div><font color=red>你已经添加该公司：{$com['company_name']}</font></div>";
			}else{
				$admin=$this->objDao->getAdminById($adminCom['adminId']);
			    $html.="<div><font color=red>客服:{$admin['name']}，已经添加该公司：{$com['company_name']}</font></div>";
			}
		}
	}
	echo $html;
  	exit;
  }
    function addCaiwuOpCompanyListJson(){
        $salTimeList=$_POST['ids'];
        $admin=$_SESSION["admin"];
        $salTimeList=json_decode($salTimeList);
        $html="";
        for($i=0;$i<(count($salTimeList));$i++){
            $this->objDao=new ServiceDao();
            $adminCom=array();
            $adminCom['adminId']=$admin['id'];
            $adminCom['companyId']=$salTimeList[$i];
            //先查询
            $result=$this->objDao->searchAdminCompany($adminCom['companyId'],$admin['id']);
            $com=$this->objDao->getCompanyById($adminCom['companyId']);
            if(!$result){
                $result=$this->objDao->addAdminCompany($adminCom);
                $html.="<div><font color=green>{$com['company_name']} :添加管理成功</font></div>";
            }else{

                if($result['adminId']==$adminCom['adminId']){
                    $html.="<div><font color=red>你已经添加该公司：{$com['company_name']}</font></div>";
                }else{
                    $admin=$this->objDao->getAdminById($adminCom['adminId']);
                    $html.="<div><font color=red>客服:{$admin['name']}，已经添加该公司：{$com['company_name']}</font></div>";
                }
            }
        }
        echo $html;
        exit;
    }
  function getEmployList(){
  	$this->mode="toEmlist";
  	$c_name=$_REQUEST['comname'];
  	$e_stat=$_POST['emState'];
  	global $employState;
  	$this->objDao=new EmployDao();
  	if(empty($e_stat)){
  		$e_stat=$employState['zhengchang'];
  	}
  	if($e_stat==-1){
  	$date_reg=date("Y-m-d");
  	$dateList=explode("-",$date_reg);
  	$dateNow=$dateList[0]."-".$dateList[1]."-01";
  	$dateEnd=$dateList[0]."-".$dateList[1]."-31";
  	$result=$this->objDao->getEmlistbyHetongriqi($c_name,$dateNow,$dateEnd);
  	}else{
  	$result=$this->objDao->getEmlistbyComname($c_name,$e_stat);
  	}
  	$this->objForm->setFormData("cname",$c_name);
  	$this->objForm->setFormData("e_stat",$e_stat);
  	$this->objForm->setFormData("emList",$result);
  }
  function toEmployAdd(){
  	$comname=$_POST['comname'];
  	$this->mode="toAddEmp";
  	$this->objForm->setFormData("comname",$comname);
  }
  function addEmp(){
  	//$this->mode="toadd";
  	$mess="";
	$succMsg="";
  	$exmsg=new EC();
  	$employ=array();
  	$employ['e_name']=$_POST['name'];
  	$employ['e_num']=$_POST['e_no'];
  	$employ['bank_name']=$_POST['bank'];
  	$employ['bank_num']=$_POST['bank_no'];
  	$employ['e_type']=$_POST['e_type'];
  	$employ['e_company']=$_POST['company'];
  	$employ['shebaojishu']=$_POST['shebaojishu'];
  	$employ['gongjijinjishu']=$_POST['gongjijinjishu'];
  	$employ['laowufei']=$_POST['laowufei'];
  	$employ['canbaojin']=$_POST['canbaofei'];
  	$employ['e_hetong_date']=$_POST['qishinian'];
  	$employ['e_hetongnian']=$_POST['hetongNian'];
  	$employ['danganfei']=$_POST['danganfei'];
  	if($employ['e_hetong_date']==''){
  		$employ['e_hetong_date']=date("Y-m-d");
  	}
  	$employ['memo']=$_POST['memo'];
  	$this->objDao=new EmployDao();
  	$emper=$this->objDao->getEmByEno($employ['e_num']);
  	if(!empty($emper)){
  	   $mess="此员工身份证号已存在，请重新确认";
  	 $this->objForm->setFormData("error",$mess);
     $this->objForm->setFormData("succ",$succMsg);
  	   return;
  	}
  	$retult=$this->objDao->addEm($employ);
   if(!$retult){
				$exmsg->setError(__FUNCTION__, "add employ  faild ");
				$mess="员工添加失败";
				$succMsg="";
				//$this->objForm->setFormData("warn","审批通过操作失败！");
				//事务回滚
				//$this->objDao->rollback();
				throw new Exception ($exmsg->error());
			}else{
				$mess="";
				$succMsg="添加成功";
			}
  $saveLastId=$this->objDao->g_db_last_insert_id();
  $adminPO=$_SESSION['admin'];
  $opLog=array();
			$opLog['who']=$adminPO['id'];
			$opLog['what']=$saveLastId;
			$opLog['Subject']=OP_LOG_ADD_EMPLOY;
			$opLog['memo']='';
			//{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
			$rasult=$this->objDao->addOplog($opLog);
			if(!$rasult){
				$exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
				$this->objForm->setFormData("warn","添加员工操作失败");
				//事务回滚
				//$this->objDao->rollback();
				throw new Exception ($exmsg->error());
			}
     $this->objForm->setFormData("error",$mess);
     $this->objForm->setFormData("succ",$succMsg);
     $this->getEmployList();
  }
 function getEmployById(){
  	$this->mode="toEmploy";
  	$emid=$_GET['eid'];
  	$this->objDao=new EmployDao();
  	$result=$this->objDao->getEmployById($emid);
  	$this->objForm->setFormData("employ",$result);
  }
  function emUpdate(){
  	$this->mode="toEmploy";
  		$mess="";
	$succMsg="";
  	$exmsg=new EC();
  	$employ=array();
  	$employ['id']=$_POST['eid'];
  	$employ['e_name']=$_POST['name'];
  	$employ['e_num']=$_POST['e_no'];
  	$employ['bank_name']=$_POST['bank'];
  	$employ['bank_num']=$_POST['bank_no'];
  	$employ['e_type']=$_POST['e_type'];
  	$employ['e_company']=$_POST['company'];
  	$employ['shebaojishu']=$_POST['shebaojishu'];
  	$employ['gongjijinjishu']=$_POST['gongjijinjishu'];
  	$employ['laowufei']=$_POST['laowufei'];
  	$employ['canbaojin']=$_POST['canbaofei'];
  	$employ['danganfei']=$_POST['danganfei'];
  	$employ['e_hetong_date']=$_POST['qishinian'];
  	$employ['e_hetongnian']=$_POST['hetongNian'];
  	$employ['memo']=$_POST['memo'];
  	$this->objDao=new EmployDao();
  /*	$emper=$this->objDao->getEmByEno($employ['e_num']);
  	if(!empty($emper)){
  	   $mess="此员工身份证号已存在，请重新确认";
  	 $this->objForm->setFormData("error",$mess);
     $this->objForm->setFormData("succ",$succMsg);
  	   return;
  	}*/
  	$retult=$this->objDao->updateEm($employ);
   if(!$retult){
				$exmsg->setError(__FUNCTION__, "update employ  faild ");
				$mess="员工修改失败";
				$succMsg="";
				//$this->objForm->setFormData("warn","审批通过操作失败！");
				//事务回滚
				//$this->objDao->rollback();
				throw new Exception ($exmsg->error());
			}else{
				$mess="";
				$succMsg="修改成功";
			}
    $adminPO=$_SESSION['admin'];
    $opLog=array();
			$opLog['who']=$adminPO['id'];
			$opLog['what']=0;
			$opLog['Subject']=OP_LOG_UPDATE_EMPLOY;
			$opLog['memo']='';
			//{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
			$rasult=$this->objDao->addOplog($opLog);
			if(!$rasult){
				$exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
				$this->objForm->setFormData("warn","修改员工操作失败");
				//事务回滚
				//$this->objDao->rollback();
				throw new Exception ($exmsg->error());
			}
     $this->objForm->setFormData("error",$mess);
     $this->objForm->setFormData("succ",$succMsg);
     $this->getEmployList();
  }
  function searchSalaryByOther(){
  	$this->objDao=new ServiceDao();
  	$this->mode="toServiceComlist";
  	$comname=$_POST['comname'];
  	$rasult=$this->objDao->searchCompanyListByComName($comname);
  	$this->objForm->setFormData("salaryTimeList",$rasult);
  }
  function makeSal(){
  	$this->mode="toMakeSal";
  	$comId=$_REQUEST['comId'];
  	$date=$_REQUEST['sDate'];
  	$salType=$_REQUEST['salType'];
  	$this->objDao=new EmployDao();
  	$company=$this->objDao->getCompanyById($comId);
    $op=new fileoperate();
	$files=$op->list_filename("upload/",1);
	$this->objForm->setFormData("files",$files);
	$this->objForm->setFormData("company",$company);
  	$this->objForm->setFormData("salDate",$date);
  	$this->objForm->setFormData("salType",$salType);
  }
  function makeSalBak(){
  	$this->mode="toMakeSal";
  	$comId=$_REQUEST['comId'];
  	$date=$_REQUEST['sDate'];
  	$this->objDao=new EmployDao();
  	$company=$this->objDao->getCompanyById($comId);
  	$type=array();
  	//$empList=$this->objDao->getEmlistbyComname($company['company_name']);
  	$empList=true;
  	if($empList){
  		$type['importEmp']['html']="<font color='green'>已导入</font>";
  		$type['importEmp']['type']=1;
  		$type['importEmp']['button']=' <input type="button" value=" 批量导入员工 " id="btn_ok"  onclick="improtEmp()" class="btn_submit" />';
  	}else{
  		$type['importEmp']['html']="<font color='red'>未导入</font>";
  		$type['importEmp']['type']=0;
  		$type['importEmp']['button']=' <input type="button" value=" 批量导入员工 " id="btn_ok"  onclick="improtEmp()" class="btn_submit" />';
  	}
  	$this->objDao=new SalaryDao();
  	$salList=$this->objDao->searchSalTimeByComIdAndSalTime($company['id'],$date);
  	if($salList){
  	    $type['sal']['html']="<font color='green'>已做工资</font>";
  		$type['sal']['type']=1;
  		$type['sal']['button']=' ';
  		$type['sal']['salTimeId']=$salList['id'];
  	}else{
  		$type['sal']['html']="<font color='red'>未做工资</font>";
  		$type['sal']['type']=0;
  		$type['sal']['button']=' <input type="button" value="做工资" id="btn_ok"  onclick="salMake()" class="btn_submit" />';
  	    $type['sal']['salTimeId']=-1;
  	}
  	/*//二次工资
  	$where=array();
  	$where['companyName']=$company['company_name'];
  	$where['salaryTime']=$date;
  	$salList=$this->objDao->searhSalaryErTimeList($where);
  	if($salList){
  		$i="";
  		while (mysql_fetch_array($salList)) {

  		}
  	}*/
  	if($type['sal']['type']==1){
  global $billType;
  	//查询发票
  	//$zhiList=$this->objDao->searchBillBySalaryTimeId($salList['id'],$billType['发票']);
  	if($salList['salary_state']<1){
  		$type['fa']['html']="<font color='red'>未开发票</font>";
  	}else{
  		$type['fa']['html']="<font color='red'>已开发票</font>";
  	}
  	//$type['sal']['html']="<font color='red'>未做工资</font>";
  	}
  	$this->objForm->setFormData("typeList",$type);
  	$this->objForm->setFormData("company",$company);
  	$this->objForm->setFormData("salDate",$date);
  }
  function toHedaoqi(){
  	$this->mode="toEmlist";
  	$comname=$_POST['comname'];
  	$this->objDao=new EmployDao();
  	$emList=$this->objDao->getEmlistbyComname($comname);
  	$date=strtotime(date("Y-m-d"));

  	//date("Y-m-d",strtotime("+3 year"));
  	//echo $date.strtotime(date("2012-03-7"));
  	$emDaoList=array();
  	$i=0;
  	while($row=mysql_fetch_array($emList)){
  		$daoqiDate=date('Y-m-d', strtotime($row['e_hetong_date']."{$row['e_hetongnian']}year"));
  		$daoqiDateNum=strtotime($daoqiDate);
  		//echo $daoqiDate.'</br>';

  		//echo date('Y-m-d', strtotime($row['e_hetong_date']."{$row['e_hetongnian']}year")).'</br>';
  		//date('Y-m-d', strtotime($row['e_hetong_date']).'1year'));
  		//echo $daoqiDateNum.$date."</br>";
  		//$dates=explode("-",)
  		if($date>=$daoqiDateNum){
  		//if($daoqiDateNum>$date){
  			$row['daoqiri']=$daoqiDate;
  			$emDaoList[$i]=$row;
  		}
  		$i++;
  	}
  	//var_dump($emDaoList);
  	$this->objForm->setFormData("cname",$comname);
  	$this->objForm->setFormData("e_stat",2);
  	$this->objForm->setFormData("emList",$emDaoList);
  }
  function cancelService(){
  	$comId=$_POST['comId'];
  	$this->objDao=new ServiceDao();
  	$admin=$_SESSION["admin"];
  	$result=$this->objDao->deleteServiceCom($admin['id'],$comId);
  	$this->getAdminCompanyList();
  }
  function tiaoguoFaPiao(){
  	$comId=$_POST['comId'];
  	//$date=$_POST['date'];
  	global $billType;
              $exmsg=new EC();
  	//$this->mode="toinvoice";
  	$salaryTime=$_REQUEST['salTimeId'];
  	$billname="跳过发票";
  	$billval=0;
  	$memo="发票跳过，自动添加0";
  	 $adminPO=$_SESSION['admin'];
  	$billArray=array();
  	$billArray['salaryTime_id']=$salaryTime;
  	$billArray['bill_type']=$billType['发票'];
  	$billArray['bill_date']=date('Y-m-d H:i:s');
  	$billArray['bill_item']=$billname;
  	$billArray['bill_value']=$billval;
  	$billArray['bill_state']=1;//对应$billState['1']=>发票已开
  	$billArray['op_id']=$adminPO['id'];
  	$billArray['text']=$memo;
  	$this->objDao=new SalaryDao();
    $result=$this->objDao->saveSalaryBill($billArray);
    $lastid=$this->objDao->g_db_last_insert_id();
    if($result){
    	//1代表$billState发票已开
    $result=$this->objDao->updateSalaryTimeState(1,$salaryTime);
    $succ="发票添加成功";
    }else{
    	$errormsg="发票添加失败！";
    }

        $opLog=array();
			$opLog['who']=$adminPO['id'];
			$opLog['what']=$lastid;
			$opLog['Subject']=OP_LOG_ADD_BILL_INVOICE;
			$opLog['memo']='';
			//{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
			$rasult=$this->objDao->addOplog($opLog);
			if(!$rasult){
				$exmsg->setError(__FUNCTION__, "delsalary  add oplog  faild ");
				$this->objForm->setFormData("warn","失败");
				//事务回滚
				//$this->objDao->rollback();
				throw new Exception ($exmsg->error());
			}
    $this->objForm->setFormData("errormsg",$errormsg);
    $this->objForm->setFormData("succ",$succ);
    $this->makeSal();
  }
  function updateEmployStat(){
  	$emId=$_POST['emId'];
  	$this->objDao=new EmployDao();

  }
function salarySend(){
              $exmsg=new EC();
  	//$this->mode="toSendSalary";
  	$salaryTimeId=$_REQUEST['timeid'];
  	$adminPO=$_SESSION['admin'];
  	$billState=4;//工资发放
  	$billArray=array();
  	$billArray['salaryTime_id']=$salaryTimeId;
  	$billArray['bill_type']=$billState;
  	$billArray['bill_date']=date('Y-m-d H:i:s');
  	$billArray['bill_item']="工资发放";
  	$billArray['bill_value']=0;
  	$billArray['bill_state']=$billState;//对应$billState['']=>""
  	$billArray['op_id']=$adminPO['id'];
  	$billArray['text']="工资发放";
  	$this->objDao=new SalaryDao();
    $result=$this->objDao->saveSalaryBill($billArray);
      /* $lastid=$this->objDao->g_db_last_insert_id();
    $errormsg="";
  if($result){
    	//1代表$billState发票已开
    $result=$this->objDao->updateSalaryTimeState($billState,$salaryTimeId);
    //$errormsg=$billname."发放成功";
    $adminPO=$_SESSION['admin'];
        $opLog=array();
			$opLog['who']=$adminPO['id'];
			$opLog['what']=$lastid;
			$opLog['Subject']=OP_LOG_SEND_SALARY;
			$opLog['memo']='';
			//{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
			$rasult=$this->objDao->addOplog($opLog);
			if(!$rasult){
				$exmsg->setError(__FUNCTION__, "delsalary  add oplog  faild ");
				$this->objForm->setFormData("warn","失败");
				//事务回滚
				//$this->objDao->rollback();
				throw new Exception ($exmsg->error());
			}
    }else{
    	$errormsg=$billname."发放失败！";
    }*/
    $this->objForm->setFormData("warn",$errormsg);
    $this->getAdminCompanyList();
  }
  function toUpdateEmpList(){
  	$this->mode="emListUpdate";
		$fname=$_GET['fname'];
		$err=Read_Excel_File("upload/".$fname,$return);
		if($err!=0){
			$this->objForm->setFormData("error",$err);
		}
		$this->objForm->setFormData("salarylist",$return);
  }
  function updateEmpList(){
  	$this->mode="duibiError";
		$shenfenzheng=($_POST['shenfenzheng']-1);
		$name=($_POST['name']-1);
		$eno=($_POST['eno']-1);
		$bank=($_POST['bank']-1);
		$etype=($_POST['etype']-1);
		$shebao=($_POST['shebao']-1);
		$gongjijin=($_POST['gongjijin']-1);
		$canbaofei=($_POST['canbaofei']-1);
		$laowufei=($_POST['laowufei']-1);
		$danganfei=($_POST['danganfei']-1);
		$nianxian=($_POST['nianxian']-1);
		$qishi=($_POST['qishi']-1);
		//print_r($addArray);
		//print_r($delArray);
		session_start();
		$salaryList=$_SESSION['salarylist'];
		$jisuan_var=array();
		$error=array();
		$this->objDao=new EmployDao();
		//根据身份证号查询出员工身份类别
		for ($i=1;$i<count($salaryList[Sheet1]);$i++)
		{
			$sql=" update OA_employ  set ";
			$updateSal="";
			$salaryList[Sheet1][$i][$shenfenzheng]=trim($salaryList[Sheet1][$i][$shenfenzheng]);
			if($salaryList[Sheet1][$i][$shenfenzheng]){
				if($name!=''&&$name!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="e_name='{$salaryList[Sheet1][$i][$name]}'";
				}
				if($eno!=''&&$eno!=-1){
				if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="bank_num='{$salaryList[Sheet1][$i][$eno]}'";
				}
				if($bank!=''&&$bank!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="bank_name='{$salaryList[Sheet1][$i][$bank]}'";
				}
				if($etype!=''&&$etype!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="e_type='{$salaryList[Sheet1][$i][$etype]}'";
				}
				if($shebao!=''&&$shebao!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="shebaojishu={$salaryList[Sheet1][$i][$shebao]}";
				}
				if($gongjijin!=''&&$gongjijin!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="gongjijinjishu={$salaryList[Sheet1][$i][$gongjijin]}";
				}
				if($canbaofei!=''&&$canbaofei!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="canbaojin={$salaryList[Sheet1][$i][$canbaofei]}";
				}
				if($laowufei!=''&&$laowufei!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="laowufei={$salaryList[Sheet1][$i][$laowufei]}";
				}
				if($danganfei!=''&&$danganfei!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="danganfei={$salaryList[Sheet1][$i][$danganfei]}";
				}
			    if($nianxian!=''&&$nianxian!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="e_hetongnian={$salaryList[Sheet1][$i][$nianxian]}";
				}
			    if($qishi!=''&&$qishi!=-1){
					if($updateSal){
						$updateSal.=",";
					}
					$updateSal.="e_hetong_date='{$salaryList[Sheet1][$i][$qishi]}'";
				}
				$where=" where e_num='{$salaryList[Sheet1][$i][$shenfenzheng]}'";
				$sql.=$updateSal.$where;
				//echo $sql."<br/>";
				$this->objDao->g_db_query($sql);
			}else{
				$error[$i]["error_shenfen"]="<font color='red'>第".($i-1)."行</font>:身份证号无法识别！";
				continue;
			}
		}
	    if(count($error)==0){
			$error[0]["succ"]="<font color=green>对比没有错误</font>";
		}
		$this->objForm->setFormData("errorlist",$error);
		$this->objForm->setFormData("excelList",$salaryList[Sheet1]);
  }

}


$objModel = new ServiceAction($actionPath);
$objModel->dispatcher();



?>
