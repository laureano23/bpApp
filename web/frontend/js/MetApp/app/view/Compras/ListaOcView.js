Ext.define('MetApp.view.Compras.ListaOcView' ,{
	extend: 'Ext.window.Window',
	height: 500,
	width: 600,
	modal: true,
	autoShow: true,
	alias: 'widget.ListaOcView',
	itemId: 'ListaOcView',
	title: 'Listado ordenes de compra',
	layout: 'border',
	bodyStyle: {
		background: 'white'
	},
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'grid',
					region: 'center',
					store: 'MetApp.store.Compras.OrdenCompraStore',
					columns: [
						{ text: 'Fecha', dataIndex: 'fecha', flex: 1 },
						{ text: 'Proveedor', dataIndex: 'proveedor', flex: 1 },
						{ text: 'OC N°', dataIndex: 'idOc', flex: 1 },	
						{ 
							header: 'Detalle',
							itemId: 'detalle',
							xtype: 'actioncolumn',
							items: [
								{ iconCls: 'search' }										
							],
							flex: 1
						},	
						{ 
							header: 'Eliminar',
							itemId: 'eliminar',
							xtype: 'actioncolumn',
							items: [
								{ iconCls: 'delete' }										
							],
							flex: 1
						},				
					]
				},
				{
					xtype: 'container',
					region: 'south',
					items: [
						{
							xtype: 'textfield',
							itemId: 'filtroProveedor',
							enableKeyEvents: true,
							width: 500,
							labelWidth: 150,
							margin: '5 5 5 5',
							fieldLabel: 'Proveedor'
						}
					]
				}		
			]
		});
		this.callParent();
	}
});
