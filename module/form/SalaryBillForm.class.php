<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class SalaryBillForm extends BaseForm {
	/**
	 *
	 * @return AdminForm
	 */
	function SalaryBillForm() {
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
			case "toinvoice" :
				return "salaryBill/add_invoice.php";
			case "tocheque" :
				return "add_cheque.php";
			case "toSendSalary" :
				return "send_salary.php";
			case "toSalaryTongji" :
				return "salaryTongji.php";
            case "toShebaoExt" :
                 return "shebaoExt.php";
            case "toSalaryTongjiExt" :
                return "salaryTongjiExt.php";
			case "billList" :
				return "bill_op.php";
			case "billUpdate" :
				return "bill_update.php";
			case "salComlist" :
				return "salComlist.php";
			default :
				return "index.php";
		}
	}
}
?>

