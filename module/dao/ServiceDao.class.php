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
    function searchAdminCompany($companyId){
    	$sql="select *  from OA_admin_company where companyId=$companyId";
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
}
?>
