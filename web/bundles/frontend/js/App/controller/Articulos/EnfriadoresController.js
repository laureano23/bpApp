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
				'EnfriadoresFormView actioncolumn[itemId=nombreImagen]': {
					click: this.VerImagen
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
					
					grid.getStore().loadData(res);
					gridEnfriadores.close();
				}
			});
		});
	},
	
	VerImagen: function(btn){
		var win = btn.up('window');
		var selection = win.down('grid').getSelectionModel().getSelection()[0];
		console.log(selection);
		
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		//myMask.show();
		var file = Routing.generate('mbp_articulos_servirImagen', {id: selection.data.id});	
		console.log(file);			
		var WinReporte=Ext.create('Ext.Window', {
			  title: 'Orden de compra',
			  width: 900,
			  height: 600,
			  layout: 'fit',
			  modal:true,										
			  html: '<iframe src='+file+' width="100%" height="100%"></iframe>'						  
		 }).show();	
	}	
});










