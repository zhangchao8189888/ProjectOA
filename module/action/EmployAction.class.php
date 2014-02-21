<?php
require_once("module/form/" . $actionPath . "Form.class.php");
require_once("module/dao/" . $actionPath . "Dao.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("tools/excel_class.php");
require_once("tools/Classes/PHPExcel.php");
require_once("tools/Util.php");

class EmployAction extends BaseAction {
    /*
        *
        * @param $actionPath
        * @return TestAction
        */
    function EmployAction($actionPath) {
        parent::BaseAction();
        $this->objForm = new EmployForm();
        $this->actionPath = $actionPath;
    }

    function dispatcher() {
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

    function setMode() {
        // 模式设定
        $this->mode = $_REQUEST['mode'];
    }

    function controller() {
        // Controller -> Model
        switch ($this->mode) {
            case "input" :
                $this->modelInput();
                break;
            case "addEmploy" :
                $this->addEmploy();
                break;
            case "toimport" :
                $this->toEmImport();
                break;
            case "emImport" :
                $this->emImport();
                break;
            case "toEmlist" :
                $this->toEmployList();
                break;
            case "toEmlistExt" :
                $this->toEmployExtList();
                break;
            case "getEmList":
                $this->getEmployList();
                break;
            case "getEmListExt":
                $this->getEmployListExt();
                break;
            case "getEm" :
                $this->getEmployById();
                break;
            case "emUpdate" :
                $this->updateEmployById();
                break;
            case "emNoUpdate":
                $this->emNoUpdate();
                break;
            case "delEm":
                $this->delEmployById();
                break;
            case "toComplist":
                $this->searchCompanyList();
                break;
            case "delEmlistByCom":
                $this->delEmployByComanyName();
                break;
            case "getEmployTemlate":
                $this->getEmployTemlate();
                break;
            default :
                $this->modelInput();
                break;
        }


    }

    function modelInput() {
        $this->mode = "toadd";
    }

    function addEmploy() {
        $this->mode = "toadd";
        $mess = "";
        $succMsg = "";
        $exmsg = new EC();
        $employ = array();
        $employ['e_name'] = $_POST['name'];
        $employ['e_num'] = $_POST['e_no'];
        $employ['bank_name'] = $_POST['bank'];
        $employ['bank_num'] = $_POST['bank_no'];
        $employ['e_type'] = $_POST['e_type'];
        $employ['e_company'] = $_POST['company'];
        $employ['shebaojishu'] = $_POST['shebaojishu'];
        $employ['gongjijinjishu'] = $_POST['gongjijinjishu'];
        $employ['laowufei'] = $_POST['laowufei'];
        $employ['canbaojin'] = $_POST['canbaofei'];
        $employ['danganfei'] = $_POST['danganfei'];
        $employ['memo'] = $_POST['memo'];
        $this->objDao = new EmployDao();
        $emper = $this->objDao->getEmByEno($employ['e_num']);
        if (!empty($emper)) {
            $mess = "此员工身份证号已存在，请重新确认";
            $this->objForm->setFormData("error", $mess);
            $this->objForm->setFormData("succ", $succMsg);
            return;
        }
        $retult = $this->objDao->addEm($employ);
        if (!$retult) {
            $exmsg->setError(__FUNCTION__, "add employ  faild ");
            $mess = "员工添加失败";
            $succMsg = "";
            //$this->objForm->setFormData("warn","审批通过操作失败！");
            //事务回滚
            //$this->objDao->rollback();
            throw new Exception ($exmsg->error());
        } else {
            $mess = "";
            $succMsg = "添加成功";
        }
        $saveLastId = $this->objDao->g_db_last_insert_id();
        $adminPO = $_SESSION['admin'];
        $opLog = array();
        $opLog['who'] = $adminPO['id'];
        $opLog['what'] = $saveLastId;
        $opLog['Subject'] = OP_LOG_ADD_EMPLOY;
        $opLog['memo'] = '';
        //{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
        $rasult = $this->objDao->addOplog($opLog);
        if (!$rasult) {
            $exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
            $this->objForm->setFormData("warn", "添加员工操作失败");
            //事务回滚
            //$this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        $this->objForm->setFormData("error", $mess);
        $this->objForm->setFormData("succ", $succMsg);
    }

    function toEmImport() {
        $this->mode = "toimport";
    }

    function emImport() {
        $errorMsg = "";
        //var_dump($_FILES);
        $fileArray = split("\.", $_FILES['file']['name']);
        //var_dump($fileArray);
        if (count($fileArray) != 2) {
            $this->mode = "toUpload";
            $errorMsg = '文件名格式 不正确';
            $this->objForm->setFormData("error", $errorMsg);
            return;
        } else if ($fileArray[1] != 'xls') {
            $this->mode = "toUpload";
            $errorMsg = '文件类型不正确，必须是xls类型';
            $this->objForm->setFormData("error", $errorMsg);
            return;
        }
        if ($_FILES['file']['error'] != 0) {
            $error = $_FILES['file']['error'];
            switch ($error) {
                case 1:
                    $errorMsg = '1,上传的文件超过了php.ini中  upload_max_filesize选项限制的值.';
                    break;
                case 2:
                    $errorMsg = '2,上传文件的大小超过了HTML表单中MAX_FILE_SIZE  选项指定的大小';
                    break;
                case 3:
                    $errorMsg = '3,文件只有部分被上传';
                    break;
                case 4:
                    $errorMsg = '4,文件没有被上传';
                    break;
                case 6:
                    $errorMsg = '找不到临文件夹';
                    break;
                case 7:
                    $errorMsg = '文件写入失败';
                    break;
            }
        }
        if ($errorMsg != "") {
            $this->mode = "toimport";
            $this->objForm->setFormData("error", $errorMsg);
            return;
        }
        $err = Read_Excel_File($_FILES['file']['tmp_name'], $return);
        if ($err != 0) {
            $this->objForm->setFormData("error", $err);
        }
        //var_dump($return);
        //exit;
        $this->objDao = new EmployDao();
        $this->objForm->setFormData("salarylist", $return);
        $this->mode = "toimport";
        $employList = array();
        //var_dump($return[Sheet1]);
        $comname = $return[Sheet1][1][0];
        $company = $this->objDao->searchCompanyByName($comname);
        if (empty($company)) {
            //添加公司信息
            $companyList = array();
            $companyList['name'] = $comname;
            $companyId = $this->objDao->addCompany($companyList);
        }
        for ($i = 1; $i < count($return[Sheet1]); $i++) {
            $employList[$i]['e_company'] = $return[Sheet1][$i][0];
            $employList[$i]['e_name'] = $return[Sheet1][$i][1];
            $employList[$i]['e_num'] = $return[Sheet1][$i][2];
            $employList[$i]['bank_name'] = $return[Sheet1][$i][3];
            $employList[$i]['bank_num'] = $return[Sheet1][$i][4];
            $employList[$i]['e_type'] = $return[Sheet1][$i][5];
            $employList[$i]['shebaojishu'] = $return[Sheet1][$i][6];
            $employList[$i]['gongjijinjishu'] = $return[Sheet1][$i][7];
            $employList[$i]['laowufei'] = $return[Sheet1][$i][8];
            $employList[$i]['canbaojin'] = $return[Sheet1][$i][9];
            $employList[$i]['danganfei'] = $return[Sheet1][$i][10];
            $employList[$i]['memo'] = $return[Sheet1][$i][11];
            $employList[$i]['e_hetong_date'] = $return[Sheet1][$i][12];
            $employList[$i]['e_hetongnian'] = $return[Sheet1][$i][13];
            //查询公司信息
            if ($comname != $employList[$i]['e_company']) {
                $company = $this->objDao->searchCompanyByName($comname);
                if (empty($company)) {
                    //添加公司信息
                    $companyList = array();
                    $companyList['name'] = $comname;
                    $companyId = $this->objDao->addCompany($companyList);
                    $comname = $employList[$i]['e_company'];
                }
            }
        }
        //var_dump($employList);
        $errorList = array();
        $emList = array();
        $j = 0;
        $z = 0;
        for ($i = 1; $i <= count($employList); $i++) {
            if ($employList[$i]['e_num']) {
                $emper = $this->objDao->getEmByEno($employList[$i]['e_num']);
                if (!empty($emper)) {
                    $errorList[$j]["errmg"] = "此员工身份证号已存在，请重新确认";
                    $errorList[$j]["e_name"] = $employList[$i]["e_name"];
                    $errorList[$j]["e_num"] = $employList[$i]["e_num"];
                    // $this->objForm->setFormData("succ",$succMsg);
                    $j++;
                    continue;
                }
                $retult = $this->objDao->addEm($employList[$i]);

                if ($retult) {
                    $emList[$z]['e_num'] = $employList[$i]["e_num"];
                    $emList[$z]['e_name'] = $employList[$i]["e_name"];
                    $z++;
                }
            } else {
                $errorList[$j]["errmg"] = "此员工身份证号为空或是系统无法识别，请重新复制到模版重新上传";
                $errorList[$j]["e_name"] = $employList[$i]["e_name"];
                $errorList[$j]["e_num"] = $employList[$i]["e_num"];
                // $this->objForm->setFormData("succ",$succMsg);
                $j++;
            }
        }
        $opLog = array();
        $adminPO = $_SESSION['admin'];
        $opLog['who'] = $adminPO['id'];
        $opLog['what'] = 0;
        $opLog['Subject'] = OP_LOG_IMPORT_EMPLOY;
        $opLog['memo'] = '';
        //{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
        $rasult = $this->objDao->addOplog($opLog);
        if (!$rasult) {
            $exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
            $this->objForm->setFormData("warn", "导入员工操作失败");
            //事务回滚
            //$this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        if (!$retult) {
            $errorList[$j]["errmg"] = "此员工添加失败，请检查格式是否正确后重新导入";
            $errorList[$j]["e_name"] = $errorList[$j]["e_name"];
            $errorList[$j]["e_num"] = $errorList[$j]["e_num"];
        }
        $this->objForm->setFormData("errorlist", $errorList);
        $this->objForm->setFormData("emList", $emList);
    }

    function newemImport() {
        set_time_limit(1800);
        $errorMsg = "";
        //var_dump($_FILES);
        $fileArray = split("\.", $_FILES['file']['name']);
        //var_dump($fileArray);
        if (count($fileArray) != 2) {
            $this->mode = "toUpload";
            $errorMsg = '文件名格式 不正确';
            $this->objForm->setFormData("error", $errorMsg);
            return;
        } else if ($fileArray[1] != 'xls') {
            $this->mode = "toUpload";
            $errorMsg = '文件类型不正确，必须是xls类型';
            $this->objForm->setFormData("error", $errorMsg);
            return;
        }
        if ($_FILES['file']['error'] != 0) {
            $error = $_FILES['file']['error'];
            switch ($error) {
                case 1:
                    $errorMsg = '1,上传的文件超过了php.ini中  upload_max_filesize选项限制的值.';
                    break;
                case 2:
                    $errorMsg = '2,上传文件的大小超过了HTML表单中MAX_FILE_SIZE  选项指定的大小';
                    break;
                case 3:
                    $errorMsg = '3,文件只有部分被上传';
                    break;
                case 4:
                    $errorMsg = '4,文件没有被上传';
                    break;
                case 6:
                    $errorMsg = '找不到临文件夹';
                    break;
                case 7:
                    $errorMsg = '文件写入失败';
                    break;
            }
        }
        if ($errorMsg != "") {
            $this->mode = "toimport";
            $this->objForm->setFormData("error", $errorMsg);
            return;
        }
        /*$err=Read_Excel_File($_FILES['file']['tmp_name'],$return);
        if($err!=0){
        $this->objForm->setFormData("error",$err);
        }*/
        $path = $_FILES['file']['tmp_name'];
        $_ReadExcel = new PHPExcel_Reader_Excel2007();
        if (!$_ReadExcel->canRead($path)) $_ReadExcel = new PHPExcel_Reader_Excel5();
        //读取Excel文件
        $_phpExcel = $_ReadExcel->load($path);
        //获取工作表的数目
        $_sheetCount = $_phpExcel->getSheetCount();

        $return = array();
        $_excelData = array();

        //循环工作表
        //for($_s = 0;$_s<$_sheetCount;$_s++) {
        for ($_s = 0; $_s < 2; $_s++) {
            //选择工作表
            $_currentSheet = $_phpExcel->getSheet($_s);
            //取得一共有多少列
            $_allColumn = $_currentSheet->getHighestColumn();
            //取得一共有多少行
            $_allRow = $_currentSheet->getHighestRow();
            for ($_r = 1; $_r <= $_allRow; $_r++) {

                for ($_currentColumn = 'A'; $_currentColumn <= $_allColumn; $_currentColumn++) {
                    $address = $_currentColumn . $_r;
                    $val = $_currentSheet->getCell($address)->getValue();
                    $return['Sheet1'][$_r][] = $val;
                }
            }
        }
        //var_dump($return);
        //exit;
        $this->objDao = new EmployDao();
        $this->objForm->setFormData("salarylist", $return);
        $this->mode = "toimport";
        $employList = array();
        //var_dump($return[Sheet1]);
        $comname = $return[Sheet1][2][0];
        $company = $this->objDao->searchCompanyByName($comname);
        if (empty($company)) {
            //添加公司信息
            $companyList = array();
            $companyList['name'] = $comname;
            $companyId = $this->objDao->addCompany($companyList);
        }
        for ($i = 2; $i < count($return[Sheet1]); $i++) {
            $employList[$i]['e_company'] = $return[Sheet1][$i][0];
            $employList[$i]['e_name'] = $return[Sheet1][$i][1];
            $employList[$i]['e_num'] = $return[Sheet1][$i][2];
            $employList[$i]['bank_name'] = $return[Sheet1][$i][3];
            $employList[$i]['bank_num'] = $return[Sheet1][$i][4];
            $employList[$i]['e_type'] = $return[Sheet1][$i][5];
            $employList[$i]['shebaojishu'] = findNullReturnNumber($return[Sheet1][$i][6]);
            $employList[$i]['gongjijinjishu'] = findNullReturnNumber($return[Sheet1][$i][7]);
            $employList[$i]['laowufei'] = findNullReturnNumber($return[Sheet1][$i][8]);
            $employList[$i]['canbaojin'] = findNullReturnNumber($return[Sheet1][$i][9]);
            $employList[$i]['danganfei'] = findNullReturnNumber($return[Sheet1][$i][10]);
            $employList[$i]['memo'] = $return[Sheet1][$i][11];
            $employList[$i]['e_hetong_date'] = $return[Sheet1][$i][12];
            $employList[$i]['e_hetongnian'] = findNullReturnNumber($return[Sheet1][$i][13]);
            //查询公司信息
            if ($comname != $employList[$i]['e_company']) {
                $company = $this->objDao->searchCompanyByName($comname);
                if (empty($company)) {
                    //添加公司信息
                    $companyList = array();
                    $companyList['name'] = $comname;
                    $companyId = $this->objDao->addCompany($companyList);
                    $comname = $employList[$i]['e_company'];
                }
            }
        }
        //var_dump($employList);
        $errorList = array();
        $emList = array();
        $j = 0;
        $z = 0;
        for ($i = 1; $i <= count($employList); $i++) {
            if ($employList[$i]['e_num']) {
                $emper = $this->objDao->getEmByEno($employList[$i]['e_num']);
                if (!empty($emper)) {
                    $errorList[$j]["errmg"] = "此员工身份证号已存在，请重新确认";
                    $errorList[$j]["e_name"] = $employList[$i]["e_name"];
                    $errorList[$j]["e_num"] = $employList[$i]["e_num"];
                    // $this->objForm->setFormData("succ",$succMsg);
                    $j++;
                    continue;
                }
                $retult = $this->objDao->addEm($employList[$i]);

                if ($retult) {
                    $emList[$z]['e_num'] = $employList[$i]["e_num"];
                    $emList[$z]['e_name'] = $employList[$i]["e_name"];
                    $z++;
                }
            } else {
                $errorList[$j]["errmg"] = "此员工身份证号为空或是系统无法识别，请重新复制到模版重新上传";
                $errorList[$j]["e_name"] = $employList[$i]["e_name"];
                $errorList[$j]["e_num"] = $employList[$i]["e_num"];
                // $this->objForm->setFormData("succ",$succMsg);
                $j++;
            }
        }
        $opLog = array();
        $adminPO = $_SESSION['admin'];
        $opLog['who'] = $adminPO['id'];
        $opLog['what'] = 0;
        $opLog['Subject'] = OP_LOG_IMPORT_EMPLOY;
        $opLog['memo'] = '';
        //{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
        $rasult = $this->objDao->addOplog($opLog);
        if (!$rasult) {
            $exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
            $this->objForm->setFormData("warn", "导入员工操作失败");
            //事务回滚
            //$this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        if (!$retult) {
            $errorList[$j]["errmg"] = "此员工添加失败，请检查格式是否正确后重新导入";
            $errorList[$j]["e_name"] = $errorList[$j]["e_name"];
            $errorList[$j]["e_num"] = $errorList[$j]["e_num"];
        }
        $this->objForm->setFormData("errorlist", $errorList);
        $this->objForm->setFormData("emList", $emList);
    }


    function getEmployList() {
        $this->mode = "toEmlist";
        $c_name = $_REQUEST['comname'];
        $empname = $_REQUEST['empname'];
        $empno = $_REQUEST['empno'];
        $this->objDao = new EmployDao();
        $result = $this->objDao->getEmlistbyComname($c_name, null, $empname, $empno);
        $this->objForm->setFormData("emList", $result);
    }

//员工列表EXT查询VY孙瑞鹏
    function getEmployListExt() {
        $company_name = $_REQUEST['company_name'];
        $emp_name = $_REQUEST['emp_name'];
        $emp_num = $_REQUEST['emp_num'];
        $this->objDao = new EmployDao ();
        $i = 0;
        $josnArray = array();
        $salaryList = $this->objDao->getEmlistbyComnameExt($company_name, null, $emp_name, $emp_num);
        while ($row = mysql_fetch_array($salaryList)) {
            $josnArray ['items'] [$i] ['e_name'] = $row ['e_name'];
            $josnArray ['items'] [$i] ['e_company'] = $row ['e_company'];
            $josnArray ['items'] [$i] ['e_num'] = $row ['e_num'];
            $josnArray ['items'] [$i] ['bank_name'] = $row ['bank_name'];
            $josnArray ['items'] [$i] ['bank_num'] = $row ['bank_num'];
            $josnArray ['items'] [$i] ['e_type'] = $row ['e_type'];
            $josnArray ['items'] [$i] ['shebaojishu'] = $row ['shebaojishu'];
            $josnArray ['items'] [$i] ['gongjijinjishu'] = $row ['gongjijinjishu'];
            $josnArray ['items'] [$i] ['laowufei'] = $row ['laowufei'];
            $josnArray ['items'] [$i] ['canbaojin'] = $row ['canbaojin'];
            $josnArray ['items'] [$i] ['danganfei'] = $row ['danganfei'];
            $josnArray ['items'] [$i] ['memo'] = $row ['memo'];
            $i++;
        }
        echo json_encode($josnArray);
        exit ();
    }


    function toEmployList() {
        $this->mode = "toEmlist";
    }

    function toEmployExtList() {
        $this->mode = "toEmExtlist";
    }

    function getEmployById() {
        $this->mode = "toEmploy";
        $emid = $_GET['eid'];
        $this->objDao = new EmployDao();
        $result = $this->objDao->getEmployById($emid);
        //var_dump($result);
        $this->objForm->setFormData("employ", $result);
    }

    function updateEmployById() {
        $this->mode = "toEmploy";
        $mess = "";
        $succMsg = "";
        $exmsg = new EC();
        $employ = array();
        $employ['id'] = $_POST['eid'];
        $employ['e_name'] = $_POST['name'];
        $employ['e_num'] = $_POST['e_no'];
        $employ['bank_name'] = $_POST['bank'];
        $employ['bank_num'] = $_POST['bank_no'];
        $employ['e_type'] = $_POST['e_type'];
        $employ['e_company'] = $_POST['company'];
        $employ['shebaojishu'] = $_POST['shebaojishu'];
        $employ['gongjijinjishu'] = $_POST['gongjijinjishu'];
        $employ['laowufei'] = $_POST['laowufei'];
        $employ['canbaojin'] = $_POST['canbaofei'];
        $employ['danganfei'] = $_POST['danganfei'];
        $employ['memo'] = $_POST['memo'];
        $this->objDao = new EmployDao();
        /*	$emper=$this->objDao->getEmByEno($employ['e_num']);
            if(!empty($emper)){
               $mess="此员工身份证号已存在，请重新确认";
             $this->objForm->setFormData("error",$mess);
           $this->objForm->setFormData("succ",$succMsg);
               return;
            }*/
        $retult = $this->objDao->updateEm($employ);
        if (!$retult) {
            $exmsg->setError(__FUNCTION__, "update employ  faild ");
            $mess = "员工修改失败";
            $succMsg = "";
            //$this->objForm->setFormData("warn","审批通过操作失败！");
            //事务回滚
            //$this->objDao->rollback();
            throw new Exception ($exmsg->error());
        } else {
            $mess = "";
            $succMsg = "修改成功";
        }
        $adminPO = $_SESSION['admin'];
        $opLog = array();
        $opLog['who'] = $adminPO['id'];
        $opLog['what'] = 0;
        $opLog['Subject'] = OP_LOG_UPDATE_EMPLOY;
        $opLog['memo'] = '';
        //{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
        $rasult = $this->objDao->addOplog($opLog);
        if (!$rasult) {
            $exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
            $this->objForm->setFormData("warn", "修改员工操作失败");
            //事务回滚
            //$this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        $this->objForm->setFormData("error", $mess);
        $this->objForm->setFormData("succ", $succMsg);
    }

    function emNoUpdate() {
        $this->mode = "toEmploy";
        $emid = $_POST['eid'];
        $emNo = $_POST['e_no'];
        $this->objDao = new EmployDao();
        $employ = $this->objDao->getEmployById($emid);
        $emper = $this->objDao->getEmByEno($emNo);
        if (!empty($emper)) {
            $mess = "此员工身份证号已存在，请重新确认";
            $this->objForm->setFormData("error", $mess);
            $this->objForm->setFormData("employ", $employ);
            return;
        }

        $exmsg = new EC(); //设置错误信息类
        $this->objDao->beginTransaction();
        $result = $this->objDao->updateEmployNoByid($emid, $emNo);
        $error = 0;
        if (!$result) {
            $exmsg->setError(__FUNCTION__, "update emNo   faild ");
            $this->objForm->setFormData("warn", "失败");
            //事务回滚
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
            $error = 1;
        }
        $this->objDao = new SalaryDao();
        $result = $this->objDao->updateSalaryEmNoByEmNo($emNo, $employ['e_num']);
        if (!$result) {
            $exmsg->setError(__FUNCTION__, "update emNo   faild ");
            $this->objForm->setFormData("warn", "失败");
            //事务回滚
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
            $error = 1;
        }
        $result = $this->objDao->updateNianSalaryEmNoByEmNo($emNo, $employ['e_num']);
        if (!$result) {
            $exmsg->setError(__FUNCTION__, "update emNo   faild ");
            $this->objForm->setFormData("warn", "失败");
            //事务回滚
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
            $error = 1;
        }
        $result = $this->objDao->updateErSalaryEmNoByEmNo($emNo, $employ['e_num']);
        if (!$result) {
            $exmsg->setError(__FUNCTION__, "update emNo   faild ");
            $this->objForm->setFormData("warn", "失败");
            //事务回滚
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
            $error = 1;
        }
        //事务提交
        if (!$error) {
            $this->objDao->commit();
        }
        $employ['e_num'] = $emNo;
        $this->objForm->setFormData("employ", $employ);

    }

    function delEmployById() {
        $exmsg = new EC();
        $modeType = $_POST['modeType'];
        if ($modeType == 'service') {
            $this->mode = "toServiceEmlist";
        } else {
            $this->mode = "toEmlist";
        }
        $emid = $_POST['eid'];
        $c_name = $_POST['comname'];
        $this->objDao = new EmployDao();
        $result = $this->objDao->delEmploy($emid);
        //var_dump($result);
        $adminPO = $_SESSION['admin'];
        $opLog = array();
        $opLog['who'] = $adminPO['id'];
        $opLog['what'] = 0;
        $opLog['Subject'] = OP_LOG_DEL_EMPLOY;
        $opLog['memo'] = '公司名称：' . $c_name;
        //{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
        $rasult = $this->objDao->addOplog($opLog);
        if (!$rasult) {
            $exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
            $this->objForm->setFormData("warn", "删除员工操作失败");
            //事务回滚
            //$this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        $result = $this->objDao->getEmlistbyComname($c_name);
        $this->objForm->setFormData("emList", $result);
        $this->objForm->setFormData("cname", $c_name);
    }

    function searchCompanyList() {
        $this->mode = "toComplist";
        $this->objDao = new EmployDao();
        $result = $this->objDao->searhCompanyList($emid);
        /*while ($row=mysql_fetch_array($result) ){
            var_dump($row);
        }*/
        $this->objForm->setFormData("comList", $result);
    }

    function delEmployByComanyName() {
        $cname = $_POST['cname'];
        $this->objDao = new EmployDao();
        $company = $this->objDao->searchCompanyByName($cname);
        $this->objDao = new SalaryDao();
        $salayList = $this->objDao->getSalaryListByComId($company['id']);
        /*if(mysql_fetch_array($salayList)){
            $this->objForm->setFormData("error","<font color='green'>$cname</font>:该公司已经做工资不可以删除");
            $this->searchCompanyList();
        }else{*/
        $this->objDao = new EmployDao();
        $result = $this->objDao->delEmployByComName($cname);
        $adminPO = $_SESSION['admin'];
        $opLog = array();
        $opLog['who'] = $adminPO['id'];
        $opLog['what'] = 0;
        $opLog['Subject'] = OP_LOG_DEL_COMPANY;
        $opLog['memo'] = '公司名称：' . $cname;
        //{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
        $rasult = $this->objDao->addOplog($opLog);
        if (!$rasult) {
            $exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
            $this->objForm->setFormData("warn", "删除员工操作失败");
            //事务回滚
            //$this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        $this->searchCompanyList();
        //}


    }

    function getEmployTemlate() {
        $file = 'template/empTemlate.xls';
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }
}


$objModel = new EmployAction($actionPath);
$objModel->dispatcher();



?>
