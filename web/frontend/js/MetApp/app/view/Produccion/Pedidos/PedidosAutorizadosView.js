Ext.define('MetApp.view.Produccion.Pedidos.PedidosAutorizadosView', {
	extend: 'Ext.window.Window',
	alias: 'widget.PedidosAutorizadosView',
	itemdId: 'PedidosAutorizadosView',
	title: 'Pedidos Autorizados',
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
						{
							xtype: 'button',
							itemId:'insertar',
							text:'Insertar',
							margins: '0 0 0 5'
						}
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
						{header: 'Descripcion', dataIndex: 'descripcion', width: 300},
						{
							header: 'Cantidad',
							dataIndex: 'cantidad',
							width: 'auto',
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
						{header: 'Cliente', dataIndex: 'clienteDesc', width: 250},
						{
							header: 'Fecha Prog.',
							dataIndex: 'fechaProgramacion',
							width: 150,
							format: 'd/m/Y',
							submitFormat: 'd/m/Y',
							xtype:'datecolumn',	
						},
						{
							header: 'Autorizado',
							dataIndex: 'cantAutorizada',
							width: 'auto'
						},
						/*{ 
							xtype : 'checkcolumn',
							text : 'Marcar',
							dataIndex: 'marcar',
						}*/
					]										
				}
			]
		});
		this.callParent();
	}
});
