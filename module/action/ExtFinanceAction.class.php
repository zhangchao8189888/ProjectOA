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
            case "toFinaceFirst":
                $this->toFinaceFirst ();
                break;
			case "searchcompanyListJosn" :
				$this->searchcompanyListJosn ();
                break;
            case "companyClear";
                $this->companyClear();
                break;
            case "searchCaiwuManageComListJosn" :
                $this->searchCaiwuManageComListJosn ();
                break;
            case "cancelManage":
                $this->cancelManage();
                break;
			default :
				$this->modelInput ();
				break;
		}
	}
	function toSalaryTongji() {
		echo "工资统计";
	}
	function modelInput() {
		echo "input null";
	}
	function toFinaceFirst () {
        $this->mode = "toFinaceFirst";
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

    /**
     * Extfinance action 取消管理公司
     */
    function cancelManage(){
        $companylist    =   $_POST["ids"];
        $this->objDao=new BaseDao();
        $arr=json_decode($companylist);
        foreach($arr as $key=>$value){
            $this->objDao->cancelManage($value);
        }
        exit;
    }

    /**
     * 查询财务管理公司集合
     */
    function searchCaiwuManageComListJosn() {
        $this->objDao=new FinanceDao();
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
        $sum =$this->objDao->searchCompanyListCount("OA_company c,OA_admin_company a","*",$where);
        $salaryTimeList=$this->objDao->searchCompanyList($start,$limit,$sorts." ".$dir,$where);
        $comArray=array();
        $comArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $comArray['items'][$i]['id']=$row['id'];
            $comArray['items'][$i]['company_name']=$row['company_name'];
            $i++;
        }
        echo json_encode($comArray);
        exit();
    }

}


$objModel = new ExtFinanceAction ( $actionPath );
$objModel->dispatcher ();

?>
