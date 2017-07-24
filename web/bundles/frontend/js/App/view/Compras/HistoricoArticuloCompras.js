Ext.define('MetApp.view.Compras.HistoricoArticuloCompras' ,{
	extend: 'Ext.window.Window',
	height: 500,
	width: 1000,
	modal: true,
	autoShow: true,
	alias: 'widget.HistoricoArticuloCompras',
	itemId: 'HistoricoArticuloCompras',
	title: 'Histórico de compras',
	layout: 'border',
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'grid',
					region: 'center',
					store: 'MetApp.store.Compras.HistoricoCompraStore',
					columns: [
						{ text: 'Fecha', dataIndex: 'fecha', flex: 1 },
						{ text: 'Código', dataIndex: 'codigo', flex: 1 },
						{ text: 'Descripción', dataIndex: 'descripcion', width: 300 },
						{ text: 'Cantidad', dataIndex: 'cant', flex: 1 },
						{ text: 'Cumplido', dataIndex: 'cumplido', flex: 1 },
						{ text: 'Pendiente', dataIndex: 'pendiente', flex: 1 }, //SALE DE CANTIDAD - CUMPLIDO
						{ text: 'Precio', dataIndex: 'precio', flex: 1 },
						{ text: 'Moneda', dataIndex: 'moneda', flex: 1 },
						{ text: 'Proveedor', dataIndex: 'proveedor', flex: 1 },
						{ text: 'OC N°', dataIndex: 'idOc', flex: 1 },
					]
				},		
			]
		});
		this.callParent();
	}
});
