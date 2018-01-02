Ext.define('MetApp.view.Produccion.Programacion.Programacion',{
	extend: 'Ext.window.Window',
	width: 1200,
	height: 485,
	modal: true,
	layout: 'border',
	alias: 'widget.programacion',
	itemId: 'tablaProgramacion',
	autoShow: true,
	title: 'Tabla de programacion',
	
	initComponent: function(){
		var me = this;
		var sector = MetApp.User.name.sector;
		
		
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'container',
					region: 'north',
					border: false,
					items: [
						{
				        	xtype: 'grid',
				        	itemId: 'gridMisPendientes',
				        	height: 200,
				        	store: 'MetApp.store.Produccion.OrdenesTrabajo.OTStore',
				        	plugins: [
			        			Ext.create('Ext.grid.plugin.CellEditing',{
			        				clicksToEdit: 1,
			        				pluginId: 'cellplugin',
			        				listeners: {
							        }				
			        			})
				        	],
				        	selType: 'cellmodel',
				        	viewConfig: {
				        		enableTextSelection: true,
						        listeners: {
						            refresh: function(dataview) {
						                Ext.each(dataview.panel.columns, function(column) {
						                    if (column.autoSizeColumn === true)
						                        column.autoSize();
						                })
						            }				            
						        },
					    	},
				        	columns: [
								{text: 'OT', hidden: false, dataIndex: 'otNum', autoSizeColumn: true},
								{text: 'Codigo', hidden: false, dataIndex: 'codigo', autoSizeColumn: true },
								{ 
									header: 'Descripcion',
									dataIndex: 'descripcion',
									width: 305,
								},
								{ 
									header: 'Cantidad',
									dataIndex: 'totalOt',
									width: 75,
								},
								{
									header: 'Fecha de entrega',
									width: 100,
									dataIndex: 'programado',
									xtype: 'datecolumn',
									format: 'd/m/Y',						
								},
								{
									header: 'Aprobado',
									dataIndex: 'aprobado',
									editor: {
										xtype: 'numberfield',										
									}		                    
					               						
								},
								{
									header: 'NC',
									width: 50,
									dataIndex: 'rechazado',
									editor: {
										xtype: 'numberfield',										
									}
								},
								{							
									header: 'Estado',
									width: 150,
									dataIndex: 'estado',
									editor: {
										xtype: 'combobox',
										width: 250,										
					                    typeAhead: true,
					                    displayField: 'estado',
					                    store: {
					                    	fields: ['idEstado', 'estado'],
					                    	data : [
										        {"idEstado":"0", "estado":"No comenzada"},
										        {"idEstado":"1", "estado":"En proceso"},
										        {"idEstado":"2", "estado":"Terminada"},
										        {"idEstado":"3", "estado":"Generada"},
										        //...
										    ]
					                    }
					               	},
									renderer: function(value, metaData, record, row, col, store, gridView){
										return value == "No comenzada" ? '<span style="color:red;">'+value+'</span>' :
										 	value == "Terminada" ? '<span style="color:green;">'+value+'</span>' :
										 	value == "Generada" ? '<span style="color:brown;">'+value+'</span>' :
										 	'<span style="background-color:yellow;">'+value+'</span>';
									},									
								},
								{
									header: 'Cliente',
									width: 150,
									dataIndex: 'cliente',
								},
								{
									header: 'Sector emisor',
									width: 150,
									dataIndex: 'sectorEmisor',
								},
								{ 
									header: 'Formula',
									itemId: 'formula',
									xtype: 'actioncolumn',
									items: [
										{ iconCls: 'search' }										
									],
									flex: 1
								},	
								{ 
									header: 'OT',
									itemId: 'otImprimir',
									xtype: 'actioncolumn',
									items: [
										{ iconCls: 'reportes2' }										
									],
									flex: 1
								},
							]	      
						},
					]
				},
				{
					xtype: 'container',
					region: 'center',
					title: 'Programado a:',
					border: false,
					items: [
						{
				        	xtype: 'grid',
				        	//border: false,
				        	itemId: 'gridMisPedidos',
				        	height: 205,
				        	store: 'MetApp.store.Produccion.OrdenesTrabajo.OTStoreEmisor',
				        	plugins: [
			        			Ext.create('Ext.grid.plugin.CellEditing',{
			        				clicksToEdit: 1,				
			        			})
				        	],	
				        	selType: 'cellmodel',
				        	viewConfig: {
				        		enableTextSelection: true,
					    	},			        	
				        	columns: [
								{text: 'OT', hidden: false, dataIndex: 'otNum', width: 50},		
								{text: 'Codigo', hidden: false, dataIndex: 'codigo', autoSizeColumn: true },
								{ 
									header: 'Descripcion',
									dataIndex: 'descripcion',
									width: 350,
								},
								{ 
									header: 'Cantidad',
									dataIndex: 'totalOt',
									width: 75,
								},
								{
									header: 'Fecha de entrega',
									width: 100,
									dataIndex: 'programado',
									xtype: 'datecolumn',
									format: 'd/m/Y',
									itemId: 'fechaEntrega',									
									editor: {
						                xtype: 'datefield',
						                format: 'd/m/Y',
						                submitFormat: 'd/m/Y',
				                        listeners : { 
				                            change : function(cmp, e,eOpts) {
				                                cmp.up("grid").fireEvent("gridcellchanging",cmp,e,eOpts);
				                            }
				                        }
						            },				
								},
								{
									header: 'Aprobado',
									dataIndex: 'aprobado',		                    
					               						
								},
								{
									header: 'NC',
									width: 50,
									dataIndex: 'rechazado',
								},
								{							
									header: 'Estado',
									width: 150,
									dataIndex: 'estado',									
					               	renderer: function(value, metaData, record, row, col, store, gridView){
										return value == "No comenzada" ? '<span style="color:red;">'+value+'</span>' : value == "Terminada" ? '<span style="color:green;">'+value+'</span>' : '<span style="background-color:yellow;">'+value+'</span>';
									},
								},
								{
									header: 'Cliente',
									width: 150,
									dataIndex: 'cliente',
								},
								{
									header: 'Sector receptor',
									width: 150,
									dataIndex: 'sectorReceptor',
								},
								{ 
									header: 'OT',
									itemId: 'otImprimir',
									xtype: 'actioncolumn',
									items: [
										{ iconCls: 'reportes2' }										
									],
									flex: 1
								},
								{ 
									header: 'Borrar',
									itemId: 'otEliminar',
									xtype: 'actioncolumn',
									items: [
										{ iconCls: 'delete' }										
									],
									flex: 1
								},
							]
						},
					]
				},
				{
					xtype: 'container',
					itemId: 'cntBtn',
					region: 'south',
					anchorSize: '100%',
					layout: 'hbox',
					height: 35,
					style: {
						background: 'white'
					},
					defaults: {
						labelWidth: 130
					},
					items: [
						{
							xtype: 'button',
							margin: '5 5 0 5',
							itemId: 'programar',
							action: 'programar',
							text: 'Programar'
						},
						{
							xtype: 'button',
							margin: '5 5 0 0',
							itemId: 'solicitarMat',
							text: 'Solicitar Materiales'
						},
						{
							xtype: 'button',
							margin: '5 5 0 0',
							itemId: 'actualizar',
							text: 'Actualizar'
						},
					]
				}			
			]
		});
		
		this.callParent();
	}, 
});


