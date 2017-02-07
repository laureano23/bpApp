Ext.define('MetApp.view.Parametros.SearchGridCentroCostosView', {
	extend: 'Ext.window.Window',
	height: 400,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.SearchGridCentroCostosView',
	itemId: 'SearchGridCentroCostosView',
	title: 'Busqueda de Centro de Costo',
	layout: 'fit',
	listeners: {
		activate: function(el){
			var searchField = el.queryById('searchField');
			searchField.focus('',200);
		}
	},
	
	initComponent: function(){
		var me = this;
		Ext.applyIf(me,{
			items:[
				{
					xtype: 'grid',
					layout: 'fit',
					store: 'MetApp.store.Parametros.CentroCostosStore',
					requires: ['Ext.grid.plugin.BufferedRenderer'],
					idProperty: 'id',
					loadMask: true,
				    plugins: 'bufferedrenderer',    
				    multiSelect: true,
				    listeners: {
				    	cellkeydown: function(cell, td, cellIndex, record, tr, rowIndex, e, eOpts ){
					    	if(e.button == 12){
					    		var grid = this;
					    		var win = grid.up('window');
					    		
								var button = win.queryById('insert');
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
									var strSearch = field.getValue();//Obtenemos el valor del campo de busqueda									
									var store = grid.getStore();	//Obtenemos el store	
									
									store.clearFilter(true);
									store.filter(
										{property: 'nombre',
										value: strSearch,
										anyMatch: true});
									},			
							
							/*
							 * Hacemos focus sobre la primer fila de la grilla
							 */
							keydown: function (field, k){						
							if(k.button == 39){									
								var panel = field.up('window');
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
						action: 'actInsert',
						itemId: 'insert'
					}		
					],
					columns: [
						{xtype: 'rownumberer', width: 50, sortable: false},
						{text: 'Id', dataIndex: 'id', width: 30},
						{text: 'Nombre', dataIndex: 'nombre', flex: 1},
						{text: 'Costo', dataIndex: 'costo', flex: 1},	
					],	
				},								
			],			
		});		
		this.callParent();
	}
});
