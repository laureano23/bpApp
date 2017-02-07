Ext.define('MetApp.view.RRHH.TablaReliquidacion', {
	extend: 'Ext.window.Window',
	height: 315,
	width: 500,
	modal: true,
	autoShow: true,
	alias: 'widget.tablaReliquidacion',
	itemId: 'tablaReliquidacion',
	title: 'Reliquidacion',
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
					itemId: 'formReliquidacion',
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
										{ boxLabel: 'Premios', name: 'pagoTipo', inputValue: 7 },
									]
								}
							]
						},						
						{
							xtype: 'numberfield',
							allowBlank: false,
							fieldLabel: 'Mes',
							name: 'mes'
						},
						{
							xtype: 'numberfield',
							allowBlank: false,
							fieldLabel: 'Año',
							name: 'anio'
						},
						{
							xtype: 'checkbox',
							uncheckedValue: 0,
							fieldLabel: 'Compensatorio',
							name: 'compensatorio'
						},
						{
							xtype: 'button',
							text: 'Reliquidar',
							height: 30,
							width: 80,
							itemId: 'reliquidar',
							iconCls: 'reportes2'
						}
					]
				}						
			],			
		});		
		this.callParent();
	}
});
