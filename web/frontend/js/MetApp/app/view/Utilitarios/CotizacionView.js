Ext.define('MetApp.view.Utilitarios.CotizacionView' ,{
	extend: 'Ext.window.Window',
	height: 600,
	width: 1000,
	modal: true,
	autoShow: true,
	alias: 'widget.CotizacionView',
	itemId: 'CotizacionView',
	title: 'Cotizaciones',
	layout: 'border',
	
	initComponent: function(){
		var me = this;
		var user = MetApp.User.name.name;
		var tc = MetApp.resources.ux.ParametersSingleton.dolarOficial;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					store: 'MetApp.store.Clientes.Clientes',
					border: false,
					itemId: 'formCotizacion',
					region: 'north',
					defaults: {
						margin: '5 0 0 5',
					},
					items: [
						{
							xtype: 'container',
							defaults: {
								readOnly: true,
								disabledCls: 'myDisabledClass',
								allowBlank: false
							},
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									width: 100,
									name: 'idCoti',
									itemId: 'idCoti',
									fieldLabel: 'id cotizacion',
									hidden: true,
									allowBlank: true						
								},
								{
									xtype: 'numberfield',
									width: 200,
									name: 'id',
									itemId: 'id',
									fieldLabel: 'Cliente',				
								},
								{
									xtype: 'button',
									disabled: false,
									iconCls: 'search',
									itemId: 'buscaCliente'
								},
								{
									xtype: 'textfield',
									name: 'rsocial',
									itemId: 'rsocial',
									width: 450,
									allowBlank: false,
									margin: '0 5 0 0'
								},								
								{
									xtype: 'datefield',
									format: 'd/m/Y',
									width: 200,
									labelWidth: 60,
									disabled: true,
									fieldLabel: 'Emision',
									itemId: 'emision',
									value: new Date()
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									fieldLabel: 'Direccion',
									name: 'direccion',
									allowBlank: false,
									width: 450
								},
								{
									xtype: 'textfield',
									name: 'cuit',
									labelWidth: 50,
									fieldLabel: 'CUIT'
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									labelWidth: 140,
									width: 600,							
									name: 'condVenta',
									fieldLabel: 'Condicion de venta',
									itemId: 'condVenta',
									allowBlank: false,
								},
								{
									xtype: 'textfield',
									margin: '0 0 0 5',
									disabled: true,
									disabledCls: 'myDisabledClass',
									width: 350,
									labelWidth: 60,
									readOnly: true,
									name: 'emisor',
									fieldLabel: 'Emisor',
									itemId: 'emisor',
									value: user
								}		
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'combobox',
									margin: '0 0 0 5',
									value: 'ARS',
									name: 'monedaCoti',
									itemId: 'monedaCoti',
									width: 130,
									labelWidth: 60,
									fieldLabel: 'Moneda',
					                forceSelection: true,
					                queryMode: 'local',
    								displayField: 'moneda',
					                store: Ext.create('Ext.data.Store', {
									    fields: ['moneda'],
									    data : [
									        {"moneda": 'U$D'},
									        {"moneda": 'ARS'},
									    ]
									 })
								},
								{
									xtype: 'numberfield',
									labelWidth: 30,
									margin: '0 0 0 5',
									width: 110,
									readOnly: true,
									name: 'tc',
									fieldLabel: 'TC',
									itemId: 'tc',
									decimalSeparator: '.',
								}
							]
						},
						{
							xtype: 'textarea',
							width: 800,
							name: 'observaciones',
							fieldLabel: 'Observaciones'
						},									
					]
				},		
				{
					xtype: 'grid',
					margin: '5 0 0 0',
					itemId: 'detallesStore',
					store: 'MetApp.store.Clientes.CotizacionDetalleStore',
					region: 'center',
					selType: 'cellmodel',
				    plugins: [
				        Ext.create('Ext.grid.plugin.CellEditing', {
				            clicksToEdit: 1
				        })
				    ],
					height: 150,
					columns: {
						defaults: {
							sortable: false
						},
						items: [
							{ text: 'IdArt', dataIndex: 'id', hidden: true, flex: 1 },
							{ text: 'Codigo', dataIndex: 'codigo' },
							{ text: 'Descripcion', dataIndex: 'descripcion', width: 250 },
							{ text: 'Cantidad', dataIndex: 'cant', width: 60, 
								editor: {
					                xtype: 'numberfield',
					                allowBlank: false
					            }
							},
							{ text: 'Unidad', dataIndex: 'unidad', width: 60,
								editor: {
					                xtype: 'textfield',
					            }
							},
							{ text: 'Precio', dataIndex: 'precio', width: 60,
								editor: {
					                xtype: 'textfield',
					                allowBlank: false
					            }
							},
							{ 
								text: 'Moneda',
								dataIndex: 'moneda',
								width: 60,
								renderer: function(val){
									if(val == "d"){
										return "U$D"
									}else{
										return "ARS"
									}
								}
							},
							{ 
								text: 'Parcial',
								renderer: function(value, meta){
									var total = meta.record.data.cant * meta.record.data.precio;
									return total;
								},
								summaryType: function (records, values) {
		                            var i = 0,
		                                length = records.length,
		                                total = 0,
		                                record;			                            
		                            
		                            for (i; i < length; ++i) {			                            	
		                                record = records[i];                             
		                               
		                                total += record.get('costo') * record.get('cant');                   
		                            }
		                            return (total);
		                            
	                      		},	
	                      		summaryRenderer: function (value, summaryData, field) {                           
		                            return Ext.util.Format.usMoney(value);
		                        },
							},
							{ 
								text: 'Entrega',
								dataIndex: 'entrega',
								xtype: 'datecolumn',
								dateFormat: 'd/m/Y',
								renderer: function(val){
									return val;
								},
								editor: {
					                xtype: 'datefield',
					                format: 'd/m/Y'
					            }
							},							
						]
					},
				},
				{
					xtype: 'container',
					region: 'south',
					layout: 'vbox',
					style: {
						background: 'white'
					},
					items: [
						{
							xtype: 'container',
							style: {
								background: 'white'
							},
							margin: '10 0 0 5',
							layout: 'hbox',
							defaults: {								
								fieldStyle: {
								    border: '2px solid #dadada',
									'border-radius': '7px',
									outline: 'none',
								    'border-color': '#9ecaed',
								    'box-shadow': '0 0 10px #9ecaed',
								    'text-align': 'right',
								    'font-size': '20px',
								    'font-weight': 'bold'
								},								
								labelStyle: 'font-size: 20px; font-weight: bold;'
							},
							items: [
								{
									xtype: 'numberfield',
									height: 30,
									itemId: 'desc',
									maxValue: 100,
									minValue: 0,
									labelWidth: 110,
									width: 200,														
									fieldLabel: 'Desc. Gral',
									name: 'descuentoGral',
									margin: '0 5 0 0',
									value: 0
								},
								{
									xtype: 'textfield',
									height: 30,
									itemId: 'total',
									readOnly: true,
									labelWidth: 60,														
									fieldLabel: 'Total',
									value: 0
								},								
							]
						},
						{
							xtype: 'form',
							border: false,
							store: 'Articulos.Articulos',
							itemId: 'formArticulos',
							items: [
								{
									xtype: 'container',
									margin: '5 0 0 0',
									layout: 'hbox',
									defaults: {
										margins: '0 0 5 0',
										readOnly: true,
									},
									items: [
										{
											xtype: 'numberfield',
											readOnly: true,
											fieldLabel: 'id articulo',
											itemId: 'idArt',
											name: 'id',
											hidden: true,
											margins: '0 0 0 5'
										},
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
											disabled: false,
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
										},
										{
											xtype: 'button',
											disabled: false,
											name: 'historicoArt',
											itemId: 'historicoArt',
											text: 'Histórico',
											margins: '0 0 0 5'
										},
									]
								},
								{
									xtype: 'container',
									margin: '5 0 5 0',
									layout: 'hbox',
									items: [
										{
											xtype: 'numberfield',
											labelWidth: 50,
											width: 150,
											fieldLabel: 'Cantidad',											
											itemId: 'cant',
											decimalSeparator: '.',
											name: 'cant',
											margins: '0 0 0 5'
										},
										{
											xtype: 'textfield',
											margin: '0 5 0 0',
											width: 150,
											labelWidth: 60,
											itemId: 'unidad',
											fieldLabel: 'Unidad',
											name: 'unidad',
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
											xtype: 'fieldcontainer',
											margins: '0 0 0 10',
								            defaultType: 'radiofield',
								            defaults: {
								                flex: 1
								            },
								            layout: 'hbox',
								            itemId: 'monedaPrecio',
								            items: [
								            	{
								                    boxLabel  : 'Pesos',
								                    checked: false,
								                    name      : 'monedaPrecio',
								                    inputValue: "false",
								                    itemId    : 'pesos'
								                },
								                {
								                    boxLabel  : 'Dolares',
								                    name      : 'monedaPrecio',
								                    inputValue: "true",
								                    itemId 	  : 'dolares'
								                },		
								                {
								                	xtype: 'datefield',
								                	labelWidth: 80,
								                	margin: '0 0 0 5',
											        fieldLabel: 'Entrega',
											        name: 'entrega', 
											        format: 'd/m/Y'
								                }
								            ]
										},						
									]
								},
								{
									xtype: 'button',
									itemId: 'insert',
									name: 'insert',
									text: 'Insertar'
								},
								{
									xtype: 'button',
									itemId: 'guardar',
									name: 'guardar',
									text: 'Guardar'
								},	
							]
						}
					]
				},
					
						
			]
		});
		this.callParent();
	}
});
