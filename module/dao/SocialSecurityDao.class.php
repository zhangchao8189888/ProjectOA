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

    /**
     * 搜索变更业务dao
     */
    function searchBusinessCount($businessLog) {
        $sql = "SELECT COUNT(id) AS cnt FROM oa_business";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
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
        $sql = "SELECT * FROM oa_business";
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
        insert into OA_business (submitTime,companyId,companyName,employId,employName,businessName,adminId,adminName,remarks,socialSecurityStateId,socialSecurityState,employStateId,employState)
        values
    	     (now(),
    	     '{$businessLog['companyId']}',
    	     '{$businessLog['companyName']}',
    	     '{$businessLog['employName']}',
    	     '{$businessLog['employNumber']}',
    	     '{$businessLog['businessName']}',
    	     '{$businessLog['adminId']}',
    	     '{$businessLog['adminName']}',
    	     '{$businessLog['remarks']}',
    	     '{$businessLog['socialSecurityStateId']}',
    	     '{$businessLog['socialSecurityState']}',
    	     '{$businessLog['employStateId']}',
    	     '{$businessLog['employState']}') ";
        $result = $this->g_db_query($sql);
        return $result;
    }
}
?>
