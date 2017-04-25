Ext.define('MetApp.view.Produccion.OrdenesTrabajo.CierreOTView', {
	extend: 'Ext.window.Window',
	alias: 'widget.CierreOTView',
	itemdId: 'CierreOTView',
	title: 'Cierre OT',
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
							store: 'Produccion.OrdenesTrabajo.CierreOTGridStore',
							columns: [
								{ text: 'N° OT', dataIndex: 'otNum', flex: 1 },
								{ text: 'Emisión', dataIndex: 'otEmision', flex: 1,  },
								{ text: 'Cliente', dataIndex: 'cliente', flex: 1 },
								{ text: 'Código', dataIndex: 'codigo', flex: 1 },
								{ text: 'Descripción', dataIndex: 'descripcion', flex: 1 },
								{ text: 'Total OT', dataIndex: 'totalOt', flex: 1 },
								{ text: 'Programado', dataIndex: 'programado', flex: 1 },
								{ text: 'Pendiente', dataIndex: 'pendiente', flex: 1 },
								{ 
									text: 'Aprobado',
									dataIndex: 'aprobado',
									flex: 1,
									tdCls: 'custom-column',
									editor: {
						                xtype: 'numberfield',
						                allowBlank: false
						            } 
								},
								{ 
									text: 'Rechazado',
									dataIndex: 'rechazado',
									tdCls: 'custom-column-red',
									flex: 1,
									editor: {
						                xtype: 'numberfield',
						                allowBlank: false
						            }  
								},
							],
							selType: 'cellmodel',
						    plugins: [
						        Ext.create('Ext.grid.plugin.CellEditing', {
						            clicksToEdit: 1
						        })
						    ],
							width: 900,
							height: 300
							
						},
						{
							xtype: 'textfield',
							fieldLabel: 'Cliente',
							itemId: 'filtroCliente',
							enableKeyEvents: true,
						}		
					],
					
					buttons: [
						{
							text: 'Guardar',
							itemId: 'guardar',
							iconCls: 'save',
							height: 30
						}				
					]
				}
			]
		});
		this.callParent();
	}
});
