Ext.define('MetApp.view.Compras.ModificarOCView' ,{
	extend: 'Ext.window.Window',
	height: 400,
	width: 1100,
	modal: true,
	autoShow: true,
	alias: 'widget.ModificarOCView',
	itemId: 'ModificarOCView',
	title: 'Modificar OC',
	layout: 'fit',
	
	
	initComponent: function(){
		var me = this;		
		var user = MetApp.User.name.name;
		
		Ext.applyIf(me,{
			items: [
				{
					xtype: 'form',
					border: false,
					itemId: 'formPedidos',
					layout: 'vbox',
					defaults: {
						margin: '5 0 0 5',
					},
					items: [
						{
							xtype: 'container',
							layout: 'hbox',
							items: [
								{
									xtype: 'numberfield',
									itemId: 'ocNum',
									name: 'ocNum',
									fieldLabel: 'Orden N°'
								},
								{
									xtype: 'button',
									itemId: 'buscarOC',
									text: 'Buscar'
								},
							]
						},								
						{
							xtype: 'grid',
							viewConfig: {
								enableTextSelection: true,
							},
							plugins: [
			        			Ext.create('Ext.grid.plugin.CellEditing',{
			        				clicksToEdit: 1,
			        				pluginId: 'cellplugin',
			        				listeners: {
							        }				
			        			})
				        	],
				        	selType: 'cellmodel',
							border: false,
							itemId: 'detallesStore',
							store: 'MetApp.store.Compras.OrdenCompraStore',
							width: 1000,
							columns: {
								items: [
									{ text: 'IdArt', dataIndex: 'id', hidden: true, flex: 1 },
									{ text: 'Codigo', dataIndex: 'codigo', width: 150 },
									{ text: 'Descripcion', dataIndex: 'descripcion', width: 380 },
									{ 
										text: 'Cantidad',
									 	dataIndex: 'cant',
									 	width: 70,
									 	editor: {
											xtype: 'numberfield',
						               	},
									},
									{ 
										text: 'Unidad',
										dataIndex: 'unidad',
										width: 55,
										editor: {
											xtype: 'textfield',
						               	},
									},
									{ 
										text: 'Precio',
										dataIndex: 'precio',
										width: 70,
										editor: {
											xtype: 'numberfield',
						               	},
									},
									{ 
										text: 'Moneda',
										dataIndex: 'moneda',
										width: 55,
										renderer: function(val){
											if(val == 'false'){
												return "ARS"
											}else{
												return "USD"
											}
										}
									},	
									{ text: 'Proveedor', dataIndex: 'proveedor', width: 80 },						
									{ 
										text: 'Entrega',
										dataIndex: 'entrega',
										xtype: 'datecolumn',
										dateFormat: 'd/m/Y',
										renderer: function(val){
											return val;
										},
										width: 100,
										editor: {
											xtype: 'datefield',
						               	},
									},
									{ 
										header: 'Editar',
										width: 90,
										itemId: 'editar',
										xtype: 'actioncolumn',								
										items: [
											{ 
												iconCls: 'edit',
											}										
										],
										dataIndex: 'imputado'
									},	
									{ 
										header: 'Eliminar',
										width: 90,
										itemId: 'eliminar',
										xtype: 'actioncolumn',
										items: [
											{ iconCls: 'delete' }										
										], 
									},	
								]
							},
						}	
					]
				},

			]
		});
		this.callParent();
	}
});
