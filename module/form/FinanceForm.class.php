<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class FinanceForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function FinanceForm()
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
            case "financeFrist" :
                return "finance/finance_first.php";
            case "toSendSalary" :
                return "finance/shenPiFafang.php";
            case "toUpload":
            	return "finance/upload_salShenpi.php";
            case "salaryList" :
                return "finance/duiBiPage.php";
            case "salDuiBi" :
                return "finance/duiBiPage.php";
             case "duibiError" :
                return "duibiError.php";
             case "toImportSalPage":
            	return "finance/caiWuImport.php";
            case "salImportByCom":
            	return "finance/salImportList.php";
            case "salGeShuiByCom":
            	return "finance/salGeShuiList.php";
			case "geShuiTypeList" :
				return "finance/geShuiTypeList.php";
			case "searchcompanyListJosn" :
				return "testBeta.php";
			case "geShuiSum" :
				return "finance/geShuiSum.php";
			case "geShuiType" :
				return "finance/geShuiType.php";
            case "gongsijibie" :
				return "finance/gongsijibie.php";

            case "faPiaoDaoZhang" :
                return "finance/fapiaodaozhang.php";
			default :
				return "BaseConfig.php";
        }
    }
    }
?>

