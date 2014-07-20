<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class PageIndexForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function PageIndexForm()
    {
        //页面formData做成
        parent::BaseForm();
    }
    /**
     * 取得tpl文件
     *
     * @param $mode　模式
     * @return 页面表示文件
     */
    function getTpl($mode = false)
    {
        switch ($mode) {
            case "toCompanyTotalByTimeListPage":
                return  "ext/view/finance/searchComSalTotal.php";
            case "toCompanyDuizhang":
                return  "ext/view/finance/modifyComValue.php";
            default :
                return "BaseConfig.php";
        }
    }
}
?>

