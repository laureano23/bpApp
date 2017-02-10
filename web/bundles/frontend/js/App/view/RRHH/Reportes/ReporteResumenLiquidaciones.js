Ext.define('MetApp.view.RRHH.Reportes.ReporteResumenLiquidaciones', {
	extend: 'Ext.window.Window',
	height: 190,
	width: 250,
	modal: true,
	autoShow: true,
	alias: 'widget.ReporteResumenLiquidaciones',
	itemId: 'ReporteResumenLiquidaciones',
	title: 'Resumen liquidaciones',
	layout: 'fit',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items:[
				{
					xtype: 'form',
					border: false,
					frame: false,
					defaults: {
						margin: '5 5 5 5',
					},
					itemId: 'formResumenLiquidaciones',
					items: [						
						{
							xtype: 'numberfield',
							width: 200,
							fieldLabel: 'Mes',
							name: 'mes'
						},
						{
							xtype: 'numberfield',
							width: 200,
							fieldLabel: 'Año',
							name: 'anio'
						},
						{
							xtype: 'button',
							text: 'Imprimir',
							height: 30,
							width: 80,
							itemId: 'imprimir',
							iconCls: 'reportes2'
						}
					]
				}						
			],			
		});		
		this.callParent();
	}
});
