Ext.define('MetApp.view.Articulos.Stock.EntradaSalidaArticulos', {
	extend: 'Ext.window.Window',
	alias: 'widget.EntradaSalidaArticulos',
	id: 'EntradaSalidaArticulos',
	itemId: 'EntradaSalidaArticulos',
	width: 800,
	height: 500,
	layout: 'border',
	autoShow: true,
	title: 'Entrada/Salida de artículos',
	modal: true,
	
	initComponent: function(){
		
		var autz = Ext.create('MetApp.controller.Security.Autorizaciones');
		autz.authorization(MetApp.User);
		
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
				
		
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					region: 'center',
					border: false,
					margins: '5 5 5 5',
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								allowBlank: false
							},
							items: [
								{
									xtype: 'datefield',
									fieldLabel: 'Emisión',
									readOnly: true,
									value: new Date(),
									labelWidth: 50,
									width: 140,
									margins: '0 5 0 0'
								},
								{
									xtype: 'combo',
									labelWidth: 75,
									width: 170,
									fieldLabel: 'Operación',
									name: 'operacion',
									itemId: 'operacion',
									store: {
										fields: ['desc'],
										data: [
											{ 'desc': 'Entrada' },
											{ 'desc': 'Salida' },
										]
									},
									displayField: 'desc',
									value: 'Entrada',
									forceSelection: true
								},
								{
									xtype: 'combo',
									margin: '0 0 0 5',
									labelWidth: 75,
									width: 400,
									fieldLabel: 'Concepto',
									name: 'concepto',
									itemId: 'concepto',
									store: 'MetApp.store.Articulos.ConceptosEntradaStore',
									displayField: 'concepto',
									valueField: 'id',
									value: 'Entrada',
									forceSelection: true
								},		
							]
						},
						{
							xtype: 'container',
							defaults: {
								allowBlank: false
							},
							margin: '10 0 0 0',
							layout: 'hbox',
							items: [
								{
									xtype: 'fieldset',
									title: 'Orígen',
									margins: '0 0 0 5',
									items: [
										{
											xtype: 'radiogroup',
											itemId: 'origen',
											labelWidth: 50,
											margins: '0 0 0 5',
											columns: 2,
		        							vertical: true,
											width: 270,
								            items: [
								                { boxLabel: 'Proveedor', name: 'origen', inputValue: 'proveedor', width: 80, checked: true },
								                { boxLabel: 'Cliente', name: 'origen', inputValue: 'cliente', width: 120 }
								            ]
										},											
									]
								},
								{
									xtype: 'textfield',
									name: 'idOrigen',
									itemId: 'idOrigen',
									hidden: true
									
								},
								{
									xtype: 'textfield',
									margin: '10 0 0 5',
									width: 400,
									name: 'fuente',
									itemId: 'fuente'
								},
								{
									xtype: 'button',
									itemId: 'buscarOrigen',
									iconCls: 'search',
									margins: '10 0 0 5'
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								allowBlank: false
							},
							margin: '10 0 0 0',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									name: 'comprobante',
									fieldLabel: 'Comprobante'
								},
								{
									xtype: 'combo',
									margin: '0 0 0 5',
									labelWidth: 75,
									width: 350,
									fieldLabel: 'Depósito',
									name: 'deposito',
									store: 'MetApp.store.Articulos.DepositoArticulosStore',
									displayField: 'deposito',
									value: 'id',
									forceSelection: true
								}
							]
						},
						{
							xtype: 'textfield',
							name: 'observaciones',
							allowBlank: false,
							margin: '5 0 5 0',
							width: 700,
							fieldLabel: 'Observaciones'
						},
						{
							xtype: 'grid',
							margins: '5 0 0 0',
							cls: 'extra-alt',
							region: 'center',
							store: 'MetApp.store.Compras.HistoricoCompraStore',
							columns: [
								{ text: 'Código', dataIndex: 'codigo', width: 150 },
								{ text: 'Descripción', dataIndex: 'descripcion', width: 300 },
								{ text: 'Cantidad', dataIndex: 'cant', flex: 1 },
								{ text: 'Lote', dataIndex: 'lote', flex: 1 },
								{ text: 'OC N°', dataIndex: 'idOc', flex: 1 },
								{ 
									header: 'Editar',
									itemId: 'editar',
									xtype: 'actioncolumn',
									items: [
										{ iconCls: 'edit' }										
									],
									flex: 1
								},	
								{ 
									header: 'Eliminar',
									itemId: 'eliminar',
									xtype: 'actioncolumn',
									items: [
										{ iconCls: 'delete' }										
									],
									flex: 1
								},
							]
						},																				
					]
				},
				{
					xtype: 'form',
					margin: '5 5 5 5',
					border: false,
					region: 'south',
					itemId: 'formArticulo',
					margins: '10 0 0 0',					
					layout: 'vbox',
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								readOnly: true,
								disabledCls: 'myDisabledClass',
								allowBlank: false	
							},
							items: [
								{
									xtype: 'textfield',
									width: 170,
									labelWidth: 60,
									name: 'codigo',
									itemId: 'codigo',
									fieldLabel: 'Código'
								},
								{
									xtype: 'button',
									itemId: 'buscarArticulo',
									iconCls: 'search',
									margins: '0 0 0 5'
								},
								{
									xtype: 'textfield',
									width: 400,
									name: 'descripcion',
									itemId: 'descipcion'									
								},
								{
									xtype: 'numberfield',
									readOnly: false,
									labelWidth: 55,
									width: 170,
									fieldLabel: 'Cantidad',
									name: 'cantidad',
									itemId: 'cantidad',
									margins: '0 0 0 5'
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								readOnly: true,
								disabledCls: 'myDisabledClass',
								allowBlank: false
							},
							margins: '5 0 0 0',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									emptyText: 'Click buscar',
									width: 170,
									labelWidth: 60,
									allowBlank: true,
									name: 'oc',
									itemId: 'oc',
									fieldLabel: 'OC N°',									
								},
								{
									xtype: 'numberfield',
									labelWidth: 50,
									width: 170,
									fieldLabel: 'Lote',
									allowBlank: true,
									name: 'lote',
									itemId: 'lote',
									margins: '0 0 0 5'
								},
								{
									xtype: 'button',
									text: 'Insertar',
									itemId: 'insertArt',
									margins: '0 0 5 5'
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
