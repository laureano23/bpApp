Ext.define('MetApp.view.Produccion.OrdenesTrabajo.VerCodigoOTView', {
	extend: 'Ext.window.Window',
	alias: 'widget.VerCodigoOTView',
	itemdId: 'CierreOTView',
	title: 'Ver OT',
	layout: 'fit',
	modal: true,
	autoShow: true,
	itemId: 'VerCodigoOTView',
	
	
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
								
							],
							width: 900,
							height: 300
							
						},
						{
							xtype: 'textfield',
							enableKeyEvents: true,
							fieldLabel: 'Codigo',
							itemId: 'filtroCodigo'
						}		
					],
					
					buttons: [
						{
							text: 'OK',
							itemId: 'verOT',
							height: 30
						}				
					]
				}
			]
		});
		this.callParent();
	}
});
