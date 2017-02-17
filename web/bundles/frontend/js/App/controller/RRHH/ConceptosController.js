Ext.define('MetApp.controller.RRHH.ConceptosController', {
	extend: 'Ext.app.Controller',
	views: [
		'MetApp.view.RRHH.TablaConceptos',
		'MetApp.view.RRHH.ConceptosWinSearch'
	],
	
	stores: [
		'MetApp.store.Personal.ConceptosStore'
	],
	
	init: function(){
		var me = this;
		me.control({
			'#tablaConceptos': {
				click: this.NuevaTabla
			},
			'conceptosTabla button[itemId=buscaConcepto]': {
				click: this.Buscador
			},
			'conceptosTabla button[action=actNew]': {
				click: this.NuevoRegistro
			},
			'conceptosTabla button[action=actSave]': {
				click: this.GuardarRegistro
			},
			'conceptosTabla button[action=actEdit]': {
				click: this.EditRegistro
			},
			'conceptosTabla button[action=actResetForm]': {
				click: this.ResetForm
			},
			'conceptosTabla button[action=actDelete]': {
				click: this.DeleteRegistro
			}
		})
	},
		
	NuevaTabla: function(btn){
		var me = this;
		var win = Ext.create('MetApp.view.RRHH.TablaConceptos');
		var store = Ext.getStore('MetApp.store.Personal.ConceptosStore');
		var proxyConceptos = store.getProxy();
		proxyConceptos.setExtraParam('variable', 0);
		proxyConceptos.setExtraParam('idP', 0);
		store.load();
		
		//HOT KEYS DE LA TABLA CONCEPTOS
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
		var winSearch = Ext.create('MetApp.view.RRHH.ConceptosWinSearch');
		var winForm = btn.up('window');
		var form = winForm.down('form');
		var grid = winSearch.down('grid');
		var btnInsert = winSearch.queryById('insertConcepto');
		
		btnInsert.on('click', function(btn){
			var record = grid.getSelectionModel().getSelection()[0];
			form.loadRecord(record);
			var codigoCalculo = winForm.queryById('codigoCalculo');
			winSearch.close();
		});
	},
	
	NuevoRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var store = Ext.getStore('MetApp.store.Personal.ConceptosStore');
		var botonera = win.queryById('botonera');
		form.queryById('buscaConcepto').setDisabled(true);
		
		form.getForm().reset();
		
		form.query('field').forEach(function(field){
			field.setReadOnly(false);
		});
				
		form.queryById('descripcion').focus();
		
		botonera.nuevoItem(botonera);
	},
	
	GuardarRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var values = form.getForm().getValues();
		var store = Ext.getStore('MetApp.store.Personal.ConceptosStore');
		
		var codigoCalc = win.queryById('codigoCalculo');
		
		//ESCUCHA EVENTOS DE ESCRITURA
		store.on('write', function(store, operation){
			var response = Ext.JSON.decode(operation.response.responseText);
			if(operation.success == true){
				form.loadRecord(operation.records[0]);
				form.query('field').forEach(function(field){
					field.setReadOnly(true);
				});
				form.queryById('id').setValue(response.idConcepto);
				form.queryById('buscaConcepto').setDisabled(false);
				botonera.busquedaItem(botonera);
			}
		});
		
		//ESCUCHA EVENTOS DE ACTUALIZACION
		store.on('update', function(store, operation){
			if(operation.success == true){
				form.loadRecord(operation.records[0]);
				form.query('field').forEach(function(field){
					field.setReadOnly(true);
				});
				form.queryById('buscaConcepto').setDisabled(false);
				botonera.busquedaItem(botonera);
			}
		});
		
		if(form.isValid()){
			if(values.id > 0){
				//EDIT
				var record = form.getRecord();
				record.beginEdit();
				record.set(values);
				record.endEdit();
			}else{
				//CREATE
				var model = Ext.create('MetApp.model.Personal.ConceptosModel');
				model.set(values);	
				store.add(model);	
			}			
		}
	},
	
	EditRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		
		form.query('field').forEach(function(field){
			field.setReadOnly(false);
		});
		
		botonera.editarItem(botonera);
	},
	
	DeleteRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var store = Ext.getStore('MetApp.store.Personal.ConceptosStore');
		
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
	},
	
	ResetForm: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		
		form.query('field').forEach(function(field){
			field.setReadOnly(true);
		});
		
		win.queryById('buscaConcepto').setDisabled(false);
		
		form.getForm().reset();
		botonera.busquedaItem(botonera);
	}
})





















