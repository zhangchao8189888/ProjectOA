<?php
/**
 * 财务ExtForm
 *  @author Alice
 *  
 */
class ExtFinanceForm extends BaseForm {
	/**
	 * @return AdminForm
	 */
	function ExtFinanceForm() {
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
			case "toCheckCompany" :
				return "ext/view/finance/checkCompany.php";
            case "toFinaceFirst" :
                return "ext/view/finance/finaceFirst.php";
			default :
				return "BaseConfig.php";
		}
	}
}
?>

