Ext.define('MetApp.view.RRHH.DatosLiquidacion', {
	extend: 'Ext.window.Window',
	height: 300,
	width: 600,
	modal: true,
	autoShow: true,
	alias: 'widget.datosLiquidacion',
	id: 'datosLiquidacion',
	itemId: 'datosLiquidacion',
	title: 'Datos de liquidacion',
	layout: 'vbox',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items:[
				{
					xtype: 'form',
					border: false,
					width: 580,
					height: 200,
					layout: 'column',
					items: [
						{
							xtype: 'fieldset',
							margin: '5 5 5 5',
							layout: 'anchor',
							defaults: {anchor: '100%'},
							title: 'Pago',
							columnWidth: 0.45,
							items: [
								{
									xtype: 'datefield',
									itemId: 'fechaDesde',
									name: 'fechaDesde',
									allowBlank: false,
									fieldLabel: 'Desde',
									format: 'd/m/Y'
								},
								{
									xtype: 'datefield',
									itemId: 'fechaHasta',
									name: 'fechaHasta',
									allowBlank: false,
									fieldLabel: 'Hasta',
									format: 'd/m/Y'
								},
								{
									xtype: 'datefield',
									name: 'fechaPago',
									itemId: 'fechaPago',
									allowBlank: false,
									fieldLabel: 'Fecha de pago',
									format: 'd/m/Y'
								},
								{
									xtype: 'combobox',	
									itemId: 'banco',
									valueField: 'id',								
									displayField: 'nombre',
									name: 'banco',
									allowBlank: false,
									fieldLabel: 'Banco',
									store: 'MetApp.store.Personal.BancosStore'
								},
								{
									xtype: 'checkbox',
									name: 'compensatorio',
									itemId: 'compensatorio',
									fieldLabel: 'Compensatorio'
								}
							]
						},
						{
							xtype: 'fieldset',
							margin: '5 5 5 5',
							layout: 'anchor',
							defaults: {anchor: '100%'},
							title: 'Pago tipo',
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
								},
								{
									xtype: 'textfield',
									allowBlank: false,
									name: 'descripcion',
									itemId: 'descripcion',
									fieldLabel: 'Descripcion'
								}
							]
						}						
					]	
				},
				{
					xtype: 'container',
					layout: 'hbox',
					height: 100,
					items: [
						{
							xtype: 'button',
							itemId: 'nuevaLiquidacion',
							margin: '5 5 5 5',
							text: 'Aceptar'
						}
					]
				}
				

			]			
		});		
		this.callParent();
	}
});

