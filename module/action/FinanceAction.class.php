<?php 
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/".$actionPath."Dao.class.php");
require_once("module/dao/ServiceDao.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/EmployDao.class.php");
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
require_once("tools/sumSalary.class.php");
class FinanceAction extends BaseAction{
 /*
     *
     * @param $actionPath
     * @return AdminAction
     */
 function FinanceAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new FinanceForm();
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
            case "input" :
                $this->getFinanceList();
                break;
            case "finance_frist" :
                $this->FinanceFirst();
                break;
            case "getSalaryTimeListByComId" :
                $this->getSalaryTimeListByComId();
                break;
            case "shenPiDuiBi" :
                $this->shenPiDuiBi();
                break;
            case "upload" :
				$this->duiBiUpload();
				break;
		    case "delFile" :
				$this->fileDel();
				break;
		    case "salDuiBi":
		    	$this->salDuiBi();
				break;
			case "shenPiSalHeji":
		    	$this->shenPiSalHeji();
				break;
		    case "salCaiwuImport":
		    	$this->salCaiwuImport();
				break;
			case "salImportByCom":
				$this->salImportByCom();
				break;
			case "salGeShuiByCom":
				$this->salGeShuiByCom();
				break;
			case "opShenPi":
				$this->opShenPi();
				break;
			case "saveGeShui":
				$this->saveGeShui();
				break;
			case "searchGeShuiTypeList":
				$this->searchGeShuiTypeList();
				break;
			case "searchGeShuiSum":
				$this->searchGeShuiSum();
				break;
			case "searchGeShuiType":
				$this->searchGeShuiType();
				break;
			case "searchcompanyListJosn":
				$this->searchcompanyListJosn();
				break;
			
