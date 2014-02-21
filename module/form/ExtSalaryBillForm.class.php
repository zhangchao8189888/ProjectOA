<?php
/**
 * 财务ExtForm
 *  @author Alice
 *
 */
class ExtSalaryBillForm extends BaseForm {
    /**
     * @return AdminForm
     */
    function ExtSalaryBillForm() {
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
            case "toFinaceIndex" :
                return "ext/view/finance/financeIndex.php";
            default :
                return "BaseConfig.php";
        }
    }
}
?>

