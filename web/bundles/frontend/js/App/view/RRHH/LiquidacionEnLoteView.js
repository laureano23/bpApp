Ext.define('MetApp.view.RRHH.LiquidacionEnLoteView', {
	extend: 'Ext.window.Window',
	modal: true,	
	width: 600,
	height: 280,
	layout: 'fit',
	border: false,
	itemId: 'LiquidacionEnLoteView',
	alias: 'widget.LiquidacionEnLoteView',	
	autoShow: true,
	title: 'Tabla de Liquidacion En Lote',
		
	initComponent: function(){
		var me = this;		
		
		var storePeriodo = Ext.create('Ext.data.Store', {
		    fields: ['id', 'periodo'],
		    data : [
		        {"id":"0", "periodo":"Quincenal"},
		        {"id":"1", "periodo":"Mensual"},
		    ]
		});
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					width: 500,
					border: false,
					frame: false,
					margins: '5 5 5 5',
					name: 'formLote',
					defaults: {
						allowBlank: false,
					},
					layout: 'vbox',
					items: [
						{
							xtype: 'filefield',
							name: 'archivoLote',
							fieldLabel: 'Archivo',
							labelWidth: 50,
					        msgTarget: 'side',					        
					        width: 500,
					        buttonText: 'Seleccion archivo...',
					        
						},
						{
							xtype: 'fieldset',
							width: 450,
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
										{ boxLabel: 'Premios 1° quincena', name: 'pagoTipo', inputValue: 7 },
										{ boxLabel: 'Premios 2° quincena', name: 'pagoTipo', inputValue: 8 },
									]
								},
							]
						},								
						{
							xtype: 'container',
							layout: 'hbox',
							defaults:{
								width: 170,
								labelWidth: 35,
								allowBlank: false,
								margins: '0 5 0 0'
							},
							items: [
								{
									xtype: 'numberfield',											
									fieldLabel: 'Año',
									name: 'anio'
								},
								{
									xtype: 'combobox',
									forceSelection: true,
									queryMode: 'local',
									valueField: 'mesNum',
									displayField: 'mes',
									fieldLabel: 'Mes',
									name: 'mes',
									store: {
										fields: ['mesNum', 'mes'],
										data: [
											{ 'mesNum': 1, 'mes': 'Enero' },
											{ 'mesNum': 2, 'mes': 'Febrero' },
											{ 'mesNum': 3, 'mes': 'Marzo' },
											{ 'mesNum': 4, 'mes': 'Abril' },
											{ 'mesNum': 5, 'mes': 'Mayo' },
											{ 'mesNum': 6, 'mes': 'Junio' },
											{ 'mesNum': 7, 'mes': 'Julio' },
											{ 'mesNum': 8, 'mes': 'Agosto' },
											{ 'mesNum': 9, 'mes': 'Setiembre' },
											{ 'mesNum': 10, 'mes': 'Octubre' },
											{ 'mesNum': 11, 'mes': 'Noviembre' },
											{ 'mesNum': 12, 'mes': 'Diciembre' },
										]
									}
								},
								{
									xtype: 'datefield',
									labelWidth: 80,
									width: 200,
									fieldLabel: 'Fecha pago',
									name: 'fechaPago'
								},
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								margins: '5 5 0 0',
								allowBlank: false,
							},
							items: [
								{
									xtype: 'textfield',
									name: 'descripcion',
									fieldLabel: 'Descripcion'
								},
								{
									xtype: 'combobox',
									width: 200,
									labelWidth: 50,
									forceSelection: true,
									itemId: 'comboBancos',
									queryMode: 'local',
									valueField: 'id',
									displayField: 'nombre',
									fieldLabel: 'Banco',
									name: 'banco',
									store: 'MetApp.store.Finanzas.BancosStore' 
								},
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								margins: '5 5 0 0',
							},
							items: [
								{
									xtype: 'combobox',									
									forceSelection: true,
									name: 'tipoLiquidacion',
									fieldLabel: 'Tipo liquidación',
									store: storePeriodo,
									valueField: 'id',
									displayField: 'periodo'
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
							xtype: 'button',
							itemId: 'liquidarLote',
							text: 'Liquidar',							
						},								
					]
				}
			]
		})
		
		me.callParent();
	}
});
