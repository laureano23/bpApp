Ext.define('MetApp.view.Produccion.CalculoRadiadores.CalculoRadAceiteForm',{
	extend: 'Ext.form.Panel',
	alias: 'widget.calculoradaceiteform',
	items:
	[
		{
			xtype: 'form',		
			store: 'Produccion.CalculoRadiadores.Calculo',			
			itemId: 'fCalculoAiAc',
			items: [
				{
					xtype: 'container',
					defaults: {
						readOnly: true
					},
					layout: {
						type: 'hbox',
						padding: '5 5 5 5'
					},
					items: [
						{
							xtype: 'textfield',
							name: 'codigo',
							fieldLabel: 'Panel a Calcular:',						
						},
						{
							xtype: 'textfield',
							name: 'id',
							fieldLabel: 'Id',
							hidden: true
						},
						{
							xtype: 'button',
							iconCls: 'search',
							itemId: 'panelToForm',
							action: 'panelToForm'
						},
						{
							xtype: 'textfield',
							name: 'descripcion',
							width: 600
						}
					]				
				},
				{
					xtype: 'container',
					defaults: {
						width: 200,
						labelWidth: 120							
					},
					layout: {
						type: 'hbox',
						padding: '5 5 0 5',								
					},
					items: [
						{
							xtype: 'numberfield',
							name: 'apoyoTapas',
							itemId: 'apoyoTapas',
							fieldLabel: 'Apoyo de tapas (A):',
							labelWidth: 130
						},
						{
							xtype: 'numberfield',
							name: 'prof',
							itemId: 'prof',
							fieldLabel: 'Profundidad (P):'
						},
						{
							xtype: 'numberfield',
							name: 'ancho',
							itemId: 'ancho',
							fieldLabel: 'Ancho (C):'
						},
						{
							xtype: 'numberfield',
							fieldStyle: {
					            color: 'red'
					       },
							readOnly: true,
							name: 'anchoCalc',
							itemId: 'anchoCalc',
							fieldLabel: 'Ancho calculado:'
						}							
					]
				},
				{
					xtype: 'container',
					defaults: {
						width: 200,
						labelWidth: 120							
					},
					layout: {
						type: 'hbox',
						padding: '5 5 0 5',								
					},
					items: [
						{
							xtype: 'checkboxfield',
							name: 'chapaPiso',
							itemId: 'chapaPiso',
							inputValue: 1,
							uncheckedValue: 0,
							fieldLabel: 'Chapa de piso Ad.'
						},
						{
							xtype: 'numberfield',
							fieldLabel: 'Cant/Radiador',
							name: 'cantAdicional',
							itemId: 'cantAdicional'
						},
						{
							xtype: 'checkboxfield',
							name: 'perfilIntermedio',
							itemId: 'perfilIntermedio',
							inputValue: 1,
							uncheckedValue: 0,
							fieldLabel: 'Perfil Intermedio:'									
						},
						{
							xtype: 'combobox',
							itemId: 'aletaTipo',
							name: 'aletaTipo',
							forceSelection: true,
							labelWidth: 100,
							fieldLabel: 'Tipo de aleta',
							store: ['Abierta', 'Cerrada'],
							value: 'Abierta'
						},
						{
							xtype: 'textfield',
							itemId: 'statusAleta',
							name: 'statusAleta',
							fieldStyle: 'background: green',	
							value: 'OK',								
							width: 70,
							readOnly: true									
						}
					]
				},
				{
					xtype: 'container',
					layout: {
						type: 'column',
						padding: '5 5 5 5'
					},
					defaults: {
						titleAlign: 'center'
					},
					items: [							
						{
							title: 'Carga manual',
							columnWidth: 0.2,
							defaults: {
								labelWidth: 130,
							},
							items: [
								{
									xtype: 'numberfield',
									fieldLabel: 'Pisos Manual 10,4:',											
									name: 'pisosManual',
									itemId: 'pisosManual'											
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'Pisos Manual 7:',
									name: 'pisosManual7',
									itemId: 'pisosManual7'											
								}
							]
						},
						{
							title: 'Datos',
							columnWidth: 0.2,
							items: [
								{
									xtype: 'numberfield',
									value: 1,
									cls: 'myDisabledClass',
									readOnly: true,
									name: 'cantPaneles',
									itemId: 'cantPaneles',
									fieldLabel: 'Cant. Paneles'
								},
								{
									xtype: 'numberfield',
									value: 650,
									name: 'maxAlt',
									itemId: 'maxAlt',
									fieldLabel: 'Altura Max.'
								}
							]
						},
						{
							title: 'Tipo',
							columnWidth: 0.2,
							items: [
								{
									xtype: 'radiogroup',
									columns: 1,
									itemId: 'tipo',											
									vertical: true,
									items: [
										{ boxLabel: 'Aire/Aceite', name: 'tipo', inputValue: '0', checked: true },
										{ boxLabel: 'Intercooler', name: 'tipo', inputValue: '1' }										
									]
								}
							]
						},								
						{
							title: 'Altura aleta lado ventilador',									
							columnWidth: 0.2,
							items: [
								{
									xtype: 'radiogroup',
									columns: 1,
									itemId: 'aletaVenA',
									vertical: true,
									action: 'aletaVenA',
									items: [
										{ boxLabel: 'Aleta de 7', name: 'aletaVenA', inputValue: '0', checked: true },
										{ boxLabel: 'Aleta de 10', name: 'aletaVenA', inputValue: '1' }										
									]
								}
							]
						},
						{
							title: 'Altura aleta de fluido',
							columnWidth: 0.2,
							items: [
								{
									xtype: 'radiogroup',
									columns: 1,
									itemId: 'aletaFluA',
									vertical: true,
									items: [
										{ boxLabel: 'Aleta de 7', name: 'aletaFluA', inputValue: '0', checked: true },
										{ boxLabel: 'Aleta de 10', name: 'aletaFluA', inputValue: '1' }										
									]
								}
							]
						}
					]
				},						
				{
					xtype: 'container',
					layout: {
						type: 'hbox',							
					},
					cls: 'panelBtn',
					defaults: {
						margin: '1 1 1 1'
					},							
					items: [
						{
							xtype: 'button',
							text: 'Calcular',									
							height: 50,
							width: 100,								
							iconCls: 'calculo',									
							itemId: 'btnCalculo',
							action: 'btnCalculo',
							disabled: true
						},
						{
							xtype: 'button',
							height: 50,
							width: 100,																		
							text: 'Guardar',
							iconCls: 'save',
							itemId: 'btnSaveCalculo',
							action: 'SaveCalculo',
							disabled: true
						}
					]
				}					
			]
		},
			{
				xtype: 'grid',
				title: 'Resultados',
				titleAlign: 'center',
				border: true,
				store: 'Produccion.CalculoRadiadores.Calculo',
				columns: [
					{ text: 'Descripcion', dataIndex: 'descripcion', width: 130, tdCls: 'custom-column' },
					{
						text: 'Dimensiones (mm)',
						columns: [
							{ text: 'Largo', dataIndex: 'largo' },
							{ text: 'Ancho', dataIndex: 'ancho' },
							{ text: 'Esp', dataIndex: 'espesor' },
						]
					},
					{
						text: 'Panel 1',
						columns: [
							{ text: 'Pisos', dataIndex: 'pisosp1' },
							{ text: 'Cant.', dataIndex: 'cantp1' },
						]
					},
					{
						text: 'Panel 2',
						columns: [
							{ text: 'Pisos', dataIndex: 'pisosp2' },
							{ text: 'Cant.', dataIndex: 'cantp2' },
						]
					},
					{
						text: 'Panel 3',
						columns: [
							{ text: 'Pisos', dataIndex: 'pisosp3' },
							{ text: 'Cant.', dataIndex: 'cantp3' },
						]
					},
					{
						text: 'Panel 4',
						columns: [
							{ text: 'Pisos', dataIndex: 'pisosp4' },
							{ text: 'Cant.', dataIndex: 'cantp4' },
						]
					}
				]
			}	
		]
	
});