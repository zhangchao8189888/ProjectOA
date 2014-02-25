<?php
/**
 * 社保dao
 */
class SocialSecurityDao extends BaseDao {

    /**
     * @return BaseConfigDao
     */
    function SocialSecurityDao() {
        parent::BaseDao ();
    }

    function loginType(){
        $id = $_SESSION ['admin'] ['id'];
        $sql="SELECT admin_type  FROM  OA_admin  WHERE  id=$id";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }

    /**
     * 搜索变更业务dao
     */
    function searchBusinessCount($businessLog,$where) {
        $sql = "SELECT COUNT(id) AS cnt FROM OA_business";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['businessName'] != "") {
                $sql .= " and businessName like '%{$where['businessName']}%' ";
            }
            if ($where ['submitTime'] != "") {
                $sql .= " and submitTime like '%{$where['submitTime']}%' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

    function searchBusinessPage($start = NULL, $limit = NULL, $sort = NULL, $where = null){
        $sql = "SELECT * FROM OA_business where 1=1";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['businessName'] != "") {
                $sql .= " and businessName ='{$where['businessName']}' ";
            }
            if ($where ['submitTime'] != "") {
                $sql .= " and submitTime like '%{$where['submitTime']}%' ";
            }
        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        $result = $this->g_db_query($sql);
        return $result;
    }
    /**
     * 增加变更业务dao
     */
    function addBusinessLog($businessLog) {
        $sql = "
        insert into OA_business (submitTime,companyId,companyName,employId,employName,businessName,serviceId,serviceName,remarks,socialSecurityStateId,socialSecurityState,employStateId,employState)
        values
    	     (now(),
    	     '{$businessLog['companyId']}',
    	     '{$businessLog['companyName']}',
    	     '{$businessLog['employNumber']}',
    	     '{$businessLog['employName']}',
    	     '{$businessLog['businessName']}',
    	     '{$businessLog['serviceId']}',
    	     '{$businessLog['serviceName']}',
    	     '{$businessLog['remarks']}',
    	     '{$businessLog['socialSecurityStateId']}',
    	     '{$businessLog['socialSecurityState']}',
    	     '{$businessLog['employStateId']}',
    	     '{$businessLog['employState']}') ";
        $result = $this->g_db_query($sql);
        return $result;
    }

    /**
     * 变更业务状态
     */
    function updateBusinessLog($adminType,$upId,$updateTypeid,$updateType,$other) {
        $name = $_SESSION ['admin'] ['name'];
        $id = $_SESSION ['admin'] ['id'];
        $sql = " UPDATE OA_business SET socialSecurityStateId =$updateTypeid,
                socialSecurityState ='$updateType'";
        if($adminType==3){
            $sql .=",serviceId = $id,serviceName =  '$name'";
        }else if($adminType==5){
            $sql .=",adminId = $id,adminName =  '$name',updateTime=now()";
            if($other['reimbursementTime']){
                $sql .=",reimbursementTime =  '{$other['reimbursementTime']}',
                reimbursementValue =  '{$other['reimbursementValue']}'";
            }
            if($other['accountTime']){
                $sql .=",accountTime =  '{$other['accountTime']}',
                accountValue =  '{$other['accountValue']}'";
            }
            if($other['grantTime']){
                $sql .=",grantTime =  '{$other['grantTime']}',
                grantValue =  '{$other['grantValue']}'";
            }
            if($other['retireTime']){
                $sql .=",retireTime =  '{$other['retireTime']}'";
            }
            if($other['accountComTime']){
                $sql .=",accountComTime =  '{$other['accountComTime']}',
                accountComValue =  '{$other['accountComValue']}'";
            }
            if($other['accountPersonTime']){
                $sql .=",accountPersonTime =  '{$other['accountPersonTime']}',
                accountPersonValue =  '{$other['accountPersonValue']}'";
            }
        }
        $sql .=" WHERE id=$upId";
        $result = $this->g_db_query($sql);
        return $result;
    }

    function searchBusinessById($id) {
        $sql = "SELECT * FROM OA_business where id =$id";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }

    /**
     * 增减员状态
     */
    function updateZengjian($upid,$uptype) {
        $name = $_SESSION ['admin'] ['name'];
        $sql = " UPDATE OA_security  SET shenbaozhuangtai ='$uptype',
                caozuoren ='$name'  WHERE id=$upid ";
        $result = $this->g_db_query($sql);
        return $result;
    }
}
?>
