Ext.define('MetApp.view.Proveedores.SearchGridProveedores', {
	extend: 'Ext.window.Window',
	height: 400,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.ProveedoresSearchGrid',
	itemId: 'ProveedoresSearchGrid',
	title: 'Busqueda de proveedores',
	layout: 'fit',
	listeners: {
		activate: function(el){
			searchField = el.queryById('searchField');
			searchField.focus('',20);
		}
	},
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items:[
				{
					xtype: 'grid',
					layout: 'fit',
					store: 'Proveedores.ProveedoresStore',
					requires: ['Ext.grid.plugin.BufferedRenderer'],
					idProperty: 'id',
					loadMask: true,
				    plugins: 'bufferedrenderer',    
				    multiSelect: true,
				    listeners: {
				    	cellkeydown: function(cell, td, cellIndex, record, tr, rowIndex, e, eOpts ){
					    	if(e.button == 12){
								var button = Ext.ComponentQuery.query('#insertProveedor')[0];
								button.fireEvent('click', button);
							}
						}
				    },
				    tbar: [		
					{
						xtype: 'textfield',			
						fieldLabel: 'Busqueda',			
						name: 'busqueda',
						itemId: 'searchField',					
						enableKeyEvents : true,
						listeners: {				
							keyup : function(field, e){
									var panel = Ext.ComponentQuery.query('#ProveedoresSearchGrid')[0];
									var grid = panel.down('grid');	
									var strSearch = grid.queryById('searchField').getValue();//Obtenemos el valor del campo de busqueda									
									var storeArt = grid.getStore();	//Obtenemos el store	
									
									storeArt.clearFilter(true);
									storeArt.filter(
										{property: 'rsocial',
										value: strSearch,
										anyMatch: true});
									},			
							
							/*
							 * Hacemos focus sobre la primer fila de la grilla
							 */
							keydown: function (field, k){						
							if(k.button == 39){									
								var panel = Ext.ComponentQuery.query('#ProveedoresSearchGrid')[0];
								var grid = panel.down('grid');
								grid.getSelectionModel().select(0);
								grid.getView().focus();									
								}				                
							}
						}							
					},					
					{
						xtype: 'button',
						text: 'Aceptar',
						action: 'actInsertProveedor',
						itemId: 'insertProveedor'
					}		
					],
					columns: [
						{xtype: 'rownumberer', width: 50, sortable: false},
						{text: 'Id', dataIndex: 'id', width: 30},
						{text: 'Razon social', dataIndex: 'rsocial', flex: 1},			
					],	
				},								
			],			
		});		
		this.callParent();
	}
});
