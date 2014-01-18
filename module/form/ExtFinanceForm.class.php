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
        echo($mode);
		switch ($mode) {
			case "toCheckCompany" :
				return "ext/view/finance/checkCompany.php";
            case "toFinaceFirst" :
                return "ext/view/finance/financeFirst.php";
            case "toFinaceIndex" :
                return "ext/view/finance/financeIndex.php";
            case "toTaxInfo" :
                return "ext/view/finance/taxInfo.php";
			default :
				return "BaseConfig.php";
		}
	}
}
?>

