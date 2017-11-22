Ext.define('MetApp.controller.Articulos.FamiliaController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Articulos.FamiliaView'
	],
	stores: [
		'MetApp.store.Articulos.FamiliaStore'
	],
	
	refs:[
		{
			ref:'FamiliaView',
			selector: 'FamiliaView'
		},
	],
	
	init: function(){
		var me = this;		
		
		var store = Ext.getStore('MetApp.store.Articulos.FamiliaStore');
		store.on({
			'write': me.callbackFamiliaStore
		});
		
		me.control({
				'#tabFamilia': {
					click: this.addFamiliaView
				},
				'FamiliaView button[itemId=btnNew]': {
					click: this.New
				},
				'FamiliaView button[itemId=btnSave]': {
					click: this.Save
				},
				'FamiliaView button[itemId=btnEdit]': {
					click: this.Edit
				},
				'FamiliaView button[itemId=btnDelete]': {
					click: this.Delete
				}
		});
	},
	
	callbackFamiliaStore: function(store, operation, eOpts){
		if(operation.action == "destroy"){
			return;
		}
		
		var resp = Ext.JSON.decode(operation.response.responseText);		
		var view = MetApp.getApplication().getController('MetApp.controller.Articulos.FamiliaController').getFamiliaView();
		var form = view.down('form');
		
		if(resp.success != true){
			form.getForm().reset();
		}else{
			form.queryById('idFamilia').setValue(resp.data.idFamilia);
			form.query('field').forEach(function(field){
				field.setDisabled(true);
			});
			var botonera = form.queryById('botonera'); 
			botonera.guardarItem(botonera);
		}
	},
	
	addFamiliaView: function(btn){
		var me = this;
		var addWin = Ext.widget('FamiliaView');
		//CARGO EL STORE
		var grid = addWin.down('grid');
		grid.getStore().load();
		//HOT KEY DE LA TABLA LIQUIDACION
		var map = new Ext.util.KeyMap({
		    target: addWin.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F3,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.New(addWin.queryById('btnNew'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.Save(addWin.queryById('btnSave'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.Edit(addWin.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.Delete(addWin.queryById('btnDelete'));
		   			}
		   		},
		   	]
		});		
	},
	
	New: function(btn){
		var win = btn.up('window');
		var form = btn.up('form');
		var botonera = win.queryById('botonera');
		
		Ext.each(form.items.items, function(a){
			a.setDisabled(false);
		});
		form.getForm().reset();
		
		form.queryById('familia').focus();
		
		botonera.nuevoItem(botonera);
	},
	
	Save: function(btn){
		var form = btn.up('form');		
		var values = form.getForm().getValues();
		var store = Ext.getStore('MetApp.store.Articulos.FamiliaStore');
		
		if(values.idFamilia > 0){
			var rec = store.findRecord('idFamilia', values.idFamilia);
			rec.set(values);
		}else{
			var model = Ext.create('MetApp.model.Articulos.Familia');
			model.set(values);
			store.add(model);	
		}
	},
	
	Edit: function(btn){
		var form = btn.up('form');
		var botonera = form.queryById('botonera');
		var grid = form.down('grid');
		var sel = grid.getSelectionModel().getSelection()[0];
		
		form.getForm().setValues(sel.data);
		form.query('field').forEach(function(field){
			field.setDisabled(false);
		});
		form.queryById('familia').focus();
		botonera.editarItem(botonera);
	},
	
	Delete: function(btn){
		var form = btn.up('form');
		var botonera = form.queryById('botonera');
		var grid = form.down('grid');
		
		
		Ext.Msg.show({
		     title:'Atencion',
		     msg: 'Desea borrar el registro?',
		     buttons: Ext.Msg.YESNO,
		     icon: Ext.Msg.QUESTION,
		     fn:function(btn){
				if(btn == 'yes'){
					var store = grid.getStore();
					var sel = grid.getSelectionModel().getSelection()[0];
					store.remove(sel);
				}
			}
		});
	}
});










