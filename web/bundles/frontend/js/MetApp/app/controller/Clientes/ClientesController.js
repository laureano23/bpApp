Ext.define('MetApp.controller.Clientes.ClientesController',{
	extend: 'Ext.app.Controller',
	stores: ['Clientes.Clientes'],
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
	
	AddClientesTb: function(btn){
		var me = this;
		var win = Ext.widget('clientestb');
		var comboLocalidad = win.queryById('comboLocalidad');
		var comboProv = win.queryById('comboProv');
		var storeProv = comboProv.getStore();
		
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
	},
	
	BuscaCliente: function(btn){
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
		
		if(form.isValid() == true){
			form.getForm().submit({
				url: Routing.generate('mbp_clientes_new'),
				
				success: function(resp, act){
					jsonResp = Ext.JSON.decode(act.response.responseText);
					if(jsonResp.success == true){
						form.query('.field').forEach(function(c){c.setReadOnly(true);});
						form.queryById('id').setValue(jsonResp.id);
					}
					botonera.guardarItem(botonera);
				},
				
				failure: ''
			});
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





































