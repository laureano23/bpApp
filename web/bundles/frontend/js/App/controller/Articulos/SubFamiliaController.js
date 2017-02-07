Ext.define('MetApp.controller.Articulos.SubFamiliaController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Articulos.SubFamiliaView'
	],
	stores: [
		'MetApp.store.Articulos.SubFamiliaStore'
	],
	
	refs:[
		{
			ref:'SubFamiliaView',
			selector: 'SubFamiliaView'
		},
	],
	
	init: function(){
		var me = this;		
		
		var store = Ext.getStore('MetApp.store.Articulos.SubFamiliaStore');
		store.on({
			'write': me.callbackSubFamiliaStore
		});
		
		me.control({
				'#tabSubFamilia': {
					click: this.addSubFamiliaView
				},
				'SubFamiliaView button[itemId=btnNew]': {
					click: this.New
				},
				'SubFamiliaView button[itemId=btnSave]': {
					click: this.Save
				},
				'SubFamiliaView button[itemId=btnEdit]': {
					click: this.Edit
				},
				'SubFamiliaView button[itemId=btnDelete]': {
					click: this.Delete
				}
		});
	},
	
	callbackSubFamiliaStore: function(store, operation, eOpts){
		if(operation.action == "destroy"){
			return;
		}
		
		var resp = Ext.JSON.decode(operation.response.responseText);		
		var view = MetApp.getApplication().getController('MetApp.controller.Articulos.SubFamiliaController').getSubFamiliaView();
		var form = view.down('form');
		
		if(resp.success != true){
			form.getForm().reset();
		}else{
			form.queryById('idSubFamilia').setValue(resp.data.idSubFamilia);
			form.query('field').forEach(function(field){
				field.setDisabled(true);
			});
			var botonera = form.queryById('botonera'); 
			botonera.guardarItem(botonera);
		}
	},
	
	addSubFamiliaView: function(btn){
		var me = this;
		var addWin = Ext.widget('SubFamiliaView');
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
		
		form.queryById('subFamilia').focus();
		
		botonera.nuevoItem(botonera);
	},
	
	Save: function(btn){
		var form = btn.up('form');		
		var values = form.getForm().getValues();
		var store = Ext.getStore('MetApp.store.Articulos.SubFamiliaStore');
		
		if(values.idSubFamilia > 0){
			var rec = store.findRecord('idSubFamilia', values.idSubFamilia);
			rec.set(values);
		}else{
			var model = Ext.create('MetApp.model.Articulos.SubFamilia');
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
		form.queryById('subFamilia').focus();
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










