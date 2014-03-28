<?php
require_once ("module/form/" . $actionPath . "Form.class.php");
require_once ("module/dao/" . $actionPath . "Dao.class.php");
require_once ("module/dao/EmployDao.class.php");
require_once ("tools/fileTools.php");
require_once ("tools/excel_class.php");
require_once ("tools/sumSalary.class.php");
require_once ("tools/Classes/PHPExcel.php");
class SalaryAction extends BaseAction {
	/*
	 * @param $actionPath @return SalaryAction
	 */
	function SalaryAction($actionPath) {
		parent::BaseAction ();
		$this->objForm = new SalaryForm ();
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
		$this->controller ();
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
			case "input" :
				$this->modelInput ();
				break;
			case "upload" :
				$this->salaryUpload ();
				break;
			case "excelToHtml" :
				$this->excelToHtml ();
				break;
			case "newExcelToHtml" :
				$this->newExcelToHtml ();
				break;
			case "sumSalary" :
				$this->sumSalary ();
				break;
			case "sumNianSalary" :
				$this->sumNianSalary ();
				break;
			case "sumErSalary" :
				$this->sumErSalary ();
				break;
			case "import" :
				$this->importSalary ();
				break;
			case "del" :
				$this->fileDel ();
				break;
			case "rename" :
				$this->rename ();
				break;
			case "toSalaryUpdate" :
				$this->toSalaryUpdate ();
				break;
			case "updateSalary" :
				$this->updateSalary ();
				break;
			case "salUpdate" :
				$this->salUpdate ();
				break;
			case "salDuiBi" :
				$this->salDuiBi ();
				break;
			case "perZiliaoDuibi" :
				$this->perZiliaoDuibi ();
				break;
			case "salPerDuibi" :
				$this->salPerDuibi ();
				break;
			case "jishuDuibi" :
				$this->jishuDuibi ();
				break;
			case "toImportSalPage" :
				$this->toImportSalPage ();
				break;
			case "salImportByCom" :
				$this->salImportByCom ();
				break;
			case "salFaByCom" :
				$this->salFaByCom ();
				break;
			case "getSalTemlate" :
				$this->getSalTemlate ();
				break;
			case "updateSalMark" :
				$this->updateSalMark ();
				break;
			case "toSalListExcel" :
				$this->toSalListExcel ();
				break;
			case "toExtTest" :
				$this->toExtTest ();
				break;
			default :
				$this->modelInput ();
				break;
		}
	}
	function modelInput() {
		$this->mode = "toUpload";
		$op = new fileoperate ();
		$files = $op->list_filename ( "upload/", 1 );
		$this->objForm->setFormData ( "files", $files );
	}
	function salaryUpload() {

		$exmsg = new EC ();
		$fileName = $_FILES ['file'] ['name'];

		$errorMsg = "";
		 var_dump($_FILES);
		
		$fileArray = split ( "\.", $_FILES ['file'] ['name'] );
		$fullfilepath = UPLOADPATH . $fileArray [0] . "." . $fileArray [1];
		 var_dump($fileArray);
		if (count ( $fileArray ) != 2) {
			$this->mode = "toUpload";
			$errorMsg = '文件名格式 不正确';
			$this->objForm->setFormData ( "error", $errorMsg );
			return;
		} else if ($fileArray [1] != 'xls' && $fileArray [1] != 'xlsx') {
			$this->mode = "toUpload";
			$errorMsg = '文件类型不正确，必须是xls，xlsx类型';
			$this->objForm->setFormData ( "error", $errorMsg );
			return;
		}
		if ($_FILES ['file'] ['error'] != 0) {
			$error = $_FILES ['file'] ['error'];
			switch ($error) {
				case 1 :
					$errorMsg = '1,上传的文件超过了php.ini中  upload_max_filesize选项限制的值.';
					break;
				case 2 :
					$errorMsg = '2,上传文件的大小超过了HTML表单中MAX_FILE_SIZE  选项指定的大小';
					break;
				case 3 :
					$errorMsg = '3,文件只有部分被上传';
					break;
				case 4 :
					$errorMsg = '4,文件没有被上传';
					break;
				case 6 :
					$errorMsg = '找不到临文件夹';
					break;
				case 7 :
					$errorMsg = '文件写入失败';
					break;
			}
		}
		if ($errorMsg != "") {
			$this->mode = "toUpload";
			$this->objForm->setFormData ( "error", $errorMsg );
			return;
		}
		if (! move_uploaded_file ( $_FILES ['file'] ['tmp_name'], $fullfilepath )) { // 上传文件
		                                                                             // print_r($_FILES);print_r($fullfilepath);
		                                                                             // $this->objDao->rollback();
			$this->objForm->setFormData ( "error", "文件导入失败" );
			throw new Exception ( UPLOADPATH . " is a disable dir" );
			
			// die("UPLOAD FILE FAILED:".$_FILES['plusfile']['error']);
		} else {
			$this->mode = "toUpload";
			$succMsg = '文件导入成功';
			$this->objForm->setFormData ( "succ", $succMsg );
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = 0;
		$opLog ['Subject'] = OP_LOG_UPLOAD_FILE;
		$opLog ['memo'] = '文件名称：' . $_FILES ['file'] ['tmp_name'];
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$this->objDao = new EmployDao ();
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "uploadfile  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			// $this->objDao->rollback();
			throw new Exception ( $exmsg->error () );
		}
		$op = new fileoperate ();
		$files = $op->list_filename ( "upload/", 1 );
		$this->objForm->setFormData ( "files", $files );
	}
	function excelToHtml() {
		$fname = $_REQUEST ['fname'];
		$checkType = $_REQUEST ['checkType'];
		$err = Read_Excel_File ( "upload/" . $fname, $return );
		if ($err != 0) {
			$this->objForm->setFormData ( "error", $err );
		}
		$this->objForm->setFormData ( "salarylist", $return );
		if ($checkType) {
			$companyName = $_POST ['companyName'];
			$companyId = $_POST ['companyId'];
			$salDate = $_POST ['salDate'];
			$this->objForm->setFormData ( "companyName", $companyName );
			$this->objForm->setFormData ( "companyId", $companyId );
			$this->objForm->setFormData ( "salDate", $salDate );
			$this->objForm->setFormData ( "checkType", $checkType );
		}
		$this->mode = "salaryList";
	}

    function newExcelToHtml() {
        $fname = $_REQUEST ['fname'];
        $checkType = $_REQUEST ['checkType'];
        $path = "upload/" . $fname;
        $_ReadExcel = new PHPExcel_Reader_Excel2007 ();
        if (!$_ReadExcel->canRead($path))
            $_ReadExcel = new PHPExcel_Reader_Excel5 ();
        $_phpExcel = $_ReadExcel->load($path);
        $_newExcel = array();
        for ($_s = 0; $_s < 1; $_s++) {
            $_currentSheet = $_phpExcel->getSheet($_s);
            $_allColumn = $_currentSheet->getHighestColumn();
            $_allRow = $_currentSheet->getHighestRow();
            $temp = 0;
            for ($_r = 1; $_r <= $_allRow; $_r++) {
                for ($_currentColumn = 'A'; $_currentColumn <= $_allColumn; $_currentColumn++) {
                    $address = $_currentColumn . $_r;
                    $val = $_currentSheet->getCell($address)->getValue();
                    $_newExcel ['Sheet1'] [$temp] [] = $val;
                }
                $temp++;
            }
        }

        $this->objForm->setFormData("salarylist", $_newExcel);
        if ($checkType) {
            $companyName = $_POST ['companyName'];
            $companyId = $_POST ['companyId'];
            $salDate = $_POST ['salDate'];
            $this->objForm->setFormData("companyName", $companyName);
            $this->objForm->setFormData("companyId", $companyId);
            $this->objForm->setFormData("salDate", $salDate);
            $this->objForm->setFormData("checkType", $checkType);
        }
        $this->mode = "salaryList";
    }
	function sumSalary() {
		$this->mode = "sumlist";
		$shenfenzheng = ($_POST ['shenfenzheng'] - 1);
		$addArray = $_POST ['add'];
		$delArray = $_POST ['del'];
		$freeTex = $_POST ['freeTex'] - 1;
		$shifajian = $_POST ['shifajian'] - 1;
		$checkType = $_REQUEST ['salType']; // 客服做工资选项
		$addArray = explode ( "+", $addArray );
		if (! empty ( $delArray )) {
			$delArray = explode ( "+", $delArray );
		} else {
			$delArray = "";
		}
		// print_r($addArray);
		// print_r($delArray);
		session_start ();
		$salaryList = $_SESSION ['salarylist'];
		$count_add = count ( $salaryList [Sheet1] [0] );
		// 增加字段1·
		// 个人失业 个人医疗 个人养老 个人合计 单位失业 单位医疗 单位养老 单位工伤 单位生育 单位合计
		// 2011-10-14增加字段 姓名 身份证号 银行卡号 身份类别 社保基数 公积金基数
		$salaryList [Sheet1] [0] [($count_add + 0)] = " 银行卡号";
		$salaryList [Sheet1] [0] [($count_add + 1)] = "身份类别";
		$salaryList [Sheet1] [0] [($count_add + 2)] = " 社保基数";
		$salaryList [Sheet1] [0] [($count_add + 3)] = "公积金基数";
		// 再次算出字段总列数
		$count = count ( $salaryList [Sheet1] [0] );
		$salaryList [Sheet1] [0] [($count + 0)] = "个人应发合计";
		$salaryList [Sheet1] [0] [($count + 1)] = "个人失业";
		$salaryList [Sheet1] [0] [($count + 2)] = "个人医疗";
		$salaryList [Sheet1] [0] [($count + 3)] = "个人养老";
		$salaryList [Sheet1] [0] [($count + 4)] = "个人公积金";
		$salaryList [Sheet1] [0] [($count + 5)] = "代扣税";
		$salaryList [Sheet1] [0] [($count + 6)] = "个人扣款合计";
		$salaryList [Sheet1] [0] [($count + 7)] = "实发合计";
		$salaryList [Sheet1] [0] [($count + 8)] = "单位失业";
		$salaryList [Sheet1] [0] [($count + 9)] = "单位医疗";
		$salaryList [Sheet1] [0] [($count + 10)] = "单位养老";
		$salaryList [Sheet1] [0] [($count + 11)] = "单位工伤";
		$salaryList [Sheet1] [0] [($count + 12)] = "单位生育";
		$salaryList [Sheet1] [0] [($count + 13)] = "单位公积金";
		$salaryList [Sheet1] [0] [($count + 14)] = "单位合计";
		$salaryList [Sheet1] [0] [($count + 15)] = "劳务费";
		$salaryList [Sheet1] [0] [($count + 16)] = "残保金";
		$salaryList [Sheet1] [0] [($count + 17)] = "档案费";
		$salaryList [Sheet1] [0] [($count + 18)] = "交中企基业合计";
		if (! empty ( $freeTex )) {
			$salaryList [Sheet1] [0] [($count + 19)] = "免税项";
		}
		if (! empty ( $_POST ['shifajian'] )) {
			$salaryList [Sheet1] [0] [($count + 20)] = "实发合计减后项";
			$salaryList [Sheet1] [0] [($count + 21)] = "交中企基业减后项";
		}
		$jisuan_var = array ();
		$error = array ();
		$this->objDao = new EmployDao ();
		// 根据身份证号查询出员工身份类别
		for($i = 1; $i < count ( $salaryList [Sheet1] ); $i ++) {
			// $error[$i]["error"]="";
			// $jisuan_var[$i]['error']="";
			/*
			 * if(!is_numeric($salaryList[Sheet1][$i][$shenfenzheng])){ $error[$i]["error"]="身份证非数字类型！"; continue; }
			 */
			$employ = $this->objDao->getEmByEno ( $salaryList [Sheet1] [$i] [$shenfenzheng] );
			if ($employ) {
				$jisuan_var [$i] ['yinhangkahao'] = $employ ['bank_num'];
				$jisuan_var [$i] ['shenfenleibie'] = $employ ['e_type'];
				$jisuan_var [$i] ['shebaojishu'] = $employ ['shebaojishu'];
				$jisuan_var [$i] ['gongjijinjishu'] = $employ ['gongjijinjishu'];
				$jisuan_var [$i] ['laowufei'] = $employ ['laowufei'];
				$jisuan_var [$i] ['canbaojin'] = $employ ['canbaojin'];
				$jisuan_var [$i] ['danganfei'] = $employ ['danganfei'];
			} else {
				$error [$i] ["error"] = "{$salaryList[Sheet1][$i][$shenfenzheng]}:未查询到该员工身份类别！";
				continue;
			}
			$addValue = 0;
			$delValue = 0;
			
			foreach ( $addArray as $row ) {
				if (is_numeric ( $salaryList [Sheet1] [$i] [($row - 1)] )) {
					$addValue += $salaryList [Sheet1] [$i] [($row - 1)];
				} else {
					$error [$i] ["error"] = "第1$row列所加项非数字类型";
					continue;
				}
			}
			if (! empty ( $delArray )) {
				foreach ( $delArray as $row ) {
					if (is_numeric ( $salaryList [Sheet1] [$i] [($row - 1)] )) {
						$delValue += $salaryList [Sheet1] [$i] [($row - 1)];
					} else {
						$error [$i] ["error"] = "第2$row列所减项非数字类型";
						continue;
					}
				}
			}
			$jisuan_var [$i] ["addValue"] = $addValue;
			$jisuan_var [$i] ["delValue"] = $delValue;
			if (! empty ( $freeTex )) {
				$jisuan_var [$i] ['freeTex'] = $salaryList [Sheet1] [$i] [$freeTex];
			} else {
				$jisuan_var [$i] ['freeTex'] = 0;
			}
		}
		// var_dump($error);
		// exit;
		$sumclass = new sumSalary ();
		$sumclass->getSumSalary ( $jisuan_var );
		$sumYingfaheji = 0;
		$sumGerenshiye = 0;
		$sumGerenyiliao = 0;
		$sumGerenyanglao = 0;
		$sumGerengongjijin = 0;
		$sumDaikousui = 0;
		$sumKoukuanheji = 0;
		$sumShifaheji = 0;
		$sumDanweishiye = 0;
		$sumDanweiyiliao = 0;
		$sumDanweiyanglao = 0;
		$sumDanweigongshang = 0;
		$sumDanweishengyu = 0;
		$sumDanweigongjijin = 0;
		$sumDanweiheji = 0;
		$sumJiaozhongqiheji = 0;
		for($i = 1; $i < count ( $salaryList [Sheet1] ); $i ++) {
			/**
			 * $jisuan_var[$i]['yingfaheji']=0;
			 * $jisuan_var[$i]['gerenshiye']="错误";
			 * $jisuan_var[$i]['gerenyiliao']="错误";
			 * $jisuan_var[$i]['gerenyanglao']="错误";
			 * $jisuan_var[$i]['gerengongjijin']=0;
			 * $jisuan_var[$i]['daikousui']=0;
			 * $jisuan_var[$i]['koukuanheji']=0;
			 * $jisuan_var[$i]['shifaheji']=0;
			 * $jisuan_var[$i]['danweishiye']="错误";
			 * $jisuan_var[$i]['danweigongshang']="错误";
			 * $jisuan_var[$i]['danweishengyu']="错误";
			 * $jisuan_var[$i]['danweiyanglao']="错误";
			 * $jisuan_var[$i]['danweiyiliao']="错误";
			 * $jisuan_var[$i]['danweigongjijin']=0;
			 * $jisuan_var[$i]['danweiheji']="错误";
			 * $jisuan_var[$i]['jiaozhongqiheji']=0;
			 */
			// 增加的字段赋值
			/*
			 * $salaryList[Sheet1][0][($count_add+0)]=" 银行卡号"; $salaryList[Sheet1][0][($count_add+1)]="身份类别"; $salaryList[Sheet1][0][($count_add+2)]=" 社保基数"; $salaryList[Sheet1][0][($count_add+3)]="公积金基数";
			 */
			$salaryList [Sheet1] [$i] [($count_add + 0)] = $jisuan_var [$i] ['yinhangkahao'];
			$salaryList [Sheet1] [$i] [($count_add + 1)] = $jisuan_var [$i] ['shenfenleibie'];
			$salaryList [Sheet1] [$i] [($count_add + 2)] = $jisuan_var [$i] ['shebaojishu'];
			$salaryList [Sheet1] [$i] [($count_add + 3)] = $jisuan_var [$i] ['gongjijinjishu'];
			$salaryList [Sheet1] [$i] [($count + 0)] = sprintf ( "%01.2f", $jisuan_var [$i] ['yingfaheji'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 1)] = sprintf ( "%01.2f", $jisuan_var [$i] ['gerenshiye'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 2)] = sprintf ( "%01.2f", $jisuan_var [$i] ['gerenyiliao'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 3)] = sprintf ( "%01.2f", $jisuan_var [$i] ['gerenyanglao'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 4)] = $jisuan_var [$i] ['gerengongjijin'] + 0;
			$salaryList [Sheet1] [$i] [($count + 5)] = sprintf ( "%01.2f", $jisuan_var [$i] ['daikousui'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 6)] = sprintf ( "%01.2f", $jisuan_var [$i] ['koukuanheji'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 7)] = sprintf ( "%01.2f", $jisuan_var [$i] ['shifaheji'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 8)] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweishiye'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 9)] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweiyiliao'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 10)] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweiyanglao'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 11)] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweigongshang'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 12)] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweishengyu'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 13)] = $jisuan_var [$i] ['danweigongjijin'] + 0;
			$salaryList [Sheet1] [$i] [($count + 14)] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweiheji'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 15)] = sprintf ( "%01.2f", $jisuan_var [$i] ['laowufei'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 16)] = sprintf ( "%01.2f", $jisuan_var [$i] ['canbaojin'] ) + 0;
			$salaryList [Sheet1] [$i] [($count + 17)] = sprintf ( "%01.2f", $jisuan_var [$i] ['danganfei'] ) + 0;
			// $jisuan_var[$i]['laowufei']+$jisuan_var[$i]['canbaojin']+$jisuan_var[$i]['danganfei']
			$salaryList [Sheet1] [$i] [($count + 18)] = sprintf ( "%01.2f", $jisuan_var [$i] ['jiaozhongqiheji'] ) + 0;
			if (! empty ( $freeTex )) {
				$salaryList [Sheet1] [$i] [($count + 19)] = sprintf ( "%01.2f", $jisuan_var [$i] ['freeTex'] ) + 0;
			}
			if (! empty ( $_POST ['shifajian'] )) {
				$salaryList [Sheet1] [$i] [($count + 20)] = sprintf ( "%01.2f", ($jisuan_var [$i] ['shifaheji'] - $salaryList [Sheet1] [$i] [$shifajian]) ) + 0;
				$salaryList [Sheet1] [$i] [($count + 21)] = sprintf ( "%01.2f", ($jisuan_var [$i] ['jiaozhongqiheji'] - $salaryList [Sheet1] [$i] [$shifajian]) ) + 0;
			}
			// echo $salaryList[Sheet1][$i][$j]."|";
			// 计算列的合计
			$sumYingfaheji += $salaryList [Sheet1] [$i] [($count + 0)];
			$sumGerenshiye += $salaryList [Sheet1] [$i] [($count + 1)];
			$sumGerenyiliao += $salaryList [Sheet1] [$i] [($count + 2)];
			$sumGerenyanglao += $salaryList [Sheet1] [$i] [($count + 3)];
			$sumGerengongjijin += $salaryList [Sheet1] [$i] [($count + 4)];
			$sumDaikousui += $salaryList [Sheet1] [$i] [($count + 5)];
			$sumKoukuanheji += $salaryList [Sheet1] [$i] [($count + 6)];
			$sumShifaheji += $salaryList [Sheet1] [$i] [($count + 7)];
			$sumDanweishiye += $salaryList [Sheet1] [$i] [($count + 8)];
			$sumDanweiyiliao += $salaryList [Sheet1] [$i] [($count + 9)];
			$sumDanweiyanglao += $salaryList [Sheet1] [$i] [($count + 10)];
			$sumDanweigongshang += $salaryList [Sheet1] [$i] [($count + 11)];
			$sumDanweishengyu += $salaryList [Sheet1] [$i] [($count + 12)];
			$sumDanweigongjijin += $salaryList [Sheet1] [$i] [($count + 13)];
			$sumDanweiheji += $salaryList [Sheet1] [$i] [($count + 14)];
			$sumLaowufeiheji += $salaryList [Sheet1] [$i] [($count + 15)];
			$sumCanbaojinheji += $salaryList [Sheet1] [$i] [($count + 16)];
			$sumDanganfeiheji += $salaryList [Sheet1] [$i] [($count + 17)];
			$sumJiaozhongqiheji += $salaryList [Sheet1] [$i] [($count + 18)];
			// echo "<br>";
		}
		// 计算合计行
		
		$countLie = count ( $salaryList [Sheet1] ); // 代表一共多少行
		for($j = 0; $j < $count; $j ++) {
			if ($j == 0) {
				$salaryList [Sheet1] [$countLie] [$j] = "合计";
			} else {
				$salaryList [Sheet1] [$countLie] [$j] = " ";
			}
		}
		$salaryList [Sheet1] [$countLie] [($count + 0)] = $sumYingfaheji;
		$salaryList [Sheet1] [$countLie] [($count + 1)] = $sumGerenshiye;
		$salaryList [Sheet1] [$countLie] [($count + 2)] = $sumGerenyiliao;
		$salaryList [Sheet1] [$countLie] [($count + 3)] = $sumGerenyanglao;
		$salaryList [Sheet1] [$countLie] [($count + 4)] = $sumGerengongjijin;
		$salaryList [Sheet1] [$countLie] [($count + 5)] = $sumDaikousui;
		$salaryList [Sheet1] [$countLie] [($count + 6)] = $sumKoukuanheji;
		$salaryList [Sheet1] [$countLie] [($count + 7)] = $sumShifaheji;
		$salaryList [Sheet1] [$countLie] [($count + 8)] = $sumDanweishiye;
		$salaryList [Sheet1] [$countLie] [($count + 9)] = $sumDanweiyiliao;
		$salaryList [Sheet1] [$countLie] [($count + 10)] = $sumDanweiyanglao;
		$salaryList [Sheet1] [$countLie] [($count + 11)] = $sumDanweigongshang;
		$salaryList [Sheet1] [$countLie] [($count + 12)] = $sumDanweishengyu;
		$salaryList [Sheet1] [$countLie] [($count + 13)] = $sumDanweigongjijin;
		$salaryList [Sheet1] [$countLie] [($count + 14)] = $sumDanweiheji;
		$salaryList [Sheet1] [$countLie] [($count + 15)] = $sumLaowufeiheji;
		$salaryList [Sheet1] [$countLie] [($count + 16)] = $sumCanbaojinheji;
		$salaryList [Sheet1] [$countLie] [($count + 17)] = $sumDanganfeiheji;
		$salaryList [Sheet1] [$countLie] [($count + 18)] = $sumJiaozhongqiheji;
		// echo ">>>>>>>>>>>>>>>>>>>>>>><br>";
		// var_dump($jisuan_var);
		// var_dump($salaryList[Sheet1]);
		// exit;
		$comlist = $this->objDao->searhCompanyListByComany ();
		$this->objForm->setFormData ( "comlist", $comlist );
		$this->objForm->setFormData ( "jisanlist", $jisuan_var );
		$this->objForm->setFormData ( "errorlist", $error );
		$this->objForm->setFormData ( "excelList", $salaryList [Sheet1] );
		if (! empty ( $freeTex )) {
			$this->objForm->setFormData ( "freeTex", $freeTex );
		}
		if (! empty ( $_POST ['shifajian'] )) {
			$this->objForm->setFormData ( "shifajian", $shifajian );
		}
		if ($checkType) {
			$companyName = $_REQUEST ['companyName'];
			$companyId = $_REQUEST ['comId'];
			$salDate = $_REQUEST ['sDate'];
			echo $companyName . $salDate;
			$this->objForm->setFormData ( "checkType", $checkType );
			$this->objForm->setFormData ( "companyName", $companyName );
			$this->objForm->setFormData ( "companyId", $companyId );
			$this->objForm->setFormData ( "salDate", $salDate );
		}
	}
	
	// FIXME 计算年终奖
	function sumNianSalary() {
		$this->mode = "sumlist";
		$shenfenzheng = ($_POST ['shenfenzheng_nian'] - 1);
		$salaryTime = $_POST ['salaryTime_nian'];
		$checkType = $_REQUEST ['checkType'];
		// $yingfa=($_POST['yingfa']-1);
		// 判断是否做过一次工资
		$isFirst = $_REQUEST ['isFirst'];
		$nian = ($_POST ['nian'] - 1);
		$addArray = explode ( "+", $addArray );
		session_start ();
		$salaryList = $_SESSION ['salarylist'];
		$count = count ( $salaryList [Sheet1] [0] );
		$salaryList [Sheet1] [0] [($count + 0)] = "当月应发合计";
		$salaryList [Sheet1] [0] [($count + 1)] = "当月实发合计";
		$salaryList [Sheet1] [0] [($count + 2)] = "年终奖代扣税";
		$salaryList [Sheet1] [0] [($count + 3)] = "实发进卡";
		$salaryList [Sheet1] [0] [($count + 4)] = "缴纳中企基业合计";
		$sumshifaheji=0;
		$sumNianzhongjiang = 0;
		$sumYingfaheji = 0;
		$sumshifajinka = 0;
		$sumJiaozhongqiheji = 0;
		$error = array ();
		$this->objDao = new SalaryDao ();
		// 根据年终奖月份和身份证号查询该员工的当月应发合计项
		for($i = 1; $i < count ( $salaryList [Sheet1] ); $i ++) {
			$jisuan_var = array ();
			$salaryList [Sheet1] [$i] [$shenfenzheng] = trim ( $salaryList [Sheet1] [$i] [$shenfenzheng] );
			$employ = $this->objDao->searchSalBy_EnoAndSalTime ( $salaryTime, trim ( $salaryList [Sheet1] [$i] [$shenfenzheng] ) );
			//echo print_r($employ);
			if (! $isFirst) { // 未做一次工资
				if (! $employ) { // 员工不为空
					$employPO = $this->objDao->getEmByEno ( $salaryList [Sheet1] [$i] [$shenfenzheng] );
					$companyPO = $this->objDao->searchCompanyByName ( $employPO ['e_company'] );
					$salaryTimePO = $this->objDao->searchSalTimeByComIdAndSalTime ( $companyPO ['id'], $salaryTime );
					if (! $salaryTimePO) {
						$salaryTimePPO = array ();
						$salaryTimePPO ['companyId'] = $companyPO ['id'];
						$salaryTimePPO ['salaryTime'] = $salaryTime;
						$salaryTimePPO ['op_salaryTime'] = date ( "Y-m-d" );
						$this->objDao->saveSalaryTime ( $salaryTimePPO );
						$saveLastId = $this->objDao->g_db_last_insert_id ();
						$salaryTimePO ['id'] = $saveLastId;
					}
					$salayList = array ();
					$salayList ['per_yingfaheji'] = 0;
					$salayList ['per_shiye'] = 0;
					$salayList ['per_yiliao'] = 0;
					$salayList ['per_yanglao'] = 0;
					$salayList ['per_gongjijin'] = 0;
					$salayList ['per_daikoushui'] = 0;
					$salayList ['per_koukuangheji'] = 0;
					$salayList ['per_shifaheji'] = 0;
					$salayList ['com_shiye'] = 0;
					$salayList ['com_yiliao'] = 0;
					$salayList ['com_yanglao'] = 0;
					$salayList ['com_gongshang'] = 0;
					$salayList ['com_shengyu'] = 0;
					$salayList ['com_gongjijin'] = 0;
					$salayList ['com_heji'] = 0;
					$salayList ['laowufei'] = 0;
					$salayList ['canbaojin'] = 0;
					$salayList ['danganfei'] = 0;
					$salayList ['paysum_zhongqi'] = 0;
					// $salary['employid']},{$salary['salaryTimeId']},{$salary['salaryTimeId']}
					$salayList ['employid'] = $salaryList [Sheet1] [$i] [$shenfenzheng];
					$salayList ['salaryTimeId'] = $salaryTimePO ['id'];
					$result = $this->objDao->saveSalary ( $salayList );
					$employ = $this->objDao->searchSalBy_EnoAndSalTime ( $salaryTime, $salaryList [Sheet1] [$i] [$shenfenzheng] );
				}
			}
			if ($employ) {
				$sal = $this->objDao->getErSalaryByDateNo ( $salaryTime, $salaryList [Sheet1] [$i] [$shenfenzheng] );
				$jisuan_var ['yingfaheji'] = $employ ['per_yingfaheji'];
				$jisuan_var['shifaheji']=$employ['per_shifaheji'];
				if ($sal) {
					$jisuan_var ['yingfaheji'] += $sal ['ercigongziheji'];
				}
				$jisuan_var ['nianzhongjiang'] = $salaryList [Sheet1] [$i] [$nian];
				$sumclass = new sumSalary ();
				$sumclass->sumNianSal ( $jisuan_var ); // 计算年终奖
				$salaryList [Sheet1] [$i] [($count + 0)] = sprintf ( "%01.2f", $jisuan_var ['yingfaheji'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 1)] = sprintf ( "%01.2f", $jisuan_var ['shifaheji'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 2)] = sprintf ( "%01.2f", $jisuan_var ['niandaikoushui'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 3)] = sprintf ( "%01.2f", $jisuan_var ['shifajinka'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 4)] = sprintf ( "%01.2f", $jisuan_var ['jiaozhongqi'] ) + 0;
			} else {
				$error [$i] ["error"] = "{$salaryList[Sheet1][$i][$shenfenzheng]}:未查询到该员工身份类别！";
				continue;
			}
			$sumNianzhongjiang += $salaryList [Sheet1] [$i] [$nian];
			$sumYingfaheji += $salaryList [Sheet1] [$i] [($count + 0)];
			$sumshifaheji	+=$salaryList [Sheet1] [$i] [($count + 1)];
			$sumNianzhongjiangdai += $salaryList [Sheet1] [$i] [($count + 2)];
			$sumshifajinka += $salaryList [Sheet1] [$i] [($count + 3)];
			$sumJiaozhongqiheji += $salaryList [Sheet1] [$i] [($count + 4)];
		}
		// 计算合计行
		
		$countLie = count ( $salaryList [Sheet1] ); // 代表一共多少行
		for($j = 0; $j < $count; $j ++) {
			if ($j == 0) {
				$salaryList [Sheet1] [$countLie] [$j] = "合计";
			} else {
				$salaryList [Sheet1] [$countLie] [$j] = " ";
			}
		}
		$salaryList [Sheet1] [$countLie] [$nian] = $sumNianzhongjiang;
		$salaryList [Sheet1] [$countLie] [($count + 0)] = $sumYingfaheji;
		$salaryList [Sheet1] [$countLie] [($count + 1)] = $sumshifaheji;
		$salaryList [Sheet1] [$countLie] [($count + 2)] = $sumNianzhongjiangdai;
		$salaryList [Sheet1] [$countLie] [($count + 3)] = $sumshifajinka;
		$salaryList [Sheet1] [$countLie] [($count + 4)] = $sumJiaozhongqiheji;
		// echo ">>>>>>>>>>>>>>>>>>>>>>><br>";
		// var_dump($jisuan_var);
		// var_dump($salaryList[Sheet1]);
		// exit;
		$this->objDao = new EmployDao ();
		$comlist = $this->objDao->searhCompanyListByComany ();
		$this->objForm->setFormData ( "comlist", $comlist );
		$this->objForm->setFormData ( "errorlist", $error );
		$this->objForm->setFormData ( "excelList", $salaryList [Sheet1] );
		if ($checkType) {
			$companyName = $_REQUEST ['companyName'];
			$companyId = $_REQUEST ['comId'];
			$salDate = $_REQUEST ['sDate'];
			echo $companyName . $salDate;
			$this->objForm->setFormData ( "checkType", $checkType );
			$this->objForm->setFormData ( "companyName", $companyName );
			$this->objForm->setFormData ( "companyId", $companyId );
			$this->objForm->setFormData ( "salDate", $salDate );
		}
	}
	
	function sumErSalary() {
		$this->mode = "sumlist";
		$shenfenzheng = ($_POST ['shenfenzheng_er'] - 1);
		$salaryTime = $_POST ['salaryTime_er'];
		// $yingfa=($_POST['yingfa']-1);
		$checkType = $_REQUEST ['salType'];
		$add = $_POST ['add'];
		$addArray = explode ( "+", $add );
		session_start ();
		$salaryList = $_SESSION ['salarylist'];
		$count = count ( $salaryList [Sheet1] [0] );
		$salaryList [Sheet1] [0] [($count + 0)] = "二次工资合计";
		$salaryList [Sheet1] [0] [($count + 1)] = "当月发放工资";
		$salaryList [Sheet1] [0] [($count + 2)] = "实际应发合计";
		$salaryList [Sheet1] [0] [($count + 3)] = "失业";
		$salaryList [Sheet1] [0] [($count + 4)] = "医疗";
		$salaryList [Sheet1] [0] [($count + 5)] = "养老";
		$salaryList [Sheet1] [0] [($count + 6)] = "公积金";
		$salaryList [Sheet1] [0] [($count + 7)] = "应扣税";
		$salaryList [Sheet1] [0] [($count + 8)] = "已扣税";
		$salaryList [Sheet1] [0] [($count + 9)] = "补扣税";
		$salaryList [Sheet1] [0] [($count + 10)] = "双薪进卡";
		$salaryList [Sheet1] [0] [($count + 11)] = "缴中企基业合计";
		$sumergongziheji = 0;
		$sumdangyuefafangheji = 0;
		$sumshijiyingfaheji = 0;
		$sumshiye = 0;
		$sumyiliao = 0;
		$sumyanglao = 0;
		$sumgongjijin = 0;
		$sumyingkoushui = 0;
		$sumyikoushui = 0;
		$sumbukoushui = 0;
		$sumjinka = 0;
		$sumjiaozhongqi = 0;
		$error = array ();
		$this->objDao = new SalaryDao ();
		// 根据年终奖月份和身份证号查询该员工的当月应发合计项
		for($i = 1; $i < count ( $salaryList [Sheet1] ); $i ++) {
			$jisuan_var = array ();
			$salaryList [Sheet1] [$i] [$shenfenzheng] = trim ( $salaryList [Sheet1] [$i] [$shenfenzheng] );
			$employ = $this->objDao->searchSalBy_EnoAndSalTime ( $salaryTime, $salaryList [Sheet1] [$i] [$shenfenzheng] );
			$erSalaryTimePO = $this->objDao->searchErSalHejiByPersonIdAndSalTimeErAndComId ( $employ ['companyId'], $salaryTime, $salaryList [Sheet1] [$i] [$shenfenzheng] );
			/*
			 * $erSalaryTimePO //select * from OA_salarytime_other,OA_er_salary where OA_salarytime_other.salarytime='2012-12-01' and OA_salarytime_other.companyId=74 and OA_salarytime_other.id=OA_er_salary.salarytimeId group by OA_er_salary.employId; $erciSalaryPO=$this->objDao->searchErSalaryListBy_SalaryTimeId();
			 */
			if ($employ) {
				$addValue = 0;
				foreach ( $addArray as $row ) {
					if (is_numeric ( $salaryList [Sheet1] [$i] [($row - 1)] )) {
						$addValue += $salaryList [Sheet1] [$i] [($row - 1)];
					} else {
						$error [$i] ["error"] = "第$row列所加项非数字类型";
						continue;
					}
				}
				if (! $erSalaryTimePO) {
					$erSalaryTimePO ['erSum'] = 0;
				}
				$jisuan_var ['ercigongziheji'] = $addValue + $erSalaryTimePO ['erSum'];
				$jisuan_var ['yingfaheji'] = $employ ['per_yingfaheji'];
				$jisuan_var ['shijiyingfaheji'] = $jisuan_var ['ercigongziheji'] + $jisuan_var ['yingfaheji'];
				$jisuan_var ['shiye'] = $employ ['per_shiye'];
				$jisuan_var ['yiliao'] = $employ ['per_yiliao'];
				$jisuan_var ['yanglao'] = $employ ['per_yanglao'];
				$jisuan_var ['gongjijin'] = $employ ['per_gongjijin'];
				$jisuan_var ['yikoushui'] = $employ ['per_daikoushui'];
				$sumclass = new sumSalary ();
				$sumclass->sumErSal ( $jisuan_var );
				/**
				 * $jisuan_var['yingkoushui']=$values;
				 * $jisuan_var['bukoushui']=$jisuan_var['yingkoushui']-$jisuan_var['yikoushui'];
				 * $jisuan_var['shuangxinjinka']=$jisuan_var['ercigongziheji']-$jisuan_var['bukoushui'];
				 * $jisuan_var['jiaozhongqi']=$jisuan_var['ercigongziheji'];
				 * //失业	医疗	养老	公积金	应扣税	已扣税	补扣税	2010年1次双薪进卡	缴中企基业合计
				 */
				$salaryList [Sheet1] [$i] [($count + 0)] = sprintf ( "%01.2f", $jisuan_var ['ercigongziheji'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 1)] = sprintf ( "%01.2f", $jisuan_var ['yingfaheji'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 2)] = sprintf ( "%01.2f", $jisuan_var ['shijiyingfaheji'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 3)] = sprintf ( "%01.2f", $jisuan_var ['shiye'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 4)] = sprintf ( "%01.2f", $jisuan_var ['yiliao'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 5)] = sprintf ( "%01.2f", $jisuan_var ['yanglao'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 6)] = sprintf ( "%01.2f", $jisuan_var ['gongjijin'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 7)] = sprintf ( "%01.2f", $jisuan_var ['yingkoushui'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 8)] = sprintf ( "%01.2f", $jisuan_var ['yikoushui'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 9)] = sprintf ( "%01.2f", $jisuan_var ['bukoushui'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 10)] = sprintf ( "%01.2f", $jisuan_var ['shuangxinjinka'] ) + 0;
				$salaryList [Sheet1] [$i] [($count + 11)] = sprintf ( "%01.2f", $jisuan_var ['jiaozhongqi'] ) + 0;
			} else {
				$error [$i] ["error"] = "{$salaryList[Sheet1][$i][$shenfenzheng]}:未查询到该员工身份类别！";
				continue;
			}
			$sumergongziheji += $salaryList [Sheet1] [$i] [($count + 0)];
			$sumdangyuefafangheji += $salaryList [Sheet1] [$i] [($count + 1)];
			$sumshijiyingfaheji += $salaryList [Sheet1] [$i] [($count + 2)];
			$sumshiye += $salaryList [Sheet1] [$i] [($count + 3)];
			$sumyiliao += $salaryList [Sheet1] [$i] [($count + 4)];
			$sumyanglao += $salaryList [Sheet1] [$i] [($count + 5)];
			$sumgongjijin += $salaryList [Sheet1] [$i] [($count + 6)];
			$sumyingkoushui += $salaryList [Sheet1] [$i] [($count + 7)];
			$sumyikoushui += $salaryList [Sheet1] [$i] [($count + 8)];
			$sumbukoushui += $salaryList [Sheet1] [$i] [($count + 9)];
			$sumjinka += $salaryList [Sheet1] [$i] [($count + 10)];
			$sumjiaozhongqi += $salaryList [Sheet1] [$i] [($count + 11)];
		}
		// 计算合计行
		
		$countLie = count ( $salaryList [Sheet1] ); // 代表一共多少行
		for($j = 0; $j < $count; $j ++) {
			if ($j == 0) {
				$salaryList [Sheet1] [$countLie] [$j] = "合计";
			} else {
				$salaryList [Sheet1] [$countLie] [$j] = " ";
			}
		}
		$salaryList [Sheet1] [$countLie] [($count + 0)] = $sumergongziheji;
		$salaryList [Sheet1] [$countLie] [($count + 1)] = $sumdangyuefafangheji;
		$salaryList [Sheet1] [$countLie] [($count + 2)] = $sumshijiyingfaheji;
		$salaryList [Sheet1] [$countLie] [($count + 3)] = $sumshiye;
		$salaryList [Sheet1] [$countLie] [($count + 4)] = $sumyiliao;
		$salaryList [Sheet1] [$countLie] [($count + 5)] = $sumyanglao;
		$salaryList [Sheet1] [$countLie] [($count + 6)] = $sumgongjijin;
		$salaryList [Sheet1] [$countLie] [($count + 7)] = $sumyingkoushui;
		$salaryList [Sheet1] [$countLie] [($count + 8)] = $sumyikoushui;
		$salaryList [Sheet1] [$countLie] [($count + 9)] = $sumbukoushui;
		$salaryList [Sheet1] [$countLie] [($count + 10)] = $sumjinka;
		$salaryList [Sheet1] [$countLie] [($count + 11)] = $sumjiaozhongqi;
		// echo ">>>>>>>>>>>>>>>>>>>>>>><br>";
		// var_dump($jisuan_var);
		// var_dump($salaryList[Sheet1]);
		// exit;
		$this->objDao = new EmployDao ();
		$comlist = $this->objDao->searhCompanyListByComany ();
		$this->objForm->setFormData ( "comlist", $comlist );
		$this->objForm->setFormData ( "errorlist", $error );
		$this->objForm->setFormData ( "excelList", $salaryList [Sheet1] );
		if ($checkType) {
			$companyName = $_REQUEST ['companyName'];
			$companyId = $_REQUEST ['comId'];
			$salDate = $_REQUEST ['sDate'];
			echo $companyName . $salDate;
			$this->objForm->setFormData ( "checkType", $checkType );
			$this->objForm->setFormData ( "companyName", $companyName );
			$this->objForm->setFormData ( "companyId", $companyId );
			$this->objForm->setFormData ( "salDate", $salDate );
		}
	}
	function importSalary() {
		require 'tools/php-excel.class.php';
		ob_end_flush ();
		$name = $_POST ['name'];
		session_start ();
		$salaryList = $_SESSION ['excelList'];
		
		// create a simple 2-dimensional array
		$data = array (
				1 => array (
						'Name',
						'Surname' 
				),
				array (
						'Schwarz',
						'Oliver' 
				),
				array (
						'Test',
						'Peter' 
				) 
		);
		
		// generate file (constructor parameters are optional)
		$xls = new Excel_XML ( 'UTF-8', false, 'My Test Sheet' );
		$xls->addArray ( $salaryList );
		$xls->generateXML ( 'my-test' );
		/*
		 * if($err!=0){ $this->objForm->setFormData("error",$err); } $this->objForm->setFormData("salarylist",$return);
		 */
		// $this->mode="salaryList";
		// $this->mode="sumlist";
	}
	function fileDel() {
		$this->mode = "toUpload";
		$fname = $_GET ['fname'];
		$op = new fileoperate ();
		$mess = $op->del_file ( "upload/", $fname );
		$files = $op->list_filename ( "upload/", 1 );
		$this->objForm->setFormData ( "files", $files );
		$this->objForm->setFormData ( "error", $mess );
	}
	function rename() {
		$this->mode = "toUpload";
		$nfname = $_POST ['nfname'];
		$ofname = $_POST ['ofname'];
		$op = new fileoperate ();
		$mess = $op->rename_file ( "upload/" . $ofname, $nfname . ".xls" );
		$files = $op->list_filename ( "upload/", 1 );
		$this->objForm->setFormData ( "files", $files );
		$this->objForm->setFormData ( "error", $mess );
	}
	function toSalaryUpdate() {
		$this->mode = "toSalaryUpdate";
		if (! empty ( $_REQUEST ['timeId'] )) {
			$empno = $_REQUEST ['timeId'];
			$salTime = $_REQUEST ['time'];
			$emp = array ();
			$this->objDao = new SalaryDao ();
			$emp ['eno'] = $empno;
			$emp ['sTime'] = $salTime;
			$result = $this->objDao->searchSalaryListBy_SalaryEmpId ( $emp );
			$salaryListArray = array ();
			$i = 0;
			global $salaryTable;
			$movKeyArr = array ();
			$z = 0;
			while ( $row = mysql_fetch_array ( $result ) ) {
				$salaryMovementList = $this->objDao->searchSalaryMovementBy_SalaryId ( $row ['sId'] );
				$j = 0;
				while ( $row_move = mysql_fetch_array ( $salaryMovementList ) ) {
					// $salaryListArray[$i][$row_move['fieldName']]=$row_move['fieldValue'];
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
				// $salaryListArray[$i]['guding_salary']=$row;
				$salaryListArray ['data'] [] = $rowData;
				$i ++;
			}
			$countData = count ( $salaryListArray ['data'] );
			// $this->objForm->setFormData("salList",$salaryListArray);
			echo json_encode ( $salaryListArray );
			exit ();
		}
	}
	function updateSalary($empno = null, $salTime = null) {
		$this->mode = "salaryUpdate";
		if ($empno == null) {
			$empno = $_REQUEST ['eid'];
			$salTime = $_REQUEST ['stId'];
		}
		echo $empno;
		$emp = array ();
		$this->objDao = new SalaryDao ();
		$emp ['eno'] = $empno;
		$emp ['stId'] = $salTime;
		$result = $this->objDao->searchSalaryListBy_Id ( $emp );
		echo $result . "  this is result";
		$this->objForm->setFormData ( "salList", $result );
	}
	function salUpdate() {
		$salary = array ();
		$salary ['eid'] = $_REQUEST ['eid'];
		$salary ['sId'] = $_REQUEST ['sId'];
		$salary ['stId'] = $_REQUEST ['stId'];
		$salary ['per_yingfaheji'] = $_REQUEST ['per_yingfaheji'];
		$salary ['per_shiye'] = $_REQUEST ['per_shiye'];
		$salary ['per_yanglao'] = $_REQUEST ['per_yanglao'];
		$salary ['per_yiliao'] = $_REQUEST ['per_yiliao'];
		$salary ['per_gongjijin'] = $_REQUEST ['per_gongjijin'];
		$salary ['per_daikoushui'] = $_REQUEST ['per_daikoushui'];
		// $salary['per_koukuangheji']=$_REQUEST['per_koukuangheji'];
		$salary ['per_koukuangheji'] = ($salary ['per_shiye'] + $salary ['per_yiliao'] + $salary ['per_yanglao'] + $salary ['per_gongjijin'] + $salary ['per_daikoushui']);
		// $salary['per_shifaheji']=$_REQUEST['per_shifaheji'];
		$salary ['per_shifaheji'] = $salary ['per_yingfaheji'] - $salary ['per_koukuangheji'];
		$salary ['com_shiye'] = $_REQUEST ['com_shiye'];
		$salary ['com_yiliao'] = $_REQUEST ['com_yiliao'];
		$salary ['com_yanglao'] = $_REQUEST ['com_yanglao'];
		$salary ['com_gongshang'] = $_REQUEST ['com_gongshang'];
		$salary ['com_shengyu'] = $_REQUEST ['com_shengyu'];
		$salary ['com_gongjijin'] = $_REQUEST ['com_gongjijin'];
		$salary ['com_heji'] = $_REQUEST ['com_heji'];
		$salary ['laowufei'] = $_REQUEST ['laowufei'];
		$salary ['canbaojin'] = $_REQUEST ['canbaojin'];
		$salary ['danganfei'] = $_REQUEST ['danganfei'];
		$salary ['paysum_zhongqi'] = $salary ['per_yingfaheji'] + $salary ['com_shiye'] + $salary ['com_gongshang'] + $salary ['com_shengyu'] + $salary ['com_yanglao'] + $salary ['com_gongjijin'] + $salary ['com_yiliao'] + $salary ['laowufei'] + $salary ['canbaojin'] + $salary ['danganfei'];
		$salary ['memo'] = $_REQUEST ['memo'];
		$exmsg = new EC (); // 设置错误信息类
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		$result = $this->objDao->updateSalBy_sId ( $salary );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "update   salary By sId  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "修改个人工资失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->updateTotalBySalaryTimeId ( $salary );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "update   SUM salary By sId  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "修改工资合计项失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = $salary ['sId'];
		$opLog ['Subject'] = OP_LOG_UPDATE_PER_SALARY;
		$opLog ['memo'] = $salary ['memo'];
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
		$this->updateSalary ( $salary ['eid'], $salary ['stId'] );
	}
	function salDuiBi() {
		$this->mode = "salDuiBi";
		$fname = $_GET ['fname'];
		$err = Read_Excel_File ( "upload/" . $fname, $return );
		if ($err != 0) {
			$this->objForm->setFormData ( "error", $err );
		}
		$this->objForm->setFormData ( "salarylist", $return );
	}
	function perZiliaoDuibi() {
		$this->mode = "duibiError";
		$shenfenzheng = ($_POST ['shenfenzheng'] - 1);
		$name = ($_POST ['name'] - 1);
		$com = ($_POST ['com'] - 1);
		$eno = ($_POST ['eno'] - 1);
		$bank = ($_POST ['bank'] - 1);
		$etype = ($_POST ['etype'] - 1);
		$shebao = ($_POST ['shebao'] - 1);
		$gongjijin = ($_POST ['gongjijin'] - 1);
		$canbaofei = ($_POST ['canbaofei'] - 1);
		$laowufei = ($_POST ['laowufei'] - 1);
		$danganfei = ($_POST ['danganfei'] - 1);
		session_start ();
		$salaryList = $_SESSION ['salarylist'];

		$error = array ();
		$this->objDao = new EmployDao ();
		// 根据身份证号查询出员工身份类别
		for($i = 1; $i < count ( $salaryList [Sheet1] ); $i ++) {
			
			$employ = $this->objDao->getEmByEno ( $salaryList [Sheet1] [$i] [$shenfenzheng] );
			if ($employ) {
				if ($com != '' && $com != - 1) {
					if ($salaryList [Sheet1] [$i] [$com] != $employ ['e_company']) {
//						echo $salaryList [Sheet1] [$i] [$com] . '|' . $employ ['e_company'];
						$error [$i] ["error_com"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:公司名称不一致！系统：{$employ['e_company']},导入文件：{$salaryList[Sheet1][$i][$com]}";
					}
				}
				if ($name != '' && $name != - 1) {
					if ($salaryList [Sheet1] [$i] [$name] != $employ ['e_name']) {
//						echo $salaryList [Sheet1] [$i] [$com] . '|' . $employ ['e_company'];
						$error [$i] ["error_ename"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:姓名不一致！系统：{$employ['e_name']},导入文件：{$salaryList[Sheet1][$i][$name]}";
					}
				}
				if ($eno != '' && $eno != - 1) {
					if ($salaryList [Sheet1] [$i] [$eno] != $employ ['bank_num']) {
						$error [$i] ["error_banknum"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:银行卡号不一致！系统：{$employ['bank_num']},导入文件：{$salaryList[Sheet1][$i][$eno]}";
					}
				}
				if ($bank != '' && $bank != - 1) {
					if ($salaryList [Sheet1] [$i] [$bank] != $employ ['bank_name']) {
						$error [$i] ["error_bankname"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:开户行不一致！系统：{$employ['bank_name']},导入文件：{$salaryList[Sheet1][$i][$bank]}";
					}
				}
				if ($etype != '' && $etype != - 1) {
					if ($salaryList [Sheet1] [$i] [$etype] != $employ ['e_type']) {
						$error [$i] ["error_etype"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:身份类别不一致！系统：{$employ['e_type']},导入文件：{$salaryList[Sheet1][$i][$etype]}";
					}
				}
				if ($shebao != '' && $shebao != - 1) {
					if ($salaryList [Sheet1] [$i] [$shebao] != $employ ['shebaojishu']) {
						$error [$i] ["error_shebao"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:社保基数不一致！系统：{$employ['shebaojishu']},导入文件：{$salaryList[Sheet1][$i][$shebao]}";
					}
				}
				if ($gongjijin != '' && $gongjijin != - 1) {
					if ($salaryList [Sheet1] [$i] [$gongjijin] != $employ ['gongjijinjishu']) {
						$error [$i] ["error_gongjijinjishu"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:公积金基数不一致！系统：{$employ['gongjijinjishu']},导入文件：{$salaryList[Sheet1][$i][$gongjijin]}";
					}
				}
				if ($canbaofei != '' && $canbaofei != - 1) {
					if ($salaryList [Sheet1] [$i] [$canbaofei] != $employ ['canbaojin']) {
						$error [$i] ["error_canbaojin"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:残保金不一致！系统：{$employ['canbaojin']},导入文件：{$salaryList[Sheet1][$i][$canbaofei]}";
					}
				}
				if ($laowufei != '' && $laowufei != - 1) {
					if ($salaryList [Sheet1] [$i] [$laowufei] != $employ ['laowufei']) {
						$error [$i] ["error_laowufei"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:劳务费不一致！系统：{$employ['laowufei']},导入文件：{$salaryList[Sheet1][$i][$laowufei]}";
					}
				}
				if ($danganfei != '' && $danganfei != - 1) {
					if ($salaryList [Sheet1] [$i] [$danganfei] != $employ ['danganfei']) {
						$error [$i] ["error_danganfei"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:档案费不一致！系统：{$employ['danganfei']},导入文件：{$salaryList[Sheet1][$i][$danganfei]}";
					}
				}
			} else {
                $error [$i] ["error_shenfen"]  ="<br>";
				$error [$i] ["error_shenfen"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:未查询到该员工身份类别！";
                $employByName = $this->objDao->getEmByEname( $salaryList[Sheet1][$i][$name]);
                    $error [$i] ["error_shenfen"].="<br>";
                while ($row=mysql_fetch_array($employByName) ){
                    $error [$i] ["error_shenfen"].="根据姓名查找信息如下：<br>";
                    $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 姓名：".$row["e_name"]."</span>";
                    $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 身份证号：".$row["e_num"]."</span>";
                    $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 所在公司：".$row["e_company"]."</span>";
                    $comid = $this->objDao->searchCompanyidByName($row["e_company"]);
                    while($companyId=mysql_fetch_array($comid)){
                        $adminlist = $this->objDao->getAdminBycomId($companyId["id"]);
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 管理者姓名：</span>";
                        while($admin=mysql_fetch_array($adminlist)){
                            $adminName = $this->objDao->getAdminById($admin["adminId"]);
                            if ($adminName) {
                                $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> ".$adminName["name"]."</span>";
                            }
                        }
                    }
                }
                $error [$i] ["error_shenfen"].="<br>";
				continue;
			}
		}
		if (count ( $error ) == 0) {
			$error [0] ["succ"] = "<font color=green>对比没有错误</font>";
		}
		$this->objForm->setFormData ( "errorlist", $error );
		$this->objForm->setFormData ( "excelList", $salaryList [Sheet1] );
	}
	function salPerDuibi() {
		set_time_limit ( 0 );
		$this->mode = "duibiError";
		$shenfenzheng = ($_POST ['shenfenzheng_com'] - 1);
        $empname = ($_POST ['name_com'] - 1);
		$salTime = $_POST ['salTime_com'];
		$pershiye = ($_POST ['pershiye_com'] - 1);
		$peryiliao = ($_POST ['peryiliao_com'] - 1);
		$peryanglao = ($_POST ['peryanglao_com'] - 1);
		$pergongjijin = ($_POST ['pergongjijin_com'] - 1);
		$comshiye = ($_POST ['comshiye_com'] - 1);
		$comyiliao = ($_POST ['comyiliao_com'] - 1);
		$comyanglao = ($_POST ['comyanglao_com'] - 1);
		$comgongshang = ($_POST ['comgongshang_com'] - 1);
		$comshengyu = ($_POST ['comshengyu_com'] - 1);
		session_start ();
		$salaryList = $_SESSION ['salarylist'];
		$jisuan_var = array ();
		$error = array ();
		$this->objDao = new SalaryDao ();
		// 根据身份证号查询出员工身份类别
		for($i = 1; $i < count ( $salaryList [Sheet1] ); $i ++) {
			$emp = array ();
			$emp ['eno'] = $salaryList [Sheet1] [$i] [$shenfenzheng];
            $emp ['e_name'] = $salaryList [Sheet1] [$i] [$empname];
			$emp ['sTime'] = $salTime;
			$employ = mysql_fetch_array ( $this->objDao->searchSalaryListBy_SalaryEmpId ( $emp ) );
			if ($employ) {
				if ($pershiye != '' && $pershiye != - 1) {
					if ($salaryList [Sheet1] [$i] [$pershiye] != $employ ['per_shiye']) {
						$error [$i] ["error_com"] = "<td>{$employ['e_name']}</td><td>{$employ['e_company']}</td><td>{$salaryList[Sheet1][$i][$shenfenzheng]}</td><td>:员工个人失业不一致！系统：{$employ['per_shiye']}导入文件：{$salaryList[Sheet1][$i][$pershiye]}</td>";
					}
				}
				if ($peryiliao != '' && $peryiliao != - 1) {
					if ($salaryList [Sheet1] [$i] [$peryiliao] != $employ ['per_yiliao']) {
						$error [$i] ["error_ename"] = "<td>{$employ['e_name']}</td><td>{$employ['e_company']}</td><td>{$salaryList[Sheet1][$i][$shenfenzheng]}</td><td>:员工个人医疗不一致！系统：{$employ['per_yiliao']},导入文件：{$salaryList[Sheet1][$i][$peryiliao]}</td>";
					}
				}
				if ($peryanglao != '' && $peryanglao != - 1) {
					if ($salaryList [Sheet1] [$i] [$peryanglao] != $employ ['per_yanglao']) {
						$error [$i] ["error_banknum"] = "<td>{$employ['e_name']}</td><td>{$employ['e_company']}</td><td>{$salaryList[Sheet1][$i][$shenfenzheng]}</font></td><td>:员工个人养老不一致！系统：{$employ['per_yanglao']},导入文件：{$salaryList[Sheet1][$i][$peryanglao]}</td>";
					}
				}
				if ($pergongjijin != '' && $pergongjijin != - 1) {
					if ($salaryList [Sheet1] [$i] [$pergongjijin] != $employ ['per_gongjijin']) {
						$error [$i] ["error_bankname"] = "<td>{$employ['e_name']}</td><td>{$employ['e_company']}</td><td>{$salaryList[Sheet1][$i][$shenfenzheng]}</font></td><td>:员工个人公积金不一致！系统：{$employ['per_gongjijin']},导入文件：{$salaryList[Sheet1][$i][$pergongjijin]}</td>";
					}
				}
				if ($comshiye != '' && $comshiye != - 1) {
					if ($salaryList [Sheet1] [$i] [$comshiye] != $employ ['com_shiye']) {
						$error [$i] ["error_etype"] = "<td>{$employ['e_name']}</td><td>{$employ['e_company']}</td><td>{$salaryList[Sheet1][$i][$shenfenzheng]}</font></td><td>:单位失业不一致！系统：{$employ['com_shiye']},导入文件：{$salaryList[Sheet1][$i][$comshiye]}</td>";
					}
				}
				if ($comyiliao != '' && $comyiliao != - 1) {
					if ($salaryList [Sheet1] [$i] [$comyiliao] != $employ ['com_yiliao']) {
						$error [$i] ["error_shebao"] = "<td>{$employ['e_name']}</td><td>{$employ['e_company']}</td><td>{$salaryList[Sheet1][$i][$shenfenzheng]}</font></td><td>:单位医疗不一致！系统：{$employ['com_yiliao']},导入文件：{$salaryList[Sheet1][$i][$comyiliao]}</td>";
					}
				}
				if ($comyanglao != '' && $comyanglao != - 1) {
					if ($salaryList [Sheet1] [$i] [$comyanglao] != $employ ['com_yanglao']) {
						$error [$i] ["error_gongjijinjishu"] = "<td>{$employ['e_name']}</td><td>{$employ['e_company']}</td><td>{$salaryList[Sheet1][$i][$shenfenzheng]}</font></td><td>:单位养老不一致！系统：{$employ['com_yanglao']},导入文件：{$salaryList[Sheet1][$i][$comyanglao]}</td>";
					}
				}
				if ($comgongshang != '' && $comgongshang != - 1) {
					if ($salaryList [Sheet1] [$i] [$comgongshang] != $employ ['com_gongshang']) {
						$error [$i] ["error_canbaojin"] = "<td>{$employ['e_name']}</td><td>{$employ['e_company']}</td><td>{$salaryList[Sheet1][$i][$shenfenzheng]}</font></td><td>:单位工伤不一致！系统：{$employ['com_gongshang']},导入文件：{$salaryList[Sheet1][$i][$comgongshang]}</td>";
					}
				}
				if ($comshengyu != '' && $comshengyu != - 1) {
					if ($salaryList [Sheet1] [$i] [$comshengyu] != $employ ['com_shengyu']) {
						$error [$i] ["error_laowufei"] = "<td>{$employ['e_name']}</td><td>{$employ['e_company']}</td><td>{$salaryList[Sheet1][$i][$shenfenzheng]}</font></td><td>:单位生育不一致！系统：{$employ['com_shengyu']},导入文件：{$salaryList[Sheet1][$i][$comshengyu]}</td>";
					}
				}
			} else {
				$error [$i] ["error_shenfen"] = "<span style=\"color: red\"> {$employ['e_name']} {$employ['e_company']} {$salaryList[Sheet1][$i][$shenfenzheng]} </span>未查询到该员工{$salTime}月份下的工资！<br>";
                $employByName = $this->objDao->searchSalaryListBy_SalaryEmpName( $emp);
                while ($row=mysql_fetch_array($employByName) ){
                    if($row){
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 根据姓名查找信息如下：</span><br>";
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\">姓名：". $row["e_name"]."</span>";
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 身份证号：".$row["e_num"]."</span>";
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 所在公司：".$row["e_company"]."</span>";
                        $adminlist = $this->objDao->getAdminBycomId($row["companyId"]);
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 管理者姓名：</span>";
                        while($admin=mysql_fetch_array($adminlist)){
                            $adminName = $this->objDao->getAdminById($admin["adminId"]);
                            if ($adminName) {
                                $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> ".$adminName["name"]."</span>";
                            }
                        }
                        $error [$i] ["error_shenfen"].="<br><br>";
                    } else{
                        $error [$i] ["error_shenfen"].= "<span style=\"color: blue\"> 抱歉，根据姓名也未找到该员工信息。</span><br>";
                    }
                }
                continue;
			}
		}
		if (count ( $error ) == 0) {
			$error [0] ["succ"] = "<font color=green>对比没有错误</font>";
		}
		$this->objForm->setFormData ( "errorlist", $error );
		$this->objForm->setFormData ( "excelList", $salaryList [Sheet1] );
	}
	function jishuDuibi() {
		$this->mode = "duibiError";
		$shenfenzheng = ($_POST ['shenfenzheng_jishu'] - 1);
        $empname = ($_POST ['name_jishu'] - 1);
		$shiye = ($_POST ['shiye_jishu'] - 1);
		$yiliao = ($_POST ['yiliao_jishu'] - 1);
		$yanglao = ($_POST ['yanglao_jishu'] - 1);
		$shengyu = ($_POST ['shengyu_jishu'] - 1);
		$gongjijin = ($_POST ['gongjijin_jishu'] - 1);
		session_start ();
		$salaryList = $_SESSION ['salarylist'];
		$jisuan_var = array ();
		$error = array ();
		$this->objDao = new EmployDao ();
		$sumclass = new sumSalary ();
		// 根据身份证号查询出员工身份类别shebaojishu
		for($i = 1; $i < count ( $salaryList [Sheet1] ); $i ++) {
			
			$employ = $this->objDao->getEmByEno ( $salaryList [Sheet1] [$i] [$shenfenzheng] );
			if ($employ) {
				$userType = $sumclass->getShenfenleibie ( $employ ['e_type'] );
				if ($shiye != '' && $shiye != - 1) {
					if ($userType == 1 || $userType == 2) {
						$gerenshiye = $sumclass->max ( $sumclass->min ( $employ ['shebaojishu'], 12603 ), 1680 );
					} else {
						$gerenshiye = 0;
					}
					if ($salaryList [Sheet1] [$i] [$shiye] != $gerenshiye) {
						$error [$i] ["error_com"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:失业基数不一致！系统：{$gerenshiye},导入文件：{$salaryList[Sheet1][$i][$shiye]}";
					}
				}
				if ($yiliao != '' && $yiliao != - 1) {
					if ($userType == 1 || $userType == 2 || $userType == 3 || $userType == 4) {
						$gerenyiliao = $sumclass->max ( $sumclass->min ( $employ ['shebaojishu'], 12603 ), 2521 );
					} else {
						$gerenyiliao = 0;
					}
					if ($salaryList [Sheet1] [$i] [$yiliao] != $gerenyiliao) {
						$error [$i] ["error_ename"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:医疗基数不一致！系统：{$gerenyiliao},导入文件：{$salaryList[Sheet1][$i][$yiliao]}";
					}
				}
				if ($yanglao != '' && $yanglao != - 1) {
					$gerenyanglao = $sumclass->max ( $sumclass->min ( $shebaojishu, 12603 ), 1680 );
					if ($salaryList [Sheet1] [$i] [$yanglao] != $gerenyanglao) {
						$error [$i] ["error_banknum"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:养老基数不一致！系统：{$gerenyanglao},导入文件：{$salaryList[Sheet1][$i][$yanglao]}";
					}
				}
				if ($shengyu != '' && $shengyu != - 1) {
					if ($userType == 1 || $userType == 3 || $userType == 5) {
						$danweishengyu = $sumclass->max ( $sumclass->min ( $employ ['shebaojishu'], 12603 ), 2521 );
					} else {
						$danweishengyu = 0;
					}
					if ($salaryList [Sheet1] [$i] [$shengyu] != $danweishengyu) {
						$error [$i] ["error_bankname"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:生育基数不一致！系统：{$danweishengyu},导入文件：{$salaryList[Sheet1][$i][$shengyu]}";
					}
				}
				if ($gongjijin != '' && $gongjijin != - 1) {
					if ($salaryList [Sheet1] [$i] [$gongjijin] != $employ ['gongjijinjishu']) {
						$error [$i] ["error_gongjijinjishu"] = "<font color='red'>{$salaryList[Sheet1][$i][$shenfenzheng]}</font>:公积金基数不一致！系统：{$employ['gongjijinjishu']},导入文件：{$salaryList[Sheet1][$i][$gongjijin]}";
					}
				}
			} else {
				$error [$i] ["error_shenfen"] = "<span style=\"color: red\"> {$salaryList[Sheet1][$i][$shenfenzheng]} </span>:未查询到该员工身份类别！<br>";
                $employByName = $this->objDao->getEmByEname( $salaryList[Sheet1][$i][$empname]);
                $rc = mysql_affected_rows();
                if($rc>0){

                }else{
                    $error [$i] ["error_shenfen"].= "<span style=\"color: blue\"> 抱歉，根据姓名'{$salaryList[Sheet1][$i][$empname]}'也未找到该员工信息。</span><br>";
                }  ;
                while ($row=mysql_fetch_array($employByName) ){
                    if($row){
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 根据姓名查找信息如下：</span><br>";
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 姓名：".$row["e_name"]."</span>";
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 身份证号：".$row["e_num"]."</span>";
                        $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 所在公司：".$row["e_company"]."</span>";
                        $comid = $this->objDao->searchCompanyidByName($row["e_company"]);
                        while($companyId=mysql_fetch_array($comid)){
                            $adminlist = $this->objDao->getAdminBycomId($companyId["id"]);
                            $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> 管理者姓名：</span>";
                            while($admin=mysql_fetch_array($adminlist)){
                                $adminName = $this->objDao->getAdminById($admin["adminId"]);
                                if ($adminName) {
                                    $error [$i] ["error_shenfen"].="<span style=\"color: blue\"> ".$adminName["name"]."</span>";
                                }
                            }
                        }
                        $error [$i] ["error_shenfen"].="<br><br>";
                    } else{
                        $error [$i] ["error_shenfen"].= "<span style=\"color: blue\"> 抱歉，根据姓名也未找到该员工信息。</span><br>";
                    }
                }
				continue;
			}
		}
		if (count ( $error ) == 0) {
			$error [0] ["succ"] = "<font color=green>对比没有错误</font>";
		}
		$this->objForm->setFormData ( "errorlist", $error );
		$this->objForm->setFormData ( "excelList", $salaryList [Sheet1] );
	}
	function toImportSalPage() {
		$this->mode = "toImportSalPage";
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searhSalaryTimeList ();
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
	}
	function salImportByCom() {
		$this->mode = "salImportByCom";
		$salTime = $_POST ['timeid'];
		$salTimeList = array ();
		$salTimeList = explode ( "*", $salTime );
		
		$this->objDao = new SalaryDao ();
		$salaryListArray = array ();
		$salarySumListArray = array ();
		for($z = 0; $z < (count ( $salTimeList ) - 1); $z ++) {
			$salaryList = $this->objDao->searchSalaryListBy_SalaryTimeId ( $salTimeList [$z] );
			$salarySumList = $this->objDao->searchSumSalaryListBy_SalaryTimeId ( $salTimeList [$z] );
			$i = 0;
			while ( $row = mysql_fetch_array ( $salaryList ) ) {
				$salaryMovementList = $this->objDao->searchSalaryMovementBy_SalaryId ( $row ['id'] );
				while ( $row_move = mysql_fetch_array ( $salaryMovementList ) ) {
					$salaryListArray [$z] [$i] [$row_move ['fieldName']] = $row_move ['fieldValue'];
				}
				// var_dump($row) ;
				if (array_keys ( $row ) !== "salary_type") {
					$salaryListArray [$z] [$i] ['guding_salary'] = $row;
				}
				// $salaryListArray[$z][$i]['guding_salary']=$row;
				$i ++;
			}
			$salarySumListArray [$z] = $salarySumList;
		}
		//
		// var_dump($salarySumListArray);
		$this->objForm->setFormData ( "salaryTimeList", $salaryListArray );
		$this->objForm->setFormData ( "salarySumTimeList", $salarySumListArray );
	}
	function salFaByCom() {
		$this->mode = "salFaByCom";
		$salTime = $_POST ['timeid'];
		$salTimeList = array ();
		$salTimeList = explode ( "*", $salTime );
		
		// $salaryTimeId=$_REQUEST['id'];
		$this->objDao = new SalaryDao ();
		$salaryListArray = array ();
		for($z = 0; $z < (count ( $salTimeList ) - 1); $z ++) {
			$salaryList = $this->objDao->searchSalaryListBy_SalaryTimeId ( $salTimeList [$z] );
			// $salarySumList=$this->objDao->searchSumSalaryListBy_SalaryTimeId($salTimeList[$z]);
			$i = 0;
			while ( $row = mysql_fetch_array ( $salaryList ) ) {
				$employ = $this->objDao->getEmByEno ( $row ['employid'] );
				$row_move = mysql_fetch_array ( $employ );
				$salaryListArray [$z] [$i] [0] = $employ ['e_name'];
				$salaryListArray [$z] [$i] [1] = $employ ['e_num'];
				$salaryListArray [$z] [$i] [2] = $employ ['bank_num'];
				$salaryListArray [$z] [$i] [3] = $employ ['bank_name'];
				$salaryListArray [$z] [$i] [4] = $row ['per_shifaheji'];
				// var_dump($row) ;
				// $salaryListArray[$z][$i]['guding_salary']=$row;
				$i ++;
			}
			$salarySumListArray [$z] = $salarySumList;
		}
		//
		// var_dump($salarySumListArray);
		$this->objForm->setFormData ( "salaryTimeList", $salaryListArray );
		// $this->objForm->setFormData("salarySumTimeList",$salarySumListArray);
	}
	function getSalTemlate() {
		$file = 'template/salTemlate.xls';
		if (file_exists ( $file )) {
			header ( 'Content-Description: File Transfer' );
			header ( 'Content-Type: application/octet-stream' );
			header ( 'Content-Disposition: attachment; filename=' . basename ( $file ) );
			header ( 'Content-Transfer-Encoding: binary' );
			header ( 'Expires: 0' );
			header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header ( 'Pragma: public' );
			header ( 'Content-Length: ' . filesize ( $file ) );
			ob_clean ();
			flush ();
			readfile ( $file );
			exit ();
		}
	}
	function updateSalMark() {
		$this->mode = "salaryListById";
		$mark = $_POST ['mark'];
		$salTimeId = $_POST ['salTimeId'];
		$this->objDao = new SalaryDao ();
		$result = $this->objDao->updateSalTimeMarkBySalTimeId ( $mark, $salTimeId );
		
		$salaryTimeId = $salTimeId;
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
	function toSalListExcel() {
		$mode = $_REQUEST ['modes'];
		if ($mode) {
			$this->mode = "salaryListById";
		} else {
			$this->mode = "toSalListExcel";
		}
		$this->objForm->setFormData ( "modes", $mode );
	}
	function toExtTest() {
		$this->mode = "toExtTest";
	}
}

$objModel = new SalaryAction ( $actionPath );
$objModel->dispatcher ();

?>
