Ext.define('MetApp.controller.Articulos.FormulasController',{
	extend: 'Ext.app.Controller',
	views: [
		'Articulos.WinArticuloSearch',
		'Articulos.ArticuloSearchGrd',
		'Articulos.ArticulosFormulas'
		],
	stores: ['Articulos.Articulos', 'Articulos.Formula'],
	
	init: function(){	
		var me = this;
		me.control({			
			'#btnFormulas': {
				click: this.addArticulosFormulas
			},
			'articulosformulas button[action=actBuscaArt]': {
				click: this.SearchArtFormulas
			},
			'articulosformulas button[action=actSave]': {
				click: this.SaveFormula
			},
			'articulosformulas button[action=actBuscaArtFormulas]': {
				click: this.SearhForm
			},
			'articulosformulas button[action=actResetForm]': {
				click: this.RefreshGrid
			},
			'articulosformulas button[action=actDelete]': {
				click: this.DeleteRow
			},
			'articulosformulas button[action=actEdit]': {
				click: this.EditRow
			},
			'articulosformulas button[itemId=estructura]': {
				click: this.Estructura
			}
		});
	},
	
	/*
	 * Iniciamos el formulario para crear formulas de los articulos
	 */
	addArticulosFormulas: function(button){				
		var addWin = Ext.widget('articulosformulas');
		var me = this;
		var grid = addWin.down('grid');
		grid.getStore().loadData([]);	//SACA TODOS LOS DATOS DE LA GRILLA		
		
		//HOT KEY DE LA TABLA LIQUIDACION
		var map = new Ext.util.KeyMap({
		    target: addWin.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.SaveFormula(addWin.queryById('btnSave'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EditRow(addWin.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.DeleteRow(addWin.queryById('btnDelete'));
		   			}
		   		},
		   	]
		});	
	},
	
	/*
	 * Buscador que define el articulo a formular
	 */
	SearchArtFormulas: function(btn){
		var win = btn.up('window');
		var searchForm = Ext.widget('winarticulosearch');
		searchForm.down('grid').getStore().clearFilter(true);
		var f2 = Ext.ComponentQuery.query('#formulaFill')[0]; //Formulario para la carga de formulas
		var btn = f2.queryById('buscarArtForm').setDisabled(false);	
		var txtIdFormula = 	win.queryById('idFormula');		
		
		var button = Ext.ComponentQuery.query('#insertArt')[0];
		/*
		 * Escuchamos al boton de la ventana de busqueda
		 */
		button.on('click',function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];	
											
			formArt = Ext.ComponentQuery.query('#articulosFormulas')[0].down('form');			
			formArt.loadRecord(selection);
						
			//Traemos a la grilla la formula del articulo
			var store = Ext.ComponentQuery.query('#articulosFormulas')[0].down('grid').getStore();
			var proxy = store.getProxy();
			proxy.extraParams.art = selection.data.id;
			store.load({
				callback: function(record, operation, success){
					var jsonResp = Ext.JSON.decode(operation.response.responseText);
					if(jsonResp.data[0]){
						txtIdFormula.setValue(jsonResp.data[0].idFormula);	
					}else{
						txtIdFormula.setValue("");	
					}
					btn.focus(false, true);		
					//BUSCAMOS EL ARTICULO EN LA TABLA FORMULA Y TRAEMOS SU ID
					Ext.Ajax.request({						
						url: Routing.generate('mbp_formulas_idNodo'),
						params: {
							idArt: selection.data.id
						},
						success: function(resp){
							jsonResp = Ext.JSON.decode(resp.responseText);
							console.log(jsonResp);
							txtIdFormula.setValue(jsonResp.idNodo);
							console.log(txtIdFormula.getValue());
						}
					});									
				}
			});
		
			button.up('window').close();
		});
	},	
	
	/*
	 * Escuchamos el boton de busqueda para la formulacion de articulos
	 */
	SearhForm: function(){
		var searchForm = Ext.widget('winarticulosearch');
		searchForm.down('grid').getStore().clearFilter(true);
		var button = Ext.ComponentQuery.query('#insertArt')[0];
		/*
		 * Escuchamos al boton de la ventana de busqueda
		 */
		button.on('click',function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			
											
			var formArt = Ext.ComponentQuery.query('#formulaFill')[0];
			var btnCant = formArt.queryById('cantidadForm').setDisabled(false); //Habilitamos campo para cargar cantidad			
			formArt.loadRecord(selection);
			button.up('window').close();
			btnCant.focus(false, true);
		});
	},
	
		
	/*
	 * Comienza el ABMC de formulas 
	 */
	AddFormula: function(button){
		var win = btn.up('window');
		var form = win.down('form');
		var btnSearch = form.queryById('buscarArt').setDisabled(false); //Habilita boton para buscar Art a formular
		btnSearch.focus();
	},
	
	SaveFormula: function(button){
		var me = this;
		var win = button.up('window');
		var form1 = win.queryById('srchFormulasForm');
		var idArtToForm = form1.queryById('id').getValue();
		var form2 = win.queryById('formulaFill');
		var dataFillForm = form2.getForm().getValues();
		var idFormula = win.queryById('idFormula').getValue();
		
		var store = me.getArticulosFormulaStore();
		var idReg = form2.queryById('id').getValue();
		var codigo = form2.queryById('codigo').getValue();
		var model = store.findRecord('codigo', codigo);
		//NUEVO REGISTRO
		if(form1.isValid() && form2.isValid() && model == null){
			var newModel = Ext.create('MetApp.model.Articulos.Formulas');
			newModel.set(dataFillForm);
			newModel.set('idFormula', idFormula);
			newModel.setDirty();
			newModel.phantom = true;
			
			store.on('write', function(st, opt){
				console.log(opt);
				var resp = Ext.JSON.decode(opt.response.responseText);
				var txtIdFormula = win.queryById('idFormula'); 
				console.log(txtIdFormula);
				txtIdFormula.setValue(resp.idFormula);
				
			});
			
			store.add(
				newModel
			);	
		}
				
		if(model != null){
			var values = form2.getForm().getValues();			
			model.set(values);
			form2.getForm().reset();
		}
		
		form2.getForm().reset();
		win.queryById('buscarArtForm').focus();
	},
	
	DeleteRow: function(btn){
		var win = btn.up('window');
		var grid = Ext.ComponentQuery.query('#gridFormulas')[0];
		var store = grid.getStore();
		store.getProxy().setExtraParam('idFormula', win.queryById('idFormula').getValue());		
		
		var record = grid.getSelectionModel().getSelection()[0];
		if(record == undefined){
			Ext.Msg.show({
				title: 'Atencion',
				msg: 'Debe seleccionar un articulo de la grilla',
				buttons: Ext.Msg.OK,
				icon: Ext.Msg.WARNING
			})
		}else{
			Ext.Msg.show({
				title: 'Atencion',
					msg: 'Desea eliminar el articulo de la formula?',
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.WARNING,
					fn:function(btn){
						if(btn == 'yes'){
							store.remove(record);
						}
					}
			});		
		}
	},
	
	/*
	 * Refresco la grilla
	 */
	RefreshGrid: function(button){
		var grid = Ext.ComponentQuery.query('#gridFormulas')[0];
		grid.getStore().reload();
	},
	
	EditRow: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		var form = win.queryById('formulaFill');
		
		form.loadRecord(selection);
		
	},
	
	/*
	 * REPORTE DE ESTRUCTURA DE MATERIALES
	 * */
	Estructura: function(btn){
		var win = btn.up('window');
		var form=win.down('form').getForm();

		form.submit({
			target: '_blank',
			url: Routing.generate('mbp_formulas_generaReporte'),
			params:{
				idArt: win.queryById('id').getValue()
			},
			standardSubmit: true
		})	
	}
});

















