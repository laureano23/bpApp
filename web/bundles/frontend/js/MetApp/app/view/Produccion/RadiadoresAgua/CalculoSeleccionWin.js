Ext.define('MetApp.view.Produccion.RadiadoresAgua.CalculoSeleccionWin', {
	extend: 'Ext.window.Window',
	alias: 'widget.calculoSeleccion',
	itemId: 'calculoSeleccion',
	layout: 'fit',
	modal: true,
	autoShow: true,
	title: 'Calculo y Seleccion',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'container',
					border: 2,
					layout: 'column',
					items: [
						{
							title: 'Calculo',
							border: false,
							columnWidth: 0.60,
							items: [
								{
									xtype: 'form',
									border: false,									
									itemId: 'calculoSeleccionForm',
									defaults: {
										margin: '5, 5, 5, 5',
										width: 200,
									},
									items: [
										{
											xtype: 'combobox',
											width: 300,
											name: 'tipoFluido',
											itemId: 'tipoFluido',
											fieldLabel: 'Fluido:',							
											store: ['Aceite Hidráulico ISO VG 10', 'Aceite Hidráulico ISO VG 15', 'Aceite Hidráulico ISO VG 22', 'Aceite Hidráulico ISO VG 32', 'Aceite Hidráulico ISO VG 46', 'Aceite Hidráulico ISO VG 68', 'Aceite Hidráulico ISO VG 100', 'Aceite Hidráulico ISO VG 150', 'Agua-etilenglicol 50%', 'Agua '],
											value: 'Agua '
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'T° entrada:',
											name: 'tEntrada',
											itemId: 'tEntrada'
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'T° salida:',
											name: 'tSalida',
											itemId: 'tSalida'
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'T° ambiente:',
											name: 'tAmbiente',
											itemId: 'tAmbiente'
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'Caudal:',
											name: 'caudal',
											itemId: 'caudal'
										},
										{
											xtype: 'button',
											text: 'Calcular',
											itemId: 'btnCalculo',
											action: 'calculoSeleccion'
										}
									]
								}								
							]	
						},
						{
							title: 'Resultados',
							border: false,
							columnWidth: 0.40,
							defaults: {
								margin: '5, 5, 5, 5',
								width: 300
							},
							items: [
								{
									xtype: 'numberfield',
									decimalPrecision: 1,		
									itemId: 'potenciaSolicitada',							
									fieldLabel: 'Potencia solicitada (Kw):',
									labelWidth: 200,
									disabled: true,
									disabledCls: 'myDisabledClass'
								},
								{
									xtype: 'textfield',
									width: 400,	
									itemId: 'equipo',								
									fieldLabel: 'Modelo seleccionado:',									
									labelWidth: 200,
									disabled: true,
									disabledCls: 'myDisabledClass'
								},
								{
									xtype: 'numberfield',
									decimalPrecision: 1,			
									itemId: 'potenciaDisipada',						
									fieldLabel: 'Potencia disipada (kW):',
									labelWidth: 200,
									disabled: true,
									disabledCls: 'myDisabledClass'
								},
								{
									xtype: 'numberfield',
									decimalPrecision: 1,
									itemId: 'tSalidaRtdo',								
									fieldLabel: 'T° salida (°C):',
									labelWidth: 200,
									disabled: true,
									disabledCls: 'myDisabledClass'
								},
								{
									xtype: 'numberfield',
									decimalPrecision: 1,
									itemId: 'tReservaPotencia',									
									fieldLabel: 'Reserva de potencia:',
									labelWidth: 200,
									disabled: true,
									disabledCls: 'myDisabledClass'
								},
								{
									xtype: 'numberfield',
									decimalPrecision: 1,
									itemId: 'perdidaCarga',										
									fieldLabel: 'Perdida de carga (mbar):',
									labelWidth: 200,
									disabled: true,
									disabledCls: 'myDisabledClass'
								}
							]
						}
					]
				}				
			]
		});
		this.callParent();
	}		
});
