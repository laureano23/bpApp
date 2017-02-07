Ext.define('MetApp.view.Produccion.Maquinas.SearchGridMaquinas', {
	extend: 'Ext.window.Window',
	height: 400,
	width: 900,
	modal: true,
	autoShow: true,
	alias: 'widget.maquinasSearchGrid',
	itemId: 'maquinasSearchGrid',
	title: 'Busqueda de maquinas',
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
					store: 'Produccion.Programacion.MaquinasStore',
					requires: ['Ext.grid.plugin.BufferedRenderer'],
					idProperty: 'id',
					loadMask: true,
				    plugins: 'bufferedrenderer',    
				    multiSelect: true,
				    listeners: {
				    	cellkeydown: function(cell, td, cellIndex, record, tr, rowIndex, e, eOpts ){
					    	if(e.button == 12){
								var button = cell.up('window').queryById('insertMaquina');
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
									var panel = field.up('window');
									var grid = panel.down('grid');	
									var strSearch = grid.queryById('searchField').getValue();//Obtenemos el valor del campo de busqueda									
									var storeArt = grid.getStore();	//Obtenemos el store	
									
									storeArt.clearFilter(true);
									storeArt.filter(
										{property: 'descripcion',
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
						action: 'actInsertMaquina',
						itemId: 'insertMaquina'
					}		
					],
					columns: [
						{text: 'Id', dataIndex: 'id', width: 60},
						{text: 'Descripcion', dataIndex: 'descripcion', flex: 1},			
					],	
				},								
			],			
		});		
		this.callParent();
	}
});
