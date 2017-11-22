Ext.define('MetApp.view.Produccion.Reportes.ReporteOrdenesPorSector', {
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 300,
	autoShow: true,
	modal: true,
	alias: 'widget.ReporteOrdenesPorSector', 
	layout: 'vbox',
	title: 'Reporte OT por sector',
	
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
