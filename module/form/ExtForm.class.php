<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class ExtForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function ExtForm()
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
            case "toExtTable":
            	return "ext/extCommon.php";
            case "toExtJson":
            	return "ext/extJson.php";
            case "toExtPage":
            	return "ext/extPage.php";
            case "toExtDongTai":
            	return "ext/extDongTai.php";
            case "toExtTest":
                return "ext/view/viewTest.php";
            case "toSalTimeList":
                return "ext/view/salary/salTimeList.php";
            case "toErSalTimeList":
                return "ext/view/salary/erSalTimeList.php";
            case "tosearhSalaryNianTimeList";
            	return "nianSalaryTime.php";
            case "toCheckCompany":
            	return "ext/view/finance/checkCompany.php";
           default :
                return "BaseConfig.php";
        }
    }
    }
?>

