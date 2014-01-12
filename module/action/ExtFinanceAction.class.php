<?php
require_once ("module/form/".$actionPath."Form.class.php");
require_once ("module/dao/FinanceDao.class.php");
require_once ("tools/fileTools.php");
require_once ("tools/excel_class.php");
require_once ("tools/sumSalary.class.php");
require_once ("tools/Classes/PHPExcel.php");

/**
 * 财务ExtAction
 *
 * @author Alice
 */
class ExtFinanceAction extends BaseAction {
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
			case "searchcompanyListJosn" :
				$this->searchcompanyListJosn ();
            case "companyClear";
                $this->companyClear();
			default :
				$this->modelInput ();
				break;
		}
	}


	function modelInput() {
		echo "input null";
	}

	// FIXME 列表所有审核公司
	/**
	 * 查询所有未审核公司
	 */
	function searchcompanyListJosn() {
		$this->objDao = new FinanceDao ();
		$start = $_REQUEST ['start'];
		$limit = $_REQUEST ['limit'];
		$sorts = $_REQUEST ['sort'];
		$dir = $_REQUEST ['dir'];
		$companyName = $_REQUEST ['company_name'];
		if (! $start) {
			$start = 0;
		}
		if (! $limit) {
			$limit = 50;
		}
		if (! $sorts) {
			$sorts = "uncheckid";
		}
		$where = array ();
		$where ['companyName'] = $companyName;
		$sum = $this->objDao->searchCheckCompanyListCount ( $where );
		$comList = $this->objDao->searchCheckCompanyListPage ( $start, $limit, $sorts . " " . $dir, $where );
		$josnArray = array ();
		$josnArray ['total'] = $sum;
		$i = 0;
		while ( $row = mysql_fetch_array ( $comList ) ) {
			$josnArray ['items'] [$i] ['id'] = $row ['id'];
			$josnArray ['items'] [$i] ['company_name'] = $row ['company_name'];
			$josnArray ['items'] [$i] ['company_address'] =$row ['company_address'];
			$josnArray ['items'] [$i] ['checked'] = '未审核';
// 			$josnArray ['items'] [$i] ['pact_start_date'] = $row ['pact_start_date'];
// 			$josnArray ['items'] [$i] ['pact_over_date'] = $row ['pact_over_date'];
// 			$josnArray ['items'] [$i] ['service_fee_state'] = $row ['service_fee_state'];
// 			$josnArray ['items'] [$i] ['service_fee_value'] = $row ['service_fee_value'];
// 			$josnArray ['items'] [$i] ['can_bao_state'] = $row ['can_bao_state'];
// 			$josnArray ['items'] [$i] ['can_bao_value'] = $row ['can_bao_value'];
// 			$josnArray ['items'] [$i] ['companyEmail'] = $row ['companyEmail'];
// 			$josnArray ['items'] [$i] ['remarks'] = $row ['remarks'];
			$i ++;
		}
		echo json_encode ( $josnArray );
		exit ();
	}

    /**
     * Extfinance action单位审核通过
     */
    function companyClear() {
        $this->objDao=new FinanceDao();
        $id=$_REQUEST['comid'];
        $company    =   $this->objDao->getCheckCompanyById($id);
        echo($company['company_address']);
        $this->objDao->companyClear($id,$company);
        exit();
    }

}


$objModel = new ExtFinanceAction ( $actionPath );
$objModel->dispatcher ();

?>
