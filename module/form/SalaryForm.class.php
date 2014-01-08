<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class SalaryForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function SalaryForm()
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
            case "toUpload" :
                return "upload.php";
            case "salaryList" :
                return "salaryHtmlList.php";
            case "test" :
                return "test.php";
            case "sumlist":
            	return "sumSalaryList.php";
            case "toSalaryUpdate":
            	return "ext/view/salary/salary_update.php";
            case "salaryUpdate":
            	return "sal_edit.php";
            case "salDuiBi":
            	return "salDuiBi.php";
            case "duibiError":
            	return "duibiError.php";
            case "toImportSalPage":
            	return "importSal.php";
            case "salImportByCom":
            	return "salImportList.php";
            case "salFaByCom":
            	return "salFaList.php";
            case "salaryListById" :
                return "salaryList.php";
            case "toSalListExcel":
                return "salListExcel.php";
            case "toExtTest":
            	return "ext/extTest.php";
           default :
                return "index.php";
        }
    }
    }
?>

