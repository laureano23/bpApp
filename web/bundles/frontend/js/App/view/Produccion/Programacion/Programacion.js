Ext.define('MetApp.view.Produccion.Programacion.Programacion',{
	extend: 'Ext.window.Window',
	width: 1200,
	height: 485,
	modal: true,
	layout: 'anchor',
	alias: 'widget.programacion',
	itemId: 'tablaProgramacion',
	autoShow: true,
	title: 'Tabla de programacion',
	
	initComponent: function(){
		var me = this;
		
		
		Ext.applyIf(me, {
			items: [
				{
		        	xtype: 'grid',
		        	itemId: 'gridPedidos',		        	
		        	viewConfig: {
		        		copy: true,
				        listeners: {
				            refresh: function(dataview) {
				                Ext.each(dataview.panel.columns, function(column) {
				                    if (column.autoSizeColumn === true)
				                        column.autoSize();
				                })
				            }				            
				        },
				        plugins: {
					        ptype: 'gridviewdragdrop',
					        dragText: 'Drag and drop to reorganize',
					    }
			    	},
		        	anchorSize: '100%',
		        	height: 200,
		        	store: 'Produccion.PedidoClientes.PedidoClientesStore',
		        	columns: [
						{text: 'Id', hidden: true, autoSizeColumn: true},		
						{text: 'Codigo', dataIndex: 'codigo', autoSizeColumn: true },
						{text: 'Descripcion', dataIndex: 'descripcion', flex: 1 },
						{text: 'Cantidad', dataIndex: 'cantidad', flex: 1 },
						{text: 'Cliente', dataIndex: 'clienteDesc', autoSizeColumn: true },
						{
							text: 'Fecha pedido',
							autoSizeColumn: true,
							dataIndex: 'fechaProgramacion',
							flex: 1,
							renderer: function(val){
														
								return Ext.util.Format.date(val.date,'d-m-Y');								
							}	
						},
					]	      
				},
				{
		        	xtype: 'grid',
		        	itemId: 'gridProgramacion',
		        	anchorSize: '100%',
		        	height: 200,
		        	store: 'Produccion.Programacion.ProgramacionStore',
		        	plugins: [
	        			Ext.create('Ext.grid.plugin.CellEditing',{
	        				clicksToEdit: 1,
	        				pluginId: 'cellplugin',
	        				listeners: {
					            edit: function(editor, e, opts){
					            },
					            beforeedit: function(editor, e, opts){
					            }
					        }				
	        			})
		        	],
		        	selType: 'cellmodel',
		        	viewConfig: {
				        listeners: {
				            refresh: function(dataview) {
				                Ext.each(dataview.panel.columns, function(column) {
				                    if (column.autoSizeColumn === true)
				                        column.autoSize();
				                })
				            }				            
				        },
				        plugins: {
					        ptype: 'gridviewdragdrop',
					        dragText: 'Drag and drop to reorganize'
					    }
			    	},
		        	columns: [
						{text: 'Id', hidden: true, dataIndex: 'id', autoSizeColumn: true},		
						{text: 'Codigo', hidden: true, dataIndex: 'codigo', autoSizeColumn: true },
						{ 
							header: 'Operacion',
							dataIndex: 'idOperacion',
							renderer: function(val){
								return val.descripcion
							},
							autoSizeColumn: true
						},
						{ 
							header: 'Sector',
							dataIndex: 'idOperacion',
							width: 150,
							renderer: function(val){
								var value = val.sector.descripcion;
								return value;
							}
						},
						{ 
							header: 'Tiempo/u',
							dataIndex: 'tiempo',
							renderer: function(val){								
								return Ext.util.Format.date(val.date,'H:i:s');								
							},
							autoSizeColumn: true
						},
						{
							header: 'Fecha inicio',
							width: 180,
							dataIndex: 'idProgramacion',
							renderer: function(val, meta, rec){
								return Ext.util.Format.date(val.fechaInicio,'Y-m-d H:i:s');
							},							
						},
						{
							header: 'Fecha fin',
							width: 180,
							dataIndex: 'idProgramacion',	
							renderer: function(val, meta, rec){
								return Ext.util.Format.date(val.fechaFin,'Y-m-d H:i:s');
							},							
						},
						{
							header: 'Maquina',							
							width: 300,
							dataIndex: 'maquina',
								id: 'maqSelect',			                    
			               						
						},
						{
							header: 'Cod. Maquina',
							dataIndex: 'idMaquina',
							name: 'idMaq',
							itemId: 'idMaq'
						},
						{							
							xtype: 'actioncolumn',
							items: [
								{
									iconCls: 'saveGrid'		
								}								
							]
						}
					]	      
				},
				{
					xtype: 'container',
					itemId: 'cntBtn',
					margin: '5 5 5 5',
					anchorSize: '100%',
					layout: 'hbox',
					defaults: {
						labelWidth: 130
					},
					items: [
						{
							xtype: 'datefield',
							allowBlank: false,
							margin: '0 5 0 5',
							itemId: 'fechaProgramar',
							fieldLabel: 'Fecha a programar'
						},
						{
							xtype: 'timefield',
							margin: '0 5 0 0',
							itemId: 'horaProgramar',
							fieldLabel: 'Hora a programar'
						},
						{
							xtype: 'textfield',
							margin: '0 5 0 0',
							itemId: 'cant',
							fieldLabel: 'cantidad',
							hidden: true
						},
						{
							xtype: 'button',
							margin: '0 5 0 0',
							itemdId: 'calcular',
							action: 'calcular',
							text: 'Calcular'
						},
						{
							xtype: 'button',
							margin: '0 5 0 0',
							itemId: 'reportePrograma',
							//action: 'reportePrograma',
							text: 'Programa'
						},
						{
							xtype: 'button',
							disabled: true,
							id: 'editarProg',
							action: 'editarProg',
							text: 'Editar'
						}
					]
				}			
			]
		});
	var gridPedidos = me.items[0];
	var gridProgramacion = me.items[1];
		
	this.callParent();
	},
	
	 
});


