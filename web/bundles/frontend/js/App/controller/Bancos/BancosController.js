Ext.define('MetApp.controller.Bancos.BancosController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Bancos.FormBancosView',
		'MetApp.view.Bancos.ConceptosBancoView'
		
	],
	stores: [
		'MetApp.store.Personal.BancosStore',
		'MetApp.store.Bancos.ConceptosBancoStore'
	],
	
	refs:[
	],
	
	init: function(){
		var me = this;
		
				
		me.control({
				'#tbBancos': {
					click: this.NewWinBancos
				},
				'FormBancosView combo[itemId=banco]': {
					change: this.DatosBancarios
				},
				'FormBancosView button[itemId=btnNew]': {
					click: this.NuevoBanco
				},
				'FormBancosView button[itemId=btnSave]': {
					click: this.GuardarBanco
				},
				'FormBancosView button[itemId=btnEdit]': {
					click: this.EditarBanco
				},
				'#movBancos': {
					click: this.NewMovBancos
				},
				'#conceptoBancos': {
					click: this.FormConceptoBancos
				},
				'ConceptosBancoView button[itemId=btnNew]': {
					click: this.NuevoConcepto
				},
				'ConceptosBancoView button[itemId=btnSave]': {
					click: this.GuardarConcepto
				},
				'ConceptosBancoView button[itemId=btnEdit]': {
					click: this.EditarConcepto
				},
				'ConceptosBancoView button[itemId=btnDelete]': {
					click: this.EliminarConcepto
				},
		});		
	},
	
	NewWinBancos: function(btn){
		var win = Ext.widget('FormBancosView');
		var store = win.queryById('banco').getStore();
		
	},
	
	DatosBancarios: function(combo){
		var form = combo.up('form');
		var gridStore = form.down('grid').getStore();
		Ext.Ajax.request({
			url: Routing.generate('mbp_bancos_ListarBanco'),
			
			params: {
				idBanco: combo.getValue()
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				form.getForm().setValues(jsonResp.data[0]);
				gridStore.loadData(jsonResp.data);
			},
			
			failure: function(){
				
			}
		})
	},
	
	NuevoBanco: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		
		form.getForm().reset();
		form.query('field').forEach(function(field){
			field.setReadOnly(false);
		});
		
		botonera.nuevoItem(botonera);
		
		
	},
	
	GuardarBanco: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var values = form.getForm().getValues();
		
		if(!form.isValid()){
			return;
		}
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_bancos_NuevoBanco'),
			
			params: {
				data: Ext.JSON.encode(values)
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				
				if(jsonResp.success == true){
					form.query('field').forEach(function(field){
						field.setReadOnly(true);
					});
					form.queryById('banco').setReadOnly(false);
					form.queryById('banco').setValue(jsonResp.idBanco);
					var store = form.queryById('banco').getStore();
					store.load();
					botonera.resetFn(botonera);
				}
			},
			
			failure: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				form.getForm().markInvalid(jsonResp.errors);
				
			}
		});
	},
	
	EditarBanco: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');		
		var values = form.getForm().getValues();
		
		botonera.editarItem(botonera);
		
		form.query('field').forEach(function(field){
			field.setReadOnly(false);
		});
	},
	
	NewMovBancos: function(btn){
		console.log(btn);
	},
	
	FormConceptoBancos: function(btn){
		var view = Ext.widget('ConceptosBancoView');
		var grid = view.down('grid');
		var store = grid.getStore();
		
		store.load();
	},
	
	NuevoConcepto: function(btn){
		var win = btn.up('window');
		var botonera = win.queryById('botonera');
		win.queryById('concepto').setReadOnly(false);
		win.queryById('concepto').focus('', 20);
		
		botonera.nuevoItem(botonera);
		
	},
	
	GuardarConcepto: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		var store = win.down('grid').getStore();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_bancos_nuevoConceptoBanco'),
			
			params: {
				id: win.queryById('id').getValue(),
				concepto: win.queryById('concepto').getValue()
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					botonera.guardarItem(botonera);
					store.load();
					form.getForm().reset();
					win.queryById('concepto').setReadOnly(true);	
				}				
			}
		})
	},
	
	EditarConcepto: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var selection = form.down('grid').getSelectionModel().getSelection()[0];
		var botonera = win.queryById('botonera');
		
		form.queryById('concepto').setReadOnly(false);
		form.loadRecord(selection);
		botonera.editarItem(botonera);
	},
	
	EliminarConcepto: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var selection = form.down('grid').getSelectionModel().getSelection()[0];
		var botonera = win.queryById('botonera');
		var store = win.down('grid').getStore();
		
		Ext.Msg.show({
		     title:'Atencion',
		     msg: 'Desea borrar el registro?',
		     buttons: Ext.Msg.YESNO,
		     icon: Ext.Msg.QUESTION,
		     fn: function(btn){
		     	if(btn == 'yes'){
		     		Ext.Ajax.request({
						url: Routing.generate('mbp_bancos_eliminarConceptoBanco'),
						
						params: {
							id: selection.data.id
						},
						
						success: function(resp){
							var jsonResp = Ext.JSON.decode(resp.responseText);
							if(jsonResp.success == true){
								botonera.guardarItem(botonera);
								store.load();
								form.getForm().reset();
								win.queryById('concepto').setReadOnly(true);	
							}
						}
					})	
		     	}
		     }
		});			
	}
});










