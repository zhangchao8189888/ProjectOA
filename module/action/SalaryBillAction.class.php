<?php
require_once ("module/form/" . $actionPath . "Form.class.php");
require_once ("module/dao/SalaryDao.class.php");
require_once ("tools/excel_class.php");
class SalaryBillAction extends BaseAction {
	/*
	 * @param $actionPath @return TestAction
	 */
	function SalaryBillAction($actionPath) {
		parent::BaseAction ();
		$this->objForm = new SalaryBillForm ();
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
			case "toAddInvoice" :
				$this->toAddInvoice ();
				break;
			case "getSalaryTimeById" :
				$this->getSalaryTimeById ();
				break;
			case "addInvoice" :
				$this->addInvoice ();
				break;
			case "toAddCheque" :
				$this->toAddCheque ();
				break;
			case "addCheque" :
				$this->addCheque ();
				break;
			case "toSendSalary" :
				$this->toSendSalary ();
				break;
			case "salaryComList" :
				$this->salaryComList ();
				break;
			case "getSalaryTimeListByComId" :
				$this->getSalaryTimeListByComId ();
				break;
			case "salarySend" :
				$this->salarySend ();
				break;
			case "toSalaryTongji" :
				$this->toSalaryTongji ();
				break;
            case "toSalaryTongjiExt" :
                $this->toSalaryTongjiExt ();
                break;
			case "searchSalaryTongji" :
				$this->searchSalaryTongji ();
				break;
			case "toBillList" :
				$this->toBillList ();
				break;
			case "searchBill" :
				$this->searchBill ();
				break;
			case "editBill" :
				$this->editBill ();
				break;
			case "billUpdate" :
				$this->billUpdate ();
				break;
			case "delBill" :
				$this->delBill ();
				break;
			case "getCompanyListByName" :
				$this->getCompanyListByName ();
				break;
			case "updateLeijiYueByTimeId" :
				$this->updateLeijiYueByTimeId ();
				break;
			default :
				$this->modelInput ();
				break;
		}
	}
	function modelInput() {
		$this->mode = "toadd";
	}
	function toAddInvoice() {
		$this->mode = "toinvoice";
		$this->objDao = new SalaryDao ();
		$comList = $this->objDao->searchCompanyList ();
		$this->objForm->setFormData ( "comList", $comList );
	}
	function toSendSalary() {
		$this->mode = "toSendSalary";
		$this->objDao = new SalaryDao ();
		$comList = $this->objDao->searchCompanyList ();
		$this->objForm->setFormData ( "comList", $comList );
	}
	function salaryComList() {
		$this->mode = "salComlist";
		$this->objDao = new SalaryDao ();
		$year = $_POST ['year'];
		if (empty ( $year )) {
			$year = '2013';
		}
		$comList = $this->objDao->searchCompanyList ();
		$salList = array ();
		$j = 0;
		while ( $row = mysql_fetch_array ( $comList ) ) {
			// 查询12个月的工资状况包括年终奖
			for($i = 1; $i <= 12; $i ++) {
				if ($i < 10) {
					$date = $year . "-0" . $i . "-01";
				} else {
					$date = $year . "-" . $i . "-01";
				}
				$result = $this->objDao->searhSalaryTimeListByComIdAndDate ( $date, $row ['id'] );
				if ($result) {
					$salList [$j] ['date' . $i] = "<font color=green>已做工资</font>";
				} else {
					$salList [$j] ['date' . $i] = "<font color=red>未做工资</font>";
				}
			}
			$result = $this->objDao->searhNianSalaryTimeListByComIdAndDate ( $year, $row ['id'] );
			if ($result) {
				$salList [$j] ['nian'] = "<font color=green>已做年终奖</font>";
			} else {
				$salList [$j] ['nian'] = "<font color=red>未做年终奖</font>";
			}
			$salList [$j] ['name'] = $row ['company_name'];
			$j ++;
		}
		// var_dump($salList);
		$this->objForm->setFormData ( "year", $year );
		$this->objForm->setFormData ( "comList", $salList );
	}
	function toAddCheque() {
		$this->mode = "tocheque";
		$this->objDao = new SalaryDao ();
		$comList = $this->objDao->searchCompanyList ();
		$this->objForm->setFormData ( "comList", $comList );
	}
	function getSalaryTimeById() {
		$comId = $_REQUEST ['comid'];
		$chequeType = $_REQUEST ['chequeType'];
		if (empty ( $chequeType )) {
			$chequeType = 1;
		}
		$this->objDao = new SalaryDao ();
		$salrayList = $this->objDao->getSalaryListByComId ( $comId, $chequeType );
		$salayString = "";
		while ( $row = mysql_fetch_array ( $salrayList ) ) {
			$salayString .= "," . $row ['id'] . "|" . $row ['salaryTime'];
		}
		echo $salayString;
		exit ();
	}
	function addInvoice() {
		global $billType;
		$exmsg = new EC ();
		// $this->mode="toinvoice";
		$comId = $_REQUEST ['companyname'];
		$salaryTime = $_REQUEST ['salaryTime'];
		$billname = $_REQUEST ['billname'];
        $billno = $_REQUEST ['billno'];
		$billval = $_REQUEST ['billval'];
		$memo = $_REQUEST ['memo'];
		$billArray = array ();
		$billArray ['salaryTime_id'] = $salaryTime;
		$billArray ['bill_type'] = $billType ['发票'];
		$billArray ['bill_date'] = date ( 'Y-m-d H:i:s' );
		$billArray ['bill_item'] = $billname;
        $billArray ['bill_no'] = $billno;
		$billArray ['bill_value'] = $billval;
		$billArray ['bill_state'] = 1; // 对应$billState['1']=>发票已开
		$billArray ['op_id'] = 0;
		$billArray ['text'] = $memo;
		$this->objDao = new SalaryDao ();
		$result = $this->objDao->saveSalaryBill ( $billArray );
		$lastid = $this->objDao->g_db_last_insert_id ();
		if ($result) {
			// 1代表$billState发票已开
			$result = $this->objDao->updateSalaryTimeState ( 1, $salaryTime );
			$succ = "发票添加成功";
		} else {
			$errormsg = "发票添加失败！";
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = $lastid;
		$opLog ['Subject'] = OP_LOG_ADD_BILL_INVOICE;
		$opLog ['memo'] = '';
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			// $this->objDao->rollback();
			throw new Exception ( $exmsg->error () );
		}
		$this->objForm->setFormData ( "errormsg", $errormsg );
		$this->objForm->setFormData ( "succ", $succ );
		$this->toAddInvoice ();
	}
	function addCheque() {
		$exmsg = new EC ();
		global $billType;
		$opLog = array ();
		// $this->mode="tocheque";
		$comId = $_REQUEST ['companyname'];
		$salaryTime = $_REQUEST ['salaryTime'];
		$chequeType = $_REQUEST ['chequeType'];
		if ($chequeType == 2) {
			$billname = "支票";
			$opLog ['Subject'] = OP_LOG_ADD_BILL_ZHI;
			$billState = 2;
		} else {
			$billname = "到账支票";
			$opLog ['Subject'] = OP_LOG_ADD_BILL_ZHIDAO;
			$billState = 3;
		}
		
		$billval = $_REQUEST ['billval'];
		$memo = $_REQUEST ['memo'];
		$billArray = array ();
		$billArray ['salaryTime_id'] = $salaryTime;
		$billArray ['bill_type'] = $billType [$billname];
		$billArray ['bill_date'] = date ( 'Y-m-d H:i:s' );
		$billArray ['bill_item'] = $billname;
		$billArray ['bill_value'] = $billval;
		$billArray ['bill_state'] = $billState; // 对应$billState['']=>""
		$billArray ['op_id'] = 0;
		$billArray ['text'] = $memo;
		$this->objDao = new SalaryDao ();
		$result = $this->objDao->saveSalaryBill ( $billArray );
		$lastid = $this->objDao->g_db_last_insert_id ();
		if ($result) {
			// 1代表$billState发票已开
			$result = $this->objDao->updateSalaryTimeState ( $billState, $salaryTime );
			$succ = $billname . "添加成功";
			$adminPO = $_SESSION ['admin'];
			$opLog ['who'] = $adminPO ['id'];
			$opLog ['what'] = $lastid;
			$opLog ['memo'] = '';
			// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
			$rasult = $this->objDao->addOplog ( $opLog );
			if (! $rasult) {
				$exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
				$this->objForm->setFormData ( "warn", "失败" );
				// 事务回滚
				// $this->objDao->rollback();
				throw new Exception ( $exmsg->error () );
			}
		} else {
			$errormsg = $billname . "添加失败！";
		}
		$this->objForm->setFormData ( "errormsg", $errormsg );
		$this->objForm->setFormData ( "succ", $succ );
		$this->toAddCheque ();
	}
	function getSalaryTimeListByComId() {
		global $billState;
		$comId = $_REQUEST ['cid'];
		$this->mode = "toSendSalary";
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searchSalaryListByComId ( $comId );
		$comList = $this->objDao->searchCompanyList ();
		$this->objForm->setFormData ( "comList", $comList );
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
		$this->objForm->setFormData ( "billState", $billState );
	}
	function salarySend() {
		$exmsg = new EC ();
		// $this->mode="toSendSalary";
		$salaryTimeId = $_REQUEST ['timeid'];
		$billState = 4; // 工资发放
		$billArray = array ();
		$billArray ['salaryTime_id'] = $salaryTimeId;
		$billArray ['bill_type'] = $billState;
		$billArray ['bill_date'] = date ( 'Y-m-d H:i:s' );
		$billArray ['bill_item'] = "工资发放";
		$billArray ['bill_value'] = 0.0;
		$billArray ['bill_state'] = $billState; // 对应$billState['']=>""
		$billArray ['op_id'] = 0;
		$billArray ['text'] = "工资发放";
		$this->objDao = new SalaryDao ();
		$result = $this->objDao->saveSalaryBill ( $billArray );
		$lastid = $this->objDao->g_db_last_insert_id ();
		$errormsg = "";
		if ($result) {
			// 1代表$billState发票已开
			$result = $this->objDao->updateSalaryTimeState ( $billState, $salaryTimeId );
			$errormsg = $billname . "发放成功";
			$adminPO = $_SESSION ['admin'];
			$opLog = array ();
			$opLog ['who'] = $adminPO ['id'];
			$opLog ['what'] = $lastid;
			$opLog ['Subject'] = OP_LOG_SEND_SALARY;
			$opLog ['memo'] = '';
			// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
			$rasult = $this->objDao->addOplog ( $opLog );
			if (! $rasult) {
				$exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
				$this->objForm->setFormData ( "warn", "失败" );
				// 事务回滚
				// $this->objDao->rollback();
				throw new Exception ( $exmsg->error () );
			}
		} else {
			$errormsg = $billname . "发放失败！";
		}
		$this->objForm->setFormData ( "warn", $errormsg );
		$this->toSendSalary ();
	}
	//FIXME 工资统计
	function toSalaryTongji() {
		$this->mode = "toSalaryTongji";
		$this->objDao = new SalaryDao ();
		$comList = $this->objDao->searchCompanyList ();
		$this->objForm->setFormData ( "comList", $comList );
	}
    //FIXME 工资统计BY孙瑞鹏
    function toSalaryTongjiExt() {
        $this->mode = "toSalaryTongjiExt";
    }
	//FIXME 搜索指定公司工资统计
	function searchSalaryTongji() {
		global $billState;
		global $billType;
		$comId = $_REQUEST ['cid'];
		$this->mode = "toSalaryTongji";
		$salaryTongjiArray = array ();
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searchSalaryListByComId ( $comId, 1 ); // 1什么也不代表
		$html_td = array ();
		$leiji = 0.0;
		$i = 0;
		while ( $rowtime = mysql_fetch_array ( $salaryTimeList ) ) {
			$count = $this->objDao->searchCountBill ( $rowtime ['id'], 1 );
			$html = "";
			$j = 0;
			$html .= '<tr >';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '">' . ($i + 1) . '</td>';
			$html .= '<td align="left" width="300px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '" ><a href="index.php?action=SaveSalary&mode=searchSalaryById&id=' . $rowtime ['id'] . '" target="_self">' . $rowtime ['company_name'] . '</a></td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '" >' . $rowtime ['salaryTime'] . '</td>';
			$html_td [$i] [$j] = $html;
			// 查询发票收据
			$salaryFaList = $this->objDao->searchBillBySalaryTimeId ( $rowtime ['id'], 1 );
			$salaryFaArr = array ();
			$j = 0;
			$sumvalue_fa = 0.0;
			while ( $row = mysql_fetch_array ( $salaryFaList ) ) {
				$html = "";
				$html = $html_td [$i] [$j];
				if ($j == 0) {
					$html .= '<td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_date'] . '</td>';
					$html .= ' <td align="left" width="300px" style="word-wrap:break-word;">' . $row ['bill_item'] . '</td>';
					$html .= '<td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_value'] . '</td>';
				} else {
					$html .= '<tr>';
					$html .= '<td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_date'] . '</td>';
					$html .= ' <td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_item'] . '</td>';
					$html .= ' <td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_value'] . '</td>';
				}
				$html_td [$i] [$j] = $html;
				$sumvalue_fa += $row ['bill_value'];
				$j ++;
			}
			$html = "";
			$html = $html_td [$i] [0];
			$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $sumvalue_fa . '</td>';
			$html_td [$i] [0] = $html;
			$salaryTongjiArray [$i] ['fapiao'] = $salaryFaArr;
			// 查询支票收据
			$count_zhi = $this->objDao->searchCountBill ( $rowtime ['id'], 2 );
			if ($count_zhi ['count'] < 1) {
				for($j = 0; $j < $count ['count']; $j ++) {
					$html = "";
					$html = $html_td [$i] [$j];
					if ($j == 0) {
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '"></td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '"></td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '"></td>';
					} else {
						// $html.='';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '"></td>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '"></td>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '"></td>';
					}
					$html_td [$i] [$j] = $html;
					$j ++;
				}
			} elseif ($count_zhi ['count'] == 1) {
				$salaryZhiList = $this->objDao->searchBillBySalaryTimeId ( $rowtime ['id'], 2 );
				$row = mysql_fetch_array ( $salaryZhiList );
				for($j = 0; $j < $count ['count']; $j ++) {
					$html = "";
					$html = $html_td [$i] [$j];
					if ($j == 0) {
						if ($html == '') {
							$html .= '<tr>';
						}
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '">' . $row ['bill_date'] . '</td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '">' . $row ['bill_value'] . '</td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '">' . $row ['bill_value'] . '</td>';
					} else {
						if ($html == '') {
							$html .= '<tr>';
						}
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;" ></td>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;"></td>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;" ></td>';
					}
					$html_td [$i] [$j] = $html;
					$j ++;
				}
			} else {
				$salaryZhiList = $this->objDao->searchBillBySalaryTimeId ( $rowtime ['id'], 2 );
				$j = 0;
				$sumvalue_zhi = 0.0;
				while ( $row = mysql_fetch_array ( $salaryZhiList ) ) {
					$html = "";
					$html = $html_td [$i] [$j];
					if ($j == 0) {
						if ($html == '') {
							$html .= '<tr>';
						}
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_date'] . '</td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_value'] . '</td>';
					} else {
						// $html.='<tr>';
						if ($html == '') {
							$html .= '<tr>';
						}
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_date'] . '</td>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_value'] . '</td>';
					}
					$html_td [$i] [$j] = $html;
					$sumvalue_zhi += $row ['bill_value'];
					$j ++;
					/*
					 * if($row['bill_date']=='2011-11-27'){ var_dump($html_td) ; exit; }
					 */
				}
				$html = "";
				$html = $html_td [$i] [0];
				$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $sumvalue_zhi . '</td>';
				$html_td [$i] [0] = $html;
			}
			// 查询到账支票收据
			$count_zhidao = $this->objDao->searchCountBill ( $rowtime ['id'], 3 );
			if ($count_zhidao ['count'] < 1) {
				$sumvalue_zhidao = 0.0;
				for($j = 0; $j < $count ['count']; $j ++) {
					$html = "";
					$html = $html_td [$i] [$j];
					if ($j == 0) {
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '"></td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '"></td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '"></td>';
					} else {
						// $html.='<tr>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;"></td>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;"></td>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;"></td>';
					}
					$html_td [$i] [$j] = $html;
					$j ++;
				}
			} elseif ($count_zhidao ['count'] == 1) {
				$sumvalue_zhidao = 0.0;
				$salaryZhiList = $this->objDao->searchBillBySalaryTimeId ( $rowtime ['id'], 3 );
				$row = mysql_fetch_array ( $salaryZhiList );
				for($j = 0; $j < $count ['count']; $j ++) {
					$html = "";
					$html = $html_td [$i] [$j];
					if ($j == 0) {
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '">' . $row ['bill_date'] . '</td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '">' . $row ['bill_value'] . '</td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '">' . $row ['bill_value'] . '</td>';
					} else {
						// $html.='<tr>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;"></td>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;"></td>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;"></td>';
					}
					$html_td [$i] [$j] = $html;
					$sumvalue_zhidao += $row ['bill_value'];
					// echo $sumvalue_zhidao."1<br/>";
					$j ++;
				}
			} else {
				$sumvalue_zhidao = 0.0;
				$salaryZhiDaoList = $this->objDao->searchBillBySalaryTimeId ( $rowtime ['id'], 3 );
				$salaryZhiDaoArr = array ();
				$j = 0;
				while ( $row = mysql_fetch_array ( $salaryZhiDaoList ) ) {
					$html = "";
					$html = $html_td [$i] [$j];
					if ($j == 0) {
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_date'] . '</td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_value'] . '</td>';
					} else {
						// $html.='<tr>';
						$html .= '<td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_date'] . '</td>';
						$html .= ' <td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_value'] . '</td>';
					}
					$html_td [$i] [$j] = $html;
					$sumvalue_zhidao += $row ['bill_value'];
					// echo $sumvalue_zhidao."2<br/>";
					$j ++;
				}
				$html = "";
				$html = $html_td [$i] [0];
				$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $sumvalue_zhidao . '</td>';
				$html_td [$i] [0] = $html;
			}
			// 查询合计收据
			$salaryHejiList = $this->objDao->searchSumSalaryListBy_SalaryTimeId ( $rowtime ['id'] );
			$row = mysql_fetch_array ( $salaryHejiList );
			$html = $html_td [$i] [0];
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_per_yingfaheji'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_per_shiye'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_per_yiliao'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_per_yanglao'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_per_gongjijin'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_per_daikoushui'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_per_koukuangheji'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_per_shifaheji'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_com_shiye'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_com_yiliao'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_com_yanglao'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_com_gongshang'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_com_shengyu'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_com_gongjijin'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_com_heji'] . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_paysum_zhongqi'] . '</td>';
			$yu_e = $sumvalue_zhidao - $row ['sum_paysum_zhongqi'];
			if ($rowtime ['salary_leijiyue'] == null) {
				$yu_e_l = $sumvalue_zhidao - $row ['sum_paysum_zhongqi'] + $leiji;
			} else {
				$yu_e_l = $rowtime ['salary_leijiyue'];
			}
			$salarttimeId = $rowtime ['id'];
			$yu_e_l = sprintf ( "%01.2f", $yu_e_l );
			// echo $sumvalue_zhidao."!!!!".$row['sum_paysum_zhongqi'];
			$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . sprintf ( "%01.2f", $yu_e ) . '</td>';
			$html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '"><div id="' . $salarttimeId . '_text">(累计' . $yu_e_l . ')</div></td>';
			
			$leiji = $yu_e_l;
			// echo $leiji.">>>>>>><br/>";
			if ($yu_e == 0) {
				$state = "<font color='green'>正常</font>";
			} elseif ($yu_e < 0) {
				$state = "<font color='red'>公司垫付</font>";
			} else {
				$state = "<font color='blue'>该公司有剩余资金</font>";
			}
			$html .= ' <td align="left" width="300px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $state . '</td>';
			$html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '"><a href="#" onclick="update(' . $salarttimeId . ',' . $yu_e_l . ')" target="_self">修改</a></td>';
			
			$html .= '</tr>';
			$html_td [$i] [0] = $html;
			$i ++;
		}
		// print_r($html_td);
		// exit;
		$comList = $this->objDao->searchCompanyList ();
		$this->objForm->setFormData ( "comList", $comList );
		$this->objForm->setFormData ( "salaryTimeList", $html_td );
		$this->objForm->setFormData ( "billState", $billState );
		$this->objForm->setFormData ( "billType", $billType );
	}


    //FIXME 搜索指定公司工资统计BY孙瑞鹏
    function searchSalaryTongjiExt() {

    }
	/*
	 * function toHtmlByCount($count){ $htmlList=array(); $htmlList[0]= for($i=1;$i<$count;$i++){ } }
	 */
	function toBillList() {
		$this->mode = "billList";
		$this->objDao = new SalaryDao ();
		$comList = $this->objDao->searchCompanyList ();
		$this->objForm->setFormData ( "comList", $comList );
	}
	function searchBill($comId = null) {
		if ($comId == null) {
			$comId = $_REQUEST ['cid'];
		}
		$this->mode = "billList";
		$salaryTongjiArray = array ();
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searchSalaryListByComId ( $comId );
		$comList = $this->objDao->searchCompanyList ();
		$i = 0;
		while ( $rowtime = mysql_fetch_array ( $salaryTimeList ) ) {
			// 查询发票收据
			$salaryFaList = $this->objDao->searchBillBySalaryTimeId ( $rowtime ['id'] );
			while ( $row = mysql_fetch_array ( $salaryFaList ) ) {
				/**
				 * $billType=array(
				 * '发票'=>'1',
				 * '支票'=>'2',
				 * '到账支票'=>'3',
				 * '工资发放'=>'4',
				 * );
				 */
				if ($row ['bill_type'] == 1) {
					$row ['type'] = '发票';
				} elseif ($row ['bill_type'] == 2) {
					$row ['type'] = '支票';
				} elseif ($row ['bill_type'] == 3) {
					$row ['type'] = '到账支票';
				}
				$row ['company_name'] = $rowtime ['company_name'];
				$row ['salaryTime'] = $rowtime ['salaryTime'];
				if ($row ['bill_type'] != 4) {
					$salaryTongjiArray [$rowtime ['salaryTime']] [$i] = $row;
				}
				$i ++;
			}
		}
		// var_dump($salaryTongjiArray);
		$this->objForm->setFormData ( "salaryTimeList", $salaryTongjiArray );
		$this->objForm->setFormData ( "comList", $comList );
		$this->objForm->setFormData ( "cid", $comId );
	}
	function editBill() {
		$billId = $_POST ['timeid'];
		$this->mode = "billUpdate";
		$this->objDao = new SalaryDao ();
		$salaryFaList = $this->objDao->searchBillById ( $billId );
		if ($salaryFaList ['bill_type'] == 1) {
			$salaryFaList ['type'] = '发票';
		} elseif ($salaryFaList ['bill_type'] == 2) {
			$salaryFaList ['type'] = '支票';
		} elseif ($salaryFaList ['bill_type'] == 3) {
			$salaryFaList ['type'] = '到账支票';
		}
		$salaryTime = $this->objDao->searchSalaryTimeBy_id ( $salaryFaList ['salaryTime_id'] );
		$this->objForm->setFormData ( "salaryTime", $salaryTime );
		$this->objForm->setFormData ( "salaryBill", $salaryFaList );
	}
	function billUpdate() {
		$this->mode = "billUpdate";
		$bill = array ();
		$bill ['id'] = $_POST ['eid'];
		$bill ['bill_item'] = $_POST ['billItem'];
		$bill ['bill_value'] = $_POST ['billValue'];
		$this->objDao = new SalaryDao ();
		$result = $this->objDao->updateBillById ( $bill );
		if (! $result) {
			$this->objForm->setFormData ( "error", "修改失败！" );
		}
		$salaryFaList = $this->objDao->searchBillById ( $bill ['id'] );
		if ($salaryFaList ['bill_type'] == 1) {
			$salaryFaList ['type'] = '发票';
		} elseif ($salaryFaList ['bill_type'] == 2) {
			$salaryFaList ['type'] = '支票';
		} elseif ($salaryFaList ['bill_type'] == 3) {
			$salaryFaList ['type'] = '到账支票';
		}
		$salaryTime = $this->objDao->searchSalaryTimeBy_id ( $salaryFaList ['salaryTime_id'] );
		$this->objForm->setFormData ( "salaryTime", $salaryTime );
		$this->objForm->setFormData ( "salaryBill", $salaryFaList );
	}
	function delBill() {
		$billId = $_POST ['timeid'];
		$comid = $_POST ['comid'];
		$this->objDao = new SalaryDao ();
		$result = $this->objDao->delBillById ( $billId );
		$this->searchBill ( $comid );
	}
	function getCompanyListByName() {
		$comName = $_POST ['comName'];
		$this->objDao = new SalaryDao ();
		$result = $this->objDao->getCompanyLisyByName ( $comName );
		while ( $row = mysql_fetch_array ( $result ) ) {
			$salayString .= "$" . $row ['id'] . "|" . $row ['company_name'];
		}
		echo $salayString;
		exit ();
	}
	function updateLeijiYueByTimeId() {
		$leijiyue = $_REQUEST ['leijie'];
		$timeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$result = $this->objDao->updateLeijiyue ( $leijiyue, $timeId );
		if ($result) {
			echo "$" . "ok";
		} else {
			echo "$" . "error";
		}
		exit ();
	}
}

$objModel = new SalaryBillAction ( $actionPath );
$objModel->dispatcher ();

?>