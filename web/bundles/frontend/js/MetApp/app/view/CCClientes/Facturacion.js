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
	listeners: {
		afterrender: {
			fn: function(win){				
				var map = new Ext.util.KeyMap({
				    target: win.getId(),	
				   	binding: [
				   		{
				   			key: Ext.EventObject.F1,
				   			defaultEventAction: 'preventDefault',
				   			fn: function(){ 
				   				var btnEdit = win.queryById('btnEdit');
				   				btnEdit.fireEvent('click', btnEdit);
				   			}
				   		},
				   		{
				   			key: Ext.EventObject.F3,
				   			defaultEventAction: 'preventDefault',
				   			fn: function(){
				   				var btnNew = win.queryById('btnNew');
				   				btnNew.fireEvent('click', btnNew);
				   			}
				   		},
				   		{
				   			key: Ext.EventObject.F8,
				   			defaultEventAction: 'preventDefault',
				   			fn: function(){ 
				   				var btnDelete = win.queryById('btnDelete');
				   				btnDelete.fireEvent('click', btnDelete);
				   			}
				   		},
				   		{
				   			key: Ext.EventObject.F5,
				   			defaultEventAction: 'preventDefault',
				   			fn: function(){ 
				   				var btnSave = win.queryById('btnSave');
				   				btnSave.fireEvent('click', btnSave);
				   			}
				   		},
				   		{
				   			key: Ext.EventObject.F4,
				   			defaultEventAction: 'preventDefault',
				   			fn: function(){ 
				   				var btnSave = win.queryById('btnRemito');
				   				btnSave.fireEvent('click', btnSave);
				   			}
				   		},
				   	]
				});	
			}
		}		
	},
		
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
								margins: '5 0 5 5',
								allowBlank: false,
							},
							items: [
								{
									xtype: 'combo',
									fieldLabel: 'Tipo',
									store: 'MetApp.store.Finanzas.TiposComprobantesStore',
									displayField: 'descripcion',
									valueField: 'id',
									name: 'tipo',
									itemId: 'tipo',
									forceSelection: true
								},
								{
									xtype: 'datefield',																		
									name: 'fecha',
									itemId: 'fecha',
									fieldLabel: 'Fecha',
									width: 200,
									labelWidth: 35,
								},
								{
									xtype: 'combobox',
									labelWidth: 60,
									value: 0,
									name: 'moneda',
									itemId: 'moneda',
									queryMode: 'local',
									displayField: 'moneda',
									valueField: 'id',
									fieldLabel: 'Moneda',
									store:{
										fields: ['id', 'moneda'],
										data: [
											{ id: 0, moneda: 'Pesos ARS' },
											{ id: 1, moneda: 'Dólares U$D' },
										]
									}
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
							{ text: 'Descripcion', dataIndex: 'descripcion', width: 275 },
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
							{ text: 'remito Id', dataIndex: 'remitoNum', hidden: false },
						]
					},
				},
				{
					xtype: 'container',
					region: 'south',
					style: {
						background: 'white'
					},
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
											margins: '0 0 0 5',
											listeners: {
												change: function(field, win){
													if(field.getValue() == "ZZZ"){
														var desc = me.queryById('descripcion') 
														desc.setReadOnly(false);
														desc.focus('', 20);
													}
												}
											}
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
									margin: '5 0 0 0',
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
							margin: '5 0 5 5',
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
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'button',
									itemId: 'btnSave',
									text: 'Guardar (F5)',
									width: 90,
									height: 50,
									margins: '5 5 5 5'
								},
								{
									xtype: 'button',
									itemId: 'btnRemito',
									text: 'Remitos (F4)',
									width: 90,
									height: 50,
									margins: '5 5 5 5'
								}
							]
						}
															
					]
				}
			]
		});
		this.callParent();
		me.queryById('fecha').setValue(new Date);
	}
});
