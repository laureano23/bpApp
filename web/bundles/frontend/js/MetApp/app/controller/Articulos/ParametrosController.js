Ext.define('MetApp.controller.Articulos.ParametrosController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Articulos.ParametrosForm'
		],
	stores: [],
	
	init: function(){
		
		var me = this;
		me.control({
				'viewport menuitem[itemId=formParametros]': {
					click: this.formParametros
				},
		});		
	},
	
	formParametros: function(btn){
		var win = Ext.widget('ParametrosForm');
		var form = win.down('form');
		form.load({
			url: Routing.generate('mbp_finanzas_listarParametros'),
			
			params: {
				data: 'algo'
			}
		});
	}
});










