Ext.define('MetApp.controller.RRHH.CategoriasController', {
	extend: 'Ext.app.Controller',
	views: [
		'MetApp.view.RRHH.TablaCategorias',
		'MetApp.view.RRHH.CategoriasWinSearch'
	],
	
	stores: [
		'MetApp.store.Personal.CategoriasStore'
	],
	
	init: function(){
		var me = this;
		
		
		me.control({
			'#tablaCategorias': {
				click: this.NuevaTabla
			},
			'categoriasTabla button[itemId=searchCategoria]': {
				click: this.Buscador
			},
			'categoriasTabla button[action=actNew]': {
				click: this.NuevoRegistro
			},
			'categoriasTabla button[action=actSave]': {
				click: this.GuardarRegistro
			},
			'categoriasTabla button[action=actEdit]': {
				click: this.EditRegistro
			},
			'categoriasTabla button[action=actResetForm]': {
				click: this.ResetForm
			},
			'categoriasTabla button[action=actDelete]': {
				click: this.DeleteRegistro
			}
		})
	},
		
	NuevaTabla: function(btn){
		var me = this;
		var win = Ext.create('MetApp.view.RRHH.TablaCategorias');
		var store = Ext.create('MetApp.store.Personal.CategoriasStore');		
		store.load();	
		
		var map = new Ext.util.KeyMap({
		    target: win.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.GuardarRegistro(win.queryById('btnSave'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EditRegistro(win.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F3,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){
		   				me.NuevoRegistro(win.queryById('btnNew'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.DeleteRegistro(win.queryById('btnDelete'));
		   			}
		   		},
		   	]
		});		
	},
	
	Buscador: function(btn){
		/*GRILLA*/
		var win = Ext.create('MetApp.view.RRHH.CategoriasWinSearch');
		var grid = win.down('grid');
		var store = grid.getStore();
		var insertBtn = win.queryById('insertCategoria');
		/*EOF GRILLA*/		
		var form = btn.up('form');
		
		insertBtn.on('click', function(){
			var rec = grid.getSelectionModel().getSelection()[0];
			form.loadRecord(rec);
			var comboSindicato = form.queryById('comboSindicato');
			var storeSindicato = Ext.getStore('MetApp.store.Personal.SindicatosStore');
			storeSindicato.load({
				callback: function(records, operation, success) {
			        if(success == true){
			        	var record = storeSindicato.findRecord('idSindicato', rec.data.idSindicato);			
						comboSindicato.select(record);
			        }
			    }
			});			
			win.close();
		});
		store.load();
	},
	
	NuevoRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		form.queryById('searchCategoria').setDisabled(true);
		
		form.getForm().reset();
		
		form.query('field').forEach(function(field){
			field.setDisabled(false);
		});
		
		botonera.nuevoItem(botonera);
		form.queryById('categoria').focus('', 500);
	},
	
	GuardarRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var store = Ext.getStore('MetApp.store.Personal.CategoriasStore');
		var botonera = win.queryById('botonera');
		var values = form.getValues();
		
		store.on('write', function(store, operation){
			if(operation.success == true){
				form.loadRecord(operation.records[0]);
				form.query('field').forEach(function(field){
					field.setDisabled(true);
				});
				form.queryById('searchCategoria').setDisabled(false);
				botonera.busquedaItem(botonera);
			}
		});
		
		if(form.isValid()){
			if(values.idCategoria > 0){
				var record = form.getRecord();
				record.set(values);
			}else{
				var record = Ext.create('MetApp.model.Personal.CategoriasModel');
				record.set(values);
				store.add(record);	
			}
		}
	},
	
	EditRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		
		form.queryById('searchCategoria').setDisabled(true);
		
		form.query('field').forEach(function(field){
			field.setDisabled(false);
		});
		
		botonera.editarItem(botonera);		
	},
	
	ResetForm: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		
		form.getForm().reset();
		
		form.queryById('searchCategoria').setDisabled(false);
		
		form.query('field').forEach(function(field){
			field.setDisabled(true);
		});
		
		botonera.busquedaItem(botonera);
	},
	
	DeleteRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var store = Ext.getStore('MetApp.store.Personal.CategoriasStore');
		
		store.on('write', function(store, operation, eOpts ){
			if(operation.success == true){
				form.getForm().reset();
				botonera.busquedaItem(botonera);
			}
		});
		
		Ext.Msg.show({
		     title:'Atencion',
		     msg: 'Desea eliminar el registro?',
		     buttons: Ext.Msg.YESNO,
		     icon: Ext.Msg.QUESTION,
		     fn:function(btn){
				if(btn == 'yes'){
					var record = form.getRecord();					
					store.remove(record);
				}
			}
		});
	}
})





















