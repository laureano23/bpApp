Ext.define('MetApp.view.Produccion.Pedidos.NuevoPedidoForm', {
	extend: 'Ext.window.Window',
	alias: 'widget.nuevoPedidoForm',
	itemdId: 'nuevoPedidoForm',
	title: 'Nuevo pedido de clientes',
	layout: 'fit',
	modal: true,
	autoShow: true,
	listeners: {
		close: function(panel, eOpts){
			store = panel.down('grid').getStore();
			store.removeAll();
		}
	},
	
	initComponent: function(){
		
		var line1 = [
			{
				xtype: 'numberfield',
				name: 'cliente',
				itemId: 'idCliente',				
				readOnly: true,
				hidden: true,
				width: 150,
				fieldLabel: 'IdCliente:'
			},
			{
				xtype: 'textfield',
				name: 'rsocial',
				itemId: 'cliente',				
				readOnly: true,
				width: 600,
				fieldLabel: 'Cliente:'
			},
			{
				xtype: 'button',
				margins: '5 5 5 5',
				height: 25,				
				width: 30,
				iconCls: 'search',
				action: 'buscaCliente',
				itemId: 'buscaCliente'
			}
		]
		
		var lineInt = [	
			{
				xtype: 'textfield',
				itemId: 'oc',
				width: 150,
				labelWidth: 40,
				name: 'oc',
				fieldLabel: 'O/C :',
				allowBlank: false
			},
			{
				xtype: 'textfield',
				allowBlank: true,
				itemId: 'autEntrega',
				width: 130,
				labelWidth: 50,
				name: 'autEntrega',
				fieldLabel: 'Aut. N°:'
			}
		]
		
		var line2 = [
			{
				xtype: 'grid',
				itemId: 'gridPedidosClientes',
				alias: 'widget.gridPedidosClientes',
				anchorSize: '100%',
				height: 200,	
				autoScroll: true,				
				multiSelect: true,
				store: {
					xtype: 'store',
					model: 'MetApp.model.Produccion.PedidoClientes.PedidosClientesModel',
					autoSync: true,
					proxy: {
						type: 'memory',		
								
						reader: {
							type: 'json',
							root: 'pedidos'			
						},
								
						writer: {
							type: 'json',
							root: 'pedidos',
							encode: true
						}
					}
				},
				columns: [
					{xtype: 'rownumberer', width: 50, sortable: false},						
					{header: 'Codigo', dataIndex: 'codigo', flex: 1},
					{header: 'Descripcion', dataIndex: 'descripcion', width: 400},
					{header: 'Cantidad', dataIndex: 'cantidad', flex: 1},
					{header: 'U', dataIndex: 'unidad', width: 50},
					{header: 'Precio', dataIndex: 'precio', flex: 1},
					{header: 'Fecha Prog.', dataIndex: 'fechaProgramacion', flex: 1},
				]										
			}			
		]
		
				
		var line3 = [
			{
				xtype: 'textfield',
				name: 'codigo',
				readOnly: true,
				width: 200,
				labelWidth: 50,
				fieldLabel: 'Codigo:',
				listeners: {
					change: {
						fn: function(txt){
							var win = txt.up('window');
							var desc = win.queryById('descripcion');
							if(txt.getValue() == 'ZZZ'){
								desc.setReadOnly(false);
							}
						}
					}
				}
			},
			{
				xtype: 'button',	
				disabled: true,
				margins: '5 5 5 5',	
				height: 25,				
				width: 30,
				iconCls: 'search',
				action: 'buscaArt',
				itemId: 'buscaArt'
			},
			{
				xtype: 'textfield',
				name: 'descripcion',
				itemId: 'descripcion',
				readOnly: true,
				width: 600,
				fieldLabel: 'Descripcion:'
			}
		]
		
		var line4 = [
			{
				xtype: 'numberfield',
				name: 'cantidad',
				itemId: 'cantidad',
				labelWidth: 55,
				width: 150,
				decimalSeparator: '.',
				fieldLabel: 'Cantidad:'
			},
			{
				xtype: 'textfield',
				name: 'unidad',
				allowBlank: true,
				readOnly: true,
				width: 100,
				labelWidth: 30,
				fieldLabel: 'Un:'
			},
			{
				xtype: 'datefield',
				name: 'fechaProgramacion',
				fieldLabel: 'Fecha Prog:'
			},
					
		]
		
		var line5= [
			{
				xtype: 'button',
				margins: '5 5 5 5',
				itemId: 'insertPedido',
				action: 'insertPedido',
				text: 'Insertar'				
			},
			{
				xtype: 'button',
				margins: '5 5 5 5',
				itemId: 'editPedido',
				action: 'editPedido',
				text: 'Editar'				
			},
			{
				xtype: 'button',
				margins: '5 5 5 5',
				itemId: 'deletePedido',
				action: 'deletePedido',
				text: 'Borrar'				
			},
			{
				xtype: 'button',
				margins: '5 5 5 5',
				itemId: 'savePedido',
				action: 'savePedido',
				text: 'Guardar'				
			}
		]
		
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',					
					fieldDefaults: {
						margins: '5 5 5 5',	
						allowBlank: false
					},					
					items: [
						{
							xtype: 'container',
							border: false,
							items: line1,
							layout: 'hbox'
						},
						{
							xtype: 'container',
							border: false,
							items: lineInt,
							layout: 'hbox'
						},
						{
							xtype: 'container',
							height: 200,
							width: 850,
							border: false,
							items: line2,
							layout: 'anchor'
						},
						{
							xtype: 'container',
							border: false,
							items: line3,
							layout: 'hbox'
						},
						{
							xtype: 'container',
							border: false,
							items: line4,
							layout: 'hbox'
						},
						{
							xtype: 'container',
							border: false,
							items: line5,
							layout: 'hbox'
						}		
					]
				}
			]
		});
		this.callParent();
	}
});
