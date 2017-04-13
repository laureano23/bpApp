Ext.define('MetApp.controller.Bancos.BancosController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Bancos.FormBancosView',
		
	],
	stores: [
		'MetApp.store.Personal.BancosStore'
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
	}
});










