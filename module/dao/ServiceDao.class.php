<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class ServiceDao extends BaseDao
{
 
    /**
     *
     * @return BaseConfigDao
     */
    function ServiceDao()
    {
        parent::BaseDao();
    }
 /*   function getAdminList(){
		$sql="select *  from  OA_admin  where  del_flag<>1";
		$result=$this->g_db_query($sql);
		return $result;
    }
  */
    function getAdminOpComListByAdminId($adminId){
    	/**
    	 * CREATE TABLE `OA_admin_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminId` int(11) NOT NULL,
  `companyId` int(11) NOT NULL,
  `opTime` date DEFAULT NULL,
  `salStat` int(2) DEFAULT '0',
  `remark` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=gbk;
    	 * @var unknown_type
    	 */
    	$sql=" select oac.*,oc.company_name  from OA_admin_company oac ,OA_company oc where oac.companyId=oc.id and oac.adminId=$adminId ";
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function addAdminCompany($adminCom){
    	$sql="insert into OA_admin_company (adminId,companyId,opTime) values({$adminCom['adminId']},{$adminCom['companyId']},now())";
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function searchAdminCompany($companyId,$adminId=null){
    	$sql="select *  from OA_admin_company where companyId=$companyId";
        if ($adminId) {
            $sql.=" and adminId = $adminId";
        }
    	$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }
    function searchCompanyListByComName($comName){
    	$sql="select *  from  OA_company where company_name like '%{$comName}%' ";
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function deleteServiceCom($adminId,$comId){
    	$sql="delete from  OA_admin_company where adminId=$adminId and companyId=$comId ";
    	$result=$this->g_db_query($sql);
		return $result;
    }

    /**
     * 客服首页dao 获得count
     * @param null $where
     * @return int
     */
    function searhManageComCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT count(distinct company_name) as cnt   from OA_company c
                LEFT JOIN OA_admin_company a  ON  a.companyId = c.id
                LEFT JOIN OA_salarytime b ON b.companyId=c.id
                where a.adminId =$id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['searchType'] != "") {
                if ($where ['searchType'] == 1) {
                    if($where ['$salTime']!=""){
                        $sql .= " and b.salaryTime='{$where ['$salTime']}' ";
                    }
                } elseif ($where ['searchType'] == 2) {
                    $sql .= " and b.op_salaryTime>='{$where ['$salTime']}' and b.op_salaryTime<='{$where ['dateEnd']}' ";
                }elseif ($where ['searchType'] == 3) {
                    $sql .= "and b.salaryTime>='{$where ['$salTime']}' and b.salaryTime<='{$where ['dateEnd']}' ";
                }
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

    /**
     * 客服首页dao 获得分页数据
     * @param null $start
     * @param null $limit
     * @param null $sort
     * @param null $where
     * @return bool|resource
     */
    function searhManageComPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT  distinct c.id,c.company_name from OA_company c
                LEFT JOIN OA_admin_company a  ON  a.companyId = c.id
                LEFT JOIN OA_salarytime b ON b.companyId=c.id
                where a.adminId =$id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['searchType'] != "") {
                if ($where ['searchType'] == 1) {
                    if($where ['$salTime']!=""){
                        $sql .= " and b.salaryTime='{$where ['$salTime']}' ";
                    }
                } elseif ($where ['searchType'] == 2) {
                    $sql .= " and b.op_salaryTime>='{$where ['$salTime']}' and b.op_salaryTime<='{$where ['dateEnd']}' ";
                }elseif ($where ['searchType'] == 3) {
                    $sql .= " and b.salaryTime>='{$where ['$salTime']}' and b.salaryTime<='{$where ['dateEnd']}' ";
                }
            }
        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        // $sql.=" order by op_salaryTime desc ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
}
?>
