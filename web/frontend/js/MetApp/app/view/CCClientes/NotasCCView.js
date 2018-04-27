Ext.define('MetApp.view.CCClientes.NotasCCView' ,{
	extend: 'Ext.window.Window',
	modal: true,
	autoShow: true,
	alias: 'widget.NotasCCView',
	itemId: 'NotasCCView',
	title: 'Notas',
	layout: 'fit',
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					store: 'MetApp.store.Clientes.Clientes',
					margins: '5 5 5 5',
					border: false,
					items: [
						{
							xtype: 'textarea',
							width: 800,
							height: 300,
							name: 'notasCC',
							itemId: 'notasCC',
							fieldLabel: 'Notas'
						},
						{
							xtype: 'button',
							name: 'guardar',
							itemId: 'guardar',
							text: 'Guardar'
						}							
					]
				}
			]
		});
		this.callParent();
	}
});
