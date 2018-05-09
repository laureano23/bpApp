Ext.define('MetApp.view.Utilitarios.ListadoCotizaciones' ,{
	extend: 'Ext.window.Window',
	height: 500,
	width: 600,
	modal: true,
	autoShow: true,
	alias: 'widget.ListadoCotizaciones',
	itemId: 'ListadoCotizaciones',
	title: 'Listado de Cotizaciones',
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
					store: 'MetApp.store.Clientes.CotizacionDetalleStore',
					columns: [
						{ text: 'Fecha', dataIndex: 'fecha', flex: 1 },
						{ text: 'Cliente', dataIndex: 'cliente', flex: 1 },
						{ text: 'Cot. N°', dataIndex: 'id', flex: 1 },	
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
							itemId: 'filtroCliente',
							enableKeyEvents: true,
							width: 500,
							labelWidth: 150,
							margin: '5 5 5 5',
							fieldLabel: 'Cliente'
						}
					]
				}		
			]
		});
		this.callParent();
	}
});
