Ext.define('MetApp.controller.RRHH.SindicatosController', {
	extend: 'Ext.app.Controller',
	views: [
		'MetApp.view.RRHH.TablaSindicatos'
	],
	
	stores: [
		'MetApp.store.Personal.SindicatosStore'
	],
	
	init: function(){
		var me = this;
		me.control({
			'#tablaSindicatos': {
				click: this.NuevaTabla
			},
			'sindicatosTabla button[itemId=nextRecord]': {
				click: this.NextRecord
			},
			'sindicatosTabla button[itemId=prevRecord]': {
				click: this.PrevRecord
			},
			'sindicatosTabla button[action=actNew]': {
				click: this.NuevoRegistro
			},
			'sindicatosTabla button[action=actSave]': {
				click: this.GuardarRegistro
			},
			'sindicatosTabla button[action=actEdit]': {
				click: this.EditRegistro
			},
			'sindicatosTabla button[action=actResetForm]': {
				click: this.ResetForm
			},
			'sindicatosTabla button[action=actDelete]': {
				click: this.DeleteRegistro
			}
		})
	},
		
	NuevaTabla: function(btn){
		var win = Ext.create('MetApp.view.RRHH.TablaSindicatos');
		var store = Ext.getStore('MetApp.store.Personal.SindicatosStore');
		var form = win.down('form');
		var record;
		
		store.load({
			callback: function(records, operation, success) {
				record = store.findRecord('idSindicato', 1);		
				form.loadRecord(record);    	
		    }
		});		
	},
	
	NextRecord: function(btn){
		var form = btn.up('form');
		var store = Ext.getStore('MetApp.store.Personal.SindicatosStore');
		var totalRecords = store.getTotalCount();
		var record = form.getRecord();
		var index;
		//SETEO EL INDEX
		if(record){
			index = record.index;
		}else{
			index = 1;
		}
		
		
		if(index < totalRecords){
			index = index + 1;
		}else{
			index = 0;
		}		
		
		var record2 = store.getAt(index);
		if (record2){
			form.loadRecord(record2);	
		}
	},
	
	PrevRecord: function(btn){
		var form = btn.up('form');
		var store = Ext.getStore('MetApp.store.Personal.SindicatosStore');
		var totalRecords = store.getTotalCount();
		var record = form.getRecord();
		var index;
		//SETEO EL INDEX
		if(record){
			index = record.index;
		}else{
			index = 1;
		}
		
		
		if(index > 1){
			index = index - 1;
		}else{
			index = 0;
		}		
		
		var record2 = store.getAt(index);
		if (record2){
			form.loadRecord(record2);	
		}		
	},
	
	NuevoRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		
		form.getForm().reset();
		form.queryById('sindicato').setDisabled(false);
		
		botonera.nuevoItem(botonera);
	},
	
	GuardarRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var store = Ext.getStore('MetApp.store.Personal.SindicatosStore');
		var idSindicato = form.queryById('idSindicato').getValue();
		
		if(form.isValid){
			store.on('write', function(store, operation){
				if(operation.action == 'create'){
					var record = operation.getRecords()[0];
					form.loadRecord(record);	
				}				
			});
			
			if(idSindicato > 0){
				var record = form.getRecord();
				record.beginEdit();
				record.set('sindicato', form.queryById('sindicato').getValue());				
				record.endEdit();
				
			}else{
				var model = Ext.create('MetApp.model.Personal.SindicatosModel');
				var values = form.getValues();
				store.add(values);
			}
			form.queryById('sindicato').setDisabled(true);
		}
		
		botonera.busquedaItem(botonera);
	},
	
	EditRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');		
		
		form.queryById('sindicato').setDisabled(false);
		botonera.editarItem(botonera);
	},
	
	DeleteRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var store = Ext.getStore('MetApp.store.Personal.SindicatosStore');
		
		Ext.Msg.show({
		     title:'Atencion',
		     msg: 'Desea eliminar el registro?',
		     buttons: Ext.Msg.YESNO,
		     icon: Ext.Msg.QUESTION,
		     fn:function(btn){
				if(btn == 'yes'){
					var record = form.getRecord();
					store.remove(record);
					store.on('remove', function(store, operation){
						var record = operation.getRecords()[0];
						form.loadRecord(record);
					});
					form.getForm().reset();
					botonera.busquedaItem(botonera);
				}
			}
		});
	},
	
	ResetForm: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		
		form.getForm().reset();
		form.queryById('sindicato').setDisabled(true);
		
		botonera.busquedaItem(botonera);
	}
})





















