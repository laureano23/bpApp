Ext.define('MetApp.view.CCProveedores.FacturaProveedor',{
	extend: 'Ext.window.Window',
	height: 250,
	width: 950,
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
							margin: '5 0 0 5',
							layout: 'hbox',
							defaults: {
								margin: '0 5 0 0',
							},
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
									labelWidth: 30,
									fieldLabel: 'Tipo',
									store: 'MetApp.store.Finanzas.TiposComprobantesStore',
									//queryMode: 'local',
									displayField: 'descripcion',
									valueField: 'id',
									name: 'tipo',
									itemId: 'tipo'
								},
								{
									xtype: 'datefield',	
									allowBlank: false,						
									width: 230,
									fieldLabel: 'Fecha carga',
									name: 'fechaCarga',
									itemId: 'fechaCarga',
									value: new Date()
									
								},
								{
									xtype: 'datefield',
									allowBlank: false,
									width: 180,
									labelWidth: 50,
									fieldLabel: 'Emision',
									name: 'fechaEmision',
									itemId: 'fechaEmision'
								},								
								{
									xtype: 'numberfield',
									allowBlank: false,
									fieldLabel: 'Sucursal',
									width: 120,
									labelWidth: 70,
									name: 'sucursal',
									itemId: 'sucursal'
								},
								{
									xtype: 'numberfield',
									allowBlank: false,
									fieldLabel: 'N°',
									width: 160,
									labelWidth: 25,
									name: 'numFc',
									itemId: 'numFc'
								}
							]
						},
						{
							xtype: 'container',
							margin: '5 0 0 5',
							layout: 'hbox',
							defaults: {
								margin: '0 5 0 0',
							},
							items: [
								{
									xtype: 'numberfield',
									allowBlank: false,
									fieldLabel: 'Neto',
									labelWidth: 40,
									width: 150,
									decimalSeparator: '.',
									name: 'neto',
									itemId: 'neto'
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'Neto no grabado',
									labelWidth: 120,
									width: 240,
									decimalSeparator: '.',
									name: 'netoNoGrabado',
									itemId: 'netoNoGrabado'
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'IVA 21%',
									labelWidth: 60,
									width: 150,
									decimalSeparator: '.',
									name: 'iva21',
									itemId: 'iva21'
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'IVA 27%',
									labelWidth: 60,
									width: 150,
									decimalSeparator: '.',
									name: 'iva27',
									itemId: 'iva27'
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'IVA 10,5%',
									labelWidth: 75,
									width: 160,
									decimalSeparator: '.',
									name: 'iva10_5',
									itemId: 'iva10_5'
								}
							]
						},
						{
							xtype: 'container',
							margin: '5 0 0 5',
							layout: 'hbox',
							defaults: {
								margin: '0 5 0 0',
							},
							items: [
								{
									xtype: 'label',
									text: 'Percepciones'
								}
							]
						},
						{
							xtype: 'container',
							margin: '5 0 0 5',
							layout: 'hbox',
							defaults: {
								margin: '0 5 0 0',
								labelWidth: 60,
								width: 150,
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
								{
									xtype: 'datefield',
									labelWidth: 30,
									allowBlank: false,
									fieldLabel: 'Vto.',
									name: 'vencimiento',
									itemId: 'vencimiento'
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'Total',
									itemId: 'total',
									disabled: true,
									disabledCls: 'myDisabledCls'					
								},
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
								{
									xtype: 'textfield',
									allowBlank: false,
									width: 550,
									fieldLabel: 'Concepto',
									name: 'concepto',
									itemId: 'concepto'
								},
								{
									xtype: 'combobox',
									queryMode: 'local',
									allowBlank: false,
									store: 'Proveedores.ImputacionGastosStore',
									width: 320,
									labelWidth: 80, 
									fieldLabel: 'Imputacion',
									itemId: 'tipoGasto',
									name: 'tipoGasto',
									displayField: 'descripcion',
									valueField: 'id',
									forceSelection: true											
								}
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













