Ext.define('MetApp.view.Produccion.Maquinas.MaquinasTabla', {
	extend: 'Ext.window.Window',
	modal: true,
	width: 800,
	layout: 'fit',
	itemId: 'maquinasTabla',
	alias: 'widget.maquinasTabla',	
	autoShow: true,
	title: 'Tabla de maquinas',
	
	initComponent: function(){
		var me = this;
		
		/*
		 * Defino el objeto que manejara la seguridad de las vistas
		 */
		var autz = Ext.create('MetApp.controller.Security.Autorizaciones');
		autz.authorization(MetApp.User);
		
		var itemsArtMenu = [
			{
				xtype: 'fieldset',
				padding: '0 0 0 0',
				width: 800,	
				height: 52,
				border: false,
				defaults: {
					disabled: false,
					margin: '1 1 1 1'
				},
				
				items: [
					{
						xtype: 'button',
						margin: '1 1 1 0',
						iconCls: 'add',												
						height: 50,
						width: 75,
						text: 'Nuevo',
						action: 'actNewArt',
						itemId: 'btnNewArt'
					},
					{
						xtype: 'button',
						iconCls: 'save',
						disabled: true,
						height: 50,
						width: 75,
						text: 'Guardar',
						action: 'actSaveArt',
						itemId: 'btnSaveArt'
					},
					{
						xtype: 'button',
						iconCls: 'edit',
						height: 50,
						width: 75,
						text: 'Editar',
						action: 'actEditArt',
						itemId: 'btnEditArt'
					},
					{
						xtype: 'button',
						iconCls: 'delete',
						height: 50,
						width: 75,
						text: 'Eliminar',
						action: 'actDeleteArt',
						itemId: 'btnDelete'
					},
					{
						xtype: 'button',
						iconCls: 'reset',
						disabled: true,
						height: 50,
						width: 75,
						text: 'Volver',
						action: 'actResetForm',
						itemId: 'btnReset'
					}	
				]
			}
			
		]
		
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					border: false,
					defaults: {
						margin: '5 5 5 5'
					},					
					items: [
						{
							xtype: 'container',
							defaults: {
								margin: '0 5 0 5',
								labelWidth: 80,
								disabled: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									name: 'codigo',
									itemId: 'codigo',
									width: 150,
									fieldLabel: 'Codigo'
								},
								{
									xtype: 'button',
									disabled: false,
									itemId: 'buscaMaquina',
									iconCls: 'search'
								},
								{
									xtype: 'textfield',
									name: 'descripcion',
									width: 500,
									fieldLabel: 'Descripcion'
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								margin: '0 5 0 5',
								labelWidth: 80,
								width: 200,
								disabled: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									name: 'marca',
									itemId: 'marca',
									fieldLabel: 'Marca'
								},
								{
									xtype: 'textfield',
									name: 'modelo',
									itemId: 'modelo',
									fieldLabel: 'Modelo'
								},
								{
									xtype: 'textfield',
									name: 'origen',
									itemId: 'origen',
									fieldLabel: 'Origen'
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								margin: '0 5 0 5',
								labelWidth: 80,
								width: 200,
								disabled: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									name: 'sector',
									itemId: 'sector',
									fieldLabel: 'Sector'
								},
								{
									xtype: 'textfield',
									name: 'piso',
									itemId: 'piso',
									fieldLabel: 'Piso'
								},
								{
									xtype: 'textfield',
									name: 'nave',
									itemId: 'nave',
									fieldLabel: 'Nave'
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								margin: '0 5 0 5',
								labelWidth: 80,
								width: 200,
								disabled: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									name: 'anoOrigen',
									itemId: 'anoOrigen',
									fieldLabel: 'Año origen'
								},
								{
									xtype: 'datefield',
									name: 'anoCompra',
									itemId: 'anoCompra',
									fieldLabel: 'Año compra'
								},
								{
									xtype: 'numberfield',
									labelWidth: 90,
									name: 'valorCompra',
									itemId: 'valorCompra',
									fieldLabel: 'Valor compra'
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								margin: '0 5 0 5',
								labelWidth: 80,
								width: 200,
								disabled: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'hbox',
							items: [								
								{
									xtype: 'numberfield',
									name: 'vidaUtil',
									itemId: 'vidaUtil',
									fieldLabel: 'Vida util'
								},
								{
									xtype: 'numberfield',
									name: 'peso',
									itemId: 'peso',
									fieldLabel: 'Peso (Kg)'
								},
								{
									xtype: 'fieldcontainer',
									defaultType: 'radiofield',
									defaults: {
										padding: '0 5 0 5'	
									},
									border: 2,
									style: {
									    borderColor: 'black',
									    borderStyle: 'solid'
									},									
									layout: 'hbox',
									name: 'criticidadRadio',
									itemId: 'criticidad',
									fieldLabel: 'Criticidad',
									items: [
										{
											boxLabel: 'A',
											name: 'criticidad',
											inputValue: 'A' 
										},
										{
											boxLabel: 'B',
											name: 'criticidad',
											inputValue: 'B' 
										},
										{
											boxLabel: 'C',
											name: 'criticidad',
											inputValue: 'C',
											checked: true
										}
									]
								}
							]
						},
						{
							xtype: 'container',
							defaults: {
								margin: '0 5 0 5',
								labelWidth: 80,
								disabled: true,
								disabledCls: 'myDisabledClass'
							},
							layout: 'hbox',
							items: [
								{
									xtype: 'textareafield',
									grow: true,
									height: 100,
									width: 750,
									name: 'notas',
									itemId: 'notas',
									fieldLabel: 'Notas'
								}
							]
						},
						{
							xtype: 'container',	
							height: 55,
							margin: '0 0 0 0',
							layout: 'hbox',
							cls: 'panelBtn',
							width: 800,
							items: autz.getAuthorizedElements(itemsArtMenu)
						}
					]
				}
			]
		});
		this.callParent();
	}
});
