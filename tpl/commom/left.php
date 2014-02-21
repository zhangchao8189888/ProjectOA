<?php
$admin = $_SESSION ["admin"];
if ($admin ['admin_type'] == 1 || $admin ['admin_type'] == 2) {
	?>
<div id="left">
	<div class="top">功能菜单</div>
	<div class="menu">
		<div class="title">系统管理</div>
		<div class="item">
			<a href="index.php?action=Admin&mode=input">添加管理员</a>
		</div>
		<div class="item">
			<a href="index.php?action=Admin&mode=modifyPass">修改密码</a>
		</div>
		<div class="item">
			<a href="index.php?action=Admin&mode=toOpLog">日志查询</a>
		</div>
		<div class='item'>
			<a href='index.php?action=Ext&mode=toCheckCompany'>单位审批</a>
		</div>
		<div class="title">员工管理</div>
		<div class="item">
			<a href="index.php?action=Employ&mode=input">添加员工</a>
		</div>
		<div class="item">
			<a href="index.php?action=Employ&mode=toimport">导入员工</a>
		</div>
<!--		<div class="item">-->
<!--			<a href="index.php?action=Employ&mode=toEmlist">员工列表查询</a>-->
<!--		</div>-->
        <div class="item">
            <a href="index.php?action=Employ&mode=toEmlistExt">员工列表查询</a>
        </div>
		<div class="item">
			<a href="index.php?action=Employ&mode=toComplist">单位列表查询</a>
		</div>
		<div class="title">工资管理</div>
		<div class="item">
			<a href="index.php?action=Salary&mode=input">文件上传</a>
		</div>
		<div class="item">
			<a href="index.php?action=Ext&mode=toSalTimeList">工资查询</a>
		</div>
		<!-- 				            <div class="item"><a href="index.php?action=SaveSalary&mode=searchSalaryTime">工资查询</a></div> -->
		<div class="item">
			<a href="index.php?action=Ext&mode=tosearhSalaryNianTimeList">年终奖查询</a>
		</div>
		<!-- 				            <div class="item"><a href="index.php?action=SaveSalary&mode=searchErSalaryTime">二次工资查询</a></div> -->
		<div class="item">
			<a href="index.php?action=Ext&mode=toErSalTimeList">二次工资查询</a>
		</div>
		<div class="item">
			<a href="index.php?action=Salary&mode=toSalaryUpdate">个人工资查询</a>
		</div>
		<div class="item">
			<a href="index.php?action=Finance&mode=searchGeShuiSum">个税统计</a>
		</div>

		<div class="item">
			<a href="index.php?action=Finance&mode=searchGeShuiType">个税类型</a>
		</div>
		<div class="title">发票管理</div>
        <div class="item">
            <a href="index.php?action=Finance&mode=searchFaPiaoDaoZhang">发票到账</a>
        </div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toAddInvoice">添加发票</a>
		</div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toAddCheque">添加支票/到账支票</a>
		</div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toSendSalary">工资发放</a>
		</div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toBillList">工资票据查询</a>
		</div>
		<div class="title">工资统计管理</div>
        <div class="item">
            <a href="index.php?action=SalaryBill&mode=toShebaoExt">社保查看</a>
        </div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toSalaryTongji">工资统计</a>
		</div>
<!--        <div class="item">-->
<!--            <a href="index.php?action=SalaryBill&mode=toSalaryTongjiExt">工资统计NEW</a>-->
<!--        </div>-->
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=salaryComList">工资查看</a>
		</div>

	</div>
	<img id="imgload" style="display: none" src="common/image/load.gif" />
</div>
<?php
} elseif ($admin ['admin_type'] == 3) {
	?>
<div id="left">
	<div class="top">功能菜单</div>
	<div class='menu'>
		<div class='title'>客服管理</div>
		<div class='item'>
			<a href='index.php?action=Ext&mode=toServiceIndex'>主页</a>
		</div>
        <div class="item">
            <a href="index.php?action=Ext&mode=toBusiness">业务变更</a>
        </div>
        <div class="item">
            <a href="index.php?action=SalaryBill&mode=toShebaoExt">社保查看</a>
        </div>
		<div class="item">
			<a href="index.php?action=Employ&mode=toEmlist">员工列表查询</a>
		</div>
		<div class='item'>
			<a href='index.php?action=Admin&mode=toOpLog'>日志查询</a>
		</div>
		<div class="item">
			<a href="index.php?action=Admin&mode=modifyPass">修改密码</a>
		</div>
		<div class="title">工资管理</div>
		<div class="item">
			<a href="index.php?action=Salary&mode=input">文件上传</a>
		</div>
		<div class="item">
			<a href="index.php?action=Ext&mode=toSalTimeList">工资查询</a>
		</div>
		<!-- 				            <div class="item"><a href="index.php?action=SaveSalary&mode=searchSalaryTime">工资查询</a></div> -->
		<div class="item">
			<a href="index.php?action=Ext&mode=tosearhSalaryNianTimeList">年终奖查询</a>
		</div>
		<div class="item">
			<a href="index.php?action=Ext&mode=toErSalTimeList">二次工资查询</a>
		</div>
		<!-- 				            <div class="item"><a href="index.php?action=SaveSalary&mode=searchErSalaryTime">二次工资查询</a></div> -->
		<div class="item">
			<a href="index.php?action=Salary&mode=toSalaryUpdate">个人工资查询</a>
		</div>
		<div class="title">发票管理</div>
        <div class="item">
            <a href="index.php?action=Finance&mode=searchFaPiaoDaoZhang">发票到账</a>
        </div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toAddInvoice">添加发票</a>
		</div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toAddCheque">添加支票/到账支票</a>
		</div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toBillList">工资票据查询</a>
		</div>
		<!-- 				        <div class="title">Ext</div> -->
		<!-- 				             <div class="item"><a href="index.php?action=Ext&mode=toExtTable">ExtList</a></div> -->
		<!-- 						     <div class="item"><a href="index.php?action=Ext&mode=toExtJson">ExtJson</a></div> -->
		<!-- 						     <div class="item"><a href="index.php?action=Ext&mode=toExtPage">ExtPage</a></div> -->
		<!-- 						     <div class="item"><a href="index.php?action=Ext&mode=toExtDongTai">ExtDongTai</a></div> -->
		<!--                              <div class="item"><a href="index.php?action=Ext&mode=toExtTest">ExtTest</a></div> -->
		<!--                         <div class="title">newOA</div> -->
		<!--                             <div class="item"><a href="index.php?action=Ext&mode=toSalTimeList">工资查询</a></div> -->
		<!--                             <div class="item"><a href="index.php?action=Ext&mode=toErSalTimeList">二次工资查询</a></div> -->
		<!--                             <div class="item"><a href="index.php?action=Ext&mode=toExtJson">ExtJson</a></div> -->
		<!--                             <div class="item"><a href="index.php?action=Ext&mode=toExtPage">ExtPage</a></div> -->
		<!--                             <div class="item"><a href="index.php?action=Ext&mode=toExtDongTai">ExtDongTai</a></div> -->
		<!--                             <div class="item"><a href="index.php?action=Ext&mode=toExtTest">ExtTest</a></div> -->
	</div>
	<img id="imgload" style="display: none" src="common/image/load.gif" />
</div>
<?php
} elseif ($admin ['admin_type'] == 4) {
	?>
<div id="left">
	<div class="top">功能菜单</div>
	<div class='menu'>
		<div class='title'>财务管理</div>
        <div class='item'>
            <a href='index.php?action=Ext&mode=toFinanceIndex'>主页</a>
        </div>
		<!--
        <div class='item'>
			<a href='index.php?action=Finance&mode=finance_frist'>财务首页</a>
		</div>
        <div class='item'>
            <a href='index.php?action=ExtFinance&mode=toFinaceFirst'>查看管理公司</a>
        </div>
        -->
		<div class='item'>
			<a href='index.php?action=Ext&mode=toCheckCompany'>单位审批</a>
		</div>
		<div class='item'>
			<a href='index.php?action=Finance&mode=getSalaryTimeListByComId'>工资发放审批</a>
		</div>
		<div class='item'>
			<a href='index.php?action=Finance&mode=salCaiwuImport'>财务文件导出</a>
		</div>
		<div class='item'>
			<a href='index.php?action=Admin&mode=toOpLog'>日志查询</a>
		</div>
		<div class="item">
			<a href="index.php?action=Admin&mode=modifyPass">修改密码</a>
		</div>
		<div class="title">工资管理</div>
		<div class="item">
			<a href="index.php?action=Salary&mode=input">文件上传</a>
		</div>
		<div class="title">工资统计管理</div>
        <div class="item">
            <a href="index.php?action=Ext&mode=tosalaryTongji">工资统计</a>
        </div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=salaryComList">工资查看</a>
		</div>
		<div class="item">
			<a href="index.php?action=Ext&mode=toSalTimeList">工资查询</a>
		</div>
        <div class="item">
            <a href="index.php?action=Ext&mode=toSalaryComList">年度工资查询</a>
        </div>
		<div class="item">
			<a href="index.php?action=Ext&mode=tosearhSalaryNianTimeList">年终奖查询</a>
		</div>
		<div class="item">
<!-- 			<a href="index.php?action=SaveSalary&mode=searchErSalaryTime">二次工资查询</a> -->
				<a href="index.php?action=Ext&mode=toErSalTimeList">二次工资查询</a>
		</div>
        <div class="item">
            <a href="index.php?action=Ext&mode=toTaxInfo">个税查看</a>
        </div>
		<div class="item">
			<a href="index.php?action=Finance&mode=searchGeShuiSum">个税统计</a>
		</div>
		<div class="item">
			<a href="index.php?action=Finance&mode=searchGeShuiType">个税类型</a>
		</div>
		<div class="title">发票管理</div>
        <div class="item">
            <a href="index.php?action=Finance&mode=searchFaPiaoDaoZhang">发票到账</a>
        </div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toAddInvoice">添加发票</a>
		</div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toAddCheque">添加支票/到账支票</a>
		</div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toSendSalary">工资发放</a>
		</div>
		<div class="item">
			<a href="index.php?action=SalaryBill&mode=toBillList">工资票据查询</a>
		</div>

	</div>
	<img id="imgload" style="display: none" src="common/image/load.gif" />
</div>
<?php
} elseif ($admin ['admin_type'] == 5) {
    ?>
    <div id="left">
        <div class="top">功能菜单</div>
        <div class='menu'>
            <div class='title'>社保管理</div>
            <div class='item'>
                <a href='index.php?action=Ext&mode=toSocialSecurityIndex'>主页</a>
            </div>
            <div class="item">
                <a href="index.php?action=Ext&mode=toBusiness">业务变更</a>
            </div>
        </div>
        <img id="imgload" style="display: none" src="common/image/load.gif" />
    </div>
<?php
}
?>