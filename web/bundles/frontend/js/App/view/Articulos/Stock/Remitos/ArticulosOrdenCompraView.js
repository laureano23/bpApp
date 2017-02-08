Ext.define('MetApp.view.Articulos.Stock.Remitos.ArticulosOrdenCompraView' ,{
	extend: 'Ext.window.Window',
	height: 300,
	width: 500,
	modal: true,
	autoShow: true,
	alias: 'widget.ArticulosOrdenCompraView',
	itemId: 'ArticulosOrdenCompraView',
	title: 'Pedidos pendientes',
	layout: 'border',
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'grid',
					border: false,
					frame: false,
					itemId: 'gridPendientes',
					width: 450,
					region: 'center',
					layout: 'fit',
					store: {
						proxy: {
					    	type: 'memory',
					        reader: {
					            type: 'json',
					            root: 'data'
					        }
					    },
					    fields: ['codigo', 'oc', 'pedidoNum']
					},					
					columns: [
						{ 
							text: 'Codigo',
							dataIndex: 'codigo',
							flex: true,
						},
						{ 
							text: 'OC',
							dataIndex: 'oc',
							flex: true,
						},
				        {
				            text:'Pedido',
				            dataIndex:'pedidoNum',				            
				            text: 'pedido N°',
				            flex: true,
				        }
					]
				},
				{
					xtype: 'button',
					itemId: 'ingresaOC',
					width: 70,	
					region: 'south',
					text: 'Ingresar',
					iconCls: 'add'
				}
			]
		});
		this.callParent();
	}
});
