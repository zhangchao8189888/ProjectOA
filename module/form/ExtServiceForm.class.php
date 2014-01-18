<?php
/**
 * 客服ExtForm
 *  @author Alice
 *
 */
class ExtServiceForm extends BaseForm {
    /**
     * @return AdminForm
     */
    function ExtServiceForm() {
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
            case "getOtherAdminComListJosn" :
                return "ext/view/Service/ServiceIndex.php";
            default :
                return "BaseConfig.php";
        }
    }
}
?>

