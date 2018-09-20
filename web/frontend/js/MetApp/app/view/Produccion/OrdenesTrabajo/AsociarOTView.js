Ext.define('MetApp.view.Produccion.OrdenesTrabajo.AsociarOTView', {
	extend: 'Ext.window.Window',
	alias: 'widget.AsociarOTView',
	itemdId: 'AsociarOTView',
	title: 'Formulario Asociar OT con Pedido',
	layout: 'fit',
	modal: true,
	autoShow: true,
	
	
	initComponent: function(){		
				
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',	
					border: false,
					frame: false,				
					fieldDefaults: {
						margins: '5 5 5 5',	
						allowBlank: false,
						readOnly: false
					},
					defaults: {
						margin: '0 5 0 0'
					},
					layout: 'vbox',					
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									fieldLabel: 'Pedidos',
									name: 'idPedido',									
									labelWidth: 55,
									itemId: 'idPedido'
								},
								{
									xtype: 'button',
									itemId: 'buscarPedido',
									iconCls: 'search',
									margin: '4 0 0 0'
								},
							]
						},	
						{
							xtype: 'numberfield',
							name: 'ot',
							labelWidth: 55,
							itemId: 'ot',
							fieldLabel: 'OT'
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'button',
									margins: '5 0 5 5',
									text: 'Asociar',
									itemId: 'insert'
								},	
								{
									xtype: 'button',
									margins: '5 0 5 5',
									text: 'Borrar Asociación',
									itemId: 'borrarAsociacion'
								}	
							]
						}												
					]					
				}
			]
		});
		this.callParent();
	}
});
