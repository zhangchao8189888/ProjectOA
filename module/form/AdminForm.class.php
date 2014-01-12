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
            	return  "ext/ServiceFirst.php";
            	//return "service/service_frist.php";
            case "toFinance":
            	return "finance/finance_first.php";
           	case "modifyPass":
            	return "modifyPassword.php";
           default :
                return "BaseConfig.php";
        }
    }
    }
?>

