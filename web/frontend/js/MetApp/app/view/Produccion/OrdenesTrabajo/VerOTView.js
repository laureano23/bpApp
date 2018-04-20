Ext.define('MetApp.view.Produccion.OrdenesTrabajo.VerOTView', {
	extend: 'Ext.window.Window',
	alias: 'widget.VerOTView',
	itemdId: 'CierreOTView',
	title: 'Ver OT',
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
					},
					defaults: {
						margin: '0 5 0 0'
					},
					layout: 'vbox',					
					items: [
						{
							xtype: 'grid',
							store: 'MetApp.store.Produccion.OrdenesTrabajo.OTStore',
							columns: [
								{ text: 'N° OT', dataIndex: 'otNum', flex: 1 },
								{ text: 'Emisión', dataIndex: 'otEmision', flex: 1,  },
								{ text: 'Cliente', dataIndex: 'cliente', flex: 1 },
								{ text: 'Código', dataIndex: 'codigo', flex: 1 },
								{ text: 'Descripción', dataIndex: 'descripcion', flex: 1 },
								{ text: 'Total OT', dataIndex: 'totalOt', flex: 1 },
								{ text: 'Aprobado', dataIndex: 'aprobado', flex: 1 },
								{ text: 'Rechazado', dataIndex: 'rechazado', flex: 1 },
								{ text: 'Estado', dataIndex: 'estado', flex: 1 },
							],
							width: 900,
							height: 300
							
						},
						{
							xtype: 'textfield',
							fieldLabel: 'Cliente',
							itemId: 'filtroCliente'
						}		
					],
					
					buttons: [
						{
							text: 'Ver',
							itemId: 'verOT',
							iconCls: 'search',
							height: 30
						}				
					]
				}
			]
		});
		this.callParent();
	}
});
