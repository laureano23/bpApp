Ext.define('MetApp.view.CCProveedores.NotasCCViewProv' ,{
	extend: 'Ext.window.Window',
	modal: true,
	autoShow: true,
	alias: 'widget.NotasCCViewProv',
	itemId: 'NotasCCViewProv',
	title: 'Notas',
	layout: 'fit',
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					store: 'MetApp.store.Proveedores.ProveedoresStore',
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
