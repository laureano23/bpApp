Ext.define('MetApp.controller.RRHH.CuentaEmpleadosController', {
	extend: 'Ext.app.Controller',
	views: [
		'MetApp.view.RRHH.Cuenta.TabCuentaEmpleados',
		'MetApp.view.RRHH.Cuenta.WinCuentaEmpleados',
		'MetApp.view.RRHH.Cuenta.TabCompensatorios'
	],
	
	stores: [
		'MetApp.store.Personal.CuentaEmpleadosStore',
		'MetApp.store.Personal.CuentaEmpleadosCompStore',
	],
	
	init: function(){
		var me = this;
		me.control({
			'#tablaCuentaEmpleados': {
				click: this.NuevaTabla
			},
			'tabEmpleados button[itemId=buscaEmpleado]': {
				click: this.BuscaEmpleado
			},
			'tabEmpleados button[itemId=pago]': {
				click: this.PagoNuevo
			},
			'tabEmpleados button[itemId=insertar]': {
				click: this.InsertarPago
			},
			'tabEmpleados grid': {
				select: this.FocusButton
			},
			'tabEmpleados button[itemId=editar]': {
				click: this.EditarPago
			},
			'tabEmpleados button[itemId=eliminar]': {
				click: this.EliminarPago
			},
			'tabCompensatorios button[itemId=insertar]': {
				click: this.InsertarPago
			},
			'tabCompensatorios button[itemId=pago]': {
				click: this.PagoNuevo
			},
			'tabCompensatorios button[itemId=editar]': {
				click: this.EditarPago
			},
			'tabCompensatorios button[itemId=eliminar]': {
				click: this.EliminarPago
			},
			'tabCompensatorios grid': {
				select: this.FocusButton
			},
		})
	},
		
	NuevaTabla: function(btn){
		var win = Ext.create('MetApp.view.RRHH.Cuenta.WinCuentaEmpleados');
		var grid = win.down('grid');
		var store = grid.getStore();
		store.removeAll();
	},
	
	BuscaEmpleado: function(btn){
		var winCuenta = btn.up('window');
		var gridCuenta = winCuenta.down('grid');
		var storeCuenta = gridCuenta.getStore();
		var storeComp = winCuenta.queryById('compGrid').getStore();
		var winEmpleado = Ext.create('MetApp.view.RRHH.SearchGridPersonal');
		var grid = winEmpleado.down('grid');
		var store = grid.getStore();
		store.load();
		
		var btnInsert = winEmpleado.queryById('insertPersona');
		btnInsert.on('click', function(btn){
			var selection = grid.getSelectionModel().getSelection()[0];
			winCuenta.queryById('idP').setValue(selection.data.idP);
			winCuenta.queryById('nombre').setValue(selection.data.nombre);
			winEmpleado.close();
			var proxyCuenta = storeCuenta.getProxy();
			var proxyComp = storeComp.getProxy();
			proxyCuenta.setExtraParam('idP', selection.data.idP);
			proxyCuenta.setExtraParam('comp', 0);
			proxyComp.setExtraParam('idP', selection.data.idP);
			proxyComp.setExtraParam('comp', 1);
			storeCuenta.load();
			storeComp.load();						
		});
	},
	
	PagoNuevo: function(btn){
		var win = btn.up('window');
		var panel = btn.up('panel');
		var form;
		if(btn.comp == 1){
			form = win.queryById('compForm');	
		}else{ form = win.queryById('form'); }
		
		form.getForm().reset();
		form.query('.field').forEach(function(c){c.setDisabled(false);}); //HABILITO TODOS LOS CAMPOS
		form.queryById('concepto').focus();
		
		panel.queryById('editar').setDisabled(false);
		panel.queryById('eliminar').setDisabled(false);
		panel.queryById('insertar').setDisabled(false);
	},
	
	InsertarPago: function(btn){
		var win = btn.up('window');
		var panel = btn.up('panel');
		var form;
		var grid;
		var id;
		if(btn.comp == 1){
			form = win.queryById('compForm');
			grid = win.queryById('compGrid');
			id = form.queryById('id').getValue();	
		}else{ 
			form = win.queryById('form');
			grid = win.queryById('grid'); 
			id = form.queryById('id').getValue();
		}
		var store = grid.getStore();
		var values = form.getValues();
		
		console.log(id);
		if(id > 0){
			var model = store.findRecord('id', id);
			model.set(values);
			form.getForm().reset();	
		}else{
			var model = Ext.create('MetApp.model.Personal.CuentaEmpleadosModel');
			
			store.on('write', function(store, records, index, eOpts){
				var response = Ext.JSON.decode(records.response.responseText);
				if(response.success == true){
					form.getForm().reset();					
				}
			});				
			model.set(values);
			btn.comp == 1 ? model.set('compensatorio', 1) : '';
			store.add(model);
		}		
	},
	
	FocusButton: function(rowModel, rec){	
		var win;
		if(rec.data.compensatorio == 0){
			win = Ext.ComponentQuery.query('#tabCuentaEmpleados')[0];	
		}else{
			win = Ext.ComponentQuery.query('#tabCompensatorios')[0];
		}
		
		win.queryById('editar').setDisabled(false);
		win.queryById('eliminar').setDisabled(false);
	},
	
	EditarPago: function(btn){
		var win;
		if(btn.comp == 1){
			win = Ext.ComponentQuery.query('#tabCompensatorios')[0];
		}else{
			win = Ext.ComponentQuery.query('#tabCuentaEmpleados')[0];
		}
		var form = win.down('form');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		
		if(selection.data.neto > 0){
			Ext.Msg.show({
				title: 'Atencion',
				msg: 'Solo puede editar pagos',
				buttons: Ext.Msg.OK,
				icon: Ext.Msg.INFO
			})
		}else{
			var id = win.queryById('id');
			var concepto = win.queryById('concepto');
			var neto = win.queryById('neto');
			
			id.setValue(selection.data.id);
			concepto.setValue(selection.data.periodo);
			neto.setValue(selection.data.pagado);
			form.query('.field').forEach(function(c){c.setDisabled(false);}); //HABILITO TODOS LOS CAMPOS
			win.queryById('insertar').setDisabled(false);
		}
	},
	
	EliminarPago: function(btn){
		var win;
		if(btn.comp == 1){
			win = Ext.ComponentQuery.query('#tabCompensatorios')[0];
		}else{
			win = Ext.ComponentQuery.query('#tabCuentaEmpleados')[0];
		}
		
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		
		if(selection.data.neto > 0){
			Ext.Msg.show({
				title: 'Atencion',
				msg: 'Solo puede eliminar pagos',
				buttons: Ext.Msg.OK,
				icon: Ext.Msg.INFO
			})
		}else{
			Ext.Msg.show({
				title: 'Atencion',
				msg: 'Desea eliminar el registro?',
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.INFO,
				fn: function(btn){
					if(btn == 'yes'){
						store.remove(selection);
					}
				}
			})
		}
	}	
})






















