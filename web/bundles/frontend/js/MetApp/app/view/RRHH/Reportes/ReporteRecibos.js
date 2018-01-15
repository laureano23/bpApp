Ext.define('MetApp.view.RRHH.Reportes.ReporteRecibos', {
	extend: 'Ext.window.Window',
	height: 345,
	width: 500,
	modal: true,
	autoShow: true,
	alias: 'widget.reporteRecibos',
	itemId: 'reporteRecibos',
	title: 'Recibos de sueldo',
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
					itemId: 'formPrintRecibos',
					items: [
						{
							xtype: 'fieldset',
							margin: '5 5 5 5',
							layout: 'anchor',
							defaults: {anchor: '100%'},
							title: 'Periodo',
							columnWidth: 0.55,
							items: [
								{
									xtype: 'radiogroup',
									itemId: 'pagoTipo',
									columns: 2,
		        					vertical: true,
									items: [
										{ boxLabel: '1° quincena', name: 'pagoTipo', inputValue: 1, checked: true },
										{ boxLabel: '2° quincena', name: 'pagoTipo', inputValue: 2 },
										{ boxLabel: 'Mes', name: 'pagoTipo', inputValue: 3 },
										{ boxLabel: 'Vacaciones', name: 'pagoTipo', inputValue: 4 },										
										{ boxLabel: 'SAC', name: 'pagoTipo', inputValue: 5 },
										{ boxLabel: 'Otros', name: 'pagoTipo', inputValue: 6 },	
										{ boxLabel: 'Premios 1°', name: 'pagoTipo', inputValue: 7 },
										{ boxLabel: 'Premios 2°', name: 'pagoTipo', inputValue: 8 },
										{ boxLabel: 'Liquidación final', name: 'pagoTipo', inputValue: 9 },
									]
								}
							]
						},						
						{
							xtype: 'numberfield',
							fieldLabel: 'Mes',
							name: 'mes'
						},
						{
							xtype: 'numberfield',
							fieldLabel: 'Año',
							name: 'anio'
						},
						{
							xtype: 'checkbox',
							fieldLabel: 'Compensatorio',
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
