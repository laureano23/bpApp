Ext.define('MetApp.view.Compras.PendienteArticuloComprasView' ,{
	extend: 'Ext.window.Window',
	height: 500,
	width: 600,
	modal: true,
	autoShow: true,
	alias: 'widget.PendienteArticuloComprasView',
	itemId: 'PendienteArticuloComprasView',
	title: 'Pendientes de ingreso',
	layout: 'border',
	bodyStyle: {
		'background-color': 'white'
	},
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'grid',
					store: {
						model: 'MetApp.model.Compras.OrdenCompraModel'
					},
					cls: 'extra-alt',
					region: 'center',
					columns: [
						{ text: 'OC N°', dataIndex: 'idOc', flex: 1 },
						{ 
							text: 'Fecha entrega',
							dataIndex: 'entrega',
							flex: 1, 
							xtype: 'datecolumn',
							dateFormat: 'd/m/Y',
							renderer: function(val){
								return val;
							}, 
						},
						{ text: 'Pendiente', dataIndex: 'pendiente', flex: 1 }, //SALE DE CANTIDAD - CUMPLIDO
					]
				},
				{
					xtype: 'container',
					region: 'south',
					border: false,
					margins: '5 5 5 5',
					style: {
						'background-color': 'white'
					},
					layout: 'vbox',
					items: [
						{
							xtype: 'button',							
							text: 'Aceptar',
							itemId: 'ok'
						}	
					]
				}							
			]
		});
		this.callParent();
	}
});
