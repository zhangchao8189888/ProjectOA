<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class ExtFinanceForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function ExtSalaryForm()
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
            case "toSalTimeList":
                return "ext/view/salary/salTimeList.php";
          	case "tosearhSalaryNianTimeList";
                return "nianSalaryTime.php";
            default :
                return "BaseConfig.php";
        }
    }
}
?>

