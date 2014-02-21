<?php 
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/".$actionPath."Dao.class.php");
require_once("module/dao/ServiceDao.class.php");
class AdminAction extends BaseAction{
 	/**
     * @param $actionPath
     * @return AdminAction
     */
 function AdminAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new AdminForm();
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
        	case "betaTest":
        		$this->betaTest();
        		break;
            case "input" :
                $this->getAdminList();
                break;
            case "delete" :
                $this->adminDelete();
                break;
            case "add" :
                $this->adminAdd();
                break;
            case "checklogin" :
                $this->checklogin();
                break;
            case "toOpLog" :
                $this->getOpLog();
                break;
            case "logoff":
            	$this->logoff();
                break;
            case "modifyPass":
            	$this->modifyPass();
            	break;
            default :
                $this->modelInput();
                break;
        }
    }
    /**
     * 新功能测试专用网页。
     */
    function betaTest() {
    	$this->mode	=	"betaTest";
    }
    
    /**
     * 得到管理员列表
     */
  function getAdminList(){
  	    $this->mode="tolist";
		$today=date("Y-m-d");//当天日期
		//查询管理员详细信息
		$this->objDao=new AdminDao();
		//开始事务    
		//取得管理员信息
		$exmsg=new EC();//设置错误信息类
		$result=$this->objDao->getAdminList();
        if(!$result){
						$exmsg->setError(__FUNCTION__, "get admin list  faild ");
						$this->objForm->setFormData("warn","管理员列表失败！");
						throw new Exception ($exmsg->error());
					}
	    $this->objForm->setFormData("adminlist",$result);
  }
  /**
   * 删除管理员
   */
  function  adminDelete(){
  	$today=date("Y-m-d");//当天日期
		//查询管理员详细信息
		global $loginusername;
		$this->objDao=new AdminDao();
		//开始事务    
		$this->objDao->beginTransaction();
		//取得管理员信息
		$admin=$_SESSION['admin'];
		$exmsg=new EC();//设置错误信息类
		$adminId=$_REQUEST["aid"];
  	    $result=$this->objDao->updateAdminToDelete($adminId);
        if(!$result){
				$exmsg->setError(__FUNCTION__, "delete admin   faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","删除管理员操作失败！");
				throw new Exception ($exmsg->error());
			}
        $opLog=array();
			$opLog['who']=$admin['id'];
			$opLog['what']=$adminId;
			$opLog['Subject']=OP_LOG_DELETE_ADMIN;
			$opLog['memo']='';
			//{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
			$rasult=$this->objDao->addOplog($opLog);
			if(!$rasult){
				$exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
				$this->objForm->setFormData("warn","删除管理员操作失败");
				//事务回滚  
				$this->objDao->rollback();
				throw new Exception ($exmsg->error());
			}
		//事务提交
	    $this->objDao->commit();
	    $this->getAdminList();
  }
  /**
   * 添加管理员
   */
  function adminAdd(){
  	$admin=array();
  	$admin['name']=$_REQUEST["byid"];
  	$admin['password']=$_REQUEST["pass"];
  	$admin['admin_type']=$_REQUEST["user_type"];
  	$admin['memo']=$_REQUEST["memo"];
  	
  	//'{$admin['name']}',{$admin['admin_type']},'{$admin['mail_addr']}',now(),'{$admin['memo']}'
  	$today=date("Y-m-d");//当天日期
		//查询管理员详细信息
		global $loginusername;
		$this->objDao=new AdminDao();
		$adminResult=$this->objDao->getAdmin($admin['name']);
		if(!empty($adminResult)){
			$this->objForm->setFormData("warn","该用户已存在！");
			$this->getAdminList();
			return;
		}
		//开始事务    
		$this->objDao->beginTransaction();
		//取得管理员信息
		//=$this->objDao->getAdmin($loginusername);
		$adminPO=$_SESSION['admin'];
		$exmsg=new EC();//设置错误信息类
  	    $result=$this->objDao->addAdmin($admin);
        if(!$result){
				$exmsg->setError(__FUNCTION__, "delete admin   faild ");
				//事务回滚
				$this->objDao->rollback();
				$this->objForm->setFormData("warn","添加管理员操作失败！");
				throw new Exception ($exmsg->error());
			}
	    $saveLastId=$this->objDao->g_db_last_insert_id();
        $opLog=array();
			$opLog['who']=$adminPO['id'];
			$opLog['what']=$saveLastId;
			$opLog['Subject']=OP_LOG_ADD_ADMIN;
			$opLog['memo']='';
			//{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
			$rasult=$this->objDao->addOplog($opLog);
			if(!$rasult){
				$exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
				$this->objForm->setFormData("warn","添加管理员操作失败");
				//事务回滚  
				$this->objDao->rollback();
				throw new Exception ($exmsg->error());
			}
	    //事务提交
	    $this->objDao->commit();
	    $this->getAdminList();
  }
  /**
   * 修改管理员密码
   */
  function modifyPass(){
  	$pass= $_POST['nowpass'];
  	$this->objDao=new AdminDao();
  	$name=$_SESSION['admin']['name'];
    $exmsg=new EC();//设置错误信息类
    $getpassword=$this->objDao->getPass($name);
   	$nowpass	=	$getpassword['password'];
  	$newpass	=	 $_POST['newpass'];
  	$repass	=	 $_POST['repass'];
  	if ($newpass) {  // new password 不为空
  		if ($pass==$nowpass) {
  			if ($newpass==$repass) {
  				$result=$this->objDao->updatePass($name,$newpass);
  				if(!$result){
  					$exmsg->setError(__FUNCTION__, "update admin   faild ");
  					//事务回滚
  					$this->objDao->rollback();
  					$this->objForm->setFormData("warn","抱歉，修改密码操作失败！");
  					throw new Exception ($exmsg->error());
  				}
  				$this->logoff();
  			}
  		}
  	}
  }
  
  function checklogin(){
  	$name=$_POST['usrname'];
  	$pass=$_POST['password'];
  	$this->objDao=new AdminDao();
  	$check=$this->objDao->checklogin($name,$pass);
  	$result=$this->objDao->updateAdminLoginTime($check);
  	if(empty($check)){
  		$this->mode="login";
  	}else{
  		if($check['admin_type']==3){
  			$admin=$_SESSION["admin"];
  	$this->objDao=new ServiceDao();
  	$result=$this->objDao->getAdminOpComListByAdminId($admin['id']);
  	 $this->objForm->setFormData("comList",$result);
  			$this->mode="service";
  		}elseif($check['admin_type']==4){
  			$this->mode="toFinance";
  		}
  		else{
  		$this->mode="index";
  		}
  		$_SESSION['admin']=$check;
  	}
  }
	/**
	 * 得到操作日志列表
	 */
	function getOpLog(){
		$this->mode="toOpLog";
		$this->objDao=new AdminDao();
		$admin=$_SESSION['admin'];
		$whereCount="1=1";//查询总数条件
		$listwhere="";
		if($admin['admin_type']!=ADMIN_TYPE_SYS){//如果是非超级管理员，只能看见自己的操作日志
			$whereCount.=" and who={$admin['id']}";
			$listwhere=" and who={$admin['id']}";
		}
		$sum =$this->objDao->g_db_count("OA_log","*",$whereCount);
		//$sum=10;
		$pagesize=PAGE_SIZE;
		//$sum=$rs['sum'];
		$count = intval($_REQUEST["c"]);
		$page = intval($_REQUEST["p"]);
		if ($count == 0){
			$count = $pagesize;
		}
		if ($page == 0){
			$page = 1;
		}

		$startIndex = ($page-1)*$count;
		$total = $sum;
		$pageindex=$page;
	 //得到商品列表
	 $listwhere.=" order by OA_log.time desc limit $startIndex,$pagesize";
	 $opLogList=array();
	 $result=$this->objDao->getOpLogList($listwhere);
	 $i=0;
	 global $LOGNAME;
	 while($row=mysql_fetch_array($result)){
	 	if($row['subject']==OP_LOG_DELETE_ADMIN||$row['subject']==OP_LOG_ADD_ADMIN){
	 		//如果是对管理员操作查询操作的管理员名称
	 		$opAdmin=$this->objDao->getAdminById($row['what']);
	 		$row['whatname']=$opAdmin['name'];
	 	}else{//否则查询相应操作的产品名称
	 		//$opProduct=$this->objDao->getTaskById($row['what']);
	 		//$row['whatname']=$opProduct['task_name'];
	 	}
	 	$opLogList[$i]=$row;
	 	$i++;
	 }
	 $this->objForm->setFormData("startIndex",$startIndex);
	 $this->objForm->setFormData("total",$total);
	 $this->objForm->setFormData("pageindex",$pageindex);
	 $this->objForm->setFormData("pagesize",$pagesize);
	 $this->objForm->setFormData("opLogList",$opLogList);
	}
	function logoff(){
		$this->mode="login";
		$_SESSION['admin']=NULL;
	}

}


$objModel = new AdminAction($actionPath);
$objModel->dispatcher();

