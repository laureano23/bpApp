Ext.define('MetApp.view.Reportes.RepoSaldoDeudor', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 300,
	autoShow: true,
	modal: true,
	alias: 'widget.RepoSaldoDeudor',
	layout: 'vbox',
	title: 'Reporte Saldo Deudor',
	itemId: 'RepoSaldoDeudor',
	listeners: {
		render: {
			fn: function(el){
				el.queryById('vencimiento').focus('', 50);
			}
		}
	},
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					itemId: 'fechaForm',
					width: 295,
					border: false,
					layout: {
						type: 'vbox',
						align: 'center'	
					},
					defaults: {
						margin: '2 2 2 2',	
					},
					fieldDefaults: {
						allowBlank: false
					},
					items: [
						{
							xtype: 'datefield',
							allowBlank: false,
							itemId: 'vencimiento',
							format: 'd/m/Y',
							submitFormat: 'd/m/Y',
							name: 'vencimiento',
							fieldLabel: 'Vencimiento',
							labelWidth: 80
						},
						{
							xtype: 'button',
							itemId: 'printDateReport',
							action: 'printDateReport',
							height: 50,
							width: 50,
							iconCls: 'reportes'
						}
					]
				}		
			]
		});
		this.callParent();
	}
});
