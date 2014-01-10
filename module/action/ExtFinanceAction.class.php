<?php
require_once ("module/form/" . $actionPath . "Form.class.php");
require_once ("module/dao/SalaryDao.class.php");
require_once ("module/dao/EmployDao.class.php");
require_once ("tools/fileTools.php");
require_once ("tools/excel_class.php");
require_once ("tools/sumSalary.class.php");
require_once ("tools/Classes/PHPExcel.php");

/**
 * 财务ExtAction
 * @author Alice
 *
 */
class ExtFinanceAction extends BaseAction {
	/**
	 * @param $actionPath @return SalaryAction
	 */
	function ExtFinanceAction($actionPath) {
		parent::BaseAction ();
		$this->objForm = new ExtFinanceForm ();
		$this->actionPath = $actionPath;
	}
	function dispatcher() {
		$this->setMode ();
		$this->initBase ( $this->actionPath );
		$this->controller ();
		$this->view ();
		$this->closeDB ();
	}
	function setMode() {
		$this->mode = $_REQUEST ['mode'];
	}
	function controller() {
		switch ($this->mode) {
			case "toSalaryTongji" :
				$this->toSalaryTongji();
				break;
			default :
				$this->modelInput ();
				break;
		}
	}
	
	function toSalaryTongji() {
		echo "工资统计";
	}
	
	function modelInput (){
		
	}
	
	/**
	 * 查询工资日期集合
	 */
	function searchSalaryTimeListJosn() {
		$this->objDao = new SalaryDao ();
		$start = $_REQUEST ['start'];
		$limit = $_REQUEST ['limit'];
		$sorts = $_REQUEST ['sort'];
		$dir = $_REQUEST ['dir'];
		$companyName = $_REQUEST ['companyName'];
		$salTime = $_REQUEST ['salTime'];
		$opTime = $_REQUEST ['opTime'];
		
		if (! $start) {
			$start = 0;
		}
		if (! $limit) {
			$limit = 50;
		}
		$where = array ();
		$where ['companyName'] = $companyName;
		$where ['salaryTime'] = $salTime;
		$where ['op_salaryTime'] = $opTime;
		
		$sum = $this->objDao->searhSalaryTimeListCount ( $where );
		
		$salaryTimeList = $this->objDao->searhSalaryTimeListPage ( $start, $limit, $sorts . " " . $dir, $where );
		$josnArray = array ();
		$josnArray ['total'] = $sum;
		$i = 0;
		/**
		 * companyId	int(11)	No
		 * salaryTime	date	No
		 * op_salaryTime	datetime	No
		 * op_id	int(11)	Yes
		 * salary_state	int(2)	No	0
		 * salary_leijiyue	float(11,2)	Yes
		 */
		while ( $row = mysql_fetch_array ( $salaryTimeList ) ) {
			$josnArray ['items'] [$i] ['id'] = $row ['id'];
			$josnArray ['items'] [$i] ['company_name'] = $row ['company_name'];
			$josnArray ['items'] [$i] ['salaryTime'] = $row ['salaryTime'];
			$josnArray ['items'] [$i] ['op_salaryTime'] = $row ['op_salaryTime'];
			$i ++;
		}
		echo json_encode ( $josnArray );
		exit ();
	}

}

$objModel = new ExtSalaryAction ( $actionPath );
$objModel->dispatcher ();

?>
