Ext.define('MetApp.controller.Articulos.EnfriadoresController',{
	extend: 'Ext.app.Controller',	
	views: [
			'Articulos.EnfriadoresFormView',
		],
	stores: ['Articulos.EnfriadoresStore', 'MetApp.store.Articulos.FormulaEnfriadoresStore'],
	
	init: function(){
		var me = this;
		me.control({
				'#tabPosEnfriadores': {
					click: this.NewForm
				},
				'EnfriadoresFormView button[itemId=buscaArt]': {
					click: this.SearchArt
				},
		});		
	},
	
	/*
	 * Iniciamos el formulario para manejar articulos
	 */
	NewForm: function(){	
		Ext.widget('EnfriadoresFormView');		
	},
	
	/*
	 * Grid para buscar articulos
	 */
	SearchArt: function(button){
		var win = button.up('window');
		var gridEnfriadores = Ext.widget('winarticulosearch',{
			store: 'Articulos.EnfriadoresStore'
		});		
		gridEnfriadores.down('grid').getStore().load();
		
		gridEnfriadores.queryById('insertArt').on('click', function(){
			Ext.Ajax.request({
				url: Routing.generate('mbp_articulos_enfriadores_formula'),
				
				params: {
					id: ''
				},
				
				success: function(resp){
					var grid = win.down('grid');
					var res = Ext.JSON.decode(resp.responseText);
					console.log(res);
					grid.getStore().loadData(res);
				}
			});
		});
	},
	
	
		
});










