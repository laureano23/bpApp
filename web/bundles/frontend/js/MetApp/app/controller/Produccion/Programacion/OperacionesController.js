Ext.define('MetApp.controller.Produccion.Programacion.OperacionesController', {
	extend: 'Ext.app.Controller',
	
	views: [
		'Produccion.Programacion.Sectores',
		'Produccion.Programacion.SectoresBusqueda',
		'Produccion.Programacion.Operaciones',
		'Produccion.Programacion.OperacionBusqueda',
	],
	
	stores: [
		'Produccion.Programacion.SectoresStore',
		'Produccion.Programacion.OperacionesStore',
	],
	
	
	init: function(){
		var me = this;
		me.control({
			'#sector': {
				click: this.TablaSectores
			},	
			'sectores combobox[itemId=sector]': {
				change: this.DatosSector
			},				
			'#operaciones': {
				click: this.TablaOperaciones
			},
			'operaciones button[action=buscaOpe]': {
				click: this.TablaBuscaOperaciones
			},
			'operaciones button[action=buscaCentroCostos]': {
				click: this.TablaBuscaCentroCostos
			},
			'operaciones button[action=actNew]': {
				click: this.NuevaOperacion
			},
			'operaciones button[action=actEdit]': {
				click: this.EditarOperacion
			},
			'operaciones button[action=actSave]': {
				click: this.GuardarOperacion
			},
			'operaciones button[action=actDelete]': {
				click: this.BorraOperacion
			}
		});
	},
	
	/*
	 * SECTORES ABM
	 */
	
	TablaSectores: function(){
		var sectoresWin = Ext.widget('sectores');
		//var store = this.getStore('Produccion.Programacion.SectoresStore');
		sectoresWin.queryById('sector').getStore().load();
		
	},	
	
	DatosSector: function(field, newV, oldV, eOpts){
		var win = field.up('window');
		var fieldCosto = win.queryById('costoSector');
		var fieldHsDisponibles = win.queryById('hsDisponibles');
		
		//INSTANCIA DEL STORE DE SECTOR
		var storeSector = this.getStore('Produccion.Programacion.SectoresStore');		
		
		//INSTANCIA DEL STORE PERSONAL CON UN PROXY REDEFINIDO
		var storePersonal = this.getStore('Personal.Personal');;
		var proxy = storePersonal.getProxy();
		proxy.api.read = Routing.generate('mbp_produccion_personal_sector');
		
		//INSTANCIA DEL STORE MAQUINAS CON UN PROXY REDEFINIDO
		var storeMaquinas = this.getStore('Produccion.Programacion.MaquinasStore');
		var proxyMaquinas = storeMaquinas.getProxy();
		proxyMaquinas.api.read = Routing.generate('mbp_produccion_maquinas_segun_sector');
		
		//CARGA DATOS DEL SECTOR									
		var proxySector = storeSector.getProxy();									
		proxySector.extraParams.sector = field.value;
		storeSector.load({
			 callback: function(records, operation, success) {				
				fieldCosto.setValue(storeSector.data.items[0].data.costo);
				fieldHsDisponibles.setValue(storeSector.data.items[0].data.tiempo); 
			 }
		}); 
		
		
		
		//CARGA GRILLA DE PERSONAL
		proxy = storePersonal.getProxy();
		proxy.extraParams = {sector: newV};
		storePersonal.load();
		//CARGA GRILLA DE MAQUINAS
		proxyMaquinas = storeMaquinas.getProxy();
		proxyMaquinas.extraParams = {sector: newV};
		storeMaquinas.load();
		
	},

	
	/*
	 * 
	 * OPERACIONES
	 * 
	 */
	
	TablaBuscaOperaciones: function(btn){
		var winBusqueda = Ext.widget('operacionBusqueda');
		var grid = winBusqueda.down('grid');
		var btnInsert = winBusqueda.queryById('insert');
		var formOperacion = btn.up('form');
		
		//CARGO STORE
		grid.getStore().load();		
		
		btnInsert.on('click', function(){
			var selection = grid.getSelectionModel().getSelection()[0];
			selection = selection.getData();
			if(selection){
				formOperacion.queryById('id').setValue(selection.id);
				formOperacion.queryById('descripcion').setValue(selection.descripcion);
				formOperacion.queryById('centroCosto').setValue(selection.centroCosto.nombre);
				
				winBusqueda.close();
			}
		});
	},
	
	TablaBuscaCentroCostos: function(btn){
		var form = btn.up('form');				
		var winBuscaSector = Ext.widget('SearchGridCentroCostosView');
		var store = winBuscaSector.down('grid').getStore();
		var btnInsert = winBuscaSector.queryById('insert');			
		
		store.load();
		btnInsert.on('click', function(){
			var grid = winBuscaSector.down('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			var fieldSector = form.queryById('centroCosto');
			fieldSector.setValue(selection.data.nombre);
			winBuscaSector.close();
		});
			
		
	},
	
	/*
	 * OPERACIONES ABM
	 */
	
	TablaOperaciones: function(){
		var operacionesWin = Ext.widget('operaciones');		
		var btnDelete = operacionesWin.queryById('btnDelete');
		
		operacionesWin.queryById('btnReset').hide();
		btnDelete.hide();
	},
	
	NuevaOperacion: function(btn){
		var form = btn.up('form');
		var fieldDesc = form.queryById('descripcion');
		var btnBuscaSector = form.queryById('buscaCentroCostos');
		var btnSave = form.queryById('btnSave');
				
		form.getForm().reset();
		fieldDesc.setReadOnly(false);
		fieldDesc.focus();
		btnBuscaSector.setDisabled(false);
		btnSave.setDisabled(false);
				
	},
	
	EditarOperacion: function(btn){
		var form = btn.up('form');
		var desc = form.queryById('descripcion');
		var btnSector = form.queryById('centroCosto');
		var btnSave = form.queryById('btnSave');
		
		desc.setReadOnly(false);
		desc.focus();
		btnSector.setDisabled(false);
		btnSave.setDisabled(false);
	},
	
	GuardarOperacion: function(btn){
		var form = btn.up('form');		
		var store = this.getStore('Produccion.Programacion.OperacionesStore');
		
		if(form.isValid()){			
			var values = form.getValues();
			if(values.id > 0){
				var record = store.findRecord('id', values.id);
				store.suspendEvents();
				record.beginEdit();
				record.set('descripcion', values.descripcion);
				record.set('centroCosto', values.sector);
				record.endEdit();
				store.resumeEvents();
			}else{
				var model = Ext.create('MetApp.model.Produccion.Programacion.OperacionesModel');
				model.set(values);				
				store.add(model);	
			}			
		}		
		
		form.queryById('descripcion').setReadOnly(true);
		form.queryById('buscaCentroCostos').setDisabled(true);
		btn.setDisabled(true);
	},
	
	BorraOperacion: function(btn){
		var form = btn.up('form');
		var id = form.queryById('id');
	},
})