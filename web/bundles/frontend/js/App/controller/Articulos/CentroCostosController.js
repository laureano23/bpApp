Ext.define('MetApp.controller.Articulos.CentroCostosController',{
	extend: 'Ext.app.Controller',
	views: ['MetApp.view.Parametros.CentroCostosView',
	'MetApp.view.Parametros.SearchGridCentroCostosView'
	],
	stores: ['MetApp.store.Parametros.CentroCostosStore'],
	
	refs: [
		{
			ref:'CentroCostosView',
			selector: 'CentroCostosView'
		}
	],
	
	init: function(){
		var me = this;
		me.control({
				'#tbCentroCostos': {
					click: this.addCentroCostosTb
				},
				'CentroCostosView button[itemId=searchCentroCosto]':{
					click: this.BuscaCentroCosto
				},
				'CentroCostosView button[itemId=btnNew]':{
					click: this.Nuevo
				},
				'CentroCostosView button[itemId=btnSave]':{
					click: this.Guardar
				},
				'CentroCostosView button[itemId=btnEdit]':{
					click: this.Editar
				},
				'CentroCostosView button[itemId=btnDelete]':{
					click: this.Eliminar
				},
				'SearchGridCentroCostosView button[itemId=insert]': {
					click: this.InsertarRegistro
				}
		});		
	},
	
	addCentroCostosTb: function(btn){		
		var win = Ext.widget('CentroCostosView');	
		var me = this;
		var map = new Ext.util.KeyMap({
		    target: win.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.Guardar(win.queryById('btnSave'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.Editar(win.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F3,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){
		   				me.Nuevo(win.queryById('btnNew'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.Eliminar(win.queryById('btnDelete'));
		   			}
		   		},
		   	]
		});		
	},
	
	BuscaCentroCosto: function(btn){
		var me = this;
		var view = Ext.widget('SearchGridCentroCostosView');
		view.down('grid').getStore('MetApp.store.Parametros.CentroCostosStore').load();
	},
	
	InsertarRegistro: function(btn){
		var me = this;
		var win = btn.up('window');
		var grid = win.down('grid');
		var selection = grid.getSelectionModel().getSelection()[0];
		
		var viewCentroCostos = me.getCentroCostosView();
		var form = viewCentroCostos.queryById('form');
		form.loadRecord(selection);
		
		win.close();
	},
	
	Nuevo: function(btn){
		var win = btn.up('window');
		var form = win.queryById('form');
		var botonera = win.queryById('botonera');
		form.getForm().reset();
		form.query('field').forEach(function(field){
			field.setDisabled(false);
		});
		win.queryById('centroCosto').focus();
		
		botonera.nuevoItem(botonera);
	},
	
	Guardar: function(btn){
		var me = this;
		var win = btn.up('window');
		var form = win.queryById('form');
		var values = form.getValues();
		var store = me.getStore('Parametros.CentroCostosStore');
		var botonera = win.queryById('botonera');
		
		if(values.id > 0){			
			var rec = form.getRecord();
			console.log(rec);
			rec.setDirty();
			if(form.isValid()){
				rec.set(values);
				form.query('field').forEach(function(field){
					field.setDisabled(true);
				});
				botonera.guardarItem(botonera);	
			}
			
		}else{
			store.on('write', function(st, op){
				var decodeResp = Ext.JSON.decode(op.response.responseText);
				if(decodeResp.success == true){
					form.query('field').forEach(function(field){
						field.setDisabled(true);
					});
					form.queryById('id').setValue(decodeResp.id);
					botonera.guardarItem(botonera);
				}
			})
			var model = Ext.create('MetApp.model.Parametros.CentroCostosModel');
			if(form.isValid()){
				model.set(values);
				store.add(model);		
			}
		}			
	},
	
	Editar: function(btn){
		var me = this;
		var win = btn.up('window');
		var form = win.queryById('form');
		var botonera = win.queryById('botonera');
		form.query('field').forEach(function(field){
			field.setDisabled(false);
		});
		botonera.editarItem(botonera);
	},
	
	Eliminar: function(btn){
		var me = this;
		var win = btn.up('window');
		var form = win.queryById('form');
		var rec = form.getRecord();
		var store = rec.store;
		store.remove(rec);
		form.getForm().reset();
	}
});










