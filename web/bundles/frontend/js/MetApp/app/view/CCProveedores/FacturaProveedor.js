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
											layout: 'vbox',
											defaults: {
												margins: '5 5 0 0',
												labelWidth: 60,
												width: 180,
											},
											items: [
												{
													xtype: 'numberfield',
													fieldLabel: 'IVA 5%',	
													name: 'perIva5',
													itemId: 'perIva5'						
												},
												{
													xtype: 'numberfield',
													fieldLabel: 'IVA 3%',	
													name: 'perIva3',
													itemId: 'perIva3'						
												},
												{
													xtype: 'numberfield',
													fieldLabel: 'IIBB CF',	
													name: 'iibbCf',
													itemId: 'iibbCf'						
												},					
											
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













