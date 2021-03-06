Ext.define('MetApp.controller.Produccion.SoldaduraController',{
	extend: 'Ext.app.Controller',
	
	views: [
		'Produccion.Soldadura.ControlProduccionView',
		'MetApp.view.RRHH.SearchGridPersonal'
	],
	
	stores: [
		'MetApp.store.Produccion.Programacion.OperacionesStore',
		'MetApp.store.Produccion.ProduccionSoldadoStore'
	],
	
	init: function(){
		var me = this;
		me.control({
			'viewport menuitem[itemId=controlProduccionSoldado]': {
				click: this.ControlProduccion
			},
			'ControlProduccionView button[itemId=buscaPersonal]': {
				click: this.BuscaPersonal
			},	
			'ControlProduccionView button[itemId=insertar]': {
				blur: this.NuevoRegistro
			},		
			'ControlProduccionView actioncolumn[itemId=editar]': {
				click: this.EditarRegistro
			},
			'ControlProduccionView actioncolumn[itemId=eliminar]': {
				click: this.EliminarRegistro
			},		
		});
	},
	
	ControlProduccion: function(btn){
		var win = Ext.widget('ControlProduccionView');
		var store = win.down('combobox').getStore();
		var storeGrid = win.down('grid').getStore();
		var grid = win.down('grid');
		
		store.getProxy().setExtraParam ('sector', 'SOLDADO'); //PARA TRAER SOLO LAS OPERACIONES DE SOLDADO
		store.load();
		
		storeGrid.on('load', function(){
			grid.getView().scrollBy(0, 999999, true);
		});
		
		var model = grid.getView();
		var selectionModel = model.getSelectionModel();

		store.clearFilter(true);
		
		grid.getStore().load();
		
		win.queryById('buscaPersonal').focus('', 10);
		
		
	},
	
	BuscaPersonal: function(btn){
		var win = Ext.widget('searchGridPersonal');
		var storePersonal = win.down('grid').getStore();
		storePersonal.getProxy().setExtraParam ('sector', 'SOLDADO');
		var form = btn.up('window').down('form');
		win.down('grid').getStore().load();
				
		win.down('button').on('click', function(btn){
			var selection = win.down('grid').getSelectionModel().getSelection()[0];
			form.loadRecord(selection);
			win.close();
			form.queryById('fecha').focus('', 10);
		});
		
		storePersonal.getProxy().setExtraParam ('sector', '');
	},
	
	NuevoRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var values = win.down('form').getForm().getValues();
		var grid = win.down('grid');
		var store = grid.getStore();
		var storeOperaciones = win.queryById('operaciones').getStore();
				
		if(form.isValid() == false){
			return;
		}
		
		var operacion = storeOperaciones.findRecord('operacionId', values.operacionId);
		
		var record;
		if(values.id > 0){
			record = store.findRecord('id', values.id);			
		}else{
			record = Ext.create('MetApp.model.Produccion.ProduccionSoldadoModel');	
		}
		
		record.set(values);
		record.set('operacion', operacion.data.descripcion);
		
		store.getProxy().addListener('exception', function(st, response, operation, eOpts){	//CAPTURA ERRORES DEL SERVER			
			var resp = Ext.JSON.decode(response.responseText);
			form.getForm().markInvalid(resp.msg);
			store.rejectChanges();
		});
		
		store.on('write', function(st, meta){
			if(meta.success == true) form.getForm().reset(); 
			win.queryById('buscaPersonal').focus('', 10);
			grid.getView().scrollBy(0, 999999, true);
		});
				
		store.addSorted(record);
			
	},
	
	EditarRegistro: function(grid, colIndex, rowIndex){
		var selection = grid.getStore().getAt(rowIndex);
		var win = grid.up('window');
		var form = win.down('form');
		
		form.loadRecord(selection);
		var combo = form.queryById('operaciones');
		var operacion = combo.getStore().findRecord('operacionId', selection.data.operacionId, 0, false, false, true);
		combo.select(operacion);
	},
	
	EliminarRegistro: function(grid, colIndex, rowIndex){
		var selection = grid.getStore().getAt(rowIndex);
		var win = grid.up('window');
		var form = win.down('form');
		var store = grid.getStore();
		
		Ext.Msg.show({
			title: 'Atencion',
			msg: 'Desea eliminar el registro?',
			buttons: Ext.Msg.YESNO,
     		icon: Ext.Msg.QUESTION,
     		fn: function(resp){
     			if(resp == 'yes'){
     				store.remove(selection);			
     			}
     		}
		});
		
	},
});




















