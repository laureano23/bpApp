Ext.define('MetApp.view.RRHH.Reportes.ReporteResumenAguinaldo', {
	extend: 'Ext.window.Window',
	height: 190,
	width: 500,
	modal: true,
	autoShow: true,
	alias: 'widget.ReporteResumenAguinaldo',
	itemId: 'ReporteResumenAguinaldo',
	title: 'Resumen aguinaldo',
	layout: 'fit',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items:[
				{
					xtype: 'form',
					defaults: {
						margin: '5 5 5 5',
					},
					itemId: 'formResumenAguinaldo',
					items: [						
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									width: 200,
									fieldLabel: 'Mes desde',
									name: 'mesDesde'
								},
								{
									xtype: 'numberfield',
									margin: '0 0 0 5',
									width: 200,
									fieldLabel: 'Hasta',
									name: 'mesHasta'
								},
							]
						},					
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									width: 200,
									fieldLabel: 'Año desde',
									name: 'anioDesde'
								},
								{
									xtype: 'numberfield',
									margin: '0 0 0 5',
									width: 200,
									fieldLabel: 'Hasta',
									name: 'anioHasta'
								},
							]
						},
						{
							xtype: 'checkbox',
							fieldLabel: 'Compensatorio',
							uncheckedValue: 0,
							name: 'compensatorio'
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
