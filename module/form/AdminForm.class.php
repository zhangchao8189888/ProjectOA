<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class AdminForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function AdminForm()
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
        	case "betaTest":
        		return "datepicker.php";
            case "tolist" :
                return "admin_list.php";
            case "login" :
                return "login.php";
            case "index" :
                return "employ_add.php";
            case "toOpLog" :
                return "log_list.php";
            case "service":
            	return  "ext/view/Service/ServiceIndex.php";
            case "toFinance":
            	return "ext/view/finance/financeIndex.php";
           	case "modifyPass":
            	return "modifyPassword.php";
           default :
                return "BaseConfig.php";
        }
    }
    }
?>

