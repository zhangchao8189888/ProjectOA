<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class ServiceForm extends BaseForm {
	/**
	 *
	 * @return AdminForm
	 */
	function ServiceForm() {
		// 页面formData做成
		parent::BaseForm ();
	}
	/**
	 * 取得tpl文件
	 *
	 * @param $mode 模式        	
	 * @return 页面表示文件
	 */
	function getTpl($mode = false) {
		switch ($mode) {
            case "old" :
                return "ext/ServiceFirst.php";
			case "serviceFrist" :
                return "ext/ServiceFirst.php";
			case "toOpCompanyList" :
				return "service/serviceComList.php";
			case "toEmlist" :
				return "service/emlist.php";
			case "toAddEmp" :
				return "service/addEmp.php";
			case "toEmploy" :
				return "service/getEmpUpdate.php";
			case "toServiceComlist" :
				return "service/serviceComList.php";
			case "toMakeSal" :
				return "service/upload.php";
			case "emListUpdate" :
				return "service/emListUpdate.php";
			case "duibiError" :
				return "duibiError.php";
            case "toAddNotice" :
                return "service/toAddNotice.php";
			default :
				return "service/service_frist.php";
		}
	}
}
?>