            default :
                $this->modelInput();
                break;
        }
    }
    //个税统计跳转
    function searchGeShuiSum(){
    	$this->mode="geShuiSum";
    }
    
    //个税统计跳转
    function searchGeShuiType(){
    	$this->mode="geShuiType";
    }
	function getFinanceList(){
	  	$this->mode="financeFrist";
	  	$admin=$_SESSION["admin"];
	  	$this->objDao=new SalaryDao();
	  	$comList=array();
	  	$year=$_POST['yearDate'];
	  	$mon=$_POST['monDate'];
	  	if(!empty($year)){
	  	$dateNow=$year."-".$mon."-01";
	  	}else{
	  	$date_reg=date("Y-m-d");
	  	$dateList=explode("-",$date_reg);
	  	$dateNow=$dateList[0]."-".$dateList[1]."-01";
	  	}
	  	$result=$this->objDao->getSalaryIdBySalaryTime($dateNow);
	  	if($result){
	  		$j=0;
	  		while ($row=mysql_fetch_array($result)){
	  			$salBillList=$this->objDao->getFinanceList($row['id']);
	  			$row['bill_fa_num']=0;//发票计数器
	  			$row['bill_zhi_num']=0;
	  			$row['bill_dao_num']=0;
	  			$row['bill_zhi_value']=0;
	  			$row['bill_fa_value']=0;
	  			$row['bill_dao_value']=0;
	  			while ($rowBill=mysql_fetch_array($salBillList)){
	  				if($rowBill['bill_type']==1){
	  					$row['bill_fa'].=$rowBill['bill_item']."<font color=red>&</font>";
	  					$row['bill_fa_date'].=$rowBill['bill_date']."&";
	  					$row['bill_fa_num']++;
	  					$row['bill_fa_value']+=$rowBill['bill_value'];
	  				}elseif ($rowBill['bill_type']==2){
	  					$row['bill_zhi'].=$rowBill['bill_item']."<font color=red>&</font>";
	  					$row['bill_zhi_num']++;
	  					$row['bill_zhi_date'].=$rowBill['bill_date']."&";
	  					$row['bill_zhi_value']+=$rowBill['bill_value'];
	  				}elseif ($rowBill['bill_type']==3){
	  					$row['bill_dao'].=$rowBill['bill_item']."<font color=red>&</font>";
	  					$row['bill_dao_num']++;
	  					$row['bill_dao_date'].=$rowBill['bill_date']."&";
	  					$row['bill_dao_value']+=$rowBill['bill_value'];
	  				}
	  			}
	  		$comList[$j]=$row;
	  		$j++;
	  		}
	  	}
	  	//var_dump($comList);
	  	 $this->objForm->setFormData("salaryTimeList",$comList);
	  	
	  
	  }
  
  //FIXME  列表所有审核公司
  function searchcompanyListJosn() {
  	$this->objDao=new FinanceDao();
  	$start=$_REQUEST['start'];
    $limit=$_REQUEST['limit'];
    $sorts=$_REQUEST['sort'];
   	$dir=$_REQUEST['dir'];
   	$companyName	=	$_REQUEST['companyName'];
   	if(!$start){
   		$start=0;    	
   	}
   	if(!$limit){
   		$limit=50;
   	}
   	if (!$sorts) {
   		$sorts="uncheckid";
   	}
   	$where=array();
   	$where['companyName']=$companyName;
   	$sum =$this->objDao->searchCheckCompanyListCount($where);
   	$comList=$this->objDao->searchCheckCompanyListPage($start,$limit,$sorts." ".$dir,$where);
    $josnArray=array();
   	$josnArray['total']=$sum;
   	$i=0;
   	while ($row=mysql_fetch_array($comList) ){
   		$josnArray['items'][$i]['id']=$row['id']; 
   		$josnArray['items'][$i]['company_name']=$row['company_name'];
    	$josnArray['items'][$i]['company_address']=$row['company_address'];
    	$josnArray['items'][$i]['checked']='未审核';
   		$josnArray['items'][$i]['pact_start_date']=$row['pact_start_date'];
   		$josnArray['items'][$i]['pact_over_date']=$row['pact_over_date'];
   		$josnArray['items'][$i]['service_fee_state']=$row['service_fee_state'];
   		$josnArray['items'][$i]['service_fee_value']=$row['service_fee_value'];    		
   		$josnArray['items'][$i]['can_bao_state']=$row['can_bao_state'];
    	$josnArray['items'][$i]['can_bao_value']=$row['can_bao_value'];
   		$josnArray['items'][$i]['companyEmail']=$row['companyEmail'];
   		$josnArray['items'][$i]['remarks']=$row['remarks'];
   		$i++;

    }
   	echo json_encode($josnArray);
   	exit;
  }
  
