Ext.define('MetApp.view.RRHH.TablaConceptos', {
	extend: 'Ext.window.Window',
	modal: true,	
	width: 580,
	height: 500,
	layout: 'border',
	border: false,
	itemId: 'conceptosTabla',
	alias: 'widget.conceptosTabla',	
	autoShow: true,
	title: 'Tabla de Conceptos',
	defaults: {
		border: false,
		frame: false
	},
	
	initComponent: function(){
		var me = this;
		
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					store: 'MetApp.store.Personal.ConceptosStore',
					region: 'center',
					frame: false,
					border: false,					
					layout: 'vbox',
					items: [
						{
							xtype: 'container',
							frame: false,
							margins: '5 5 5 5',
							flex: 1,
							items: [
								{
									xtype: 'container',
									layout: 'hbox',
									frame: false,
									margins: '5 5 5 5',
									flex: 1,
									defaults: {
										disabled: true,
										disabledCls: 'myDisabledClass',
									},
									items: [
										{
											xtype: 'numberfield',
											name: 'id',
											itemId: 'id',
											fieldLabel: 'Id',
											width: 200,
											hidden: true										
										},											
									]	
								},
								{
									xtype: 'container',
									layout: 'hbox',
									margin: '0 0 5 0',
									items: [
										{
											xtype: 'textfield',
											disabled: true,
											disabledCls: 'myDisabledClass',
											width: 350,
											name: 'descripcion',
											itemId: 'descripcion',
											fieldLabel: 'Descripcion',
										},
										{
											xtype: 'button',
											disabled: false,
											action: 'buscaConcepto',
											itemId: 'buscaConcepto',
											iconCls: 'search',
											height: 25,
											width: 30,
											margin: '0 5 0 5'
										},
										{
											xtype: 'textfield',
											disabled: true,
											disabledCls: 'myDisabledClass',
											width: 150,
											labelWidth: 50,
											name: 'unidad',
											itemId: 'unidad',
											fieldLabel: 'Unidad',
										},
									]									
								},
								{
						            xtype: 'fieldset',
						            disabled: true,
									disabledCls: 'myDisabledClass',
						            fieldLabel: 'Tipo de concepto',
						            defaultType: 'radiofield',
						            width: 400,
						            columns: 2,
						            vertical: true,
						            columnWidth: 200,
						            items: [
							            {
							            	xtype: 'checkbox',
											name: 'remunerativo',
											boxLabel: 'Remunerativo',
											inputValue: 1,		//EMPLEADO QUINCENAL
											uncheckedValue: 0,
											checked: true,
										},
										{
											xtype: 'checkbox',
											name: 'descuento',
											boxLabel: 'Descuento',
											inputValue: 1,
											uncheckedValue: 0
										},
										{
											xtype: 'checkbox',
											name: 'asignacion',
											boxLabel: 'Asignacion',
											inputValue: 1,
											uncheckedValue: 0
										},
										{
											xtype: 'checkbox',
											name: 'fijo',
											boxLabel: 'Fijo',
											inputValue: 1,
											uncheckedValue: 0
										},
										{
											xtype: 'checkbox',
											name: 'noRemunerativo',
											boxLabel: 'No remunerativos',
											inputValue: 1,
											uncheckedValue: 0
										},
						            ]
						       },
						       {
						       		xtype: 'fieldset',
						       		//disabled: true,
									//disabledCls: 'myDisabledClass',
						       		width: 350,
									margin: '5 5 5 5',
									layout: 'anchor',
									defaults: {anchor: '100%'},
									title: 'Aplica a',
									items: [
										{
											xtype: 'checkboxgroup',
											itemId: 'pagoTipo',
											columns: 2,
				        					vertical: true,
											items: [
												{ boxLabel: '1° quincena', name: 'quincena1', inputValue: 1, uncheckedValue: 0 },
												{ boxLabel: '2° quincena', name: 'quincena2', inputValue: 2, uncheckedValue: 0 },
												{ boxLabel: 'Mes', name: 'mes', inputValue: 3, uncheckedValue: 0 },
												{ boxLabel: 'Vacaciones', name: 'vacaciones', inputValue: 4, uncheckedValue: 0 },
												{ boxLabel: 'SAC', name: 'sac', inputValue: 5, uncheckedValue: 0 },
												{ boxLabel: 'Otros', name: 'otros', inputValue: 6, uncheckedValue: 0 },
												{ boxLabel: 'Premios', name: 'premios', inputValue: 7, uncheckedValue: 0 },											
											]
										},										
									]						       		
						       },
						       {
									xtype: 'combobox',
									disabled: true,
									disabledCls: 'myDisabledClass',
									name: 'codigoCalculo',
									allowBlank: false,
									labelWidth: 50,
									width: 375,
									margin: '0 5 0 0',
									fieldLabel: 'Calculo',
									store: Ext.create('Ext.data.Store', {
									    fields: [{name: 'calculo'}, {name: 'codigo'}],
									    data : [
									        {'codigo': 1, 'calculo': 'Cantidad de horas por Valor de hora'},
									        {'codigo': 2, 'calculo': 'Horas extras al 50%'},
									        {'codigo': 3, 'calculo': 'Importe fijo'},
									        {'codigo': 4, 'calculo': 'Mensualizado'},
									        {'codigo': 5, 'calculo': 'Horas extras 50% mensualizados'},
									        {'codigo': 6, 'calculo': 'SAC'},
									    ]
									}),
									queryMode: 'local',
									displayField: 'calculo',
									valueField: 'codigo',
									itemId: 'codigoCalculo'
								},
								{
									xtype: 'numberfield',
									decimalSeparator: '.',
									disabled: true,
									disabledCls: 'myDisabledClass',
									margin: '5 0 0 0',
									labelWidth: 50,
									fieldLabel: 'Importe',
									name: 'importe',
									itemId: 'importe'
								},
								{
									xtype: 'checkbox',
									disabled: true,
									disabledCls: 'myDisabledClass',
									margin: '5 0 0 0',
									labelWidth: 70,
									boxLabel: 'Porcentaje',
									name: 'porcentaje',
									itemId: 'porcentaje',
									inputValue: 1,
									uncheckedValue: 0
								}
							]
						}						
					]
				},						
				{
					xtype: 'container',
					padding: '0 5 0 5',
					region: 'south',
					layout: 'vbox',		
					items: [botonera]
				}			
			]
		})
		
		me.callParent();
	}
});
