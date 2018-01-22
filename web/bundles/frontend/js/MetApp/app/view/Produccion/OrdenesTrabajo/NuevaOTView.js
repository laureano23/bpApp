Ext.define('MetApp.view.Produccion.OrdenesTrabajo.NuevaOTView', {
	extend: 'Ext.window.Window',
	alias: 'widget.NuevaOTView',
	itemdId: 'NuevaOTView',
	title: 'Formulario OT',
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
						readOnly: true
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
									xtype: 'datefield',
									fieldLabel: 'Emisión',
									itemId: 'emision',
									name: 'fechaEmision',
									width: 165,
									labelWidth: 55,
									value: new Date
								},
								
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'textfield',
									fieldLabel: 'Código',
									name: 'codigo',									
									labelWidth: 55,
									itemId: 'codigo'
								},
								{
									xtype: 'button',
									itemId: 'btnCodigo',
									iconCls: 'search',
									margin: '2 0 0 0'
								},
								{
									xtype: 'textfield',
									name: 'descripcion',
									fieldLabel: 'Descripción',
									width: 550
								}
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									fieldLabel: 'Cantidad',
									name: 'cantidad',
									itemId: 'cantidad',
									width: 200,
									labelWidth: 55
									
								},
								{
									xtype: 'datefield',
									labelWidth: 150,
									itemId: 'fechaProg',
									name: 'fechaProg',
									fieldLabel: 'Fecha de programación',
									width: 270
								},
								{
									xtype: 'textfield',
									fieldLabel: 'Id',
									width: 450,
									labelWidth: 55,		
									name: 'idCliente',
									itemId: 'idCliente',
									hidden: true,
									allowBlank: true		
								},
								{
									xtype: 'textfield',
									fieldLabel: 'Cliente',
									width: 300,
									labelWidth: 55,		
									name: 'rsocial',
									allowBlank: false							
								},
								{
									xtype: 'button',
									iconCls: 'search',
									margin: '2 0 0 0',
									itemId: 'btnCliente'
								},
							]
						},
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype     : 'textareafield',
									name: 'observaciones',
		        					fieldLabel: 'Observaciones',
		        					width: 500,
		        					allowBlank: true
								},
								{
									xtype: 'container',
									layout: 'vbox',
									items: [
										{
											xtype: 'combo',
											fieldLabel: 'Sector',
											labelWidth: 50,
											store: 'MetApp.store.Produccion.Programacion.SectoresStore',
											itemId: 'sector',
											title: 'Sector',
											name: 'tipo',
											displayField: 'descripcion',
											valueField: 'id'
										},
										{
											xtype: 'container',
											layout: 'hbox',
											items: [
												{
													xtype: 'button',
													text: 'Asociar Pedidos',
													itemId: 'pedidos'
												},
												{
													xtype: 'textareafield',
													name: 'pedidosAsociados',
													itemId: 'pedidosAsociados',
													allowBlank: true
												}
											]
										}												
									]
								}																		
							]
						},
						{
				        	xtype: 'grid',
				        	title: 'Ordenes asociadas',
				        	itemId: 'gridMisPendientes',
				        	height: 200,
				        	width: 800,
				        	store: 'MetApp.store.Produccion.OrdenesTrabajo.OTStore',	
				        	selType: 'checkboxmodel',			        	
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
									width: 150,
									dataIndex: 'programado',
									xtype: 'datecolumn',
									format: 'd/m/Y',						
								},
							]	      
						},		
					],
					buttons: [
						{
							text: 'Nueva',
							itemId: 'nueva',
							iconCls: 'add',
							height: 30
						},
						{
							text: 'Guardar',
							itemId: 'guardar',
							iconCls: 'save',
							height: 30
						},
						{
							text: 'Reset',
							iconCls: 'reset',
							height: 30,							
							handler: function(){
								this.up('form').getForm().reset();
								this.up('form').query('field').forEach(function(field){
									field.setReadOnly(true);		
								});
							}
						},						
					]
				}
			]
		});
		this.callParent();
	}
});
