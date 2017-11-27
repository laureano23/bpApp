Ext.define('MetApp.view.RRHH.Reportes.ReporteLibroSueldos', {
	extend: 'Ext.window.Window',
	width: 500,
	modal: true,
	autoShow: true,
	alias: 'widget.reporteLibroSueldos',
	itemId: 'reporteLibroSueldos',
	title: 'Libro de Sueldos',
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
					itemId: 'formPrintLibroSueldos',
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
									itemId: 'periodo',
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
										{ boxLabel: 'Todos', name: 'pagoTipo', inputValue: 99 },
									]
								}
							]
						},
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
