<?php
require_once ("module/form/SaveSalaryForm.class.php");
require_once ("module/dao/SalaryDao.class.php");
require_once ("module/dao/EmployDao.class.php");
class SaveSalaryAction extends BaseAction {
	/*
	 * @param $actionPath @return TestAction
	 */
	function SaveSalaryAction($actionPath) {
		parent::BaseAction ();
		$this->objForm = new SaveSalaryForm ();
		$this->actionPath = $actionPath;
	}
	function dispatcher() {
		// (1) mode set
		$this->setMode ();
		// (2) COM initialize
		$this->initBase ( $this->actionPath );
		// (3)验证SESSION是否过期
		// $this->checkSession();
		// (4) controll -> Model
		try {
			$this->controller ();
		} catch ( Exception $exp ) {
			$LOG = new log ();
			$date = date ( 'Y-m-d H:i:s' );
			
			$LOG->setLogdata ( 'error_code', $exp->getCode () );
			$LOG->setLogdata ( 'error_msg', $exp->getMessage () );
			
			// $LOG->write('error_code');
			$LOG->write ( 'error_msg' );
			// exit;
		}
		// (5) view
		$this->view ();
		// (6) closeConnect
		$this->closeDB ();
	}
	function setMode() {
		// 模式设定
		$this->mode = $_REQUEST ['mode'];
	}
	function controller() {
		// Controller -> Model
		switch ($this->mode) {
			case "saveSalary" :
				$this->saveSalary ();
				break;
			case "saveNianSalary" :
				$this->saveNianSalary ();
				break;
			case "saveErSalary" :
				$this->saveErSalary ();
				break;
			case "searchSalaryTime" :
				$this->searchSalaryTime ();
				break;
			case "searchNianSalaryTime" :
				$this->searchNianSalaryTime ();
				break;
			case "searchErSalaryTime" :
				$this->searchErSalaryTime ();
				break;
			case "searchSalaryByOther" :
				$this->searchSalaryByOther ();
				break;
			case "searchSalaryById" :
				$this->searchSalaryById ();
				break;
			case "searchSalaryByIdJosn" :
				$this->searchSalaryByIdJosn ();
				break;
			case "searchNianSalaryById" :
				$this->searchNianSalaryById ();
				break;
			case "searchNianSalaryByIdJson" :
				$this->searchNianSalaryByIdJson ();
				break;
			case "searchErSalaryById" :
				$this->searchErSalaryById ();
				break;
			case "searchErSalaryTimeListByIdJson" :
				$this->searchErSalaryTimeListByIdJson ();
				break;
			case "searchErSalaryByIdJson" :
				$this->searchErSalaryByIdJson ();
				break;
			case "delSalayByTimeId" :
				$this->delSalayByTimeId ();
				break;
			case "delNianSalayByTimeId" :
				$this->delNianSalayByTimeId ();
				break;
			case "delErSalayByTimeId" :
				$this->delErSalayByTimeId ();
				break;
			case "searchSaveSalaryTime" :
				$this->searchSaveSalaryTime ();
				break;
			case "searchGeshuiByIdJosn" :
				$this->searchGeshuiByIdJosn ();
				break;
			case "searchGeshuiTypeByIdJosn" :
				$this->searchGeshuiTypeByIdJosn ();
				break;
			case "setShangyueType" :
				$this->setShangyueType ();
				break;
			case "setBenyueType" :
				$this->setBenyueType ();
				break;
			default :
				$this->modelInput ();
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
        $time["first"]  =    $first_date;
        $time["last"]   =      $for_day;
        return $time;
    }
    // FIXME 保存工资
	function saveSalary() {
		// echo "input</br>";
		// $this->mode="toSaveOk";
		$exmsg = new EC (); // 设置错误信息类
		session_start ();
		$comname = $_POST ['comname'];
		$salaryTimeDate = $_POST ['salaryTime'];
        $time   =   $this->AssignTabMonth ($salaryTimeDate,0);
        echo($time["first"]);
		$shifajian = $_POST ['shifajian'];
		$freeTex = $_POST ['shifajian']; // 免税项
		                              // echo $comname.$salaryTime;
		$salaryList = $_SESSION ['excelList'];
		$mark = $_POST ['mark'];
		// var_dump($salaryList);
		foreach ( $salaryList [0] as $num => $row ) {
			if (ereg ( $row, "身份证号" )) {
				$sit_shenfenzhenghao = $num; // 等到“身份证”字段的标志位
			} elseif (ereg ( $row, "个人应发合计" )) {
				$sit_gerenyinfaheji = $num; // 得到个人应发合计字段的标志位
			}
		}
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		// 查询公司信息
		$company = $this->objDao->searchCompanyByName ( $comname );
		if (! empty ( $company )) {
			// 添加公司信息
			$companyId = $company ['id'];
			// 根据日期查询公司时间
			$salaryTime = $this->objDao->searchSalTimeByComIdAndSalTime ( $companyId, "{$time["first"]}", "{$time["last"]}", 3 );
			if (! empty ( $salaryTime ['id'] )) {
				$this->objForm->setFormData ( "warn", " $comname 本月已做工资 ,有问题请联系财务！" );
				$this->searchSalaryTime ();
				return;
			}
		} else {
			$companyList = array ();
			$companyList ['name'] = $comname;
			$companyId = $this->objDao->addCompany ( $companyList );
			if (! $companyId) {
				$exmsg->setError ( __FUNCTION__, "save  company  get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存工资时间失败！" );
				throw new Exception ( $exmsg->error () );
			}
		}
		
		// 添加工资日期
		$salaryTime = array ();
		// $salaryTime['companyId']},'{$salaryTime['salaryTime']}','{$salaryTime['op_salaryTime']}
		$salaryTime ['companyId'] = $companyId;
		$salaryTime ['salaryTime'] = $salaryTimeDate;
		$salaryTime ['op_salaryTime'] = date ( "Y-m-d H:i:s" );
		$salaryTime ['mark'] = $mark;
		$lastSalaryTimeId = $this->objDao->saveSalaryTime ( $salaryTime );
		if (! $lastSalaryTimeId && $lastSalaryId != 0) {
			$exmsg->setError ( __FUNCTION__, "save  salaryTime  get last_insert_id  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "保存工资时间失败！" );
			throw new Exception ( $exmsg->error () );
		}
		for($i = 1; $i < count ( $salaryList ); $i ++) {
			// 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
			$salayList = array ();
			$salayList ['per_yingfaheji'] = $salaryList [$i] [$sit_gerenyinfaheji];
			$salayList ['per_shiye'] = $salaryList [$i] [($sit_gerenyinfaheji + 1)];
			$salayList ['per_yiliao'] = $salaryList [$i] [($sit_gerenyinfaheji + 2)];
			$salayList ['per_yanglao'] = $salaryList [$i] [($sit_gerenyinfaheji + 3)];
			$salayList ['per_gongjijin'] = $salaryList [$i] [($sit_gerenyinfaheji + 4)];
			$salayList ['per_daikoushui'] = $salaryList [$i] [($sit_gerenyinfaheji + 5)];
			$salayList ['per_koukuangheji'] = $salaryList [$i] [($sit_gerenyinfaheji + 6)];
			$salayList ['per_shifaheji'] = $salaryList [$i] [($sit_gerenyinfaheji + 7)];
			$salayList ['com_shiye'] = $salaryList [$i] [($sit_gerenyinfaheji + 8)];
			$salayList ['com_yiliao'] = $salaryList [$i] [($sit_gerenyinfaheji + 9)];
			$salayList ['com_yanglao'] = $salaryList [$i] [($sit_gerenyinfaheji + 10)];
			$salayList ['com_gongshang'] = $salaryList [$i] [($sit_gerenyinfaheji + 11)];
			$salayList ['com_shengyu'] = $salaryList [$i] [($sit_gerenyinfaheji + 12)];
			$salayList ['com_gongjijin'] = $salaryList [$i] [($sit_gerenyinfaheji + 13)];
			$salayList ['com_heji'] = $salaryList [$i] [($sit_gerenyinfaheji + 14)];
			$salayList ['laowufei'] = $salaryList [$i] [($sit_gerenyinfaheji + 15)];
			$salayList ['canbaojin'] = $salaryList [$i] [($sit_gerenyinfaheji + 16)];
			$salayList ['danganfei'] = $salaryList [$i] [($sit_gerenyinfaheji + 17)];
			$salayList ['paysum_zhongqi'] = $salaryList [$i] [($sit_gerenyinfaheji + 18)];
			
			// $salary['employid']},{$salary['salaryTimeId']},{$salary['salaryTimeId']}
			$salayList ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];
			$salayList ['salaryTimeId'] = $lastSalaryTimeId;
			if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
			                                  // 以上保存成功后，保存合计项
				$lastSumSalaryId = $this->objDao->saveSumSalary ( $salayList );
				if (! $lastSumSalaryId) {
					$exmsg->setError ( __FUNCTION__, "save  sumSalary get last_insert_id  faild " );
					// 事务回滚
					$this->objDao->rollback ();
					$this->objForm->setFormData ( "warn", "保存合计工资失败！" );
					throw new Exception ( $exmsg->error () );
				}
			} else {
				$lastSalaryId = $this->objDao->saveSalary ( $salayList );
			}
			if (! $lastSalaryId && $lastSalaryId != 0) {
				$exmsg->setError ( __FUNCTION__, "save  salary get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存固定工资失败！" );
				throw new Exception ( $exmsg->error () );
			}
			
			if ($i != ((count ( $salaryList ) - 1))) {
				// 如果是小于$sit_gerenyinfaheji的标志位存储到动态字段中
				for($j = 0; $j < $sit_gerenyinfaheji; $j ++) {
					// {$salaryMovement['fieldName']}',{$salaryMovement['salaryId']},{$salaryMovement['fieldValue']
					$salaryMovement = array ();
					$salaryMovement ['fieldName'] = $salaryList [0] [$j];
					$salaryMovement ['salaryId'] = $lastSalaryId;
					$salaryMovement ['fieldValue'] = $salaryList [$i] [$j];
					$lastSalaryMovementId = $this->objDao->saveSalaryMovement ( $salaryMovement );
					if (! $lastSalaryMovementId && $lastSalaryId != 0) {
						$exmsg->setError ( __FUNCTION__, "save  salaryMovement get last_insert_id  faild " );
						// 事务回滚
						$this->objDao->rollback ();
						$this->objForm->setFormData ( "warn", "保存动态工资字段失败！" );
						throw new Exception ( $exmsg->error () );
					}
				}
			}
			if (! empty ( $shifajian ) && $i != ((count ( $salaryList ) - 1))) {
				/**
				 * $salaryList[Sheet1][0][($count+20)]="实发合计减后项";
				 * $salaryList[Sheet1][0][($count+21)]="交中企基业减后项";
				 * $salaryList[Sheet1][0][($count+22)]="实发扣减项";
				 * $salaryList[Sheet1][$i][($count+20)]=sprintf("%01.2f", ($jisuan_var[$i]['shifaheji']-$salaryList[Sheet1][$i][$shifajian]))+0;
				 * $salaryList[Sheet1][$i][($count+21)]=sprintf("%01.2f", ($jisuan_var[$i]['jiaozhongqiheji']-$salaryList[Sheet1][$i][$shifajian]))+0;
				 * $salaryList[Sheet1][$i][($count+22)]=$salaryList[Sheet1][$i][$shifajian];
				 * 
				 * @var unknown_type
				 */
				$salaryMovement = array ();
				$salaryMovement ['fieldName'] = "实发合计减后项";
				$salaryMovement ['salaryId'] = $lastSalaryId;
				$salaryMovement ['fieldValue'] = $salaryList [$i] [($sit_gerenyinfaheji + 20)];
				$lastSalaryMovementId = $this->objDao->saveSalaryMovement ( $salaryMovement );
				if (! $lastSalaryMovementId && $lastSalaryId != 0) {
					$exmsg->setError ( __FUNCTION__, "save  salaryMovement get last_insert_id  faild " );
					// 事务回滚
					$this->objDao->rollback ();
					$this->objForm->setFormData ( "warn", "保存动态工资字段失败！" );
					throw new Exception ( $exmsg->error () );
				}
				$salaryMovement = array ();
				$salaryMovement ['fieldName'] = "交中企基业减后项";
				$salaryMovement ['salaryId'] = $lastSalaryId;
				$salaryMovement ['fieldValue'] = $salaryList [$i] [($sit_gerenyinfaheji + 21)];
				$lastSalaryMovementId = $this->objDao->saveSalaryMovement ( $salaryMovement );
				if (! $lastSalaryMovementId && $lastSalaryId != 0) {
					$exmsg->setError ( __FUNCTION__, "save  salaryMovement get last_insert_id  faild " );
					// 事务回滚
					$this->objDao->rollback ();
					$this->objForm->setFormData ( "warn", "保存动态工资字段失败！" );
					throw new Exception ( $exmsg->error () );
				}
			}
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = $lastSalaryTimeId;
		$opLog ['Subject'] = OP_LOG_SAVE_SALARY;
		$opLog ['memo'] = '';
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "savesalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchSalaryTime ();
		// exit;
		// $this->objForm->setFormData("errorlist",$errorList);
		// $this->objDao->getSome();
	}
	
	//FIXME 保存年终奖
	function saveNianSalary() {
		$exmsg = new EC (); // 设置错误信息类
		$adminPO = $_SESSION ['admin'];
		session_start ();
		$comname = $_POST ['comname'];
		$salaryTimeDate = $_POST ['salaryTime'];
		$salaryList = $_SESSION ['excelList'];
		// var_dump($salaryList);
		foreach ( $salaryList [0] as $num => $row ) {
			if (ereg ( $row, "身份证号" )) { // ereg字符串比对解析。
				$sit_shenfenzhenghao = $num; // 得到“身份证”字段的标志位
			} elseif (ereg ( $row, "年终奖" )) {
				$sit_nianzhongjiang = $num; // 得到年终奖字段的标志位
			}
		}
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		/*
		 * //根据日期查询公司时间 $salaryTime=$this->objDao->searchSalaryTimeBy_Salarydata($salaryTimeDate); if(!empty($salaryTime)){ $this->objForm->setFormData("warn","工资月份日期已经存在！"); $this->searchSalaryTime(); }
		 */
		// 查询公司信息
		$company = $this->objDao->searchCompanyByName ( $comname );
		if (empty ( $company )) {
			// 添加公司信息
			$companyList = array ();
			$companyList ['name'] = $comname;
			$companyId = $this->objDao->addCompany ( $companyList );
			if (! $companyId) {
				
				$exmsg->setError ( __FUNCTION__, "save  company  get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存工资时间失败！" );
				throw new Exception ( $exmsg->error () );
			}
		} else {
			$companyId = $company ['id'];
		}
		
		// 添加工资日期
		$salaryTime = array ();
		// $salaryTime['companyId']},'{$salaryTime['salaryTime']}','{$salaryTime['op_salaryTime']}
		$salaryTime ['companyId'] = $companyId;
		$salaryTime ['salary_time'] = $salaryTimeDate;
		$salaryTime ['op_salaryTime'] = date ( "Y-m-d" );
		$salaryTime ['salaryType'] = SALARY_TIME_TYPE;
		$salaryTime ['op_id'] = $adminPO ['id'];
		$lastSalaryTimeId = $this->objDao->saveSalaryNianTime ( $salaryTime );
		if (! $lastSalaryTimeId && $lastSalaryId != 0) {
			$exmsg->setError ( __FUNCTION__, "save  salaryNianTime  get last_insert_id  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "保存年终奖工资时间失败！" );
			throw new Exception ( $exmsg->error () );
		}
// 		echo  var_dump($salaryList);
		for($i = 1; $i < count ( $salaryList ); $i ++) {
			// 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
			$salayList = array ();
			$salayList ['nianzhongjiang'] = $salaryList [$i] [$sit_nianzhongjiang];
			$salayList ['yingfaheji'] = $salaryList [$i] [($sit_nianzhongjiang + 1)];
			$salayList ['nian_daikoushui'] = $salaryList [$i] [($sit_nianzhongjiang + 3)];
			$salayList ['shifajinka'] = $salaryList [$i] [($sit_nianzhongjiang + 4)];
			$salayList ['jiaozhongqi'] = $salaryList [$i] [($sit_nianzhongjiang + 5)];
			$salayList ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];
			$salayList ['salaryTimeId'] = $lastSalaryTimeId;
			if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
			                                  // 以上保存成功后，保存合计项
				$lastSumSalaryId = $this->objDao->saveSumNianSalary ( $salayList );
				if (! $lastSumSalaryId) {
					$exmsg->setError ( __FUNCTION__, "save  sumNianSalary get last_insert_id  faild " );
					// 事务回滚
					$this->objDao->rollback ();
					$this->objForm->setFormData ( "warn", "保存年终奖合计工资失败！" );
					throw new Exception ( $exmsg->error () );
				}
			} else {
				$lastSalaryId = $this->objDao->saveNianSalary ( $salayList );
			}
			if (! $lastSalaryId && $lastSalaryId != 0) {
				$exmsg->setError ( __FUNCTION__, "save  nian_salary get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存年终奖工资失败！" );
				throw new Exception ( $exmsg->error () );
			}
			/*
			 * if($i!=((count($salaryList)-1))){ //如果是小于$sit_gerenyinfaheji的标志位存储到动态字段中 for($j=0;$j<$sit_gerenyinfaheji;$j++){ //{$salaryMovement['fieldName']}',{$salaryMovement['salaryId']},{$salaryMovement['fieldValue'] $salaryMovement=array(); $salaryMovement['fieldName']=$salaryList[0][$j]; $salaryMovement['salaryId']=$lastSalaryId; $salaryMovement['fieldValue']=$salaryList[$i][$j]; $lastSalaryMovementId=$this->objDao->saveSalaryMovement($salaryMovement); if(!$lastSalaryMovementId&&$lastSalaryId!=0){ $exmsg->setError(__FUNCTION__, "save salaryMovement get last_insert_id faild "); //事务回滚 $this->objDao->rollback(); $this->objForm->setFormData("warn","保存动态工资字段失败！"); throw new Exception ($exmsg->error()); } } }
			 */
		}
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = $lastSalaryTimeId;
		$opLog ['Subject'] = OP_LOG_SAVE__NIAN_SALARY;
		$opLog ['memo'] = '';
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "saveniansalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchNianSalaryTime ();
	}
	