function FinanceFirst(){
$this->mode="financeFrist";
  	$admin=$_SESSION["admin"];
  	$this->objDao=new SalaryDao();
  	$comList=array();
  	$year=$_POST['yearDate'];
  	$mon=$_POST['monDate'];
  	$dateEnd=null;
  	if(!empty($year)){
  	$dateNow=$year."-".$mon."-01";
  	}else{
  	$date_reg=date("Y-m-d");
  	$dateList=explode("-",$date_reg);
  	$dateNow=$dateList[0]."-".$dateList[1]."-01";
  	}
  	$financeList=array();
  	//查询公司列表
  	$result=$this->objDao->searchCompanyList();
  	global $billType;
  	$i=0;
  	while ($row=mysql_fetch_array($result)){
  		//查询工资列表
  		$sal=$this->objDao->searchSalTimeByComIdAndSalTime($row['id'],$dateNow,$dateEnd,1);
  		if($sal){
  			$row['salState']='<font color=green>已做工资</font>';
  			$row['salPO']=$sal;
  		  		}else{
  		  	$row['salState']='<font color=red>未做工资</font>';		
  		  		}
  		//查询发票，支票，到账，是否发放
  		$row['bill_fa']='<font color=blue>未开发票</font>';
  		$row['bill_zhi']='<font color=blue>未开支票</font>';
  		$row['bill_dao']='<font color=blue>支票未到账</font>';
  		$row['bill_fafang']='<font color=blue>未处理审批</font>';
  		if($sal){
  			$billList=$this->objDao->searchBillBySalaryTimeId($sal['id']);
  			while ($bill=mysql_fetch_array($billList)){
  				if($bill['bill_type']==$billType['发票']){
  					$row['bill_fa']='<font color=green>已开发票</font>';
  				}elseif($bill['bill_type']==$billType['支票']){
  					$row['bill_zhi']='<font color=green>已开支票</font>';
  				}elseif($bill['bill_type']==$billType['到账支票']){
  					$row['bill_dao']='<font color=green>支票已到帐</font>';
  				}elseif($bill['bill_type']==$billType['工资发放']){
	  				if($bill['bill_value']==0){
	    	        $row['bill_fafang']='<font color="red">等待审批</font>';
			    	}elseif($bill['bill_value']==1){
			    		$row['bill_fafang']='<font color="green">审批通过</font>';
			    	}elseif($bill['bill_value']==2){
			    		$row['bill_fafang']='<font color="red">审批未通过</font>';
			    	}
  				}
  			}
  		}
  	    $row['date']=$dateNow;
  		$financeList[$i]=$row;
  		$i++;
  	 }
  	 //var_dump($financeList);
  	 $this->objForm->setFormData("financeList",$financeList);
}
function getSalaryTimeListByComId(){
  	global $billState;
  	$comId=$_REQUEST['cid'];
    $year=$_POST['yearDate'];
  	$mon=$_POST['monDate'];
  	if(!empty($year)){
  	$dateNow=$year."-".$mon."-01";
  	}else{
  	$date_reg=date("Y-m-d");
  	$dateList=explode("-",$date_reg);
  	$dateNow=$dateList[0]."-".$dateList[1]."-01";
  	}
  	$this->mode="toSendSalary";
   $this->objDao=new FinanceDao();
   $salaryTimeList=array();
   $result=$this->objDao->searchSalTimeListBySalTime($dateNow);
   $i=0;
    while ($row=mysql_fetch_array($result) ){
    $billFaPO=$this->objDao->searchFaBill($row['id']);
    if($billFaPO){
    	if($billFaPO['bill_value']==0){
    	$row['falsate']='<font color="red">等待审批</font>';
    	}elseif($billFaPO['bill_value']==1){
    		$row['falsate']='<font color="green">审批通过</font>';
    	}elseif($billFaPO['bill_value']==2){
    		$row['falsate']='<font color="red">审批未通过</font>';
    	}
    	$row['faValue']=$billFaPO;
    	$salaryTimeList[$i]=$row;
    	$i++;
    }
    }
   $this->objForm->setFormData("salaryTimeList",$salaryTimeList);
   $this->objForm->setFormData("billState",$billState);
  }
  function shenPiDuiBi(){
  	    $this->mode="toUpload";
		$op=new fileoperate();
		$files=$op->list_filename("upload/duiBi/",1);
		$this->objForm->setFormData("files",$files);
		$this->objForm->setFormData("timeId",$_POST['timeId']);
  }
