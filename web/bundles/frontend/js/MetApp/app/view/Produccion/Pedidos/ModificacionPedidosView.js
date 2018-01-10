Ext.define('MetApp.view.Produccion.Pedidos.ModificacionPedidosView', {
	extend: 'Ext.window.Window',
	alias: 'widget.ModificacionPedidosView',
	itemdId: 'ModificacionPedidosView',
	title: 'Modificacion Pedidos de Clientes',
	layout: 'fit',
	modal: true,
	autoShow: true,
	
	
	initComponent: function(){
		
		var me = this;
		Ext.applyIf(me, {
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
		        	viewConfig: {
		        		enableTextSelection: true,
			    	},
		        	selType: 'cellmodel',
					itemId: 'gridModificacionPedidos',
					alias: 'widget.gridModificacionPedidos',
					anchorSize: '100%',
					height: 200,	
					width: 1100,
					autoScroll: true,				
					multiSelect: true,
					store: 'Produccion.PedidoClientes.PedidoClientesStore',
					columns: [
						{xtype: 'rownumberer', width: 50, sortable: false},
						{header: 'id Pedido', dataIndex: 'idPedido', width: 50, hidden: true},
						{header: 'id Detalle', dataIndex: 'idDetalle', width: 50, hidden: true},					
						{header: 'Codigo', dataIndex: 'codigo', width: 200},
						{header: 'Descripcion', dataIndex: 'descripcion', width: 300},
						{
							header: 'Cantidad',
							dataIndex: 'cantidad',
							width: 'auto',
							editor: {
								xtype: 'numberfield',
							}
						},
						{header: 'Cliente', dataIndex: 'clienteDesc', width: 250},
						{
							header: 'Fecha Prog.',
							dataIndex: 'fechaProgramacion',
							width: 150,
							format: 'd/m/Y',
							submitFormat: 'd/m/Y',
							xtype:'datecolumn',
							editor: {
				                xtype: 'datefield',
				                format: 'd/m/Y',
				                submitFormat: 'd/m/Y',
				            },	
						},
						{ 
							header: 'Eliminar',
							itemId: 'eliminar',
							xtype: 'actioncolumn',
							iconCls: 'delete',
							width: 'auto'
						},
					]										
				}
			]
		});
		this.callParent();
	}
});
