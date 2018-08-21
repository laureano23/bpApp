Ext.define('MetApp.view.Articulos.ArticulosForm', {
	extend: 'Ext.window.Window',
	alias: 'widget.articulosform',
	id: 'articulosForm',
	itemId: 'articulosForm',
	width: 900,
	//height: 470,
	layout: 'fit',
	autoShow: true,
	title: 'Tabla de Articulos',
	modal: true,
	
	initComponent: function(){
		
		/*
		 * Defino el objeto que manejara la seguridad de las vistas
		 */
		var autz = Ext.create('MetApp.controller.Security.Autorizaciones');
		autz.authorization(MetApp.User);
		
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		
		var itemsBox1 = [
			{
				xtype: 'numberfield',
				name: 'id',
				itemId: 'id',
				fieldLabel: 'Id',				
				width: 250,
				text: 'id',
				hidden: true,
				listeners: {//PARA SETEAR EL ID EN LA TAB DE IMAGENES
					change: {
						fn: function(){
							var btn = this;
							var form = btn.up('window');
							form.queryById('idArt').setValue(btn.getValue());
						}
					}
				}				
			},
			{
				xtype: 'textfield',		
				fieldStyle : 'text-transform: uppercase',
				listeners:{
                    change: function(field, newValue, oldValue){
                        field.setValue(newValue.toUpperCase());
                    }
                 },		
				name: 'codigo',
				readOnly: true,
				itemId: 'codigo',
				fieldLabel: 'Codigo',				
				width: 250,
				text: 'codigo'				
			},
			{
				xtype: 'button',				
				height: 25,				
				width: 30,
				margin: '0 0 0 5',
				iconCls: 'search',
				action: 'actBuscaArt',
				itemId: 'buscarArt'
			},
			{
				xtype: 'button',
				text: 'Pedidos Pendientes',
				itemId: 'pedidoPendiente',
				margin: '0 0 0 5'				
			},
			{
				xtype: 'button',
				text: 'En que formulas',
				itemId: 'enQueFormulas',
				margin: '0 0 0 5'				
			},
			{
				xtype: 'button',
				text: 'Estructura',
				itemId: 'estructuraProducto',
				margin: '0 0 0 5'				
			},
			{
				xtype: 'button',
				text: 'Cotizaciones',
				itemId: 'cotizacionArt',
				margin: '0 0 0 5'				
			},
			{
				xtype: 'button',
				text: 'OTs Pendientes',
				itemId: 'otsPendientes',
				margin: '0 0 0 5'				
			}
		]
		
		var itemsBox2 = [
			
			{
				xtype: 'textfield',
				name: 'unidad',
				readOnly: true,
				fieldLabel: 'Unidad',
				width: 200,
				allowBlank: true
			}
		]
		
		var costos = [
			{
				xtype: 'numberfield',
				decimalSeparator: '.',
				decimalPrecision: 4,
				name: 'costo',
				readOnly: true,
				fieldLabel: 'Costo',
				width: 200,
				allowBlank: true
			},
			{
				xtype      : 'fieldcontainer',
				margins:	'0 0 0 5',
	            defaultType: 'radiofield',
	            defaults: {
	                flex: 1
	            },
	            layout: 'hbox',
	            itemId: 'moneda',
	            items: [
	            	{
	                    boxLabel  : 'Pesos',
	                    checked: true,
	                    name      : 'moneda',
	                    inputValue: 'p',
	                    itemId    : 'pesos'
	                },
	                {
	                    boxLabel  : 'Dolares',
	                    name      : 'moneda',
	                    inputValue: 'd',
	                    itemId 	  : 'dolares'
	                },
	            ]
			},
			{
				xtype: 'numberfield',
				readOnly: true,
				width: 130,
				labelWidth: 40,
				margin: '0 0 0 15',
				itemId: 'iva',
				name: 'iva',
				fieldLabel: 'Iva',
				allowBlank: true,
				decimalSeparator: '.',
				value: 21
			}
		]
		
		var familias = [
			{
				xtype: 'combobox',
				name: 'familia',
				itemId: 'familia',
				width: 350,
				fieldLabel: 'Familia',
				store: 'MetApp.store.Articulos.FamiliaStore',
				displayField: 'familia',
				valueField: 'idFamilia',
				margins: '0 5 0 0'
			},
			{
				xtype: 'combobox',
				itemId: 'subFamilia',
				name: 'subFamilia',
				width: 350,
				fieldLabel: 'Sub familia',
				store: 'MetApp.store.Articulos.SubFamiliaStore',
				displayField: 'subFamilia',
				valueField: 'idSubFamilia',
			}
		]
		
		var precio = [
			{
				xtype: 'numberfield',
				decimalSeparator: '.',
				decimalPrecision: 4,
				name: 'precio',
				readOnly: true,
				fieldLabel: 'Precio',
				width: 200,
				allowBlank: true
			},
			{
				xtype      : 'fieldcontainer',
				margins:	'0 0 0 5',
	            defaultType: 'radiofield',
	            defaults: {
	                flex: 1
	            },
	            layout: 'hbox',
	            itemId: 'monedaPrecio',
	            items: [
	            	{
	                    boxLabel  : 'Pesos',
	                    checked: true,
	                    name      : 'monedaPrecio',
	                    inputValue: 'p',
	                    itemId    : 'pesos'
	                },
	                {
	                    boxLabel  : 'Dolares',
	                    name      : 'monedaPrecio',
	                    inputValue: 'd',
	                    itemId 	  : 'dolares'
	                },
	            ]
			},
			{
				xtype: 'datefield',
				itemId: 'vigenciaPrecio',
				name: 'vigenciaPrecio',
				fieldLabel: 'Vigencia',
				labelWidth: 70,
				margins: '0 0 0 20',
				allowBlank: true,
				readOnly: true
			}
		]
		
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'tabpanel',
					region: 'center',
					tabBarHeaderPosition: 1,
					layout: 'fit',
	                tabBar: {
	                    flex: 1
	                },
					items:[
						{
							title: 'Datos generales',
							items: [
								{
									xtype: 'form',
									store: 'MetApp.store.Articulos.Articulos',
									itemId: 'artForm',	
									border: false,						
									fieldDefaults: {						
										msgTarget: 'side',
										blankText: 'Debe completar este campo',
										allowBlank: false,							
									},
									items: [
										{
											xtype: 'container',
											border: false,
											defaults: {
												disabledCls: 'myDisabledClass'	
											},
											layout: 'hbox',							
											width: 850,
											padding: '5 5 5 5',
											items: autz.getAuthorizedElements(itemsBox1)
										},
										{
											xtype: 'textarea',
											name: 'descripcion',
											margin: '5 5 5 5',
											readOnly: true,
											fieldLabel: 'Descripcion',
											width: 750
										},
										{
											xtype: 'container',
											defaults: {
												disabledCls: 'myDisabledClass'	
											},
											layout: 'hbox',
											padding: '5 5 5 5',
											items: autz.getAuthorizedElements(itemsBox2)
										},
										{
											xtype: 'container',
											defaults: {
												disabledCls: 'myDisabledClass',
												readOnly: true,
												allowBlank: true
											},
											layout: 'hbox',							
											width: 800,
											padding: '0 5 5 5',
											items: autz.getAuthorizedElements(familias)
										},
										{
											xtype: 'container',
											layout: 'hbox',
											items: [
												{
													xtype: 'textfield',
													padding: '0 5 5 5',
													name: 'peso',
													itemId: 'peso',
													fieldLabel: 'Peso (Kg.)',
													readOnly: true,
													allowBlank: true,
													disabledCls: 'myDisabledClass',
												},
												{
													xtype: 'numberfield',
													name: 'stock',
													itemId: 'stock',
													readOnly: true,
													fieldLabel: 'Stock',
													labelWidth: 50,
													width: 150,
													decimalSeparator: '.'
												},
												{
													xtype: 'datefield',
													labelWidth: 80,
													width: 230,
													margin: '0 0 0 5',
													name: 'fechaStock',
													itemId: 'fechaStock',
													allowBlank: true,
													readOnly: true,
													fieldLabel: 'Fecha act.'
												}												
											]
										},
										{
											xtype: 'container',
											layout: 'vbox',
											margin: '5 0 0 5',
											defaults: {
												disabledCls: 'myDisabledClass',
												readOnly: true,
												allowBlank: true,
												store: 'MetApp.store.Proveedores.ProveedoresStore',
												displayField: 'rsocial',
												valueField: 'id',
												margins: '0 5 0 0',
												forceSelection: true,
												queryMode: 'local',
												typeAhead: true,							
												minChars: 1,
												width: 350
											},
											margins: '0 5 0 8',
											items: [
												{
													xtype: 'combobox',
													name: 'provSug1',
													itemId: 'provSug1',															
													fieldLabel: 'Proveedor 1'															
												},
												{
													xtype: 'combobox',
													name: 'provSug2',
													itemId: 'provSug2',
													fieldLabel: 'Proveedor 2'
												},
												{
													xtype: 'combobox',
													name: 'provSug3',
													itemId: 'provSug3',
													fieldLabel: 'Proveedor 3'
												}
											]
										},								
										{
											xtype: 'checkbox',
											readOnly: true,
											disabledCls: 'myDisabledClass',
											padding: '0 5 5 5',
											fieldLabel: 'Requiere control de calidad',
											name: 'requiereControl',
											itemId: 'requiereControl',
											labelWidth: 170,
											uncheckedValue: false
										},
										{
											xtype: 'fieldset',
											collapsible: true,
											collapsed: false,
											itemId: 'fieldSetCosto',
											layout: 'hbox',
											defaults: {
												disabledCls: 'myDisabledClass'	
											},
											title: 'Costos',
											items: autz.getAuthorizedElements(costos)
										},
										{
											xtype: 'fieldset',
											collapsible: true,
											collapsed: false,
											itemId: 'fieldSetPrecio',
											layout: 'hbox',
											defaults: {
												disabledCls: 'myDisabledClass'	
											},
											title: 'Precio',
											items: autz.getAuthorizedElements(precio)
										},
										{
											xtype: 'container',
											layout: 'hbox',
											items: [
												{
													xtype: 'textfield',
													disabledCls: 'myDisabledClass',
													fieldLabel: 'Ruta Server',
													readOnly: true,
													name: 'rutaServer',
													itemId: 'rutaServer',
													width: 700,
													labelWidth: 100,
													allowBlank: true,
													margin: '5 0 0 5',
												},
												{
													xtype: 'button',
													text: 'Copiar',
													margins: '5 0 0 5',
													itemId: 'copiarRuta'
												}
											]
										},												
										{
											xtype: 'container',	
											height: 55,
											margin: '5 0 0 5',
											layout: 'hbox',
											cls: 'panelBtn',
											width: 600,
											items: botonera
										}
									]
								}		
							]
						},						
						{
							title: 'Imagenes',
							items: [
								{
									xtype: 'form',
									border: false,
									margin: '5 0 0 0',
									width: 600,									
									items: [
										{
											xtype: 'textfield',
											name: 'idArt',
											itemId: 'idArt',
											fieldLabel: 'id',
											hidden: true
										},						
										{
											xtype: 'filefield',
											width: 600,
											fieldLabel: 'Plano',
											margin: '0 0 0 5',
											labelWidth: 35,
											name: 'rutaPlano',
											itemId: 'nombreImagen',
											buttonText: 'Seleccion archivo...',
										},
										{
											xtype: 'button',
											margin: '5 5 5 5',
											itemId: 'cargarImagen',
											text: 'Guardar'
										}
									]
								},
								{
								    xtype: 'component',
								    hidden: true,
								    margin: '0 0 0 5',
								    itemId: 'imagen',
								    autoEl: {
								        tag: 'a',
								        name: 'imagen',								        
								        href: '#',
								        html: 'Ver imágen',
								        target: '_blank'
								    }
								}
							]
						}
					]
				},
								
			]
		});
		this.callParent();
	}
});
