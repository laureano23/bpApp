Ext.define('MetApp.view.RRHH.CategoriasWinSearch', {
	extend: 'Ext.window.Window',
	height: 400,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.categoriasWinSearch',
	itemId: 'categoriasWinSearch',
	title: 'Busqueda de Categorias',
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
					store: 'MetApp.store.Personal.CategoriasStore',
					requires: ['Ext.grid.plugin.BufferedRenderer'],
					idProperty: 'id',
					loadMask: true,
				    plugins: 'bufferedrenderer',    
				    multiSelect: true,
				    listeners: {
				    	cellkeydown: function(cell, td, cellIndex, record, tr, rowIndex, e, eOpts ){
					    	if(e.button == 12){
								var button = Ext.ComponentQuery.query('#insertCategoria')[0];
								button.fireEvent('click');
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
									var win = field.up('window');
									var grid = win.down('grid');	
									var strSearch = grid.queryById('searchField').getValue();//Obtenemos el valor del campo de busqueda									
									var storeArt = grid.getStore();	//Obtenemos el store	
									
									storeArt.clearFilter(true);
									storeArt.filter(
										{property: 'categoria',
										value: strSearch,
										anyMatch: true});
									},			
							
							/*
							 * Hacemos focus sobre la primer fila de la grilla
							 */
							keydown: function (field, k){						
							if(k.button == 39){									
								var win = field.up('window');
								var grid = win.down('grid');
								grid.getSelectionModel().select(0);
								grid.getView().focus();									
								}				                
							}
						}							
					},					
					{
						xtype: 'button',
						text: 'Aceptar',
						action: 'actInsertCategorias',
						itemId: 'insertCategoria'
					}		
					],
					columns: [
						{xtype: 'rownumberer', width: 50, sortable: false},
						{text: 'Id', dataIndex: 'id', width: 30},
						{text: 'Categoria', dataIndex: 'categoria', flex: 1},
						{text: 'Sindicato', dataIndex: 'sindicato', flex: 1},			
					],	
				},								
			],			
		});		
		this.callParent();
	}
});
