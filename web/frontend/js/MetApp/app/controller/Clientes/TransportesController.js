Ext.define('MetApp.controller.Clientes.TransportesController',{
	extend: 'Ext.app.Controller',
	stores: [
		'MetApp.store.Personal.ProvinciasStore',
		'MetApp.store.Personal.PartidosStore',
		'MetApp.store.Clientes.TransportesStore'
	],
	views: [
		'MetApp.view.Clientes.TransportesView'
	],
	
	init: function(){
		var me = this;
		me.control({
			'viewport menuitem[itemId=tbTransportes]': {
				click: this.ViewFormTransportes
			},
			'TransportesView button[itemId=insert]': {
				click: this.GuardarRegistro
			},
			'TransportesView actioncolumn[itemId=eliminar]': {
				click: this.BorrarRegistro
			},
			'TransportesView actioncolumn[itemId=editar]': {
				click: this.EditarRegistro
			},
		});
	},
	
	EditarRegistro: function(grid, colIndex, rowIndex){
		var win = grid.up('window');
		var store = grid.getStore();
		var selection = store.getAt(rowIndex);
		var form = win.down('form');
				
		form.getForm().setValues(selection.data);
		var comboProv = form.queryById('comboProv');
		var storeProv = comboProv.getStore();
		var prov = storeProv.findRecord('nombre', selection.data.provincia);
		
		var comboPartido = win.queryById('comboPartido');
		comboPartido.getStore().getProxy().setExtraParam('provinciaId', prov.data.idProvincia);
		var storePartido = comboPartido.getStore(); 
		storePartido.load({
			callback: function(records, operation, success){
				var modelPartido = storePartido.findRecord('nombre', selection.data.departamento);
				comboPartido.select(modelPartido);
				
			}
		});
		
		comboProv.select(prov);
		
	},
	
	BorrarRegistro: function(grid, colIndex, rowIndex){
		var store = grid.getStore();
		var selection = store.getAt(rowIndex);
		
		Ext.Msg.show({
			title: 'Atención',
			msg: 'Desea borrar el registro?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.ALERT,
			fn:function(btn){
				if(btn == 'yes'){
					store.remove(selection);	
				}
			}
		});
	},
	
	GuardarRegistro: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var values = form.getForm().getValues();
		
		
		
		var store = win.down('grid').getStore();
		var id = form.queryById('id').getValue();
		
		if(id > 0){
			var model = store.findRecord('id', id);
			model.set(values);
		}else{
			var model = Ext.create('MetApp.model.Clientes.TransportesModel');
			model.set(values);
			store.add(model);
		}
		form.getForm().reset();		
	},
	
	ViewFormTransportes: function(btn){
		
		var view = Ext.widget('TransportesView');
		var comboProv = view.queryById('comboProv'); 
		comboProv.getStore().load();
		
		comboProv.on('select', function(){
			var comboPartido = view.queryById('comboPartido');
			comboPartido.getStore().getProxy().setExtraParam('provinciaId', comboProv.getValue());
			comboPartido.getStore().load();
		});
		view.down('grid').getStore().load();
	},
	
})





































