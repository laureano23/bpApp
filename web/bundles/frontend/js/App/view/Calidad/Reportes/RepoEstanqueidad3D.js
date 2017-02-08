Ext.define('MetApp.view.Calidad.Reportes.RepoEstanqueidad3D', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 300,
	autoShow: true,
	modal: true,
	alias: 'widget.RepoEstanqueidad3D',
	layout: 'vbox',
	title: 'Reporte estanqueidad 3D',
	
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
							itemId: 'desde',
							name: 'desde',
							fieldLabel: 'Desde',
							labelWidth: 40
						},
						{
							xtype: 'datefield',
							itemId: 'hasta',
							name: 'hasta',
							fieldLabel: 'Hasta',
							labelWidth: 40
						},
						{
							xtype: 'button',
							itemId: 'printBtn',
							action: 'printBtn',
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
