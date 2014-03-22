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
            case "tosalaryTongji":
                return  "ext/view/salary/salary_Tongji.php";
            case "toSalaryComList":
                return "ext/view/salary/salaryComList.php";
            case "toSalTimeList":
                return "ext/view/salary/salTimeList.php";
            case "toErSalTimeList":
                return "ext/view/salary/erSalTimeList.php";
            case "tosearhSalaryNianTimeList";
            	return "ext/view/salary/nianSalaryTime.php";
            case "toCheckCompany":
            	return "ext/view/finance/checkCompany.php";
            case "toFinanceIndex":
                return "ext/view/finance/financeIndex.php";
            case "toServiceIndex":
                return "ext/view/service/ServiceIndex.php";
            case "toSocialSecurityIndex":
                return "ext/view/socialSecurity/socialsecurityIndex.php";
            case "toModifyPass";
                return "ext/view/salary/modifyPassword.php";
            case "toTaxInfo":
                return "ext/view/finance/taxInfo.php";
            case "toBusiness":
                return"ext/view/socialsecurity/changeBusiness.php";
            case "toInsurance":
                return "ext/view/socialsecurity/addInsurance.php";
            case "toPersonsalary":
                return "ext/view/socialsecurity/addSalary.php";
            case "todemo":
                return"ext/view/demo/demoIndex.php";
            case "toTeshushenfen":
                return"finance/canjirenshezhi.php";
            case "contractInfo";
                return "ext/view/service/contractInfo.php";
            case "toServiceApproval":
                return "ext/view/service/salaryServiceApproval.php";
            case "toFinanceApproval":
                return "ext/view/finance/salaryfinanceApproval.php";
           default :
                return "BaseConfig.php";
        }
    }
    }
?>

