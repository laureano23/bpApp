Ext.define('MetApp.view.Produccion.Pedidos.PedidosPendientesView', {
	extend: 'Ext.window.Window',
	alias: 'widget.PedidosPendientesView',
	itemdId: 'PedidosPendientesView',
	title: 'Pedidos Pendientes',
	layout: 'vbox',
	modal: true,
	autoShow: true,
	
	
	initComponent: function(){
		
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'grid',
		        	viewConfig: {
		        		enableTextSelection: true,
			    	},
					itemId: 'gridPedidosPendientes',
					alias: 'widget.gridPedidosPendientes',
					anchorSize: '100%',
					height: 200,	
					width: 1100,
					autoScroll: true,	
					store: 'Produccion.PedidoClientes.PedidoClientesStore',
					columns: [
						{xtype: 'rownumberer', width: 50, sortable: false},
						{header: 'id Pedido', dataIndex: 'idPedido', width: 50, hidden: true},
						{header: 'id Detalle', dataIndex: 'idDetalle', width: 50, hidden: true},					
						{header: 'Codigo', dataIndex: 'codigo', width: 200},
						{header: 'Descripcion', dataIndex: 'descripcion', width: 300},
						{header: 'Cantidad', dataIndex: 'cantidad', width: 'auto'},
						{header: 'OC', dataIndex: 'oc', width: 150},
						{
							header: 'Fecha Prog.',
							dataIndex: 'fechaProgramacion',
							width: 150,
							format: 'd/m/Y',
							submitFormat: 'd/m/Y',
							xtype:'datecolumn',							
						},
					]										
				},
				{
					xtype:'button',
					margins: '5 5 5 5',
					text: 'Insertar',
					itemId: 'insert'
				}
			]
		});
		this.callParent();
	}
});
