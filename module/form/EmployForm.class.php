<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class EmployForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function EmployForm()
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
            case "toimport" :
                return "employ_import.php";
            case "toEmlist" :
                return "employ_select.php";
            case "toEmExtlist" :
                return "employExt_select.php";
            case "toEmploy" :
                return "service/getEmpUpdate.php";
            case "toComplist" :
                return "company_list.php";
            case "toServiceEmlist" :
                return "service/emlist.php";
           default :
                return "index.php";
        }
    }
    }
?>

