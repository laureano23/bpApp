Ext.define('MetApp.view.CCClientes.Facturacion' ,{
	extend: 'Ext.window.Window',
	height: 450,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.facturacion',
	itemId: 'facturacion',
	title: 'Facturacion',
	layout: 'border',
		
	initComponent: function(){
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnNew').setText('Insertar (F3)');
		botonera.queryById('btnSave').hide();
		botonera.queryById('btnReset').hide();
		
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					itemId: 'datosFc',
					store: 'MetApp.store.Finanzas.CCClientesStore',
					border: false,
					region: 'north',
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							defaults: {
								margins: '5 0 0 0',
								allowBlank: false,
							},
							items: [
								{
									xtype: 'combo',
									labelWidth: 150,
									fieldLabel: 'Tipo de comprobante',
									store: {
										fields: ['tipo', 'descripcion'],
										data: [
											{ 'tipo': 1, 'descripcion': 'Factura A' },
											{ 'tipo': 2, 'descripcion': 'Nota de debito A' },
											{ 'tipo': 3, 'descripcion': 'Nota de credito A' },
											{ 'tipo': 6, 'descripcion': 'Factura B' },
											{ 'tipo': 7, 'descripcion': 'Nota de debito B' },
											{ 'tipo': 8, 'descripcion': 'Nota de credito B' },
										]
									},
									queryMode: 'local',
									displayField: 'descripcion',
									valueField: 'tipo',
									name: 'tipo',
									value: 1
								},
								{
									xtype: 'datefield',																		
									name: 'fecha',
									itemId: 'fecha',
									fieldLabel: 'Fecha',
									width: 200,
									labelWidth: 35,
								}
							]
						}					
					]
				},
				{
					xtype: 'grid',
					region: 'center',
					store: 'MetApp.store.Finanzas.GrillaFacturacionStore',
					plugins: [
				        Ext.create('Ext.grid.plugin.CellEditing', {
				            clicksToEdit: 1
				        })
				    ],
					columns: {
						defaults: {
							sortable: false
						},
						items: [							
							{ text: 'Codigo', dataIndex: 'codigo' },
							{ text: 'Descripcion', dataIndex: 'descripcion' },
							{ text: 'Cantidad', dataIndex: 'cantidad' },
							{ text: 'Costo', dataIndex: 'costo' },
							{ 
								text: 'Precio',								
								dataIndex: 'precio',
								editor: {
					                xtype: 'numberfield',
					                allowBlank: false,
					           }					           
							},
							{ text: 'Parcial', dataIndex: 'parcial' },							
						]
					},
				},
				{
					xtype: 'container',
					region: 'south',
					layout: 'vbox',
					defaults: {
						margins: '5 0 5 5'
					},
					items: [
						{
							xtype: 'form',
							border: false,
							itemId: 'formArticulos',
							store: 'MetApp.store.Finanzas.Articulos',
							items: [
								{
									xtype: 'container',
									layout: 'hbox',
									defaults: {
										margins: '0 0 5 0'
									},
									items: [
										{
											xtype: 'textfield',
											readOnly: true,
											labelWidth: 50,
											width: 250,
											fieldLabel: 'Codigo',
											itemId: 'codigo',
											name: 'codigo',
											margins: '0 0 0 5'
										},
										{
											xtype: 'button',
											name: 'buscarArt',
											itemId: 'buscarArt',
											iconCls: 'search',
											margins: '0 5 0 0'
										},
										{
											xtype: 'textfield',
											readOnly: true,
											width: 500,
											itemId: 'descripcion',
											name: 'descripcion',
										}
									]
								},
								{
									xtype: 'container',
									margins: '5 0 0 0',
									layout: 'hbox',
									items: [
										{
											xtype: 'numberfield',
											labelWidth: 50,
											width: 150,
											fieldLabel: 'Cantidad',											
											itemId: 'cantidad',
											decimalSeparator: '.',
											name: 'cantidad',
											margins: '0 0 0 5'
										},
										{
											xtype: 'numberfield',
											labelWidth: 50,
											width: 150,
											fieldLabel: 'Precio',
											decimalSeparator: '.',
											itemId: 'precio',
											name: 'precio'
										},
										{
											xtype: 'numberfield',
											margins: '0 5 5 0',
											decimalSeparator: '.',
											readOnly: true,
											labelWidth: 50,
											width: 150,
											fieldLabel: 'Costo',
											itemId: 'costo',
											name: 'costo'
										},
										botonera
									]
								},								
							]
						},
						{
							xtype: 'container',									
							margins: '5 0 5 5',
							layout: 'hbox',
							defaults: {
								fieldStyle: {
								    border: '2px solid #dadada',
									'border-radius': '7px',
									outline: 'none',
								    'border-color': '#9ecaed',
								    'box-shadow': '0 0 10px #9ecaed'
								},
								width: 200,
								readOnly: true
							},
							items: [
								{
									xtype: 'numberfield',	
									labelWidth: 70,										
									fieldLabel: 'Sub-Total',
									decimalSeparator: '.',
									itemId: 'subTotal'
								},
								{
									xtype: 'numberfield',
									labelWidth: 30,
									fieldLabel: 'IVA',
									decimalSeparator: '.',
									itemId: 'iva'
								},
								{
									xtype: 'numberfield',
									labelWidth: 40,
									fieldLabel: 'Total',
									decimalSeparator: '.',
									itemId: 'total'
								}
							]
						},
						{
							xtype: 'button',
							itemId: 'btnSave',
							text: 'Guardar',
							width: 80,
							height: 50,
							margins: '5 5 5 5'
						}						
					]
				}
			]
		});
		this.callParent();
		me.queryById('fecha').setValue(new Date);
	}
});