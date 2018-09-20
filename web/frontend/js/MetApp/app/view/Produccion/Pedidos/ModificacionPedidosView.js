Ext.define('MetApp.view.Produccion.Pedidos.ModificacionPedidosView', {
	extend: 'Ext.window.Window',
	alias: 'widget.ModificacionPedidosView',
	itemdId: 'ModificacionPedidosView',
	title: 'Modificacion Pedidos de Clientes',
	layout: 'border',
	modal: true,
	autoShow: true,
	width: 1100,
	height: 500,
	bodyStyle: {
		background: 'white'
	},
	
	initComponent: function(){
		
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					frame: false,
					region: 'north',
					border: false,
					margins: '5 5 5 5',
					layout: 'hbox',
					items: [
						{
							xtype: 'textfield',
							fieldLabel: 'Cliente',
							itemId: 'cliente',
							name: 'cliente',
							enableKeyEvents: true
						},
					]
				},
				{
					xtype: 'grid',
					region: 'center',
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
					height: 500,	
					width: 1100,
					autoScroll: true,				
					multiSelect: true,
					store: 'Produccion.PedidoClientes.PedidoClientesStore',
					columns: [
						{xtype: 'rownumberer', width: 50, sortable: false, hidden: true},
						{header: 'id', dataIndex: 'idPedido', width: 50, hidden: true},
						{header: 'id Detalle', dataIndex: 'idDetalle', width: 50, hidden: false},					
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
