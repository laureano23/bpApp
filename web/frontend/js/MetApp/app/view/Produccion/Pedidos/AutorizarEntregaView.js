Ext.define('MetApp.view.Produccion.Pedidos.AutorizarEntregaView', {
	extend: 'Ext.window.Window',
	alias: 'widget.AutorizarEntregaView',
	itemdId: 'AutorizarEntregaView',
	title: 'Autorizar entregas',
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
					store: 'Produccion.PedidoClientes.AutorizarEntregaStore',
					columns: [
						//{xtype: 'rownumberer', width: 50, sortable: false},
						{header: 'id Pedido', dataIndex: 'idPedido', width: 50, hidden: true},
						{header: 'id Detalle', dataIndex: 'idDetalle', width: 50, hidden: true},					
						{header: 'Codigo', dataIndex: 'codigo', width: 110},
						{header: 'Descripcion', dataIndex: 'descripcion', width: 250},
						{
							header: 'Cantidad',
							dataIndex: 'cantidad',
							width: 'auto',
						},
						{
							header: 'OC',
							dataIndex: 'oc',
							width: 80
						},
						{
							header: 'Entregado',
							dataIndex: 'entregado',
							width: 'auto',
						},
						{
							header: 'Saldo',
							width: 'auto',
							renderer: function(col, st, meta){
								return meta.data.cantidad - meta.data.entregado;
							}
						},
						{header: 'Cliente', dataIndex: 'clienteDesc', width: 150},
						{
							header: 'Fecha Prog.',
							dataIndex: 'fechaProgramacion',
							width: 100,
							format: 'd/m/Y',
							submitFormat: 'd/m/Y',
							xtype:'datecolumn',	
						},
						{
							header: 'Autorizado',
							dataIndex: 'cantAutorizada',
							width: 'auto',
							editor: {
								xtype: 'numberfield',
							}
						},
						{
							header: 'Observación',
							dataIndex: 'observacionesAutorizacion',
							width: 160,
							editor: {
								xtype: 'textfield',
							}
						},
					]										
				}
			]
		});
		this.callParent();
	}
});