	// 个税详细BY孙瑞鹏
	function searchGeshuiByIdJosn() {
		// $this->mode="salaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
		$salaryTime = $_REQUEST ['time'];
       // var_dump($salaryTimeId);
        $salaryTime=str_replace('\"','"',$salaryTime);
       $salaryTimeId=str_replace('\"','"',$salaryTimeId);
       // var_dump($salaryTimeId);
        $salaryTimeId=json_decode($salaryTimeId);
        //var_dump($salaryTimeId);
        $salaryTime=json_decode($salaryTime);
		$this->objDao = new SalaryDao ();

		$salaryListArrayAll = array ();
		$i = 0;
		global $salaryTypeTable;
        /**
         * $salaryList=array();
        // foreach ($excelList as $salaryTimeList) {
        //var_dump($salaryTimeList);
        //导出excel行数标记

        $salaryList[Sheet1][$hang][0]="姓名";
        $salaryList[Sheet1][$hang][1]="身份证号";
        $salaryList[Sheet1][$hang][2]="银行卡号";
        $salaryList[Sheet1][$hang][3]="开户行";
        $salaryList[Sheet1][$hang][4]="个人所得税";
         */
        ["aaa"=> 111,"bbb" => 222];
        $salaryList=array();
        while ( $row = mysql_fetch_array ( $salaryList ) ) {
		$movKeyArr = array ();
		$z = 0;
        $salaryTimeId = preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $salaryTimeId);

       for($h=0;$h<(count($salaryTimeId));$h++){

        $salaryList = $this->objDao->searchGeshuiBy_SalaryTimeId ( $salaryTimeId[$h], $salaryTime[$h] );

		while ( $row = mysql_fetch_array ( $salaryList ) ) {

			foreach ( $salaryTypeTable as $key => $value ) {
				$rowSalCol = array ();
				$rowFields = array ();
				if ($i == 0) {
					$rowSalCol ['text'] = $value;
					$rowSalCol ["dataIndex"] = $key;
					$salaryListArray ['columns'] [] = $rowSalCol;
				}
				$rowFields ["name"] = $key;
				$salaryListArray ['fields'] [] = $rowFields;
				$rowData [$key] = $row [$key];
			}
			$salaryListArray ['data'] [] = $rowData;
			$i ++;
		}
        //print_r($salaryListArray);
		echo json_encode ( $salaryListArray );
            if($h==0){
                $salaryListArrayAll['data'] = $salaryListArray['data'];
                $salaryListArrayAll['columns'] = $salaryListArray['columns'];
                $salaryListArrayAll['fields'] = $salaryListArray['fields'];
            }
            else{

                $salaryListArrayAll['data']= array_merge($salaryListArrayAll['data'],$salaryListArray['data']);
            }
        }
		$countData = count ( $salaryListArrayAll ['data'] );
		
		// $salarySumListArray=array();e
		// var_dump($salarySumListArray);
		echo json_encode ( $salaryListArrayAll );
		exit ();
	}
	
