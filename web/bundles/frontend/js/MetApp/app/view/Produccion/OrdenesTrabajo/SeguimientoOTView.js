Ext.define('MetApp.view.Produccion.OrdenesTrabajo.SeguimientoOTView', {
	extend: 'Ext.window.Window',
	alias: 'widget.SeguimientoOTView',
	itemdId: 'SeguimientoOTView',
	title: 'Seguimiento OT',
	layout: 'fit',
	modal: true,
	autoShow: true,
	
	
	initComponent: function(){		
		
		console.log(MetApp.User);	
		
		var me = this;
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',	
					border: false,
					frame: false,				
					fieldDefaults: {
						margins: '5 5 5 5',	
						readOnly: true,
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
									xtype: 'numberfield',
									fieldLabel: 'OT numero:',	
									itemId: 'otNum',
									readOnly: false						
								},
								{
									xtype: 'button',
									margins: '5 0 0 0',									
									text: 'Buscar',
									itemId: 'buscar'
								},
								{
									xtype: 'textfield',
									name: 'descripcion',
									itemId: 'descripcion',
									width: 600,
									fieldLabel: 'Descripcion',
									readOnly: true,
									disableCls: 'myDisableCls',
								},
								{
									xtype: 'numberfield',
									name: 'cantidad',
									itemId: 'cantidad',
									fieldLabel: 'cantidad',
									readOnly: true,
									disableCls: 'myDisableCls',
									width: 250
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									name: 'codigo',
									fieldLabel: 'Código',
									readOnly: true,
									itemId: 'codigo',
								},
								{
									xtype: 'button',
									margin: '5 0 0 0',
									iconCls: 'search',
									itemId: 'buscarArt',
								}
							]
						},
						{
				        	xtype: 'grid',
				        	itemId: 'gridMisPendientes',
				        	height: 200,
				        	width: 1200,
				        	store: 'MetApp.store.Produccion.OrdenesTrabajo.OTStore',
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
									header: 'Rechazado',
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
										        {"idEstado":"0", "estado":"No comenzado"},
										        {"idEstado":"1", "estado":"En proceso"},
										        {"idEstado":"2", "estado":"Terminada"}
										        //...
										    ]
					                    }
					               	},
									renderer: function(value, metaData, record, row, col, store, gridView){
										return value == "No comenzada" ? '<span style="color:red;">'+value+'</span>' : value == "Terminada" ? '<span style="color:green;">'+value+'</span>' : '<span style="background-color:yellow;">'+value+'</span>';
									},									
								},
								{
									header: 'Sector emisor',
									width: 150,
									dataIndex: 'sectorEmisor',
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
						}						
					]
				}
			]
		});
		this.callParent();
	}
});
