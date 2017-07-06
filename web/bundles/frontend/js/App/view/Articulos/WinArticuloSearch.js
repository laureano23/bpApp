Ext.define('MetApp.view.Articulos.WinArticuloSearch',{
	extend: 'Ext.window.Window',
	alias: 'widget.winarticulosearch',	
	itemId: 'winarticulosearch',
	layout: 'fit',
	height: 400,
	width: 800,
	defaultFocus: 'searchField',
	title: 'Busqueda de articulos',
	autoShow: true,
	modal: true,
	
	config: {
		store: null
	},
	
	
	initComponent: function(cfg){
		var store;
		if(this.store == null){
			store = 'Articulos.Articulos';
		}else{
			store = this.store;
		}
		
		this.items = [
			Ext.create('MetApp.view.Articulos.ArticuloSearchGrd', {
				store: store
			}),			
		]		
		this.callParent();
	}
});