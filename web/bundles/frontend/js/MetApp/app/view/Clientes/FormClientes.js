Ext.define('MetApp.view.Clientes.FormClientes' ,{
	extend: 'Ext.window.Window',
	height: 565,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.clientestb',
	itemId: 'clientesTb',
	title: 'Tabla de Clientes',
	
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					store: 'MetApp.store.Clientes.Clientes',
					border: false,
					itemId: 'formClientes',
					fieldDefaults: {
						msgTarget: 'side',
						blankText: 'Complete este campo',
						allowBlank: true,						
					},					
					items: [
						{
							xtype: 'container',	
							defaults: {								
								readOnly: true,
								disabledCls: 'myDisabledClass'
							},					
							height: 50,
							layout: {
								type: 'hbox',
								align: 'middle',
								padding: '0 0 0 5'
							},
							items: [
								{
									xtype: 'numberfield',
									fieldLabel : 'N°',
									itemId: 'id',
									name: 'id',
									labelWidth: 20,
									width: 100
								},
								{
									xtype: 'button',
									readOnly: true,
									iconCls: 'search',
									action: 'buscaCliente',
									itemId: 'buscarCliente'
								},
								{
									xtype: 'textfield',
									padding: '0 0 0 5',
									name: 'rsocial',
									itemId: 'rsocial',
									fieldLabel: 'Razon social:',
									width: 700,
									allowBlank: false
								}								
							]
						},
						{
							xtype: 'container',
							height: 50,
							defaults: {								
								readOnly: true,
								disabledCls: 'myDisabledClass'
							},
							layout: {
								type: 'hbox',
								align: 'middle',
								padding: '0 0 0 5'
							},
							items: [
								{
									xtype: 'textfield',
									name: 'denominacion',
									fieldLabel: 'Denominacion',
									width: 550
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								padding: '5 5 0 5',
								readOnly: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'column',
							items: [
								{
									xtype: 'textfield',
									//width: 300,
									name: 'direccion',
									fieldLabel: 'Direccion:',
									columnWidth: 0.33
								},
								{
									xtype: 'combobox',
									columnWidth: 0.33,
									labelWidth: 70,
									name: 'provincia',
									itemId: 'comboProv',
									store: 'MetApp.store.Personal.ProvinciasStore',
									displayField: 'nombre',
									forceSelection: true,
									queryMode: 'local',
									valueField: 'idProvincia',
									typeAhead: true,							
									minChars: 1,
									fieldLabel: 'Provincia',
								},								
								{
									xtype: 'combobox',
									forceSelection: true,
									queryMode: 'local',
									name: 'departamento',
									itemId: 'comboPartido',
									store: 'MetApp.store.Personal.PartidosStore',
									displayField: 'nombre',
									valueField: 'idPartido',
									typeAhead: true,
									minChars: 1,
									fieldLabel: 'Partido',
									columnWidth: 0.33
								},
							]							
						},
						{
							xtype: 'container',
							defaults: {
								padding: '5 5 0 5',
								readOnly: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'column',
							items: [
								{
									xtype: 'textfield',
									name: 'email',
									columnWidth: 0.33,
									fieldLabel: 'Email:'
								},
								{
									xtype: 'numberfield',
									name: 'cuit',
									columnWidth: 0.33,
									fieldLabel: 'CUIT:',
									emptyText: '33678767439',
									labelWidth: 35
								},
								{
									xtype: 'textfield',
									name: 'cPostal',
									columnWidth: 0.33,
									fieldLabel: 'Cod. Postal:'
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								padding: '5 0 5 5',
								border: false
							},
							layout: 'column',
							items: [
								{
									title: 'Posicion IVA',
									defaults: {
										readOnly: true,
										border: false,
										disabledCls: 'myDisabledClass'
									},
									columnWidth: 0.33,
									items: [
										{
											xtype: 'radiogroup',
											columns: 1,
											vertical: true,
											items: [
												{ boxLabel: 'Responsable Inscripto', name: 'iva', inputValue: '1', checked: true },
												{ boxLabel: 'Responsable No Inscripto', name: 'iva', inputValue: '2' },
												{ boxLabel: 'Exento', name: 'iva', inputValue: '3' },
												{ boxLabel: 'Responsable Monotributo', name: 'iva', inputValue: '4' },
												{ boxLabel: 'Consumidor Final', name: 'iva', inputValue: '5' },
												{ boxLabel: 'Exportacion', name: 'iva', inputValue: '6' },
												
											]
										},
										{
											title: 'Percepciones',
											columnWidth: 0.33,
											defaults: {
												readOnly: true,
												disabledCls: 'myDisabledClass'
											},
											items: [
												{
													xtype: 'numberfield',
													name: 'netoPercepcion',
													fieldLabel: 'Neto mayor a:'
												},
												{
													xtype: 'numberfield',
													name: 'porcentajePercepcion',
													fieldLabel: 'Percepcion'
												}
											]
										}
									]
								},
								{
									title: 'Telefonos',									
									columnWidth: 0.67,
									layout: 'vbox',
									defaults: {
										margin: '0 0 2 0',
									},
									items: [
										{
											xtype: 'container',
											layout: 'hbox',
											defaults: {
												border: false,
												readOnly: true,
												disabledCls: 'myDisabledClass'
											},
											margin: '2 0 2 0',
											items: [
												{ 
													xtype: 'button',
													iconCls: 'phone' 
												},
												{
													xtype: 'textfield',	
													name: 'telefono1',												
													padding: '0 20 0 0',
													width: 250
												},
												{
													xtype: 'button',											
													iconCls: 'contact'
												},
												{
													xtype: 'textfield',
													name: 'contacto1',
													width: 250
												}
											]
										},
										{
											xtype: 'container',
											defaults: {
												border: false,
												readOnly: true,
												disabledCls: 'myDisabledClass'
											},
											layout: 'hbox',
											items: [
												{ 
													xtype: 'button',
													iconCls: 'phone' 
												},
												{
													xtype: 'textfield',
													name: 'telefono2',
													padding: '0 20 0 0',
													width: 250
												},
												{
													xtype: 'button',											
													iconCls: 'contact'
												},
												{
													xtype: 'textfield',
													name: 'contacto2',
													width: 250
												}
											]
										},
										{
											xtype: 'container',
											defaults: {
												border: false,
												readOnly: true,
												disabledCls: 'myDisabledClass'
											},
											layout: 'hbox',
											items: [
												{ 
													xtype: 'button',
													iconCls: 'phone' 
												},
												{
													xtype: 'textfield',
													name: 'telefono3',
													padding: '0 20 0 0',
													width: 250
												},
												{
													xtype: 'button',											
													iconCls: 'contact'
												},
												{
													xtype: 'textfield',
													name: 'contacto3',
													width: 250
												}
											]
										}									
									]
								},
								{
									title: 'Condicion Comerciales',
									columnWidth: 0.67,
									layout: 'vbox',
									defaults: {
										padding: '5 0 0 0',
									},
									items: [
										{
											xtype: 'textfield',
											name: 'condVenta',
											fieldLabel: 'Condicion de Vta:',
											width: 570,
											labelWidth: 120,
											readOnly: true,
											disabledCls: 'myDisabledClass'
										},
										{
											xtype: 'container',
											defaults: {
												padding: '5 0 0 0',
												readOnly: true,
												disabledCls: 'myDisabledClass'
											},
											layout: 'hbox',
											items: [
												{
													xtype: 'numberfield',
													name: 'vencimientoFc',
													fieldLabel: 'Dias de Vencimiento:',
													width: 200,
													labelWidth: 130
												},
												{
													xtype: 'checkbox',
													name: 'cuentaCerrada',
													fieldLabel: 'Cuenta Cerrada:',
													width: 200,
												}
											]
										},
										{
											xtype: 'combobox',
											readOnly: true,
											disabledCls: 'myDisabledClass',
											width: 350,
											labelWidth: 70,
											name: 'transporte',
											itemId: 'transporte',
											store: 'MetApp.store.Clientes.TransportesStore',
											displayField: 'nombre',
											forceSelection: true,
											queryMode: 'local',
											valueField: 'id',
											typeAhead: true,							
											minChars: 1,
											fieldLabel: 'Transporte',
										},
										{
											xtype: 'container',
											layout: 'hbox',
											defaults: {
												readOnly: true,
												disabledCls: 'myDisabledClass'
											},
											items: [
												{
													xtype: 'checkbox',
													name: 'intereses',
													itemId: 'intereses',
													fieldLabel: 'Aplica interés',
													uncheckedValue: 0,
												},
												{
													xtype: 'numberfield',
													name: 'tasa',
													itemId: 'tasa',
													fieldLabel: 'Tasa diaria'
												}
											]
										}										
									]
								}
							]
						},
						{
							xtype: 'container',
							cls: 'panelBtn',
							border: false,
							defaults: {
								padding: '5 0 5 5',
								border: false,
								margin: '5 2 5 2'								
							},
							layout: 'hbox',
							items: botonera
						}
					]
				}
			]
		});
		this.callParent();
	}
});
