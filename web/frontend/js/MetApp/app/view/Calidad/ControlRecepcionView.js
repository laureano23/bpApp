Ext.define('MetApp.view.Calidad.ControlRecepcionView', {
	extend: 'Ext.window.Window',
	alias: 'widget.ControlRecepcionView',
	itemId: 'ControlRecepcionView',
	width: 1000,
	layout: 'fit',
	autoShow: true,
	title: 'Control Recepción de Artículos',
	modal: true,	
	bodyStyle:{"background-color":"white"}, 
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					itemId: 'formGral',
					region: 'center',					
					border: false,
					layout: 'vbox',
					padding: '5 5 5 5',
					items: [						
						{
							xtype: 'grid',
							height: 300,
							width: 950,
							plugins: [
			        			Ext.create('Ext.grid.plugin.CellEditing',{
			        				clicksToEdit: 1,
			        				pluginId: 'cellplugin',
			        			})
				        	],
				        	selType: 'cellmodel',
				        	viewConfig: {
				        		enableTextSelection: true,
				        		preserveScrollOnRefresh: true,
					    	},
							style: {
								background: 'white'
							},
							margins: '5 0 0 0',
							region: 'center',
							store: 'MetApp.store.Calidad.ControlRecepcionStore',
							columns: [
								{ text: 'Código', dataIndex: 'codigo', width: 150 },
								{ text: 'Descripción', dataIndex: 'descripcion', width: 300 },
								{ text: 'Cantidad', dataIndex: 'cant', flex: 1 },
								{ text: 'Lote', dataIndex: 'lote', flex: 1 },
								{							
									header: 'Estado',
									width: 150,
									dataIndex: 'estadoCalidad',
									editor: {
										xtype: 'combobox',
										width: 250,										
					                    typeAhead: true,
					                    displayField: 'estado',
					                    store: {
					                    	fields: ['idEstado', 'estado'],
					                    	data : [
										        {"idEstado":"0", "estado":"Aprobado"},
										        {"idEstado":"1", "estado":"Rechazado"}
										    ]
					                    }
					               	},			
								},
								{ 
									text: 'Cert.',
									dataIndex: 'certificadoNum',
									flex: 1,
									editor: {
										xtype: 'textfield',
									}
								},
							]
						},	
						{
							xtype: 'button',
							margin: '5 0 0 0',
							text: 'Guardar',
							itemId: 'guardar'
						}																									
					]
				},	
			]
		});
		this.callParent();
	}
});
