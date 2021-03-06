Ext.define('MetApp.view.CCProveedores.BalanceView' ,{
	extend: 'Ext.window.Window',
	modal: true,
	autoShow: true,
	alias: 'widget.BalanceView',
	itemId: 'BalanceView',
	title: 'Balance de Cuenta',
	layout: 'fit',
	bodyStyle: {
		'background-color': 'white'
	},
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					margins: '5 5 5 5',
					border: false,
					defaults: {
						allowBlank: false
					},
					items: [
						{
							xtype: 'datefield',
							itemId: 'fecha',
							name: 'fecha',
							fieldLabel: 'Fecha',
							allowBlank: false
						},
						{
							xtype: 'numberfield',
							itemId: 'neto',
							name: 'neto',
							fieldLabel: 'Neto',
							decimalSeparator: '.'
						},
						{
							xtype: 'textareafield',
							name: 'observaciones',
							itemId: 'observaciones',
							fieldLabel: 'Observaciones',
							emptyText: 'Ingrese el motivo del balance',
						},
						{
							xtype: 'button',
							text: 'Guardar',
							itemId: 'guardar'
						}
					]
				}							
			]
		});
		this.callParent();
	}
});
