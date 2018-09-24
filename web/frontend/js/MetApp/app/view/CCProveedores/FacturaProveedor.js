Ext.define('MetApp.view.CCProveedores.FacturaProveedor',{
	extend: 'Ext.window.Window',
	layout: 'fit',
	width: 800,
	modal: true,
	autoShow: true,
	alias: 'widget.FacturaProveedor',
	itemId: 'FacturaProveedor',
	title: 'Factura de proveedor',
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					store: 'MetApp.store.Proveedores.FacturaProveedoresStore',
					itemId: 'formFc',
					border: false,					
					items: [
						{
							xtype: 'container',
							layout: 'column',
							defaults: {
								labelWidth: 110,
								width: 300
							},
							items: [
								{
									xtype: 'container',
									columnWidth: 0.35,
									margin: '5 0 0 5',
									items: [
										{
											xtype: 'numberfield',
											name: 'id',
											itemId: 'id',
											fieldLabel: 'idF',
											hidden: true
										},
										{
											xtype: 'combo',
											fieldLabel: 'Tipo',
											store: 'MetApp.store.Finanzas.TiposComprobantesStore',
											displayField: 'descripcion',
											valueField: 'id',
											name: 'tipo',
											itemId: 'tipo'
										},
										{
											xtype: 'datefield',	
											allowBlank: false,
											fieldLabel: 'Fecha carga',
											name: 'fechaCarga',
											itemId: 'fechaCarga',
											value: new Date()
											
										},
										{
											xtype: 'datefield',
											allowBlank: false,
											fieldLabel: 'Emision',
											name: 'fechaEmision',
											itemId: 'fechaEmision'
										},								
										{
											xtype: 'numberfield',
											allowBlank: false,
											fieldLabel: 'Sucursal',
											name: 'sucursal',
											itemId: 'sucursal'
										},
										{
											xtype: 'numberfield',
											allowBlank: false,
											fieldLabel: 'N° Cbte.',
											name: 'numFc',
											itemId: 'numFc'
										},
										{
											xtype: 'numberfield',
											allowBlank: false,
											fieldLabel: 'Neto',
											decimalSeparator: '.',
											name: 'neto',
											itemId: 'neto'
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'Neto no grabado',
											decimalSeparator: '.',
											name: 'netoNoGrabado',
											itemId: 'netoNoGrabado'
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'IVA 21%',
											decimalSeparator: '.',
											name: 'iva21',
											itemId: 'iva21'
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'IVA 27%',
											decimalSeparator: '.',
											name: 'iva27',
											itemId: 'iva27'
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'IVA 10,5%',
											decimalSeparator: '.',
											name: 'iva10_5',
											itemId: 'iva10_5'
										},
									]
								},
								{
									xtype: 'container',
									columnWidth: 0.65,
									layout: 'anchor',
									defaults: {
										labelWidth: 90,
									},
									items: [
										{
											xtype: 'fieldset',
											margin: '5 0 0 5',
											title: 'Percepciones',
											margin: '0 5 5 0',
											layout: 'hbox',
											defaults: {
												margins: '5 5 0 0',
												labelWidth: 60,
												//width: 160,
											},
											items: [
												{
													xtype: 'container',
													layout: 'vbox',
													defaults: {
														width: 190
													},
													flex: .4,
													items:[
														{
															xtype: 'numberfield',
															fieldLabel: 'IVA 5%',	
															name: 'perIva5',
															itemId: 'perIva5',
															decimalSeparator: '.',						
														},
														{
															xtype: 'numberfield',
															fieldLabel: 'IVA 3%',	
															name: 'perIva3',
															itemId: 'perIva3',
															decimalSeparator: '.',						
														},
														{
															xtype: 'numberfield',
															fieldLabel: 'IIBB CABA',	
															name: 'iibbCf',
															decimalSeparator: '.',
															itemId: 'iibbCf'						
														},
													]
												},
												{
													xtype: 'container',
													layout: 'vbox',
													flex: .4,
													defaults: {
														width: 190
													},
													items:[
														{
															xtype: 'numberfield',
															fieldLabel: 'IIBB BS.AS',	
															name: 'iibbBsas',
															decimalSeparator: '.',
															itemId: 'iibbBsas'						
														},
														{
															xtype: 'numberfield',
															fieldLabel: 'IIBB Otras',	
															name: 'iibbOtras',
															decimalSeparator: '.',
															itemId: 'iibbOtras'						
														},
													]
												}
											]
										},
										{
											xtype: 'datefield',
											allowBlank: false,
											fieldLabel: 'Vto.',
											name: 'vencimiento',
											itemId: 'vencimiento'
										},
										{
											xtype: 'numberfield',
											fieldLabel: 'Total',
											itemId: 'total',
											name: 'totalFc',
											readOnly: true,
											disabledCls: 'myDisabledCls'					
										},
										{
											xtype: 'textfield',
											allowBlank: false,
											fieldLabel: 'Concepto',
											name: 'concepto',
											itemId: 'concepto',
											width: 300
										},
										{
											xtype: 'combobox',
											queryMode: 'local',
											allowBlank: false,
											store: 'Proveedores.ImputacionGastosStore',
											width: 250,
											fieldLabel: 'Imputacion',
											itemId: 'tipoGasto',
											name: 'tipoGasto',
											displayField: 'descripcion',
											valueField: 'id',
											forceSelection: true											
										},
										{
											xtype: 'textareafield',
											name: 'observaciones',
											itemId: 'observaciones',
											fieldLabel: 'Observaciones',
											anchor: '100%',
											margin: '0 5 0 0'
										},
										{
											xtype: 'grid',
											anchor: '100%',
											store: {
												fields: ['emision', 'tipoCbte', 'numero', 'importe', 'activo'],
												proxy: {
													type: 'memory',
													reader: {
														type: 'json',
														root: 'items'
													}
												}
											},
											margin: '5 0 0 0',
											height: 150,
											itemId: 'gridImputacion',
											columns: [
												{ header: 'Fecha', dataIndex: 'emision', flex: 1 },
												{ header: 'Tipo', dataIndex: 'tipoCbte', flex: 1 },
												{ header: 'N°', dataIndex: 'numero', flex: 1 },
												{ header: 'Importe', dataIndex: 'importe', flex: 1 },
												{ xtype : 'checkcolumn', text : 'Active',  dataIndex: 'activo', flex: 1 }
											]
										}
									]
								}
							]
						},
						
										
						{
							xtype: 'container',
							layout: 'hbox',
							margin: '5 0 0 5',
							layout: 'hbox',
							defaults: {
								margin: '0 5 0 0',
								labelWidth: 60,
								width: 150,
							},
							items: [
										
							]
						},
						{
							xtype: 'container',
							margin: '5 0 0 5',
							layout: 'hbox',
							defaults: {
								width: 105,
								height: 30,
								margin: '1 1 1 1',
							},
							items: [
								{
									xtype: 'button',
									iconCls: 'save',
									text: 'Guardar',
									itemId: 'saveFcProv'
								},
								{
									xtype: 'button',
									text: 'Salir',
									itemId: 'closeFc',
									listeners: {
										click: function(btn){
											var win = btn.up('window');
											win.close();
										}
									}
								}
							]
						}
					]
				}
			]
		})
		this.callParent();
	}
});













