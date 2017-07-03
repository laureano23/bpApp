Ext.define('MetApp.controller.Articulos.PosEnfriadoresController',{
	extend: 'Ext.app.Controller',	
	views: [
		//'Articulos.ArticulosForm',
		//'Articulos.WinArticuloSearch',
		//'Articulos.ArticuloSearchGrd',
		'Articulos.PosEnfriadoresFormView',
		],
	stores: ['Articulos.Articulos', 'Articulos.Formula'],
	
	init: function(){
		var me = this;
		me.control({
				'#tabPosEnfriadores': {
					click: this.addPosTb
				},
				'PosEnfiradoresFormView button[itemId=actBuscaArt]': {
					click: this.SearchArt
				},
		});		
	},
	
	/*
	 * Iniciamos el formulario para manejar articulos
	 */
	addPosTb: function(){		
		var addForm = Ext.widget('PosEnfriadoresFormView',{
			title: 'Tabla de posenfriadores',
			//itemId: 'pos',
			//alias: 'widget.pos'
		});
		//addForm.alias = 'widget.pos';
		console.log(addForm);
		var storeFamilia = addForm.queryById('familia').getStore();
		var storeSubFamilia = addForm.queryById('subFamilia').getStore();
		
		storeFamilia.load();
		storeSubFamilia.load();
	},
	
	/*
	 * Grid para buscar articulos
	 */
	SearchArt: function(button){
		console.log("yo no busco");			
	},
	
	
		
});










