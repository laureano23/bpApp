Ext.define('MetApp.view.CCClientes.ImputarFacturaView' ,{
	extend: 'Ext.window.Window',
	height: 430,
	width: 500,
	modal: true,
	autoShow: true,
	alias: 'widget.ImputarFacturaView',
	itemId: 'ImputarFacturaView',
	title: 'Imputar Facturas',
	layout: 'border',
	bodyStyle: 'background-color: transparent',
	
	initComponent: function(){
		var me = this;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'container',
					region: 'center',
					items: [
						{
							xtype: 'grid',
							height: 350,
							itemId: 'gridImputaFc',
							store: 'MetApp.store.Finanzas.GridImputaFcStore',
							plugins: [
						        Ext.create('Ext.grid.plugin.CellEditing', {
						            clicksToEdit: 1,
						            
						        })
						    ],
							columns: [
								{ text: 'Id', dataIndex: 'id', hidden: true },
								{ text: 'Factura N°', dataIndex: 'numFc' },
								{ text: 'Importe', dataIndex: 'haber', xtype: 'numbercolumn' },
								{ text: 'Vencimiento', dataIndex: 'vencimiento' },
								/*{ text: 'Aplicado', dataIndex: 'aplicado', xtype: 'numbercolumn' },
								{ 
									text: 'Pendiente',
									dataIndex: 'pendiente',
									xtype: 'numbercolumn'
								},*/
								{ 
									text: 'Asociar',
                                    xtype : 'checkcolumn',
                                    dataIndex:'asociado',
                                    style: {
                                        color: 'blue'
                                    }
								},
							]
                        },
                        {
                            xtype: 'container',
							region: 'south',
							layout: 'hbox',
							margin: '5 5 5 5',
                            items: [
                                {
                                    xtype: 'button',                                    
                                    text: 'Insertar',
                                    itemId: 'insertar'
								},
								{
									xtype: 'numberfield',
									margins: '0 0 0 30',
									itemId: 'total',
									value: 0,
									style: 'font-weight: bold;',
									labelWidth: 50,
									fieldLabel: 'Total',
									readOnly: true,
									disableCls: 'myDisableCls'
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
