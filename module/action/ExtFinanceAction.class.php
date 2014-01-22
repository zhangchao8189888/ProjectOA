<?php
require_once ("module/form/".$actionPath."Form.class.php");
require_once ("module/dao/FinanceDao.class.php");
require_once ("module/dao/SalaryDao.class.php");
require_once ("module/dao/ServiceDao.class.php");
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
            case "searchcaiwuListJosn":
                $this->searchcaiwuListJosn();
                break;
            case "comTaxListJosn":
                $this->comTaxListJosn();
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
     * ExtFinance action 财务首页
     */
    function searchcaiwuListJosn(){
        $this->objDao = new FinanceDao();
        $start = $_REQUEST ['start'];
        $limit = $_REQUEST ['limit'];
        $sorts = $_REQUEST ['sort'];
        $dir = $_REQUEST ['dir'];
        $searchType = $_REQUEST['sType'];
        $companyName=$_REQUEST['companyName'];
        $date   =   $_REQUEST['date'];
        if (empty($searchType)) {
            $searchType = 1;
        }
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
        $comList = array ();
        $where['searchType']=$searchType;
        $where['companyName']=$companyName;
        if($date!=null) {
            $time   =   $this->AssignTabMonth($date,0);
            if(1==$searchType){
                $date=  $time["data"];
                $where['$salTime']=$date;
            }elseif(2 == $searchType){
                $date   =    $time["first"];
                $dateEnd   =     $time["next"];
                $where['$salTime']=$date;
                $where['dateEnd']=$dateEnd;
            }elseif(3 == $searchType){
                $date   =    $time["first"];
                $dateEnd   =    $time["last"];
                $where['$salTime']=$date;
                $where['dateEnd']=$dateEnd;
            }
        }else{
            $date=date('Y-m',time())."-01";
        }

        // 查询公司列表
        $sum =$this->objDao->searhManageComCount($where);
        $result=$this->objDao->searhManageComPage($start,$limit,$sorts." ".$dir,$where);
        $comList['total']=$sum;
        global $billType;
        $i = 0;
        while ( $row = mysql_fetch_array ( $result ) ) {
            $comList ['items'] [$i] ['id'] = $row ['id'];
            $comList ['items'] [$i] ['company_name'] = $row ['company_name'];
            $sal = $this->objDao->searchSalTimeByComIdAndSalTime($row['id'], $date, $dateEnd, $searchType);
            $comList ['items'] [$i] ['id'] = $row ['id'];
            $comList ['items'] [$i] ['company_name'] = $row ['company_name'];
            if ($sal) {
                $comList ['items'] [$i] ['sal_state'] = "<span style=\"color: green\">已做工资</span>";
            } else {
                $comList ['items'] [$i] ['sal_state'] = "<span style=\"color: red\">未做工资</span>";
            }
            // 查询发票，支票，到账，是否发放
            $comList ['items'] [$i] ['bill_state'] = "<span style=\"color: blue\">未开发票</span>";
            $comList ['items'] [$i] ['cheque_state'] = "<span style=\"color: blue\">未开支票</span>";
            $comList ['items'] [$i] ['cheque_account'] = "<span style=\"color: blue\">支票未到账</span>";
            $comList ['items'] [$i] ['sal_approve'] = "<span style=\"color: blue\">未处理审批</span>";
            if ($sal) {
                $billList = $this->objDao->searchBillBySalaryTimeId ( $sal ['id'] );
                while ( $bill = mysql_fetch_array ( $billList ) ) {
                    if ($bill ['bill_type'] == $billType ['发票']) {
                        $comList ['items'] [$i] ['bill_state'] = "<span style=\"color: green\">已开发票</span>";
                    } elseif ($bill ['bill_type'] == $billType ['支票']) {
                        $comList ['items'] [$i] ['cheque_state'] = "<span style=\"color: green\">已开支票</span>";
                    } elseif ($bill ['bill_type'] == $billType ['到账支票']) {
                        $comList ['items'] [$i] ['cheque_account'] = "<span style=\"color: green\">支票已到帐</span>";
                    } elseif ($bill ['bill_type'] == $billType ['工资发放']) {
                        if ($bill ['bill_value'] == 0) {
                            $comList ['items'] [$i] ['sal_approve'] = "<span style=\"color: blue\">等待审批</span>";
                        } elseif ($bill ['bill_value'] == 1) {
                            $comList ['items'] [$i] ['sal_approve'] =  "<span style=\"color: green\">审批通过</span>";
                        } elseif ($bill ['bill_value'] == 2) {
                            $comList ['items'] [$i] ['sal_approve'] = "<span style=\"color: red\">审批未通过</span>";
                        }
                    }
                }
            }
            if($sal ['salaryTime'])  {
                $comList ['items'] [$i]  ['sal_date'] = $sal ['salaryTime'];
            } else{
                $comList ['items'] [$i]  ['sal_date'] = "<span style=\"color: black\"> - - - - </span>";
            }

            $i ++;
        }
        echo json_encode($comList);
        exit ();
    }

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
     * ExtFinance action个税查看
     */
    function comTaxListJosn(){
        $this->objDao = new FinanceDao ();
        $start = $_REQUEST ['start'];
        $limit = $_REQUEST ['limit'];
        $sorts = $_REQUEST ['sort'];
        $dir = $_REQUEST ['dir'];
        $companyName = $_REQUEST ['company_name'];
        $year = $_REQUEST ['year'];
        if (! $start) {
            $start = 0;
        }
        if (! $limit) {
            $limit = 50;
        }
        if (! $sorts) {
            $sorts = "uncheckid";
        }
        if(!$year){
            $year="2014";
        }
        $where = array ();
        $where ['companyName'] = $companyName;
        $sum =$this->objDao->searchTaxListCount($where);
        $result=$this->objDao->searchTaxListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray = array ();
        $josnArray ['total'] = $sum;
        $j = 0;
        while ( $row = mysql_fetch_array ( $result ) ) {
            $josnArray['items'][$j]['id']=$row['id'];
            $josnArray['items'][$j]['company_name']=$row['company_name'];
            // 查询12个月的工资状况包括年终奖

            for($i = 1; $i <= 12; $i ++) {

                if ($i < 10) {
                    $date = $year . "-0" . $i . "-01";
                } else {
                    $date = $year . "-" . $i . "-01";
                }
                $resul = $this->objDao->searchTaxTimeByDateAndComId ( $date, $row ['id'] );
                if ($resul && $resul ['geSui_type'] == 1) {
                    $josnArray['items'][$j]['mouth'.$i] = "<span style='color: green'>已报个税</span>";
                } else {
                    $josnArray['items'][$j]['mouth'.$i] = "<span style='color: red'>未报个税</span>";
                }
            }
            $j++;
        }
        echo json_encode ( $josnArray );
        exit ();
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
