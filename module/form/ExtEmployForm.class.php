<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class ExtEmployForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function ExtEmployForm()
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
            case "toadd" :
                return "employ_add.php";
            default :
                return "index.php";
        }
    }
}
?>

