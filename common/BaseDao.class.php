﻿<?php
class BaseDao extends db{
function BaseDao(){
//echo "BaseDao</br> ";
parent::db();

}
function addCompany($company){
  $sql="insert into oa_checkcompany (company_name) values('{$company['name']}')";
  $result=$this->g_db_query($sql); 
  if($result){
			return $this->g_db_last_insert_id();
		}else{
			return false;
		}
  }
    function getEmByEno($eNo){
    	$sql="select *  from OA_employ  where e_num='{$eNo}'";
    	$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }
function searchCompanyByName($companyName){
  $sql="select * from  OA_company where company_name='{$companyName}'";
  $result=$this->g_db_query($sql); 
  //var_dump($result);
  return mysql_fetch_array($result);
}
function getCompanyById($comId){
    $sql="select *  from OA_company where  id=$comId";
    $result=$this->g_db_query($sql);
  return mysql_fetch_array($result);
    }
function searchCompanyList($start=NULL,$limit=NULL,$sort=NULL,$where='1=1'){
  $sql="select id,company_name from OA_company  where $where";
  if($sort){
  	$sql.=" order by $sort";
  }
  if($start>=0&&$limit){
     $sql.=" limit $start,$limit";	
  }
  $result=$this->g_db_query($sql); 
  return $result;
}
function getAdmin($loginName){
  $sql="select *  from OA_admin where name='$loginName'";
  $admin=$this->g_db_query($sql); 
  if(!$admin){
     return false; 
  }
  return mysql_fetch_array($admin);

}
function getAdminById($loginId){
  $sql="select *  from OA_admin where id='$loginId'";
  $admin=$this->g_db_query($sql); 
  return mysql_fetch_array($admin);
}
/**
 * 根据查询条件取得操作日志内容
 * @param unknown_type $adminId
 * @param unknown_type $taskId
 * @param unknown_type $where
 */
function getOpLog($adminId=null,$taskId=null,$where=null){
	$sql="select *  from OA_log where 1=1  ";
	if($adminId){
	$sql.=" and who={$adminId} ";	
	}
	if($taskId){
	$sql.="  and what={$taskId} ";	
	}
	if($where){
		$sql.=" and ".$where;
	}
	$sql.=" order by  time desc limit 1";
	$opLog=$this->g_db_query($sql);
	return $opLog;
}
function getOpLogByTaskId($taskId,$where=null){
	$sql="select *  from OA_log where what={$taskId}";
	if($where){
		$sql.=" and ".$where;
	}
	
	$opLog=$this->g_db_query($sql);
	return $opLog;
}
function addOplog($OpLog){
	$sql="insert into OA_log (who,what,Subject,time,memo)  values({$OpLog['who']},{$OpLog['what']},'{$OpLog['Subject']}',now(),'{$OpLog['memo']}')";
	$opLogR=$this->g_db_query($sql);
	return $opLogR;
}
/**
 *修改管理员最后登录时间
 */
function updateAdminLoginTime($admin){
   $sql="update OA_admin  set last_login_time=now()  where id={$admin['id']}";
   $result=$this->g_db_query($sql);
   return $result;
}
    function searchSalTimeByComIdAndSalTime($comId,$salTime,$dateEnd,$searchType){
    	$sql="select *  from OA_salarytime  where companyId=$comId and ";
    	if($searchType==1){
    	$sql.=" salaryTime='{$salTime}' ";	
    	}elseif($searchType==2){
    	$sql.=" op_salaryTime>='{$salTime}' and op_salaryTime<='{$dateEnd}' ";	
    	}
    	$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }
    /**
     * 根据公司id和月份查询二次及其他工资明细
     * @param $comId
     * @param $salTime
     */
    function searchOrSalTimeByComIdAndSalTime($comId,$salTime){
    	$sql="select *  from OA_salarytime_other  where companyId=$comId and salaryTime='{$salTime}'";
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function getSalaryIdBySalaryTime($salTime){
    	//$sql="﻿﻿﻿﻿﻿﻿﻿﻿ select st.*,c.company_name from OA_salarytime st,OA_company c where st.companyId=c.id and st.salaryTime='{$salTime}' ";
    	//$sql="﻿﻿﻿﻿﻿﻿﻿﻿ select *  from OA_salarytime where salaryTime='{$salTime}' ";
    	$sql="select st.*,c.company_name from OA_salarytime st,OA_company c where st.companyId=c.id and st.salaryTime='{$salTime}' and st.salary_state>0";
    	$result=$this->g_db_query($sql);
		return $result;
    }
   function getSalaryTimeBySalId($salTimeId){
    	$sql="select st.*,c.company_name from OA_salarytime st,OA_company c where st.companyId=c.id and st.id=$salTimeId ";
    	$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }
    /*function getOpCompanyOperByComId(){
    	
    }*/
}
?>
