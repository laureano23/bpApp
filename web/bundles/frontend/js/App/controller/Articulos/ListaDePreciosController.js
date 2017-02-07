Ext.define('MetApp.controller.Articulos.ListaDePreciosController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Articulos.ListaDePrecios.ReporteListaMaestra'
	],
	stores: [
		'MetApp.store.Articulos.SubFamiliaStore',
		'MetApp.store.Articulos.FamiliaStore'
	],
	
	refs:[
	],
	
	init: function(){
		var me = this;		
		
		
		me.control({
				'#tbListaMaestra': {
					click: this.ListaDePreciosView
				},
		});
	},
	
	ListaDePreciosView: function(btn){
		Ext.widget('ReporteListaMaestra');
	}
});










