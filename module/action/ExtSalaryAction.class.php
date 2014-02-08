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
            case "searchSalaryTongji":
                $this->searchSalaryTongji();
                break;
            case "searchErSalaryTimeListJosn":
                $this->searchErSalaryTimeListJosn();
                break;
            case "searhSalaryNianTimeListJosn":
            	$this->searhSalaryNianTimeListJosn();
            case "searchGeshuiListJosn" :
            	$this->searchGeshuiListJosn();
            	break;
            case "searchFapiaoTypeJosn" :
                $this->searchFapiaoTypeJosn();
                break;
            case "searchDaozhangTypeJosn" :
                $this->searchDaozhangTypeJosn();
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
        $time["month"]  =   (date("Y-m",strtotime("+1 day",strtotime($date))));
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
        if($salTime){
            $timesal=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$timesal["month"];
        }
        if($opTime) {
            $time=$this->AssignTabMonth($opTime,0);
            $where['op_salaryTime']=$time["next"];
            $where['op_time']   =   $time["data"];
        }
    	$where['companyName']=$companyName;
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
        $where['salaryTime']=$salTime;
        if($salTime) {
            $time=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$time["month"];
        }
        $where['companyName']=$companyName;
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

    /**
     * 工资统计Ext
     */
    function searchSalaryTongji(){
        $this->objDao=new SalaryDao();
        $comId  =   $_POST['id'];
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
        $salaryTimeList = $this->objDao->searchSalaryListByComId ( $comId, 1 ); // 1什么也不代表
        $sum =$this->objDao->searchSalaryListCountByComId( $comId, 1);
        $josnArray=array();
        $josnArray['total']=$sum;
        $leiji = 0.0;
        $i = 0;

        while ( $rowtime = mysql_fetch_array ( $salaryTimeList ) ) {
            $count = $this->objDao->searchCountBill ( $rowtime ['id'], 1 );
            $html = "";
            $j = 0;
            $html .= '<tr >';
            $html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '">' . ($i + 1) . '</td>';
            $html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan="' . $count ['count'] . '" ><a href="index.php?action=SaveSalary&mode=searchSalaryById&id=' . $rowtime ['id'] . '" target="_self">' . $rowtime ['company_name'] . '</a></td>';
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
                    $html .= ' <td align="left" width="150px" style="word-wrap:break-word;">' . $row ['bill_item'] . '</td>';
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
            $html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_per_yingfaheji'] . '</td>';
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
            $html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_com_gongjijin'] . '</td>';
            $html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_com_heji'] . '</td>';
            $html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $row ['sum_paysum_zhongqi'] . '</td>';
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
            $html .= ' <td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '">' . $state . '</td>';
            $html .= '<td align="left" width="150px" style="word-wrap:break-word;" rowspan=" ' . $count ['count'] . '"><a href="#" onclick="update(' . $salarttimeId . ',' . $yu_e_l . ')" target="_self">修改</a></td>';

            $html .= '</tr>';
            $html_td [$i] [0] = $html;
            $i ++;
        }

        $salaryList = $this->objDao->searchSalaryListByComId ( $comId, 1 ); // 1什么也不代表
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
                    if ($row_move ['fieldName'] == '工资表月份' || $row_move ['fieldName'] == '本月余额') {
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
    //发票统计BY孙瑞鹏
    function searchFapiaoTypeJosn(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['comname'];
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
        $sum =$this->objDao->searhFapiaoCount($where);
        $salaryTimeList=$this->objDao->searhFapiaoListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['bill_no']=$row['bill_no'];
            $josnArray['items'][$i]['salaryTime']=$row['salaryTime'];
            $josnArray['items'][$i]['company_name']=$row['company_name'];
            $josnArray['items'][$i] ['bill_value'] = $row ['bill_value'];
            $i++;
        }
        echo json_encode($josnArray);
        exit;
    }

    //到账统计BY孙瑞鹏
    function searchDaozhangTypeJosn(){
        $this->objDao=new SalaryDao();
        $start=$_REQUEST['start'];
        $limit=$_REQUEST['limit'];
        $sorts=$_REQUEST['sort'];
        $dir=$_REQUEST['dir'];
        $companyName=$_REQUEST['comname'];
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
        $sum =$this->objDao->searhDaozhangCount($where);
        $salaryTimeList=$this->objDao->searhDaozhangListPage($start,$limit,$sorts." ".$dir,$where);
        $josnArray=array();
        $josnArray['total']=$sum;
        $i=0;
        while ($row=mysql_fetch_array($salaryTimeList) ){
            $josnArray['items'][$i]['daozhangTime']=$row['daozhangTime'];
            $josnArray['items'][$i]['cname']=$row['cname'];
            $josnArray['items'][$i]['daozhangValue']=$row['daozhangValue'];
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
        $where=array();
    	if(!$start){
    		$start=0;
    	}
    	if(!$limit){
    		$limit=50;
    	}
        if($salTime){
            $time=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$time["month"];
        }
    	$where['companyName']=$companyName;
    
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
        $where=array();
        if(!$start){
            $start=0;
        }
        if(!$limit){
            $limit=50;
        }
        if($salTime){
            $timesal=$this->AssignTabMonth($salTime,0);
            $where['salaryTime']=$timesal["month"];
        }
        $where['companyName']=$companyName;
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
