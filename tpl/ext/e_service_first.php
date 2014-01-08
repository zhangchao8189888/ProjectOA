<?php 
$comlist=$form_data['comList'];
$errorlist=$form_data['error'];
$searchType=$form_data['searchType'];
$comlist=json_encode($comlist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>{$title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <link href="tpl/ext/lib/prettify/prettify.css" type="text/css" rel="stylesheet"/>
        <link href="tpl/ext/resources/KitchenSink-all.css" rel="stylesheet"/>
            <link href="common/css/admin.css" rel="stylesheet" type="text/css" />
   <link href="common/css/validator.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="common/ext/ext-all-debug.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="common/ext/locale/ext-lang-zh_CN.js" charset="utf-8"></script>
        <script type="text/javascript">
        Ext.Loader.setConfig({
            enabled: true
        });

        Ext.require([
            'Ext.grid.*',
            'Ext.data.*',
            'Ext.selection.CheckboxModel'
        ]);

        Ext.onReady(function(){

            var getLocalStore = function() {
                return Ext.create('Ext.data.Store', {//创建一个store 
                	fields: ['salDate', 'op_salaryTime', 'company_name','salStat'], 
                	data: { 'items': <?php echo $comlist;?> 
                	}, 
                	proxy: { 
                	type: 'memory', 
                	reader: { 
                	type: 'json', 
                	root: 'items' 
                	} 
                	} 
                	});
            };
            
            Ext.define('KitchenSink.view.layout.Accordion', {
                extend: 'Ext.panel.Panel',
                
                requires: [
                    'Ext.layout.container.Accordion',
                    'Ext.grid.*',
                    'KitchenSink.model.Company'
                ],
                xtype: 'layout-accordion',
                
                
                layout: 'accordion',
                width: 500,
                height: 400,
                defaults: {
                    bodyPadding: 10
                },
                
                initComponent: function() {
                    Ext.apply(this, {
                        items: [{
                            bodyPadding: 0,
                            xtype: 'grid',
                            title: 'Array Grid (Click header to collapse)',
                            hideCollapseTool: true,
                            columnLines: true,
                            viewConfig: {
                                stripeRows: true
                            },
                            store: new Ext.data.Store({
                                model: KitchenSink.model.Company,
                                data: KitchenSink.data.DataSets.company
                            }),
                            columns: [{
                                text     : 'Company',
                                flex     : 1,
                                sortable : false,
                                dataIndex: 'company'
                            }, {
                                text     : 'Price',
                                width    : 75,
                                sortable : true,
                                renderer : 'usMoney',
                                dataIndex: 'price'
                            }, {
                                text     : 'Change',
                                width    : 75,
                                sortable : true,
                                renderer : this.changeRenderer,
                                dataIndex: 'change'
                            }, {
                                text     : '% Change',
                                width    : 90,
                                sortable : true,
                                renderer : this.pctChangeRenderer,
                                dataIndex: 'pctChange'
                            }]
                        }, {
                            title: 'Accordion Item 2',
                            html: 'Empty'
                        }, {
                            title: 'Accordion Item 3',
                            html: 'Empty'
                        }, {
                            title: 'Accordion Item 4',
                            html: 'Empty'
                        }, {
                            title: 'Accordion Item 5',
                            html: 'Empty'
                        }]
                    });
                    this.callParent();
                },
                
                changeRenderer: function(val) {
                    if (val > 0) {
                        return '<span style="color:green;">' + val + '</span>';
                    } else if(val < 0) {
                        return '<span style="color:red;">' + val + '</span>';
                    }
                    return val;
                },
                
                pctChangeRenderer: function(val){
                    if (val > 0) {
                        return '<span style="color:green;">' + val + '%</span>';
                    } else if(val < 0) {
                        return '<span style="color:red;">' + val + '%</span>';
                    }
                    return val;
                }
            })
        });
        
        </script>
  </head>
  <body>
  <?php include("tpl/commom/top.html"); ?>
    <div id="main" style="min-width:960px">
    <?php include("tpl/commom/left.php"); ?>
    <div id="right">

    </div>
    </div>
  </body>
</html>