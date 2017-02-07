Ext.define('MetApp.controller.Produccion.Programacion.FormulasMoController', {
	extend: 'Ext.app.Controller',
	
	views: [
		'Produccion.Programacion.FormulasMo',
		'Produccion.Programacion.OperacionBusqueda',
		'Articulos.WinArticuloSearch',
		'Articulos.ArticuloSearchGrd',
	],
	
	stores: [
		'Produccion.Programacion.FormulaMoStore',
		'Produccion.Programacion.OperacionesStore',
	],
	
	
	init: function(){
		var me = this;
		me.control({
			'#formulasMo': {
				click: this.TablaFormulasMo
			},
			'formulasMo button[action=buscaArt]': {
				click: this.BuscaArticulo
			},
			'formulasMo button[action=buscaOperacion]': {
				click: this.BuscaOperacion
			},
			'formulasMo button[action=actInsert]': {
				click: this.InsertarArticulo
			},
			'formulasMo button[action=actEdit]': {
				click: this.EditarArticulo
			},
			'formulasMo button[action=actDelete]': {
				click: this.EliminarArticulo
			}
		});
	},
		
	TablaFormulasMo: function(){
		var formulasMoWin = Ext.widget('formulasMo');
		var store = formulasMoWin.down('grid').getStore();		
		store.proxy.extraParams.codigo = '';
		store.load();
		formulasMoWin.queryById('buscaArt').focus('',true);
		formulasMoWin.queryById('btnNew').hide();
		formulasMoWin.queryById('btnSave').hide();
		formulasMoWin.queryById('btnReset').setDisabled(false);
	},
	
	BuscaArticulo: function(btn){
		var winFormula = btn.up('window');
		var gridFormula = winFormula.down('grid');
		var storeGrid = gridFormula.getStore();		
		
		var gridArt = Ext.widget('winarticulosearch');
		var btnInsert = gridArt.queryById('insertArt');
		var window = btn.up('window');
		
		var idArt = window.queryById('idArt');
		var codigoField = window.queryById('codigo');
		var descField = window.queryById('descripcion');
				
		btnInsert.on('click', function(){			 
				var grid = btnInsert.up('grid');
				var selection = grid.getSelectionModel().getSelection()[0];
				
				codigoField.setValue(selection.data.codigo);
				descField.setValue(selection.data.descripcion);
				idArt.setValue(selection.data.id);
				
				gridArt.close();
					
				storeGrid.proxy.extraParams.codigo = selection.data.codigo;
				storeGrid.load(); 						
				
				winFormula.queryById('buscaOperacion').focus('',true);
		});
		
	},
	
	BuscaOperacion: function(btn){
		var win = Ext.widget('operacionBusqueda');
		var button = win.queryById('insert');
		var grid = win.down('grid');
		var store = grid.getStore();
				
		store.load();
		
		/*
		 * Escuchamos al boton de la ventana de busqueda
		 */
		button.on('click',function(){
			var winFormula = btn.up('window');
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];	
			
			winFormula.queryById('operacion').setValue(selection.data.id);
			winFormula.queryById('descripcionForm').setValue(selection.data.descripcion);											
			
			win.close();	
			winFormula.queryById('tiempo').focus('',true);	
		});
	},
	
	//INSERTA ARTICULO EN LA GRILLA, NO GUARDA EN LA BD
	InsertarArticulo: function(btn){
		var win = btn.up('window');
		var store = win.down('grid').getStore();
		var formMo = btn.up('form');
		var formArt = win.queryById('formArt');
		var grid = win.down('grid');
		
		
			var valuesMo = formMo.getValues();
			var valuesArt = formArt.getValues();
			var tiempo = {
					date: new Date()
			}
			
			if(valuesMo.id > 0){				
				var selection = grid.getSelectionModel().getSelection()[0];
				store.suspendEvents(); //SUSPENDO EVENTOS
				selection.beginEdit();
				selection.set('id', parseInt(valuesMo.id));
				selection.set('idOperacion', valuesMo.operacion);
				selection.set('tiempo', valuesMo.tiempo);
				selection.endEdit();
				store.resumeEvents();	//RESUMO EVENTOS
			}else{
				var model = Ext.create('MetApp.model.Produccion.Programacion.FormulasMoModel');
				model.set('id', parseInt(valuesMo.id));
				model.set('idOperacion', valuesMo.operacion);
				model.set('tiempo', valuesMo.tiempo);
				store.suspendEvents(); //SUSPENDO EVENTOS
				store.add(model);		//AGREGO EL REGISTRO
				store.resumeEvents();	//RESUMO EVENTOS
			}
			
			formMo.getForm().reset();	
			
			win.queryById('buscaOperacion').focus('',true);
		
	},
	
	//EDITA ARTICULO MO FORMULA
	EditarArticulo: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		
		if(!selection){
			Ext.Msg.show({
				title: 'Atencion',
					msg: 'Debe seleccionar un elemento de la grilla',
					buttons: Ext.Msg.OK,
					icon: Ext.Msg.WARNING
			});	
		}else{
			//CAMPOS
			win.queryById('id').setValue(selection.data.id);
			win.queryById('operacion').setValue(selection.data.idOperacion.id);
			win.queryById('descripcionForm').setValue(selection.data.idOperacion.descripcion);
			win.queryById('tiempo').setValue(Ext.util.Format.date(selection.data.tiempo.date,'H:i:s'));	
		}
	},
	
	//ELIMINAR ARTICULO MO FORMULA
	EliminarArticulo: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		
		if(!selection){
			Ext.Msg.show({
				title: 'Atencion',
					msg: 'Debe seleccionar un elemento de la grilla',
					buttons: Ext.Msg.OK,
					icon: Ext.Msg.WARNING
			});	
		}else{
			//CAMPOS
			store.remove(selection);
		}
	}
});


















