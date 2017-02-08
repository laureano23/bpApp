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
	
	
	initComponent: function(){
		this.items = [
			Ext.create('MetApp.view.Articulos.ArticuloSearchGrd'),			
		]		
		this.callParent();
	}
});