function duiBiUpload(){
        $exmsg=new EC();
		$fullfilepath = DUIBIUPLOADPATH.UPLOAD_FILE_NAME.date("Y-m-d-Hs").".xls";
		$errorMsg="";
		//var_dump($_FILES);
		$fileArray=split("\.",$_FILES['file']['name']);
		//var_dump($fileArray);
		if(count($fileArray)!=2){
			//$this->mode="toUpload";
			$errorMsg='文件名格式 不正确';
			$this->objForm->setFormData("error",$errorMsg);
			return;
		}else if($fileArray[1]!='xls'){
			//$this->mode="toUpload";
			$errorMsg='文件类型不正确，必须是xls类型';
			$this->objForm->setFormData("error",$errorMsg);
			return;
		}
		if($_FILES['file']['error'] != 0){
			$error = $_FILES['file']['error'];
			switch($error)
			{
				case 1:
					$errorMsg= '1,上传的文件超过了php.ini中  upload_max_filesize选项限制的值.';
					break;
				case 2:
					$errorMsg= '2,上传文件的大小超过了HTML表单中MAX_FILE_SIZE  选项指定的大小';
					break;
				case 3:
					$errorMsg= '3,文件只有部分被上传';
					break;
				case 4:
					$errorMsg= '4,文件没有被上传';
					break;
				case 6:
					$errorMsg= '找不到临文件夹';
					break;
				case 7:
					$errorMsg= '文件写入失败';
					break;
			}
		}
		if($errorMsg!=""){
			$this->objForm->setFormData("error",$errorMsg);
			return;
		}
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $fullfilepath)){//上传文件
			//print_r($_FILES);print_r($fullfilepath);
			//$this->objDao->rollback();
			$this->objForm->setFormData("error","文件导入失败");
			throw new Exception(DUIBIUPLOADPATH." is a disable dir");

			//die("UPLOAD FILE FAILED:".$_FILES['plusfile']['error']);
		}else{
			//$this->mode="toUpload";
			$succMsg='文件导入成功';
			$this->objForm->setFormData("succ",$succMsg);
			 
		}
		$adminPO=$_SESSION['admin'];
		$opLog=array();
		$opLog['who']=$adminPO['id'];
		$opLog['what']=0;
		$opLog['Subject']=OP_LOG_UPLOAD_FILE;
		$opLog['memo']='文件名称：'.$_FILES['file']['tmp_name'];
		//{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$this->objDao=new EmployDao();
		$rasult=$this->objDao->addOplog($opLog);
		if(!$rasult){
			$exmsg->setError(__FUNCTION__, "uploadfile  add oplog  faild ");
			$this->objForm->setFormData("warn","失败");
			//事务回滚
			//$this->objDao->rollback();
			throw new Exception ($exmsg->error());
		}
		$op=new fileoperate();
		$files=$op->list_filename("upload/",1);
		$this->objForm->setFormData("files",$files);
		$this->shenPiDuiBi();
	}
	function fileDel(){
		$fname=$_GET['fname'];
		$op=new fileoperate();
		$mess=$op->del_file("upload/duiBi/",$fname);
		$files=$op->list_filename("upload/",1);
		$this->objForm->setFormData("files",$files);
		$this->objForm->setFormData("error",$mess);
		$this->shenPiDuiBi();
	}
    function salDuiBi(){
		$this->mode="salDuiBi";
		$fname=$_REQUEST['fname'];
		$timeId=$_REQUEST['timeId'];
		$err=Read_Excel_File("upload/duiBi/".$fname,$return);
		$this->objDao=new FinanceDao();
		$salTime=$this->objDao->getSalaryTimeBySalId($timeId);
		if($err!=0){
			$this->objForm->setFormData("error",$err);
		}
		$this->objForm->setFormData("salarylist",$return);
		$this->objForm->setFormData("salTime",$salTime);
	}
