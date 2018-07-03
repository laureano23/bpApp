Ext.define('MetApp.controller.Clientes.ClientesController',{
	extend: 'Ext.app.Controller',
	stores: ['Clientes.Clientes', 'MetApp.store.Finanzas.VendedorStore', 'MetApp.store.Finanzas.PosicionIvaStore'],
	views: [
		'Clientes.FormClientes',
		'Clientes.SearchGridClientes'
	],
	
	init: function(){
		var me = this;
		me.control({
			'#tbClientes': {
				click: this.AddClientesTb
			},
			'clientestb button[action=buscaCliente]': {
				click: this.BuscaCliente
			},
			'clientestb button[action=actNew]': {
				click: this.NewCliente
			},
			'clientestb combobox[itemId=comboProv]': {
				select: this.FilterPartido
			},
			'clientestb combobox[itemId=comboPartido]': {
				select: this.FilterLocalidad
			},
			'clientestb button[action=actEdit]': {
				click: this.EditaCliente
			},
			'clientestb button[action=actResetForm]': {
				click: this.ResetForm
			},
			'clientestb button[action=actSave]': {
				click: this.SaveCliente
			},
			'clientestb button[action=actDelete]': {
				click: this.EliminaCliente
			}
		});
	},
	
	FilterLocalidad: function(combo){
		var win = combo.up('window');
		var storeLocalidad = win.queryById('comboLocalidad').getStore();
		var storeVendedor = win.queryById('vendedor').getStore();
		var deptoId = win.queryById('comboPartido').getValue();
		
		storeLocalidad.getProxy().setExtraParam('partidoId', deptoId);
		storeLocalidad.load();	
		storeVendedor.load();
	},
	
	FilterPartido: function(combo){
		var win = combo.up('window');
		var storePartido = win.queryById('comboPartido').getStore();
		var provId = win.queryById('comboProv').getValue();
		
		
		storePartido.getProxy().setExtraParam('provinciaId', provId);
		storePartido.load();
	},
	
	AddClientesTb: function(btn){
		var me = this;
		var win = Ext.widget('clientestb');
		var comboLocalidad = win.queryById('comboLocalidad');
		var comboProv = win.queryById('comboProv');
		var storeProv = comboProv.getStore();
		var storeTransportes = win.queryById('transporte').getStore();
		
		var map = new Ext.util.KeyMap({
		    target: win.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.SaveCliente(win.queryById('btnSave'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EditaCliente(win.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F3,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){
		   				me.NewCliente(win.queryById('btnNew'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EliminaCliente(win.queryById('btnDelete'));
		   			}
		   		},
		   	]
		});	
		
		storeProv.load();
		storeTransportes.load();
	},
	
	BuscaCliente: function(btn){
		var me = this;
		var tablaCliente = btn.up('window');
		var botonera = tablaCliente.queryById('botonera');
		var win = Ext.widget('clientesSearchGrid');
		var store = win.down('grid').getStore();
		store.load();
		store.clearFilter(true);
		var input = win.down('textfield');
		input.focus('', 10); // Le damos un tiempo al metodo focus para que el form termine de renderizar
		var button = win.down('button');
		
		botonera.busquedaItem(botonera);
		
		button.on('click', function(){
			var grid = button.up('grid');
			var store = grid.getStore();
			var selection = grid.getSelectionModel().getSelection()[0];
			
			var rec = store.findRecord('id', selection.data.id);
			var form = Ext.ComponentQuery.query('#clientesTb')[0].down('form');
						
			form.loadRecord(rec);
			
			var comboProv = tablaCliente.queryById('comboProv');
			var comboPartido = tablaCliente.queryById('comboPartido');
			me.FilterPartido(comboProv);
			form.loadRecord(rec);
			me.FilterLocalidad(comboPartido);
			form.loadRecord(rec);
			
			win.close();
		});
	},
	
	NewCliente: function(btn){
		var form = btn.up('form');
		var botonera = form.queryById('botonera');
		form.getForm().reset(); //RESET DEL FORM
		
		form.query('.field').forEach(function(c){c.setReadOnly(false);}); //HABILITO TODOS LOS CAMPOS
		botonera.nuevoItem(botonera);
		form.queryById('rsocial').focus(); //FOCUS SOBRE RSOCIAL				
	},
	
	EditaCliente: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var botonera = win.queryById('botonera');
		botonera.editarItem(botonera);
		
		form.query('.field').forEach(function(c){c.setReadOnly(false);}); //HABILITO TODOS LOS CAMPOS
	},
	
	ResetForm: function(btn){
		var form = btn.up('form');
		form.getForm().reset();	
		form.query('.field').forEach(function(c){c.setReadOnly(true);});
	},
	
	SaveCliente: function(btn){
		var botonera = btn.up('window').queryById('botonera');
		form = btn.up('form');
		values = form.getForm().getValues();
		var store = Ext.StoreManager.lookup('Clientes.Clientes');
		
		store.on('write', function(st, op){
			console.log(op.getRecords());
			var rec = op.getRecords();
			form.loadRecord(rec[0]);
			botonera.guardarItem(botonera);
			form.query('.field').forEach(function(c){c.setReadOnly(true);});
		});
		
		if(form.isValid() == true){
			if(values.id > 0){
				console.log(values);
				var model=form.getRecord();
				model.set(values);	
				model.save();		
			}else{				
				var model=Ext.create('MetApp.model.Clientes.ClientesModel');
				model.set(values);
				var newModel=store.add(model);
				store.sync();	
			}	
		}
	},
	
	EliminaCliente: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var idCliente = win.queryById('id').getValue();
		
		Ext.Msg.show({
		     title:'Atencion',
		     msg: 'Esta por eliminar un registro, desea continuar?',
		     buttons: Ext.Msg.YESNO,
		     icon: Ext.Msg.INFO,
		     fn: function(btn){
		     	if(btn == 'yes'){
		     		Ext.Ajax.request({
						url: Routing.generate('mbp_clientes_eliminar'),
						
						params: {
							id: idCliente
						},
						
						success: function(resp){
							jsonResp = Ext.JSON.decode(resp.responseText);
							if(jsonResp.success == true){
								form.getForm().reset();
							}
						}
					});
		     	}
		     }
		})
		
	}
})





































