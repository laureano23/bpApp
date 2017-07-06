Ext.define('MetApp.view.Articulos.ArticuloSearchGrd', {
	config:{
		store: 'Articulos.Articulos'
	},
	extend: 'Ext.grid.Panel',
	alias: 'widget.articulosearchgrd',
	itemId: 'articulosearchgrd',
	//store: 'Articulos.Articulos',
	requires: ['Ext.grid.plugin.BufferedRenderer'],
	idProperty: 'id',
	loadMask: true,
	width: 700,
    height: 500,
    plugins: 'bufferedrenderer',    
    multiSelect: true,
    listeners: {
    	cellkeydown: function(cell, td, cellIndex, record, tr, rowIndex, e, eOpts ){
	    	if(e.button == 12){
				var button = Ext.ComponentQuery.query('#insertArt')[0];
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
						var strSearch = Ext.ComponentQuery.query('#searchField')[0].getValue();//Obtenemos el valor del campo de busqueda
						var grid = Ext.ComponentQuery.query('#articulosearchgrd')[0];
						var storeArt = grid.getStore();	//Obtenemos el store		
						var radio = this.up('window').queryById('paramArtSearch').getChecked()[0];
												
						if(radio.inputValue == 'codigo'){
							storeArt.clearFilter(true);
							storeArt.filter(
								{property: 'codigo',
								value: strSearch,
								anyMatch: false}
							);
						}else{
							storeArt.clearFilter(true);
							storeArt.filter(
								{property: 'descripcion',
								value: strSearch,
								anyMatch: true}
							);
						}
									
				},
				/*
				 * Hacemos focus sobre la primer fila de la grilla
				 */
				keydown: function (field, k){								
								if(k.button == 39){									
									var grid = field.up('window').queryById('articulosearchgrd');
									grid.getSelectionModel().select(0);
									grid.getView().focus();									
								}				                
					        } 
				}			
		},
		{
			xtype: 'radiogroup',
			width: 200,
			columns: 2,
			id: 'paramArtSearch',
			itemId: 'paramArtSearch',
			items:[
				{
					xtype: 'radiofield', 
					boxLabel: 'Codigo',
					name: 'busqueda',
					inputValue: 'codigo',
					checked: true			
				},
				{
					xtype: 'radiofield',
					boxLabel: 'Descripcion',
					name: 'busqueda',
					inputValue: 'descripcion'
				}
			]
		},
		{
			xtype: 'button',
			text: 'Aceptar',
			action: 'actInserArt',
			itemId: 'insertArt'
		}		
	],
	
	initComponent: function(){	
		this.columns = [
			{xtype: 'rownumberer', width: 50, sortable: false},
			{header: 'Id', dataIndex: 'id', flex: 1, hidden: true},
			{header: 'Codigo', dataIndex: 'codigo', flex: 1},
			{header: 'Descripcion', dataIndex: 'descripcion', flex: 1}
		],
		this.callParent();
	}
});