function shenPiSalHeji (){
		$this->mode="duibiError";
		$shenfenzheng=($_POST['shenfenzheng_nian']-1);
		$salaryTimeId=$_POST['timeId'];
		echo $salaryTimeId.'>>>>>>>>>>>>';
		//$yingfa=($_POST['yingfa']-1);
		$shifa=($_POST['nian']-1);
		session_start();
		$salaryList=$_SESSION['salarylist'];
		$jisuan_var=array();
		$error=array();
		$this->objDao=new SalaryDao();
		//根据身份证号查询出员工身份类别shebaojishu
		for ($i=1;$i<count($salaryList[Sheet1]);$i++)
		{
				
			$sal=$this->objDao->searchSalBy_EnoAndSalTimeId($salaryTimeId,$salaryList[Sheet1][$i][$shenfenzheng]);
			if($sal){
					if($salaryList[Sheet1][$i][$shifa]!=$sal['per_shifaheji']){
						$error[$i]["error_com"]="<font color='blue'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:实发合计项不一致！系统：{$sal['per_shifaheji']},导入文件：{$salaryList[Sheet1][$i][$shifa]}";
					}
				
			}else{
				$error[$i]["error_shenfen"]="<font color='blue'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:未查询到该员工！";
				continue;
			}
		}
		if(count($error)==0){
			$error[0]["succ"]="<font color=green>对比没有错误</font>";
		}
		//index.php?action=Finance&mode=getSalaryTimeListByComId
		$this->objForm->setFormData("errorlist",$error);
		$this->objForm->setFormData("back",'index.php?action=Finance&mode=getSalaryTimeListByComId');
		$this->objForm->setFormData("excelList",$salaryList[Sheet1]);
	}
	function salCaiwuImport(){
	$this->mode="toImportSalPage";
  	/*$this->objDao=new SalaryDao();
  	$salaryTimeList=$this->objDao->searhSalaryTimeList();
  	$this->objForm->setFormData("salaryTimeList",$salaryTimeList);*/
	}
	function salImportByCom(){
	$this->mode="salImportByCom";
	$salTime=$_POST['timeid'];
	$salTimeList=array();
	$salTimeList=explode("*",$salTime);
  	$this->objDao=new SalaryDao();
  	$salaryListArray=array();
  	$salarySumListArray=array();
  	$where=$salTimeList[0];
  	for($z=0;$z<(count($salTimeList)-1);$z++){
  	$salaryList=$this->objDao->searchSalaryListBy_SalaryTimeId($salTimeList[$z]);
  	$i=0;
  	while ($row=mysql_fetch_array($salaryList) ){
  		$employ=$this->objDao->getEmByEno($row['employid']);
  		$row['e_name']=$employ['e_name'];
  		$salaryListArray[$z][$i]['guding_salary']=$row;
  		$i++;
  	}
  	//$salarySumListArray[$z]=$salarySumList;
  	if($z!=0){
  		$where.=','.$salTimeList[$z];
  	}
  	}
  	$salarySumList=$this->objDao->searchSumSalaryListBy_ManyCom($where);
  	$this->objForm->setFormData("salaryTimeList",$salaryListArray);
  	$this->objForm->setFormData("salarySumTimeList",$salarySumList);
	}
