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
				'EnfriadoresFormView button[itemId=estructura]': {
					click: this.VerEstructura
				},
		});		
	},
	
	
	NewForm: function(){	
		var win = Ext.widget('EnfriadoresFormView');
		var grid = win.down('grid');
		var model = grid.getView();
		var selectionModel = model.getSelectionModel();
		
		//EVENTO PARA SELECCIONAR UN ITEM AL POSIONAR EL MOUSE
		model.on('highlightitem', function(view, node){
			var domEl = new Ext.dom.Element(node);
			selectionModel.deselectAll();
			selectionModel.select(domEl.dom.rowIndex);			
		});

		//EVENTO PARA SACAR LA SELECCION DE LA GRILLA
		model.on('itemmouseleave', function(view, node){
			selectionModel.deselectAll();	
		});		
	},
	
	/*
	 * Grid para buscar enfriadores
	 */
	SearchArt: function(button){
		var win = button.up('window');
		var gridEnfriadores = Ext.widget('winarticulosearch',{
			store: 'Articulos.EnfriadoresStore'
		});		
		var grid = gridEnfriadores.down('grid');
		grid.getStore().load();
		
		gridEnfriadores.queryById('insertArt').on('click', function(){
			var selection = grid.getSelectionModel().getSelection()[0];
			win.queryById('codigo').setValue(selection.data.codigo);
			
			//BUSCAMOS EL ID DE LA FORMULA SEGUN EL ARTICULO SELECCIONADO
			Ext.Ajax.request({						
				url: Routing.generate('mbp_formulas_idNodo'),
				params: {
					idArt: selection.data.id
				},
				success: function(resp){
					jsonResp = Ext.JSON.decode(resp.responseText);
					win.queryById('idFormula').setValue(jsonResp.idNodo);
					
					//BUSCAMOS LA FORMULA DEL ARTICULO CON EL ID DE LA FORMULA
					Ext.Ajax.request({
						url: Routing.generate('mbp_articulos_enfriadores_formula'),
						
						params: {
							idNodo: win.queryById('idFormula').getValue()
						},
						
						success: function(resp){
							
							var grid = win.down('grid');
							var res = Ext.JSON.decode(resp.responseText);
							console.log(res);
							grid.getStore().loadData(res.items);
							gridEnfriadores.close();							
						}
					});
				}
			});				
					
		});
	},
	
	VerImagen: function(btn){
		var win = btn.up('window');
		var selection = win.down('grid').getSelectionModel().getSelection()[0];
		
		var file = Routing.generate('mbp_articulos_servirImagen', {id: selection.data.idArt});
		window.open(file, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
	},
	
	VerEstructura: function(btn){
		var win = btn.up('window');
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_formulas_generaReporte'),
			
			params: {
				idNodo: win.queryById('idFormula').getValue(),
				tc: MetApp.resources.ux.ParametersSingleton.dolarOficial
			},
			
			success: function(resp, opt){
				var jsonReporte = Ext.JSON.decode(resp.responseText);
							
				var ruta = Routing.generate('mbp_formulas_muestraReporte');				
				window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
			}
		});
	}	
});










