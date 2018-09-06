Ext.define('MetApp.view.Articulos.Stock.Remitos.RemitosPendientesView' ,{
	extend: 'Ext.window.Window',	
	modal: true,
	autoShow: true,
	alias: 'widget.RemitosPendientesView',
	itemId: 'RemitosPendientesView',
	title: 'Remitos pendientes de facturación',
	layout: 'fit',
	
	initComponent: function(){
		
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'container',
					layout: 'vbox',
					margins: '5 5 5 5',
					items: [
						{
							xtype: 'grid',
							height: 400,
							width: 1200,
							itemId: 'gridRemitoPendiente',
							store: 'MetApp.store.Articulos.RemitosPendientesStore',
							columns: [						
								{header: 'Codigo', dataIndex: 'codigo', flex: 1},
								{header: 'Descripcion', dataIndex: 'descripcion', width: 400},
								{header: 'Cantidad', dataIndex: 'cantidad', flex: 1},
								{header: 'U', dataIndex: 'unidad', width: 50},
								{header: 'Cliente', dataIndex: 'cliente', width: 300},
								{header: 'OC', dataIndex: 'oc', flex: 1},
								{header: 'Pedido N°', dataIndex: 'pedido', flex: 1},
								{header: 'Remito N°', dataIndex: 'numeroRemito', flex: 1},
								{ xtype : 'checkcolumn', text : 'Marca', dataIndex: 'facturado' }
							]
						},
						{
							xtype: 'button',
							margins: '5 0 0 0',
							text: 'Aceptar',
							itemId: 'insertar'
						}
					]
				}
			]
			
		});
		
		this.callParent();
	}
});