function salGeShuiByCom(){
		$this->mode="salGeShuiByCom";
	$salTime=$_POST['timeid'];
	$salTimeList=array();
	$salTimeList=explode("*",$salTime);
	
  	//$salaryTimeId=$_REQUEST['id'];
  	$this->objDao=new SalaryDao();
  	$salaryListArray=array();
  	$where=$salTimeList[0];
  	$salarySumList['sum_per_daikoushui']=0.00;
  	for($z=0;$z<(count($salTimeList)-1);$z++){
  	$salaryList=$this->objDao->searchSalaryListBy_SalaryTimeId($salTimeList[$z]);
  	$salaryTimePO=$this->objDao->searchSalaryTimeBy_id($salTimeList[$z]);
  	$i=0;
  	while ($row=mysql_fetch_array($salaryList) ){
  		$employ=$this->objDao->getEmByEno($row['employid']);
  		$erSalaryPO=$this->objDao->searchErBuKouShuaiHejiByPersonIdAndSalTimeErAndComId($salaryTimePO['companyId'],$salaryTimePO['salaryTime'],$row['employid']);
  		
        $nianTimePO=$this->objDao->searchNianSalaryTimeBySalaryTimeAndComId($salaryTimePO['salaryTime'],$salaryTimePO['companyId']);
        $nianSalaryPO=mysql_fetch_array($this->objDao->searchNianSalaryListBy_SalaryTimeIdAndPersonNo($nianTimePO['id'],$row['employid']));
  		
        if(!$erSalaryPO['erSum']){
  			$erSalaryPO['erSum']=0;
  		}
  	    if(!$nianSalaryPO['nian_daikoushui']){
  			$nianSalaryPO['nian_daikoushui']=0;
  		}
  		//echo $nianSalaryPO['nian_daikoushui']."<><><><><><br/>";
  		$row_move=mysql_fetch_array($employ);
  			$salaryListArray[$z][$i][0]=$employ['e_name'];
  			$salaryListArray[$z][$i][1]=$employ['e_num'];
  			$salaryListArray[$z][$i][2]=$employ['bank_num'];
  			$salaryListArray[$z][$i][3]=$employ['bank_name'];
  			$salaryListArray[$z][$i][4]=$row['per_daikoushui']+$erSalaryPO['erSum']+$nianSalaryPO['nian_daikoushui'];
  	    //var_dump($row) ;
  		//$salaryListArray[$z][$i]['guding_salary']=$row;
  		$salarySumList['sum_per_daikoushui']+=$salaryListArray[$z][$i][4];
  		$i++;
  	}
  	if($z!=0){
  		$where.=','.$salTimeList[$z];
  	}
  	}
  	//
  	//var_dump($salarySumListArray);
  	//$salarySumList=$this->objDao->searchSumSalaryListBy_ManyCom($where);
  	$comList=$this->objDao->searchCompanyList();
  	$this->objForm->setFormData("comlist",$comList);
  	$this->objForm->setFormData("timeid",$timeid);
  	$this->objForm->setFormData("salaryTimeList",$salaryListArray);
  	$this->objForm->setFormData("salarySumTimeList",$salarySumList);
	}
	function opShenPi(){
		//$this->mode="billUpdate";
  	$bill=array();
  	$bill['id']=$_POST['billId'];
  	$bill['bill_item']='审批发放';
  	$bill['bill_value']=$_POST['shenPiType'];
  	$this->objDao=new SalaryDao();
  	$result=$this->objDao->updateBillById($bill);
  	if(!$result){
  		$this->objForm->setFormData("error","修改失败！");
  	}
		$this->getSalaryTimeListByComId();
	}
	function saveGeShui(){
		
		$salTime=$_REQUEST['salaryTime'];
		$comname=$_REQUEST['comname'];
		$this->objDao=new SalaryDao();
   //查询公司信息
    $company=$this->objDao->searchCompanyByName($comname);
  //根据日期查询公司时间
   $salaryTime=$this->objDao->searchSalaryGeShuiTimeByDateAndComId($salTime,$company['id']);
   if(!empty($salaryTime)){
   	$this->objForm->setFormData("warn"," $comname ： $salTime ，个税月份日期已经存在！");
   	$this->searchGeShuiTypeList();
   }
   $geShuiPO=array();
   /**
    * `id` int(11) NOT NULL,
  `salaryTimeId` int(11) DEFAULT NULL,
  `salTime` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geSui_type` int(2) DEFAULT '0',
  `opId` int(5) DEFAULT NULL,
  `comId` int(11) DEFAULT NULL,
    */
   $salaryTime=$this->objDao->searchSalTimeByComIdAndSalTime($company['id'],$salTime);
   $geShuiPO['salaryTimeId']=$salaryTime['id'];
   $geShuiPO['salTime']=$salTime;
   $geShuiPO['geSui_type']=1;
   $geShuiPO['comId']=$company['id'];
   $reslut=$this->objDao->addSalGeShui($geShuiPO);
		$this->mode="geShuiTypeList";
	}
	function searchGeShuiTypeList(){
		$this->mode="geShuiTypeList";
   $this->objDao=new SalaryDao();
   $year=$_POST['year'];
   if(empty($year)){
   	$year='2013';
   }
   $comList=$this->objDao->searchCompanyList();
   $salList=array();
   $j=0;
   while($row=mysql_fetch_array($comList)){
   	//查询12个月的工资状况包括年终奖
   	for($i=1;$i<=12;$i++){
   		if($i<10){
   			$date=$year."-0".$i."-01";
   		}else{
   		$date=$year."-".$i."-01";
   		}
   		$result=$this->objDao->searchSalaryGeShuiTimeByDateAndComId($date,$row['id']);
   		if($result&&$result['geSui_type']==1){
   			$salList[$j]['date'.$i]="<font color=green>已报个税</font>";
   		}else{
   			$salList[$j]['date'.$i]="<font color=red>未报个税</font>";
   		}
   	}
   	$salList[$j]['name']=$row['company_name'];
   	$j++;
   }
   //var_dump($salList);
   $this->objForm->setFormData("year",$year);
   $this->objForm->setFormData("comList",$salList);
		
	}
}
$objModel = new FinanceAction($actionPath);
$objModel->dispatcher();



?>
