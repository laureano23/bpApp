Ext.define('MetApp.view.Compras.ListaPedidosInternosView' ,{
	extend: 'Ext.window.Window',
	height: 500,
	width: 1200,
	modal: true,
	autoShow: true,
	alias: 'widget.ListaPedidosInternosView',
	itemId: 'ListaPedidosInternosView',
	title: 'Pedidos Internos',
	layout: 'border',
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'grid',
					plugins: [
	        			Ext.create('Ext.grid.plugin.CellEditing',{
	        				clicksToEdit: 1,
	        				pluginId: 'cellplugin',
	        				listeners: {
					        }				
	        			})
		        	],
		        	selType: 'cellmodel',
					cls: 'extra-alt',
					region: 'center',
					store: 'MetApp.store.Compras.OrdenCompraStore',
					columns: [
						{ text: 'id Detalle', dataIndex: 'id', width: 100, hidden: true },
						{ text: 'Emision', dataIndex: 'fecha', width: 100 },
						{ text: 'Código', dataIndex: 'codigo', width: 150 },
						{ text: 'Descripción', dataIndex: 'descripcion', width: 300 },
						{ text: 'Cantidad', dataIndex: 'cant', flex: 1 },
						{ 
							header: 'Cumplido',
							dataIndex: 'cumplido',
							flex: 1,
							editor: {
								xtype: 'numberfield',
			               	}, 
						},
						{ 
							header: 'Pedido',
							dataIndex: 'pedido',
							flex: 1,
							editor: {
								xtype: 'numberfield',
			               	}, 
						},
						{ text: 'Pendiente', dataIndex: 'pendiente', flex: 1 }, //SALE DE CANTIDAD - CUMPLIDO
						{ text: 'Precio', dataIndex: 'precio', flex: 1 },
						{ text: 'Moneda', dataIndex: 'moneda', flex: 1 },
						{ text: 'Proveedor', dataIndex: 'proveedor', width: 150 },
						{ text: 'Entrega', dataIndex: 'entrega', flex: 1 },
					]
				},		
			]
		});
		this.callParent();
	}
});
