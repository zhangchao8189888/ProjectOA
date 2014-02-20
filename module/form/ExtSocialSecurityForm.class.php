<?php
/**
 *社保Form
 */
class ExtSocialSecurityForm extends BaseForm {
    /**
     * @return AdminForm
     */
    function ExtSocialSecurityForm() {
        parent::BaseForm ();
    }
    /**
     * 取得tpl文件
     */
    function getTpl($mode = false) {
        switch ($mode) {
            case "getOtherAdminComListJosn" :
                return "ext/view/service/ServiceIndex.php";
            default :
                return "BaseConfig.php";
        }
    }
}
?>

