<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class FinanceDao extends BaseDao
{
 
    /**
     *
     * @return BaseConfigDao
     */
    function FinanceDao()
    {
        parent::BaseDao();
    }
    /*function getAdminList(){
		$sql="select *  from  OA_admin  where  del_flag<>1";
		$result=$this->g_db_query($sql);
		return $result;
    }
    function checklogin($name,$pass){
    	$sql="select *  from  OA_admin  where  name='{$name}' and password='{$pass}'";
		$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }*/
    function getFinanceList($salaryTimeId){
		$sql="select *  from  OA_bill  where salaryTime_id=$salaryTimeId" ;
		$result=$this->g_db_query($sql);
		return $result;
    }
	function searchSalTimeListBySalTime($salTime){
		$id= $_SESSION['admin']['id'];
		$sql="select st.*,c.company_name from OA_salarytime st,OA_company c,OA_admin_company a where st.companyId=c.id and st.salaryTime='{$salTime}'
		and a.companyId = c.id and a.adminId = $id ";
    	$result=$this->g_db_query($sql);
		return $result;
	}
	function  searchFaBill($salaryTimeId){
		$sql="select *  from  OA_bill  where salaryTime_id=$salaryTimeId and bill_state=4 "  ;
		$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }

    /**
     * financedao审核公司
     * @param null $start
     * @param null $limit
     * @param null $sort
     * @param null $where
     * @return bool|resource
     */
    function searchCheckCompanyListPage($start=NULL,$limit=NULL,$sort=NULL,$where=null){
    	$sql="select * from oa_checkcompany where 1=1";
    	if($where!=null){
    		if($where['companyName']!=""){
    			$sql.=" and company_name like '%{$where['companyName']}%' ";
    		}
    	}
    	if($sort){
    		$sql.=" order by $sort";
    	}
    	if($start>=0&&$limit){
    		$sql.=" limit $start,$limit";
    	}

    	$result=$this->g_db_query($sql);
    	return $result;
    }
    function searchCheckCompanyListCount($where=null){
    	$sql="select count(*) as cnt from oa_checkcompany where 1=1";
    	if($where!=null){
    		if($where['companyName']!=""){
    			$sql.=" and company_name like '%{$where['companyName']}%' ";
    		}
    	}
    	$result=$this->g_db_query($sql);
    	if (!$result) {
    		return 0;
    	}
    	$row = mysql_fetch_assoc($result);
    	return $row['cnt'];
    }
    function getCheckCompanyById($comId) {
        $sql = "select *  from oa_checkcompany where  id=$comId";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function companyClear($id,$company){

        $sql = "insert into oa_company (company_name,company_address) values('{$company['company_name']}','{$company['company_address']}')";
        $result = $this->g_db_query ( $sql );
        echo($sql);
        if ($result) {
            $sql="delete from oa_checkcompany where id =$id";
            $result = $this->g_db_query ( $sql );
            if ($result) {
                return $this->g_db_last_insert_id ();
            } else {
                return false;
            }
        } else {
            return false;
        }

    }
    
}
?>
