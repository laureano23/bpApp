Ext.define('MetApp.view.Proveedores.FormProveedores' ,{
	extend: 'Ext.window.Window',
	height: 540,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.ProveedoresWin',
	itemId: 'ProveedoresWin',
	title: 'Tabla de Proveedores',
	
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					store: 'MetApp.store.Proveedores.ProveedoresStore',
					border: false,
					itemId: 'formProveedores',
					fieldDefaults: {
						msgTarget: 'side',
						blankText: 'Complete este campo',
						allowBlank: true,						
					},					
					items: [
						{
							xtype: 'container',
							margin: '5 0 0 5',	
							defaults: {								
								readOnly: true,
								disabledCls: 'myDisabledClass'
							},			
							layout: {
								type: 'hbox',
								align: 'middle',								
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
									readOnly: false,
									iconCls: 'search',
									action: 'buscaProveedor',
									itemId: 'buscaProveedor'
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
									width: 300,
									name: 'direccion',
									fieldLabel: 'Direccion:',
									columnWidth: 1
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
								{
									xtype: 'combobox',
									forceSelection: true,
									queryMode: 'local',
									name: 'localidad',
									itemId: 'comboLocalidad',
									store: 'MetApp.store.Personal.LocalidadesStore',
									displayField: 'nombre',
									valueField: 'idLocalidad',
									typeAhead: true,
									minChars: 1,
									fieldLabel: 'Localidad',
									labelWidth: 60,
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
									xtype: 'container',
									columnWidth: 0.33,
									layout: 'vbox',
									items: [										
										{
											title: 'Retencion',
											border: false,
											defaults: {
												readOnly: true,
												disabledCls: 'myDisabledClass'
											},
											items: [
												{
													xtype: 'checkbox',
													name: 'aplicaRetencion',
													fieldLabel: 'Aplica:',
													uncheckedValue: false
												},
												{
													xtype: 'numberfield',
													name: 'porcentajeRetencion',
													fieldLabel: 'Retencion'
												}
											]
										},
										{
											xtype: 'combobox',
											queryMode: 'local',
											allowBlank: false,
											store: 'Proveedores.ImputacionGastosStore',
											readOnly: true,
											disabledCls: 'myDisabledClass',
											width: 280,
											labelWidth: 130, 
											fieldLabel: 'Imputacion sugerida',
											itemId: 'tipoGasto',
											name: 'tipoGasto',
											displayField: 'descripcion',
											valueField: 'id',
											forceSelection: true
										},	
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
									xtype: 'container',
									border: false,
									columnWidth: 1,
									layout: 'column',
									items: [
										{
											title: 'Condicion Comerciales',
											border: false,
											columnWidth: 0.6,
											layout: 'vbox',
											margin: '0 5 0 0',
											defaults: {
												padding: '5 0 0 0',
											},
											items: [
												{
													xtype: 'textfield',
													name: 'condCompra',
													fieldLabel: 'Condicion de Compra:',
													width: 450,
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
															uncheckedValue: false,
															fieldLabel: 'Cuenta Cerrada:',
															width: 200,
														}
													]
												}
												
											]
										},
										{
											title: 'Notas',
											margin: '0 0 0 5',
											border: false,
											columnWidth: 0.4,
											layout: 'anchor',
											items: [
												{
													xtype: 'textareafield',
													anchor: '100%',											
													grow: true,
													name: 'notas',
													itemId: 'notas',
													readOnly: true,
													disabledCls: 'myDisabledClass'
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
