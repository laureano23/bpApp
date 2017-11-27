Ext.define('MetApp.view.Compras.PedidosInternosView' ,{
	extend: 'Ext.window.Window',
	height: 400,
	width: 1000,
	modal: true,
	autoShow: true,
	alias: 'widget.PedidosInternosView',
	itemId: 'PedidosInternosView',
	title: 'Formulario pedidos de materiales',
	layout: 'border',
	
	
	initComponent: function(){
		var me = this;		
		var user = MetApp.User.name.name;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					border: false,
					itemId: 'formPedidos',
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
									xtype: 'textfield',
									name: 'emisor',
									itemId: 'emisor',
									fieldLabel: 'Emisor',	
									value: user														
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
					]
				},		
				{
					xtype: 'grid',
					region: 'center',
					//margin: '5 0 0 0',
					border: false,
					itemId: 'detallesStore',
					store: 'MetApp.store.Compras.OrdenCompraStore',
					height: 150,
					columns: {
						items: [
							{ text: 'IdArt', dataIndex: 'id', hidden: true, flex: 1 },
							{ text: 'Codigo', dataIndex: 'codigo', width: 150 },
							{ text: 'Descripcion', dataIndex: 'descripcion', width: 380 },
							{ text: 'Cantidad', dataIndex: 'cant', width: 70 },
							{ text: 'Unidad', dataIndex: 'unidad', width: 55 },							
							{ 
								text: 'Entrega',
								dataIndex: 'entrega',
								xtype: 'datecolumn',
								format: 'd/m/Y',
								width: 100,
							},
							{ 
								header: 'Editar',
								width: 90,
								itemId: 'editar',
								xtype: 'actioncolumn',								
								items: [
									{ 
										iconCls: 'edit',
									}										
								],
								dataIndex: 'imputado'
							},	
							{ 
								header: 'Eliminar',
								width: 90,
								itemId: 'eliminar',
								xtype: 'actioncolumn',
								items: [
									{ iconCls: 'delete' }										
								], 
							},	
						]
					},
				},
				{
					xtype: 'form',
					region: 'south',
					border: false,
					style: {
						background: 'white'
					},
					layout: 'vbox',
					itemId: 'formaArt',
					padding: '5 5 5 5',
					items: [
						{
							xtype: 'container',
							itemId: 'cntBtn',							
							anchorSize: '100%',
							layout: 'hbox',
							style: {
								background: 'white'
							},
							defaults: {
								labelWidth: 90
							},
							margin: '0 0 5 0',
							items: [
								{
									xtype: 'textfield',
									fieldLabel: 'Código',
									name: 'codigo',									
									labelWidth: 55,
									readOnly: true
								},
								{
									xtype: 'button',
									itemId: 'btnCodigo',
									iconCls: 'search',
									margin: '0 0 0 2'
								},
								{
									xtype: 'textfield',
									name: 'descripcion',
									fieldLabel: 'Descripción',
									width: 550,
									readOnly: true
								}	
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							style: {
								background: 'white'
							},
							defaults: {
								labelWidth: 90
							},
							items: [
								{
									xtype: 'numberfield',
									name: 'cant',
									itemId: 'cantidad',
									fieldLabel: 'Cantidad'
								},
								{
									xtype: 'textfield',
									name: 'unidad',
									itemId: 'unidad',
									fieldLabel: 'Unidad'
								},
								{
									xtype: 'datefield',
									fieldLabel: 'Fecha entrega',
									itemId: 'fechaEntrega',
									name: 'entrega',
									dateFormat: 'd/m/Y'
								},
								{
									xtype: 'button',
									itemId: 'insertar',
									text: 'Insertar',
									margin: '0 0 0 2'
								}
							]
						},		
						{
							xtype: 'button',
							text: 'Guardar',
							itemId: 'guardar'
						}
					]
				}		
			]
		});
		this.callParent();
	}
});
