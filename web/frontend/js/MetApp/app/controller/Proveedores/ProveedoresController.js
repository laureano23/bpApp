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
			'ProveedoresWin combobox[itemId=comboProv]': {
				select: this.FilterPartido
			},
			'ProveedoresWin combobox[itemId=comboPartido]': {
				select: this.FilterLocalidad
			},
			'ProveedoresWin button[itemId=btnNew]': {
				click: this.NuevoProveedor
			},
			'ProveedoresWin button[itemId=btnSave]': {
				click: this.GuardarProveedor
			},
			'ProveedoresWin button[itemId=btnEdit]': {
				click: this.EditarProveedor
			},
			'ProveedoresWin button[itemId=btnDelete]': {
				click: this.EliminarProveedor
			}
		});
	},
	
	EliminarProveedor: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var idProv = win.queryById('id').getValue();
		
		var botonera = win.queryById('botonera');
	
		Ext.Msg.show({
			title: 'Atención',
			msg: 'Desea eliminar este proveedor?',
			buttons: Ext.Msg.YESNO,
 			icon: Ext.Msg.ALERT,
 			fn: function(resp){
 				if(resp=='yes'){
 					Ext.Ajax.request({
						url: Routing.generate('mbp_proveedores_eliminarProveedor'),
						
						params:{
							id: idProv
						},
						
						success: function(resp){
							var jsonResp = Ext.JSON.decode(resp.responseText);
							if(jsonResp.success == true){
								win.queryById('id').setValue(jsonResp.id);
								form.query('.field').forEach(function(c){c.setReadOnly(true);}); //HABILITO TODOS LOS CAMPOS
								form.getForm().reset();
								botonera.resetFn(botonera);		
							}
						}
					})
 				}
 			}
		})
	},
	
	FilterLocalidad: function(combo){
		var win = combo.up('window');
		var storeLocalidad = win.queryById('comboLocalidad').getStore();
		var deptoId = win.queryById('comboPartido').getValue();
		
		storeLocalidad.getProxy().setExtraParam('partidoId', deptoId);
		storeLocalidad.load();	
	},
	
	FilterPartido: function(combo){
		var win = combo.up('window');
		var storePartido = win.queryById('comboPartido').getStore();
		var provId = win.queryById('comboProv').getValue();
		
		if(provId == null){
			Ext.Msg.show({
				title: 'Atención',
				msg: 'Seleccione una provincia primero',
				buttons: Ext.Msg.OK,
     			icon: Ext.Msg.ALERT
			})
		}else{
			storePartido.getProxy().setExtraParam('provinciaId', provId);
			storePartido.load();	
		}
	},
	
	AddProveedoresWin: function(btn){
		var win = Ext.widget('ProveedoresWin');
		var comboLocalidad = win.queryById('comboLocalidad');
		var comboProvincia = win.queryById('comboProv');
		var comboGastos = win.queryById('tipoGasto');
		
		comboProvincia.getStore().load();
		comboGastos.getStore().load();		
		
	},
	
	BuscaProveedor: function(btn){
		var me = this;
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
			
			var comboProv = tablaProveedor.queryById('comboProv');
			var comboPartido = tablaProveedor.queryById('comboPartido');
			me.FilterPartido(comboProv);
			form.loadRecord(record);
			me.FilterLocalidad(comboPartido);
			form.loadRecord(record);
		});
	},
	
	NuevoProveedor: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		
		form.getForm().reset();
		form.query('.field').forEach(function(c){c.setReadOnly(false);}); //HABILITO TODOS LOS CAMPOS
		botonera.nuevoItem(botonera);
		form.queryById('rsocial').focus();		
	},
	
	GuardarProveedor: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var values=form.getForm().getValues();
		var botonera = win.queryById('botonera');		
		
		if(form.isValid() == true){
			var rec;
			if(values.id>0){
				rec=form.getRecord();
			}else{
				rec = Ext.create('MetApp.model.Proveedores.ProveedoresModel');
			}		
			rec.set(values);
			rec.save({
				success:function(resp){
					win.queryById('id').setValue(resp.data.id);	
					form.loadRecord(resp);				
					form.query('.field').forEach(function(c){c.setReadOnly(true);}); //HABILITO TODOS LOS CAMPOS
					botonera.resetFn(botonera);
				}
			});
		}
	},
	
	EditarProveedor: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		form.query('.field').forEach(function(c){c.setReadOnly(false);});
		
		botonera.editarItem(botonera);
	}
})





































