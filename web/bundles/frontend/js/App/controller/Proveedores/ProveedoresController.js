Ext.define('MetApp.controller.Proveedores.ProveedoresController',{
	extend: 'Ext.app.Controller',
	stores: ['Proveedores.ProveedoresStore',
			'Proveedores.ImputacionGastosStore'],
	views: [
		'Proveedores.FormProveedores',
		'Proveedores.SearchGridProveedores',		
	],
	
	init: function(){
		var me = this;
		me.control({
			'#tbProveedores': {
				click: this.AddProveedoresWin
			},
			'ProveedoresWin button[itemId=buscaProveedor]': {
				click: this.BuscaProveedor
			},
			'ProveedoresWin button[itemId=btnNew]': {
				click: this.NuevoProveedor
			},
			'ProveedoresWin button[itemId=btnSave]': {
				click: this.GuardarProveedor
			},
			'ProveedoresWin button[itemId=btnEdit]': {
				click: this.EditarProveedor
			}
		});
	},
	
	AddProveedoresWin: function(btn){
		var win = Ext.widget('ProveedoresWin');
		var comboLocalidad = win.queryById('comboLocalidad');
		var comboProvincia = win.queryById('comboProv');
		var comboGastos = win.queryById('tipoGasto');
		
		comboLocalidad.getStore().load();
		comboProvincia.getStore().load();
		comboGastos.getStore().load();
	},
	
	BuscaProveedor: function(btn){
		var tablaProveedor = btn.up('window');
		var form = tablaProveedor.down('form');
		var win = Ext.widget('ProveedoresSearchGrid');
		var grid = win.down('grid');
		var store = grid.getStore();
		store.load();
		
		var btnInsert = win.queryById('insertProveedor');
		btnInsert.on('click', function(){
			var record = grid.getSelectionModel().getSelection()[0];
			form.loadRecord(record);
			win.close();
		});
	},
	
	NuevoProveedor: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		
		form.getForm().reset();
		form.query('.field').forEach(function(c){c.setDisabled(false);}); //HABILITO TODOS LOS CAMPOS
		botonera.nuevoItem(botonera);
		form.queryById('rSocial').focus();		
	},
	
	GuardarProveedor: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var values = form.getValues();
		var botonera = win.queryById('botonera');
		
		if(form.isValid() == true){
			Ext.Ajax.request({
				url: Routing.generate('mbp_proveedores_nuevo'),
				
				params:{
					data: Ext.JSON.encode(values)
				},
				
				success: function(resp){
					console.log(resp);
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						win.queryById('id').setValue(jsonResp.id);
						form.query('.field').forEach(function(c){c.setDisabled(true);}); //HABILITO TODOS LOS CAMPOS
						botonera.resetFn(botonera);		
					}
				}
			})
		}
	},
	
	EditarProveedor: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		form.query('.field').forEach(function(c){c.setDisabled(false);});
		
		botonera.editarItem(botonera);
	}
})





