	// 个税类型修改BY孙瑞鹏
	function searchGeshuiTypeByIdJosn() {
		// $this->mode="salaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchGeshuiBy_SalaryTypeId ( $salaryTimeId );
		$salaryListArray = array ();
		$salaryListArray ['data'] [] = mysql_fetch_array ( $salaryList );
		echo json_encode ( $salaryListArray );
		exit ();
	}
	
	// 个税类型修改上月BY孙瑞鹏
	function setShangyueType() {
		// $this->mode="salaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$this->objDao->setTypeShangyue ( $salaryTimeId );
		// echo json_encode($salaryListArray);
		exit ();
	}
	
	// 个税类型修改本月BY孙瑞鹏
	function setBenyueType() {
		// $this->mode="salaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$this->objDao->setTypeBenyue ( $salaryTimeId );
		// echo json_encode($salaryListArray);
		exit ();
	}
	
	function saveErSalary() {
		$exmsg = new EC (); // 设置错误信息类
		$adminPO = $_SESSION ['admin'];
		session_start ();
		$comname = $_POST ['comname'];
		
		$salaryTimeDate = $_POST ['salaryTime'];
		echo $comname . $salaryTimeDate;
		$salaryList = $_SESSION ['excelList'];
		// var_dump($salaryList);
		foreach ( $salaryList [0] as $num => $row ) {
			if (ereg ( $row, "身份证号" )) {
				$sit_shenfenzhenghao = $num; // 等到“身份证”字段的标志位
			} elseif (ereg ( $row, "二次工资合计" )) {
				$sit_ercigongziheji = $num; // 得到年终奖字段的标志位
			}
		}
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		// 查询公司信息
		$company = $this->objDao->searchCompanyByName ( $comname );
		if (empty ( $company )) {
			// 添加公司信息
			$companyList = array ();
			$companyList ['name'] = $comname;
			$companyId = $this->objDao->addCompany ( $companyList );
			if (! $companyId) {
				$exmsg->setError ( __FUNCTION__, "save  company  get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存二次工资时间失败！" );
				throw new Exception ( $exmsg->error () );
			}
		} else {
			$companyId = $company ['id'];
		}
		
		// 添加工资日期
		$salaryTime = array ();
		// $salaryTime['companyId']},'{$salaryTime['salaryTime']}','{$salaryTime['op_salaryTime']}
		$salaryTime ['companyId'] = $companyId;
		$salaryTime ['salary_time'] = $salaryTimeDate;
		$salaryTime ['op_salaryTime'] = date ( "Y-m-d" );
		$salaryTime ['salaryType'] = ER_SALARY_TIME_TYPE;
		$salaryTime ['op_id'] = $adminPO ['id'];
		$lastSalaryTimeId = $this->objDao->saveSalaryNianTime ( $salaryTime );
		if (! $lastSalaryTimeId && $lastSalaryId != 0) {
			$exmsg->setError ( __FUNCTION__, "save  salaryNianTime  get last_insert_id  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "保存二次工资时间失败！" );
			throw new Exception ( $exmsg->error () );
		}
		for($i = 1; $i < count ( $salaryList ); $i ++) {
			// 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
			$salayList = array ();
			$salayList ['ercigongziheji'] = $salaryList [$i] [$sit_ercigongziheji];
			$salayList ['dangyueyingfa'] = $salaryList [$i] [($sit_ercigongziheji + 1)];
			$salayList ['yingfaheji'] = $salaryList [$i] [($sit_ercigongziheji + 2)];
			$salayList ['shiye'] = $salaryList [$i] [($sit_ercigongziheji + 3)];
			$salayList ['yiliao'] = $salaryList [$i] [($sit_ercigongziheji + 4)];
			$salayList ['yanglao'] = $salaryList [$i] [($sit_ercigongziheji + 5)];
			$salayList ['gongjijin'] = $salaryList [$i] [($sit_ercigongziheji + 6)];
			$salayList ['yingkoushui'] = $salaryList [$i] [($sit_ercigongziheji + 7)];
			$salayList ['yikoushui'] = $salaryList [$i] [($sit_ercigongziheji + 8)];
			$salayList ['bukoushui'] = $salaryList [$i] [($sit_ercigongziheji + 9)];
			$salayList ['jinka'] = $salaryList [$i] [($sit_ercigongziheji + 10)];
			$salayList ['jiaozhongqi'] = $salaryList [$i] [($sit_ercigongziheji + 11)];
			$salayList ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];
			$salayList ['salaryTimeId'] = $lastSalaryTimeId;
			if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
			                                  // 以上保存成功后，保存合计项
				$lastSumSalaryId = $this->objDao->saveSumErSalary ( $salayList );
				if (! $lastSumSalaryId) {
					$exmsg->setError ( __FUNCTION__, "save  sumNianSalary get last_insert_id  faild " );
					// 事务回滚
					$this->objDao->rollback ();
					$this->objForm->setFormData ( "warn", "保存二次工资合计失败！" );
					throw new Exception ( $exmsg->error () );
				}
			} else {
				$lastSalaryId = $this->objDao->saveErSalary ( $salayList );
			}
			if (! $lastSalaryId && $lastSalaryId != 0) {
				$exmsg->setError ( __FUNCTION__, "save  nian_salary get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存二次工资失败！" );
				throw new Exception ( $exmsg->error () );
			}
			if ($i != ((count ( $salaryList ) - 1))) {
				// 如果是小于$sit_gerenyinfaheji的标志位存储到动态字段中
				for($j = 0; $j < $sit_ercigongziheji; $j ++) {
					// {$salaryMovement['fieldName']}',{$salaryMovement['salaryId']},{$salaryMovement['fieldValue']
					$salaryMovement = array ();
					$salaryMovement ['fieldName'] = $salaryList [0] [$j];
					$salaryMovement ['ersalaryId'] = $lastSalaryId;
					$salaryMovement ['fieldValue'] = $salaryList [$i] [$j];
					$lastSalaryMovementId = $this->objDao->saveErSalaryMovement ( $salaryMovement );
					if (! $lastSalaryMovementId && $lastSalaryId != 0) {
						$exmsg->setError ( __FUNCTION__, "save  salaryMovement get last_insert_id  faild " );
						// 事务回滚
						$this->objDao->rollback ();
						$this->objForm->setFormData ( "warn", "保存动态工资字段失败！" );
						throw new Exception ( $exmsg->error () );
					}
				}
			}
		}
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = $lastSalaryTimeId;
		$opLog ['Subject'] = OP_LOG_SAVE__ER_SALARY;
		$opLog ['memo'] = '';
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "saveniansalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchErSalaryTime ();
	}
	
	// FIXME 查询工资明细
	function searchSalaryTime() {
		$this->mode = "salaryTimeList";
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searhSalaryTimeList ();
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
	}
	function searchNianSalaryTime() {
		$this->mode = "salaryNianTimeList";
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searhSalaryNianTimeList ();
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
	}
	function searchErSalaryTime() {
		$this->mode = "salaryErTimeList";
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searhSalaryErTimeList ();
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
	}
	function searchSalaryByOther() {
		$type = $_POST ['modeType'];
		if ($type == 'import') {
			$this->mode = "toSalComlist";
		} else if ($type == 'service') {
			$this->mode = "toServiceComlist";
		} else if ($type == 'caiWuImport') {
			$this->mode = "toCaiwuImport";
		} else {
			$this->mode = "salaryTimeList";
		}
		$where = array ();
		$where ['companyName'] = $_POST ['comname'];
		$where ['salaryTime'] = $_POST ['salaryTime'];
		$where ['op_salaryTime'] = $_POST ['opTime'];
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searhSalaryTimeList ( $where );
		if ($type == 'caiWuImport') {
			$result = $this->objDao->getCompanyLisyByName ( $where ['companyName'] );
			$salErList = array ();
			while ( $row = mysql_fetch_array ( $result ) ) {
				$companyId = $row ['id'];
				$salaryNianList = $this->objDao->searchNianSalaryAndErSalaryTimeByComId ( $companyId );
				$salErList = array ();
				$s = 0;
				while ( $list = mysql_fetch_array ( $salaryNianList ) ) {
					$salErList [$list ['salaryTime']] [$s] = $list;
					$s ++;
				}
				$salErList [$companyId] = $salErList;
				$this->objForm->setFormData ( "salErList", $salErList );
			}
		}
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
	}
	function searchSalaryById() {
		$this->mode = "salaryList";
		$salaryTimeId = $_REQUEST ['id'];
		$this->objDao = new SalaryDao ();
		$salaryPO = $this->objDao->searchSalaryTimeBy_id ( $salaryTimeId );
		$salaryList = $this->objDao->searchSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salarySumList = $this->objDao->searchSumSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$salaryMovementList = $this->objDao->searchSalaryMovementBy_SalaryId ( $row ['id'] );
			while ( $row_move = mysql_fetch_array ( $salaryMovementList ) ) {
				$salaryListArray [$i] [$row_move ['fieldName']] = $row_move ['fieldValue'];
			}
			// var_dump($row) ;
			if (array_keys ( $row ) !== "salary_type") {
				$salaryListArray [$i] ['guding_salary'] = $row;
			}
			if ($row ['salary_type'] == 1) {
				$result = $this->objDao->getOpLog ( null, $row ['id'], " subject='" . OP_LOG_UPDATE_PER_SALARY . "'" );
				$log = "<font style='word-wrap:break-word; background-color:red;'>";
				$row_log = mysql_fetch_array ( $result );
				$log .= "修改时间" . $row_log ['time'] . ':' . $row_log ['memo'];
				$log .= "</font>";
			} else if ($i != 0) {
				$log = " ";
			}
			unset ( $row ['salary_type'] );
			unset ( $row ['22'] );
			$salaryListArray [$i] ['guding_salary'] = $row;
			$salaryListArray [$i] ['log'] = $log;
			$i ++;
		}
		// $salarySumListArray=array();
		$this->objForm->setFormData ( "salaryPO", $salaryPO );
		$this->objForm->setFormData ( "salaryTimeList", $salaryListArray );
		$this->objForm->setFormData ( "salarySumTimeList", $salarySumList );
	}
	function searchSalaryByIdJosn() {
		// $this->mode="salaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		global $salaryTable;
		$movKeyArr = array ();
		$z = 0;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$salaryMovementList = $this->objDao->searchSalaryMovementBy_SalaryId ( $row ['id'] );
			$j = 0;
			while ( $row_move = mysql_fetch_array ( $salaryMovementList ) ) {
				$rowFields = array ();
				$rowCol = array ();
				if ($row_move ['fieldName'] == NULL) {
					continue;
				}
				if ($i == 0) {
					$rowCol ['text'] = $row_move ['fieldName'];
					if ($row_move ['fieldName'] == '部门' || $row_move ['fieldName'] == '身份证号' || $row_move ['fieldName'] == '姓名') {
						// hidden:true
						$rowCol ["locked"] = true;
					} else {
						$rowCol ["hidden"] = true;
					}
					$rowCol ["dataIndex"] = $row_move ['id'];
					$salaryListArray ['columns'] [] = $rowCol;
					$movKeyArr [$z] = $row_move ['id'];
					$z ++;
				}
				$rowData ["{$movKeyArr[$j]}"] = $row_move ['fieldValue'];
				$j ++;
				if ($i == 0) {
					$rowFields ["name"] = "{$row_move['id']}";
					$salaryListArray ['fields'] [] = $rowFields;
				}
			}
			foreach ( $salaryTable as $key => $value ) {
				$rowSalCol = array ();
				$rowFields = array ();
				if ($i == 0) {
					$rowSalCol ['text'] = $value;
					$rowSalCol ["dataIndex"] = $key;
					$rowSalCol ["summaryType"] = 'sum';
					// summaryType: 'count',
					if ($key == 'paysum_zhongqi') {
						$rowSalCol ["width"] = 150;
					} else {
						$rowSalCol ["width"] = 80;
					}
					$salaryListArray ['columns'] [] = $rowSalCol;
				}
				$rowFields ["name"] = $key;
				$rowFields ["type"] = 'float';
				// type: 'int'
				$salaryListArray ['fields'] [] = $rowFields;
				$rowData [$key] = $row [$key];
			}
			$salaryListArray ['data'] [] = $rowData;
			$i ++;
		}
		$countData = count ( $salaryListArray ['data'] );
		
		// $salarySumListArray=array();e
		// var_dump($salarySumListArray);
		echo json_encode ( $salaryListArray );
		exit ();
	}
	function searchNianSalaryById() {
		$this->mode = "nianSalaryList";
		$salaryTimeId = $_REQUEST ['id'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchNianSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salarySumList = $this->objDao->searchSumNianSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		$this->objDao = new EmployDao ();
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$employ = $this->objDao->getEmByEno ( $row ['employid'] );
			$row ['comName'] = $employ ['e_company'];
			$row ['e_name'] = $employ ['e_name'];
			$salaryListArray [$i] = $row;
			// $salaryListArray[$i]['log']=$log;
			$i ++;
		}
		// $salarySumListArray=array();
		$this->objForm->setFormData ( "salaryTimeList", $salaryListArray );
		$this->objForm->setFormData ( "salarySumTimeList", $salarySumList );
	}
	function searchNianSalaryByIdJson() {
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchNianSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		$this->objDao = new EmployDao ();
		global $salNianTable;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$employ = $this->objDao->getEmByEno ( $row ['employid'] );
			$row ['comName'] = $employ ['e_company'];
			$row ['e_name'] = $employ ['e_name'];
			foreach ( $salNianTable as $key => $value ) {
				$rowSalCol = array ();
				$rowFields = array ();
				if ($i == 0) {
					$rowSalCol ['text'] = $value;
					if ($value == '单位' || $value == '身份证号' || $value == '姓名') {
						$rowSalCol ["locked"] = true;
					} else {
						$rowSalCol ["summaryType"] = 'sum';
					}
					$rowSalCol ["dataIndex"] = $key;
					
					// summaryType: 'count',
					$salaryListArray ['columns'] [] = $rowSalCol;
				}
				$rowFields ["name"] = $key;
				if ($value != '单位' && $value != '身份证号' && $value != '姓名') {
					$rowFields ["type"] = 'float';
				}
				
				// type: 'int'
				$salaryListArray ['fields'] [] = $rowFields;
				$rowData [$key] = $row [$key];
			}
			$salaryListArray ['data'] [] = $rowData;
			$i ++;
		}
		echo json_encode ( $salaryListArray );
		exit ();
	}
	function searchErSalaryById() {
		$this->mode = "erSalaryList";
		$salaryTimeId = $_REQUEST ['id'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchErSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salarySumList = $this->objDao->searchSumErSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		$this->objDao = new SalaryDao ();
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$salaryMovementList = $this->objDao->searchErSalaryMovementBy_SalaryId ( $row ['id'] );
			while ( $row_move = mysql_fetch_array ( $salaryMovementList ) ) {
				// var_dump($row_move);
				$salaryListArray [$i] [$row_move ['fieldName']] = $row_move ['fieldValue'];
			}
			// var_dump($salaryListArray) ;
			if (array_keys ( $row ) !== "salary_type") {
				$salaryListArray [$i] ['guding_salary'] = $row;
			}
			$i ++;
		}
		// var_dump($salaryListArray);
		// $salarySumListArray=array();
		$this->objForm->setFormData ( "salaryTimeList", $salaryListArray );
		$this->objForm->setFormData ( "salarySumTimeList", $salarySumList );
	}
	function searchErSalaryTimeListByIdJson() {
		$companyId = $_REQUEST ['companyId'];
		$salTime = $_REQUEST ['salTime'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchErSalaryTimeBySalaryTimeAndComId ( $salTime, $companyId );
		$salTimeList = array ();
		$i = 0;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$salTimeList [$i] ['salTimeId'] = $row ['id'];
			$salTimeList [$i] ['salaryTime'] = $row ['salaryTime'];
			$i ++;
		}
		echo json_encode ( $salTimeList );
		exit ();
	}
	function searchErSalaryByIdJson() {
		$this->mode = "erSalaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchErSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		$this->objDao = new SalaryDao ();
		global $salErTable;
		$movKeyArr = array ();
		$z = 0;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$salaryMovementList = $this->objDao->searchErSalaryMovementBy_SalaryId ( $row ['id'] );
			$j = 0;
			while ( $row_move = mysql_fetch_array ( $salaryMovementList ) ) {
				$rowFields = array ();
				$rowCol = array ();
				if ($row_move ['fieldName'] == NULL) {
					continue;
				}
				if ($i == 0) {
					$rowCol ['text'] = $row_move ['fieldName'];
					if ($row_move ['fieldName'] == '部门' || $row_move ['fieldName'] == '身份证号' || $row_move ['fieldName'] == '姓名') {
						// hidden:true
						$rowCol ["locked"] = true;
					} else {
						$rowCol ["hidden"] = true;
					}
					$rowCol ["dataIndex"] = $row_move ['id'];
					$salaryListArray ['columns'] [] = $rowCol;
					$movKeyArr [$z] = $row_move ['id'];
					$z ++;
				}
				$rowData ["{$movKeyArr[$j]}"] = $row_move ['fieldValue'];
				$j ++;
				if ($i == 0) {
					$rowFields ["name"] = "{$row_move['id']}";
					$salaryListArray ['fields'] [] = $rowFields;
				}
			}
			
			foreach ( $salErTable as $key => $value ) {
				$rowSalCol = array ();
				$rowFields = array ();
				if ($i == 0) {
					$rowSalCol ['text'] = $value ['key'];
					$rowSalCol ["dataIndex"] = $key;
					$rowSalCol ["summaryType"] = 'sum';
					// summaryType: 'count',
					if (! empty ( $value ['width'] )) {
						$rowSalCol ["width"] = $value ['width'];
					}
					$salaryListArray ['columns'] [] = $rowSalCol;
				}
				$rowFields ["name"] = $key;
				$rowFields ["type"] = 'float';
				// type: 'int'
				$salaryListArray ['fields'] [] = $rowFields;
				$rowData [$key] = $row [$key];
			}
			$salaryListArray ['data'] [] = $rowData;
			$i ++;
		}
		echo json_encode ( $salaryListArray );
		exit ();
	}
	function delSalayByTimeId() {
		$salaryTimeId = $_REQUEST ['timeid'];
		$exmsg = new EC (); // 设置错误信息类
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		$salaryList = $this->objDao->searchSalaryTimeBy_id ( $salaryTimeId );
		$result = $this->objDao->delSalaryMovement_BySalaryId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryMovement  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除动态工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delSalaryBy_TimeId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salary  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除固定工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delSalaryTimeBy_Id ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryTime  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除工资时间表失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = 0;
		$opLog ['Subject'] = OP_LOG_DEL_SALARY;
		$opLog ['memo'] = $salaryList ['company_name'] . ':' . $salaryList ['salaryTime'];
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchSalaryTime ();
	}
	function delNianSalayByTimeId() {
		$salaryTimeId = $_REQUEST ['timeid'];
		$exmsg = new EC (); // 设置错误信息类
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		$salaryList = $this->objDao->searchNianSalaryTimeBy_id ( $salaryTimeId );
		/*
		 * //$result=$this->objDao->delSalaryMovement_BySalaryId($salaryTimeId); if(!$result){ $exmsg->setError(__FUNCTION__, "del salaryMovement faild "); //事务回滚 $this->objDao->rollback(); $this->objForm->setFormData("warn","删除动态工资字段失败！"); throw new Exception ($exmsg->error()); }
		 */
		$result = $this->objDao->delNianSalaryBy_TimeId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salary  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除固定工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delNianSalaryTimeBy_Id ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryTime  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除工资时间表失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = 0;
		$opLog ['Subject'] = OP_LOG_DEL_NIAN_SALARY;
		$opLog ['memo'] = $salaryList ['company_name'] . ':' . $salaryList ['salaryTime'];
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchNianSalaryTime ();
	}
	function delErSalayByTimeId() {
		$salaryTimeId = $_REQUEST ['timeid'];
		$exmsg = new EC (); // 设置错误信息类
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		$salaryList = $this->objDao->searchNianSalaryTimeBy_id ( $salaryTimeId );
		$result = $this->objDao->delErSalaryMovement_BySalaryId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryMovement  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除动态工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delErSalaryBy_TimeId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salary  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除固定工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delNianSalaryTimeBy_Id ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryTime  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除工资时间表失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = 0;
		$opLog ['Subject'] = OP_LOG_DEL_NIAN_SALARY;
		$opLog ['memo'] = $salaryList ['company_name'] . ':' . $salaryList ['salaryTime'];
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchErSalaryTime ();
	}
}

$objModel = new SaveSalaryAction ( $actionPath );
$objModel->dispatcher ();

?>
