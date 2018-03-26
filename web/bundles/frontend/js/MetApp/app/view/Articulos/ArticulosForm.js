Ext.define('MetApp.view.Articulos.ArticulosForm', {
	extend: 'Ext.window.Window',
	alias: 'widget.articulosform',
	id: 'articulosForm',
	itemId: 'articulosForm',
	width: 800,
	height: 370,
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
				itemId: 'idCodigo',
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
				iconCls: 'search',
				action: 'actBuscaArt',
				itemId: 'buscarArt'
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
				width: 110,
				labelWidth: 40,
				margin: '0 0 0 15',
				itemId: 'iva',
				name: 'iva',
				fieldLabel: 'Iva',
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
											width: 600,
											padding: '5 5 5 5',
											items: autz.getAuthorizedElements(itemsBox1)
										},
										{
											xtype: 'textfield',
											name: 'descripcion',
											margin: '5 5 5 5',
											readOnly: true,
											fieldLabel: 'Descripcion',
											width: 550
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
											height: 55,
											padding: '1 0 0 1',
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
