Ext.Loader.setConfig({
    enabled: true
});

Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.selection.CheckboxModel'
])
Ext.onReady(function(){
    var bt3 = Ext.create("Ext.Button", {
        renderTo: Ext.get("li3").dom,
        text: "添加管理公司",
        id: "bt3"
    });
    bt3.on("click", function () {
        comListStore.load();
        adminCompanyWindow.show();
    });
    adminCompanyListstore.load();
    var grid2 = Ext.create('Ext.grid.Panel', {
        id: 'grid2',
        store: adminCompanyListstore,
        columns: [
            {text: "工资日期", dataIndex: 'salDate'},
            {text: "工资操作日期",dataIndex: 'op_salaryTime',width: 150},
            {
                text: "单位名称",
                renderer : function(val, cellmeta, record) {
                    //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                    return '<a href="#" onclick=getEmploy("'+val+'") >'+val+'</a>';
                },
                dataIndex: 'company_name'},//<a href="#" onclick="getEmploy('<?php  echo $row['company_name'];?>')" target="_self">
            {
                text: "一次工资",
                renderer : function(val, cellmeta, record) {
                    if (val == 0) {
                        //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                        return '<a href="#" title="做工资" onclick=makeSal('+record.data['companyId']+',"'+record.data['salDate']+'","first")><font color="red">未做工资</font></a>';
                    } else if (val > 0) {
                        return '<font color="green" title="查看工资" _salTimeId="'+record.data['salTimeid']+'"  id="check">已做工资</font>';
                    }
                    return val;
                },
                dataIndex: 'salStat'
            },
            {
                text: "二次工资",

                renderer : function(val, cellmeta, record) {
                    if (val == 0) {
                        //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                        return '<a href="#" title="做二次工资" onclick=makeSal('+record.data['companyId']+',"'+record.data['salDate']+'","second")><font color="red">无</font></a>';
                    } else if (val > 0) {
                        return '<font color="green" title="查看工资" _salTimeId="'+record.data['salOrStat']+'" _salTime="'+record.data['salDate']+'" _companyId="'+record.data['companyId']+'"  id="check">已做二次工资</font>';
                    }
                    return val;
                },
                dataIndex: 'salOrStat'
            },
            {
                text: "年终奖",
                renderer : function(val, cellmeta, record) {
                    if (val == 0) {
                        //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                        return '<a href="#" title="做年终奖" onclick=makeSal('+record.data['companyId']+',"'+record.data['salDate']+'","nian")><font color="red">无</font></a>';
                    } else if (val > 0) {
                        return '<font color="green" title="查看年终奖" _salTimeId="'+record.data['salNianStat']+'"  id="check">已做年终奖</font>';
                    }
                    return val;
                },
                dataIndex: 'salNianStat'
            },
            {
                text: "发票情况",
                renderer : function(val, cellmeta, record) {
                    if (val == 0) {
                        //return '<font color="red"   title="做工资" _comanyId="'+record.data['companyId']+'" _comanyName="'+record.data['company_name']+'" _salDate="'+record.data['salDate']+'" id="make">未做工资</font>';
                        return '<a href="#" title="添加发票" onclick=addFa()><font color="red">添加发票</font></a>';
                    } else if (val > 0) {
                        return '<font color="green" title="查看发票情况"  id="check">已添加发票</font>';
                    }
                    return val;
                },
                dataIndex: 'fastat'},
            {
                text: "审批状态",
                renderer : function(val, cellmeta, record) {
                    if(val == 0){
                        return '<font color=red>申请发放审批中</font>';
                    }else if(val == 1){
                        return '<font color=green>批准发放</font>';
                    }else if(val == 2){
                        return '<a href="#" onclick="send('+record.data['salTimeid']+')" target="_self"><font color=red>未批准发放</font></a>';
                    }else if(val == -1){
                        return '<font color=red>未批准发放</font>';
                    }
                    return '<a href="#" onclick="send('+record.data['salTimeid']+')" target="_self">'+val+'</font></a>';
                },
                dataIndex: 'fa_state'},
            {text: "添加管理时间", dataIndex: 'opTime'},
            {text: "备注", dataIndex: 'mark'} ],
        listeners: {
            'cellclick': function(iView, iCellEl, iColIdx, iStore, iRowEl, iRowIdx, iEvent) {
                var rowEl=Ext.get(iEvent.getTarget());
                var zRec = iView.getRecord(iRowEl);
                var type = rowEl.getAttribute('id');
                if(iColIdx == 3||iColIdx == 4||iColIdx == 5){
                    if(type == 'make'){
                        var comId = rowEl.getAttribute('_comanyId');
                        makeSalWin('做工资');
                    }else if(type == 'check'){
                        var timeId = rowEl.getAttribute('_salTimeId');
                        checkSalWin("查看工资",iColIdx,timeId,rowEl);
                    }
                }

            }
        },
        columnLines: true,
        width: 1000,
        height: 400,
        frame: true,
        title: '客服管理公司首页',
        iconCls: 'icon-grid',
        margin: '0 0 20 0',
        renderTo: 'tableList'
    });
});